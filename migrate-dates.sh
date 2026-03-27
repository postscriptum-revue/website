#!/bin/bash
#
# Migration des champs de date dans les fichiers de contenu Kirby.
#
# Ce script standardise les noms de champs de date :
#   1. Ajoute "Issued-date:" aux fichiers qui ont "Date:" mais pas "Issued-date:"
#      (review.txt, creation.txt, issue.txt)
#      Le nouveau champ est ajouté dans son propre bloc ---- (requis par Kirby).
#   2. Renomme "Submit-date:" en "Date-end:" dans les call-for-papers.txt
#
# Gère les fins de ligne Windows (CRLF) et Unix (LF).
#
# Usage :
#   chmod +x migrate-dates.sh
#   ./migrate-dates.sh [chemin/vers/content]
#
# Par défaut, utilise le dossier "content" à côté du script.

set -euo pipefail

CONTENT_DIR="${1:-$(dirname "$0")/content}"

if [ ! -d "$CONTENT_DIR" ]; then
    echo "Erreur : dossier '$CONTENT_DIR' introuvable."
    exit 1
fi

echo "=== Migration des dates ==="
echo "Dossier : $CONTENT_DIR"
echo ""

# --------------------------------------------------------------------------
# 1. Ajouter Issued-date: là où il manque (reviews, creations, issues)
#    Chaque champ Kirby doit être dans son propre bloc séparé par ----
# --------------------------------------------------------------------------
count=0
for pattern in "review.txt" "creation.txt" "issue.txt"; do
    while IFS= read -r file; do
        # Ignorer les fichiers qui ont déjà Issued-date:
        if grep -q "^Issued-date:" "$file"; then
            continue
        fi

        # Extraire la valeur de Date: (en retirant un éventuel \r)
        date_val=$(grep "^Date:" "$file" | head -1 | sed 's/^Date: //' | tr -d '\r')

        if [ -z "$date_val" ]; then
            echo "  SKIP (Date: vide) : $file"
            continue
        fi

        # Détecter le type de fin de ligne du fichier
        if grep -qP '\r\n' "$file" 2>/dev/null; then
            EOL=$'\r\n'
        else
            EOL=$'\n'
        fi

        # Insérer un nouveau bloc "Issued-date:" après le bloc "Date:"
        # On cherche la ligne "----" qui suit "Date:" et on insère après
        perl -i -0777 -pe "s/(Date: [^\r\n]*\r?\n\r?\n----)/\$1\n\nIssued-date: ${date_val}\n\n----/" "$file"

        # Vérifier que l'insertion a fonctionné
        if grep -q "^Issued-date:" "$file"; then
            count=$((count + 1))
        else
            echo "  ERREUR : insertion échouée pour $file"
        fi
    done < <(grep -rl "^Date:" "$CONTENT_DIR" --include="$pattern" 2>/dev/null || true)
done

echo "1. Issued-date ajouté à $count fichiers."

# --------------------------------------------------------------------------
# 2. Renommer Submit-date: en Date-end: dans les call-for-papers
# --------------------------------------------------------------------------
count=0
while IFS= read -r file; do
    # Ne renommer que si le fichier n'a pas déjà un Date-end:
    if grep -q "^Date-end:" "$file"; then
        echo "  SKIP (Date-end: existe déjà) : $file"
        continue
    fi

    sed -i 's/^Submit-date:/Date-end:/' "$file"
    count=$((count + 1))
done < <(grep -rl "^Submit-date:" "$CONTENT_DIR" --include="call-for-papers.txt" 2>/dev/null || true)

echo "2. Submit-date → Date-end dans $count fichiers call-for-papers."

echo ""
echo "=== Migration terminée ==="
