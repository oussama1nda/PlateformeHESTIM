"""
3 - Analyse des performances du système
KPI : taux d'occupation des salles, taux d'approbation des réservations,
délai moyen de traitement, volume horaire, etc.
"""
import sys
from pathlib import Path

import pandas as pd
import plotly.express as px
import plotly.graph_objects as go

sys.path.insert(0, str(Path(__file__).resolve().parent))
from config import DATA_DIR


def load_data():
    """Charge les CSV."""
    data = {}
    for name, filename in [
        ("salles", "salles.csv"),
        ("enseignants", "enseignants.csv"),
        ("reservations", "reservations.csv"),
        ("emploi_du_temps", "emploi_du_temps.csv"),
    ]:
        path = DATA_DIR / filename
        if path.exists():
            data[name] = pd.read_csv(path)
            for col in ["date", "created_at", "updated_at"]:
                if col in data[name].columns:
                    data[name][col] = pd.to_datetime(data[name][col], errors="coerce")
        else:
            data[name] = None
    return data


# --- KPI Réservations ---

def kpi_taux_approbation(df_reservations):
    """Taux d'approbation = approuvées / (approuvées + refusées)."""
    if df_reservations is None or df_reservations.empty:
        return None, None
    traitees = df_reservations[df_reservations["statut"].isin(["approuvee", "refusee"])]
    total = len(traitees)
    if total == 0:
        return 0.0, {"total_traitees": 0, "approuvees": 0, "refusees": 0}
    approuvees = len(traitees[traitees["statut"] == "approuvee"])
    return (approuvees / total) * 100, {"total_traitees": total, "approuvees": approuvees, "refusees": total - approuvees}


def kpi_delai_moyen_traitement(df_reservations):
    """Délai moyen (en jours) entre created_at et updated_at pour les réservations traitées."""
    if df_reservations is None or df_reservations.empty:
        return None, None
    traitees = df_reservations[df_reservations["statut"].isin(["approuvee", "refusee"])].copy()
    traitees = traitees.dropna(subset=["created_at", "updated_at"])
    if traitees.empty:
        return None, None
    traitees["delai_jours"] = (traitees["updated_at"] - traitees["created_at"]).dt.total_seconds() / 86400
    return traitees["delai_jours"].mean(), traitees


def kpi_reservations_en_attente(df_reservations):
    """Nombre et part des réservations en attente."""
    if df_reservations is None or df_reservations.empty:
        return 0, 0.0
    en_attente = len(df_reservations[df_reservations["statut"] == "en_attente"])
    total = len(df_reservations)
    pct = (en_attente / total) * 100 if total else 0
    return en_attente, pct


# --- KPI Occupation (à partir de l'emploi du temps) ---

def _to_hours(t):
    """Convertit heure (str HH:MM:SS ou HH:MM) en décimal heures."""
    if pd.isna(t):
        return 0
    s = str(t).strip()[:8]
    if ":" not in s:
        return 0
    parts = s.split(":")
    h = int(parts[0]) if len(parts) > 0 else 0
    m = int(parts[1]) if len(parts) > 1 else 0
    sec = int(parts[2]) if len(parts) > 2 else 0
    return h + m / 60 + sec / 3600


def kpi_occupation_salles(df_edt, df_salles, heures_ouvrables_par_semaine=40):
    """
    Taux d'occupation par salle : heures utilisées (emploi du temps) / heures ouvrables.
    """
    if df_edt is None or df_edt.empty or df_salles is None or df_salles.empty:
        return pd.DataFrame()

    edt = df_edt.copy()
    edt["duree_h"] = edt.apply(
        lambda r: max(0, _to_hours(r["heure_fin"]) - _to_hours(r["heure_debut"])), axis=1
    )

    heures_par_salle = edt.groupby("salle_id")["duree_h"].sum().reset_index()
    heures_par_salle.columns = ["salle_id", "heures_utilisees"]
    # Par semaine on peut considérer N semaines dans la période ; ici on fait simple : ratio sur une base hebdo
    heures_par_salle["heures_ouvrables"] = heures_ouvrables_par_semaine
    heures_par_salle["taux_occupation_pct"] = (heures_par_salle["heures_utilisees"] / heures_ouvrables_par_semaine * 100).clip(upper=100)
    return heures_par_salle.merge(df_salles[["id", "nom", "capacite"]], left_on="salle_id", right_on="id", how="left")


def kpi_volume_horaire_departement(df_enseignants):
    """Volume horaire total par département."""
    if df_enseignants is None or df_enseignants.empty:
        return pd.DataFrame()
    return df_enseignants.groupby("departement_nom", dropna=False)["volume_horaire_total"].sum().reset_index()


def rapport_performances(data):
    """Construit un dictionnaire de tous les KPI."""
    r = {}

    # Réservations
    res = data.get("reservations")
    r["taux_approbation"], r["detail_approbation"] = kpi_taux_approbation(res)
    r["delai_moyen_jours"], r["df_delais"] = kpi_delai_moyen_traitement(res)
    r["en_attente_n"], r["en_attente_pct"] = kpi_reservations_en_attente(res)
    r["total_reservations"] = len(res) if res is not None else 0

    # Occupation
    r["occupation_salles"] = kpi_occupation_salles(data.get("emploi_du_temps"), data.get("salles"))
    r["volume_par_departement"] = kpi_volume_horaire_departement(data.get("enseignants"))

    return r


# --- Visualisations des performances ---

def fig_taux_approbation_et_statuts(df_reservations):
    """Barres : répartition des statuts (pour illustrer le taux d'approbation)."""
    if df_reservations is None or df_reservations.empty:
        return go.Figure().add_annotation(text="Aucune donnée", x=0.5, y=0.5, showarrow=False)
    counts = df_reservations["statut"].value_counts().reset_index()
    counts.columns = ["statut", "nombre"]
    fig = px.bar(counts, x="statut", y="nombre", title="Réservations par statut (performance du workflow)",
                 color="nombre", color_continuous_scale="Blues")
    return fig


def fig_delai_traitement(df_delais):
    """Histogramme des délais de traitement en jours."""
    if df_delais is None or df_delais.empty or "delai_jours" not in df_delais.columns:
        return go.Figure().add_annotation(text="Données délais insuffisantes", x=0.5, y=0.5, showarrow=False)
    fig = px.histogram(df_delais, x="delai_jours", nbins=20, title="Délai de traitement des réservations (jours)")
    fig.update_layout(xaxis_title="Délai (jours)")
    return fig


def fig_taux_occupation_par_salle(df_occupation):
    """Barres : taux d'occupation par salle."""
    if df_occupation is None or df_occupation.empty:
        return go.Figure().add_annotation(text="Aucune donnée occupation", x=0.5, y=0.5, showarrow=False)
    df = df_occupation.sort_values("taux_occupation_pct", ascending=True)
    fig = px.bar(df, x="nom", y="taux_occupation_pct", title="Taux d'occupation des salles (%)",
                 labels={"nom": "Salle", "taux_occupation_pct": "Taux d'occupation (%)"})
    fig.update_layout(xaxis_tickangle=-45)
    return fig


if __name__ == "__main__":
    data = load_data()
    r = rapport_performances(data)

    print("=== Performances du système ===\n")
    print("Réservations")
    print(f"  Total réservations : {r['total_reservations']}")
    print(f"  Taux d'approbation : {r['taux_approbation']:.1f}%" if r['taux_approbation'] is not None else "  Taux d'approbation : N/A")
    print(f"  Délai moyen de traitement : {r['delai_moyen_jours']:.1f} jours" if r['delai_moyen_jours'] is not None else "  Délai moyen : N/A")
    print(f"  En attente : {r['en_attente_n']} ({r['en_attente_pct']:.1f}%)")
    print("\nOccupation salles")
    if not r["occupation_salles"].empty:
        print(r["occupation_salles"][["nom", "heures_utilisees", "taux_occupation_pct"]].to_string(index=False))
    else:
        print("  Aucune donnée emploi du temps/salles.")
    print("\nTerminé.")
