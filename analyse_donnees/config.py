# Configuration pour la connexion à la base (optionnel)
# Lit le .env à la racine du projet Laravel
import os
from pathlib import Path

# Racine du projet Laravel
PROJECT_ROOT = Path(__file__).resolve().parent.parent
DATA_DIR = Path(__file__).resolve().parent / "data"
DATA_DIR.mkdir(exist_ok=True)

def load_env():
    """Charge les variables du .env Laravel."""
    env_path = PROJECT_ROOT / ".env"
    if not env_path.exists():
        return {}
    env = {}
    with open(env_path, encoding="utf-8") as f:
        for line in f:
            line = line.strip()
            if line and not line.startswith("#") and "=" in line:
                key, _, value = line.partition("=")
                env[key.strip()] = value.strip().strip('"').strip("'")
    return env

def get_db_config():
    """Retourne la config MySQL pour connexion Python."""
    env = load_env()
    return {
        "host": env.get("DB_HOST", "127.0.0.1"),
        "port": int(env.get("DB_PORT", 3306)),
        "user": env.get("DB_USERNAME", "root"),
        "password": env.get("DB_PASSWORD", ""),
        "database": env.get("DB_DATABASE", "plateformehestim"),
        "charset": "utf8mb4",
    }
