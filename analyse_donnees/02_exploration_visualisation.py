"""
2 - Exploration et visualisation
Charge les datasets, statistiques descriptives et graphiques Plotly
(salles, enseignants, réservations).
"""
import sys
from pathlib import Path

import pandas as pd
import plotly.express as px
import plotly.graph_objects as go
from plotly.subplots import make_subplots

sys.path.insert(0, str(Path(__file__).resolve().parent))
from config import DATA_DIR


def load_data():
    """Charge tous les CSV du data/."""
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


def exploration_resume(data):
    """Résumé exploration (shape, describe, types)."""
    resume = []
    for name, df in data.items():
        if df is None:
            resume.append((name, "Fichier absent"))
            continue
        resume.append((name, f"{df.shape[0]} lignes, {df.shape[1]} colonnes"))
        resume.append((name + "_dtypes", str(df.dtypes.to_dict())))
        if not df.empty:
            resume.append((name + "_describe", df.describe(include="all").to_string()))
    return resume


# --- Graphiques Plotly ---

def fig_salles_capacite(df_salles):
    """Barres : capacité par salle."""
    if df_salles is None or df_salles.empty:
        return go.Figure().add_annotation(text="Aucune donnée salles", x=0.5, y=0.5, showarrow=False)
    df = df_salles.sort_values("capacite", ascending=True)
    fig = px.bar(df, x="nom", y="capacite", title="Capacité des salles", labels={"nom": "Salle", "capacite": "Capacité"})
    fig.update_layout(xaxis_tickangle=-45)
    return fig


def fig_salles_par_type(df_salles):
    """Camembert : répartition par type de salle."""
    if df_salles is None or df_salles.empty:
        return go.Figure().add_annotation(text="Aucune donnée salles", x=0.5, y=0.5, showarrow=False)
    counts = df_salles["type"].value_counts().reset_index()
    counts.columns = ["type", "nombre"]
    fig = px.pie(counts, values="nombre", names="type", title="Répartition des salles par type")
    return fig


def fig_volume_horaire_par_departement(df_enseignants):
    """Barres : volume horaire total par département."""
    if df_enseignants is None or df_enseignants.empty:
        return go.Figure().add_annotation(text="Aucune donnée enseignants", x=0.5, y=0.5, showarrow=False)
    dep = df_enseignants.groupby("departement_nom", dropna=False)["volume_horaire_total"].sum().reset_index()
    dep = dep.sort_values("volume_horaire_total", ascending=True)
    dep["departement_nom"] = dep["departement_nom"].fillna("Non renseigné")
    fig = px.bar(dep, x="departement_nom", y="volume_horaire_total",
                 title="Volume horaire par département",
                 labels={"departement_nom": "Département", "volume_horaire_total": "Volume horaire"})
    fig.update_layout(xaxis_tickangle=-45)
    return fig


def fig_volume_horaire_par_enseignant(df_enseignants, top=15):
    """Barres : volume horaire par enseignant (top N)."""
    if df_enseignants is None or df_enseignants.empty:
        return go.Figure().add_annotation(text="Aucune donnée enseignants", x=0.5, y=0.5, showarrow=False)
    df = df_enseignants.nlargest(top, "volume_horaire_total")
    fig = px.bar(df, x="enseignant", y="volume_horaire_total",
                 title=f"Volume horaire par enseignant (top {top})",
                 labels={"enseignant": "Enseignant", "volume_horaire_total": "Volume horaire"})
    fig.update_layout(xaxis_tickangle=-45)
    return fig


def fig_reservations_par_statut(df_reservations):
    """Camembert : répartition des réservations par statut."""
    if df_reservations is None or df_reservations.empty:
        return go.Figure().add_annotation(text="Aucune donnée réservations", x=0.5, y=0.5, showarrow=False)
    counts = df_reservations["statut"].value_counts().reset_index()
    counts.columns = ["statut", "nombre"]
    fig = px.pie(counts, values="nombre", names="statut", title="Répartition des réservations par statut")
    return fig


def fig_reservations_par_mois(df_reservations):
    """Barres : nombre de réservations par mois (création)."""
    if df_reservations is None or df_reservations.empty or "created_at" not in df_reservations.columns:
        return go.Figure().add_annotation(text="Aucune donnée réservations", x=0.5, y=0.5, showarrow=False)
    df = df_reservations.copy()
    df["mois"] = df["created_at"].dt.to_period("M").astype(str)
    by_month = df.groupby("mois").size().reset_index(name="nombre")
    fig = px.bar(by_month, x="mois", y="nombre", title="Réservations par mois (date de création)")
    fig.update_layout(xaxis_tickangle=-45)
    return fig


def fig_reservations_par_salle(df_reservations, df_salles=None, top=15):
    """Barres : nombre de réservations par salle."""
    if df_reservations is None or df_reservations.empty:
        return go.Figure().add_annotation(text="Aucune donnée réservations", x=0.5, y=0.5, showarrow=False)
    by_salle = df_reservations.groupby("salle_id").size().reset_index(name="nombre")
    by_salle = by_salle.sort_values("nombre", ascending=False).head(top)
    if df_salles is not None and not df_salles.empty:
        by_salle = by_salle.merge(df_salles[["id", "nom"]], left_on="salle_id", right_on="id", how="left")
        by_salle["salle"] = by_salle["nom"].fillna(by_salle["salle_id"].astype(str))
    else:
        by_salle["salle"] = by_salle["salle_id"].astype(str)
    fig = px.bar(by_salle, x="salle", y="nombre", title=f"Réservations par salle (top {top})")
    fig.update_layout(xaxis_tickangle=-45)
    return fig


if __name__ == "__main__":
    data = load_data()
    print("Exploration - résumé")
    for name, text in exploration_resume(data):
        if "dtypes" in name or "describe" in name:
            continue
        print(f"  {name}: {text}")

    # Exemple : afficher les figures (en HTML si pas Streamlit)
    if data.get("salles") is not None and not data["salles"].empty:
        fig = fig_salles_capacite(data["salles"])
        fig.write_html(DATA_DIR / "viz_salles_capacite.html")
        print("Généré: data/viz_salles_capacite.html")
    if data.get("enseignants") is not None and not data["enseignants"].empty:
        fig = fig_volume_horaire_par_departement(data["enseignants"])
        fig.write_html(DATA_DIR / "viz_volume_departement.html")
        print("Généré: data/viz_volume_departement.html")
    if data.get("reservations") is not None and not data["reservations"].empty:
        fig = fig_reservations_par_statut(data["reservations"])
        fig.write_html(DATA_DIR / "viz_reservations_statut.html")
        print("Généré: data/viz_reservations_statut.html")
    print("Fin exploration.")
