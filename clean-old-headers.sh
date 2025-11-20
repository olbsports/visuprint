#!/bin/bash
# Script pour nettoyer les anciennes références header/footer.html dans tous les fichiers PHP

echo "🧹 Nettoyage des anciennes références header/footer dynamiques..."

# Trouver tous les fichiers PHP contenant les anciennes références
files=$(grep -rl "header-placeholder\|footer-placeholder" /home/user/visuprint --include="*.php" 2>/dev/null)

count=0
for file in $files; do
    echo "Nettoyage: $file"

    # Supprimer les blocs header dynamique
    sed -i '/<!-- HEADER DYNAMIQUE -->/,/<script>fetch.*header\.html.*<\/script>/d' "$file"
    sed -i '/<div id="header-placeholder"><\/div>/d' "$file"

    # Supprimer les blocs footer dynamique
    sed -i '/<!-- FOOTER DYNAMIQUE -->/,/<script>fetch.*footer\.html.*<\/script>/d' "$file"
    sed -i '/<div id="footer-placeholder"><\/div>/d' "$file"

    count=$((count + 1))
done

echo "✅ $count fichiers nettoyés!"
