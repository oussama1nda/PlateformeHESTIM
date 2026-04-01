# Analyse des données – Plateforme HESTIM (PACTE 3A)

Ce dossier contient la **construction du dataset**, l’**exploration/visualisation** et l’**analyse des performances** du système.

## 1. Construction du dataset

- **Script** : `01_construction_dataset.py`
- **Rôle** : extraire depuis la base MySQL (Laravel) les données :
  - **Salles** : `id`, `nom`, `numero`, `capacite`, `type`, `equipements`, `disponible`
  - **Enseignants** : `id`, `name`, `departement_id`, `departement_nom`, **volume horaire total** (somme des `volume_horaire` des cours)
  - **Réservations** : `id`, `user_id`, `salle_id`, `date`, `heure_debut`, `heure_fin`, `motif`, `statut`, `created_at`, `updated_at`
  - **Emploi du temps** : créneaux (pour calcul du taux d’occupation des salles)

Les fichiers CSV sont enregistrés dans `data/`.

**Utilisation :**
```bash
cd analyse_donnees
pip install -r requirements.txt
python 01_construction_dataset.py
```
La connexion MySQL utilise le fichier `.env` à la racine du projet Laravel (`DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

---

## 2. Exploration et visualisation

- **Script** : `02_exploration_visualisation.py`
- **Rôle** : charger les CSV, produire des graphiques Plotly :
  - Capacité des salles (barres)
  - Répartition des salles par type (camembert)
  - Volume horaire par département et par enseignant (barres)
  - Réservations par statut (camembert), par mois, par salle

**Utilisation :**
```bash
python 02_exploration_visualisation.py
```
Génère des fichiers HTML dans `data/` (ex. `viz_salles_capacite.html`).

---

## 3. Analyse des performances du système

- **Script** : `03_analyse_performances.py`
- **Rôle** : calcul des KPI :
  - **Réservations** : taux d’approbation, délai moyen de traitement, nombre en attente
  - **Occupation** : taux d’occupation des salles (heures utilisées / heures ouvrables)
  - **Pédagogie** : volume horaire par département

**Utilisation :**
```bash
python 03_analyse_performances.py
```

---

## Dashboard Streamlit (tout-en-un)

L’application `app_dashboard.py` regroupe les trois étapes dans une interface web avec onglets :

1. **Construction du dataset** : bouton pour lancer l’extraction MySQL, aperçu des CSV.
2. **Exploration et visualisation** : graphiques interactifs (salles, enseignants, réservations).
3. **Performances du système** : KPI et graphiques (taux d’approbation, délai, occupation).

**Lancer le dashboard :**
```bash
cd analyse_donnees
pip install -r requirements.txt
streamlit run app_dashboard.py
```

Ouvrir l’URL affichée (souvent `http://localhost:8501`).
