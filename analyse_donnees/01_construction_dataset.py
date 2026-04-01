"""
1 - Construction du dataset
Identifie et extrait les données relatives aux :
  - Salles (id, capacité, type, etc.)
  - Enseignants (département, volume horaire)
  - Réservations
"""
import sys
from pathlib import Path

import pandas as pd

# Ajouter le dossier parent pour importer config
sys.path.insert(0, str(Path(__file__).resolve().parent))
from config import DATA_DIR, get_db_config


def connect_and_extract():
    """Extrait les données depuis la base MySQL (Laravel)."""
    try:
        import pymysql
    except ImportError:
        print("Installer pymysql : pip install pymysql")
        return None

    cfg = get_db_config()

    try:
        conn = pymysql.connect(**cfg)
    except Exception as e:
        print(f"Connexion DB impossible : {e}")
        print("Vérifiez .env (DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD)")
        return None

    # --- Salles : id, capacité, type, nom, numero ---
    sql_salles = """
    SELECT id, nom, numero, capacite, type, equipements, disponible
    FROM salles
    ORDER BY id
    """
    df_salles = pd.read_sql(sql_salles, conn)
    df_salles.to_csv(DATA_DIR / "salles.csv", index=False, encoding="utf-8-sig")
    print(f"Salles : {len(df_salles)} enregistrements → data/salles.csv")

    # --- Enseignants : id, nom, département, volume horaire (somme des cours) ---
    sql_enseignants = """
    SELECT
        u.id,
        u.name AS enseignant,
        u.departement_id,
        d.nom AS departement_nom,
        COALESCE(SUM(c.volume_horaire), 0) AS volume_horaire_total
    FROM users u
    LEFT JOIN departements d ON d.id = u.departement_id
    LEFT JOIN cours c ON c.enseignant_id = u.id
    WHERE u.role = 'enseignant'
    GROUP BY u.id, u.name, u.departement_id, d.nom
    ORDER BY volume_horaire_total DESC
    """
    df_enseignants = pd.read_sql(sql_enseignants, conn)
    df_enseignants.to_csv(DATA_DIR / "enseignants.csv", index=False, encoding="utf-8-sig")
    print(f"Enseignants : {len(df_enseignants)} enregistrements → data/enseignants.csv")

    # --- Réservations ---
    sql_reservations = """
    SELECT
        r.id,
        r.user_id,
        r.salle_id,
        r.date,
        r.heure_debut,
        r.heure_fin,
        r.motif,
        r.statut,
        r.created_at,
        r.updated_at
    FROM reservations r
    ORDER BY r.created_at
    """
    df_reservations = pd.read_sql(sql_reservations, conn)
    df_reservations["date"] = pd.to_datetime(df_reservations["date"])
    df_reservations["created_at"] = pd.to_datetime(df_reservations["created_at"])
    df_reservations["updated_at"] = pd.to_datetime(df_reservations["updated_at"])
    df_reservations.to_csv(DATA_DIR / "reservations.csv", index=False, encoding="utf-8-sig")
    print(f"Réservations : {len(df_reservations)} enregistrements → data/reservations.csv")

    # --- Emploi du temps (pour taux d'occupation) ---
    sql_edt = """
    SELECT
        id, cours_id, salle_id, groupe_id, jour,
        heure_debut, heure_fin, date_debut, date_fin, type_seance
    FROM emploi_du_temps
    ORDER BY salle_id, jour, heure_debut
    """
    df_edt = pd.read_sql(sql_edt, conn)
    df_edt["heure_debut"] = pd.to_datetime(df_edt["heure_debut"], format="%H:%M:%S").dt.time
    df_edt["heure_fin"] = pd.to_datetime(df_edt["heure_fin"], format="%H:%M:%S").dt.time
    df_edt.to_csv(DATA_DIR / "emploi_du_temps.csv", index=False, encoding="utf-8-sig")
    print(f"Emploi du temps : {len(df_edt)} créneaux → data/emploi_du_temps.csv")

    conn.close()
    return {
        "salles": df_salles,
        "enseignants": df_enseignants,
        "reservations": df_reservations,
        "emploi_du_temps": df_edt,
    }


def load_from_csv():
    """Charge les datasets depuis les CSV (si déjà extraits)."""
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
            if "date" in data[name].columns:
                data[name]["date"] = pd.to_datetime(data[name]["date"], errors="coerce")
            if "created_at" in data[name].columns:
                data[name]["created_at"] = pd.to_datetime(data[name]["created_at"], errors="coerce")
            if "updated_at" in data[name].columns:
                data[name]["updated_at"] = pd.to_datetime(data[name]["updated_at"], errors="coerce")
        else:
            data[name] = None
    return data


if __name__ == "__main__":
    print("=== Construction du dataset HESTIM ===\n")
    datasets = connect_and_extract()
    if datasets is None:
        print("\nChargement depuis data/ si les CSV existent...")
        datasets = load_from_csv()
        for k, v in datasets.items():
            if v is not None:
                print(f"  {k}: {len(v)} lignes")
            else:
                print(f"  {k}: fichier absent")
    print("\nTerminé.")
