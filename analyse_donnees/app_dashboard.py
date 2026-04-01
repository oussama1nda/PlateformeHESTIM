"""
Dashboard Streamlit – HESTIM
Réunit : construction du dataset, exploration/visualisation, analyse des performances.
"""
import os
import re
import sys
from pathlib import Path

import pandas as pd
import plotly.express as px
import streamlit as st

sys.path.insert(0, str(Path(__file__).resolve().parent))
from config import DATA_DIR

# Import des modules d'analyse
from importlib.util import spec_from_file_location, module_from_spec

def load_module(name, path):
    spec = spec_from_file_location(name, path)
    mod = module_from_spec(spec)
    spec.loader.exec_module(mod)
    return mod

analyse_01 = load_module("01_construction_dataset", Path(__file__).parent / "01_construction_dataset.py")
analyse_02 = load_module("02_exploration_visualisation", Path(__file__).parent / "02_exploration_visualisation.py")
analyse_03 = load_module("03_analyse_performances", Path(__file__).parent / "03_analyse_performances.py")

st.set_page_config(page_title="HESTIM – Analyse des données", layout="wide")
st.title("Plateforme HESTIM – Analyse des données")

# Chargement des données (depuis CSV)
@st.cache_data
def load_all_data():
    return analyse_02.load_data()

# Sidebar : choix d'onglet
st.sidebar.header("Navigation")
tab_choice = st.sidebar.radio(
    "Étapes",
    ["1. Construction du dataset", "2. Exploration et visualisation", "3. Performances du système"],
)

# --- 1. Construction du dataset ---
if tab_choice == "1. Construction du dataset":
    st.header("1. Construction du dataset")
    st.markdown("""
    **Données extraites :**
    - **Salles** : id, nom, numéro, capacité, type
    - **Enseignants** : id, nom, département, volume horaire total (somme des cours)
    - **Réservations** : id, salle, date, statut, créée le, mise à jour le
    - **Emploi du temps** : créneaux (pour taux d'occupation)
    """)
    if st.button("Extraire les données depuis la base MySQL"):
        with st.spinner("Extraction en cours..."):
            result = analyse_01.connect_and_extract()
        if result:
            st.success("Données extraites et enregistrées dans `data/`.")
            for name, df in result.items():
                st.metric(name, f"{len(df)} lignes")
        else:
            st.warning("Connexion impossible. Vérifiez le fichier .env à la racine du projet et que MySQL est démarré.")
    st.subheader("Aperçu des fichiers présents")
    for fname in ["salles.csv", "enseignants.csv", "reservations.csv", "emploi_du_temps.csv"]:
        path = DATA_DIR / fname
        if path.exists():
            df = pd.read_csv(path)
            with st.expander(f"{fname} ({len(df)} lignes)"):
                st.dataframe(df.head(20), use_container_width=True)
        else:
            st.caption(f"{fname} – absent (lancer l'extraction)")

# --- 2. Exploration et visualisation ---
elif tab_choice == "2. Exploration et visualisation":
    st.header("2. Exploration et visualisation")
    data = load_all_data()

    if data.get("salles") is not None and not data["salles"].empty:
        st.subheader("Salles")
        col1, col2 = st.columns(2)
        with col1:
            st.plotly_chart(analyse_02.fig_salles_capacite(data["salles"]), use_container_width=True)
        with col2:
            st.plotly_chart(analyse_02.fig_salles_par_type(data["salles"]), use_container_width=True)
    else:
        st.info("Pas de données salles. Lancez l'extraction dans l'onglet 1.")

    if data.get("enseignants") is not None and not data["enseignants"].empty:
        st.subheader("Enseignants – Volume horaire")
        col1, col2 = st.columns(2)
        with col1:
            st.plotly_chart(analyse_02.fig_volume_horaire_par_departement(data["enseignants"]), use_container_width=True)
        with col2:
            top = st.slider("Top N enseignants", 5, 30, 15)
            st.plotly_chart(analyse_02.fig_volume_horaire_par_enseignant(data["enseignants"], top=top), use_container_width=True)
    else:
        st.info("Pas de données enseignants.")

    if data.get("reservations") is not None and not data["reservations"].empty:
        st.subheader("Réservations")
        col1, col2 = st.columns(2)
        with col1:
            st.plotly_chart(analyse_02.fig_reservations_par_statut(data["reservations"]), use_container_width=True)
        with col2:
            st.plotly_chart(analyse_02.fig_reservations_par_salle(data["reservations"], data.get("salles"), top=15), use_container_width=True)
        st.plotly_chart(analyse_02.fig_reservations_par_mois(data["reservations"]), use_container_width=True)
    else:
        st.info("Pas de données réservations.")

# --- 3. Performances du système ---
elif tab_choice == "3. Performances du système":
    st.header("3. Analyse des performances du système")
    data = load_all_data()
    r = analyse_03.rapport_performances(data)

    st.subheader("KPI – Réservations")
    c1, c2, c3, c4 = st.columns(4)
    c1.metric("Total réservations", r["total_reservations"])
    c2.metric("Taux d'approbation", f"{r['taux_approbation']:.1f}%" if r["taux_approbation"] is not None else "N/A")
    c3.metric("Délai moyen (jours)", f"{r['delai_moyen_jours']:.1f}" if r["delai_moyen_jours"] is not None else "N/A")
    c4.metric("En attente", f"{r['en_attente_n']} ({r['en_attente_pct']:.1f}%)")

    if data.get("reservations") is not None and not data["reservations"].empty:
        st.plotly_chart(analyse_03.fig_taux_approbation_et_statuts(data["reservations"]), use_container_width=True)
    if r.get("df_delais") is not None and not r["df_delais"].empty:
        st.plotly_chart(analyse_03.fig_delai_traitement(r["df_delais"]), use_container_width=True)

    st.subheader("KPI – Occupation des salles")
    if r["occupation_salles"] is not None and not r["occupation_salles"].empty:
        st.plotly_chart(analyse_03.fig_taux_occupation_par_salle(r["occupation_salles"]), use_container_width=True)
        st.dataframe(r["occupation_salles"][["nom", "capacite", "heures_utilisees", "taux_occupation_pct"]], use_container_width=True)
    else:
        st.info("Données emploi du temps ou salles manquantes pour le taux d'occupation.")

    if r["volume_par_departement"] is not None and not r["volume_par_departement"].empty:
        st.subheader("Volume horaire par département")
        st.dataframe(r["volume_par_departement"], use_container_width=True)

if st.sidebar.button("Rafraîchir les données (vider le cache)"):
    load_all_data.clear()
    st.sidebar.success("Cache vidé. Rechargez la page ou changez d'onglet.")
st.sidebar.markdown("---")
st.sidebar.caption("PACTE 3A – HESTIM – Analyse des données")
