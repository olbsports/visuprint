#!/bin/bash
# Corriger les chemins d'includes selon la profondeur

# Dossiers racine : /includes/header.php
for file in /home/user/visuprint/*.php; do
    [ -f "$file" ] || continue
    [ "$(basename "$file")" = "convert-to-includes.php" ] && continue
    sed -i "s|include __DIR__ \. '/includes/header\.php'|include __DIR__ . '/includes/header.php'|g" "$file"
    sed -i "s|include __DIR__ \. '/includes/footer\.php'|include __DIR__ . '/includes/footer.php'|g" "$file"
done

# Sous-dossiers niveau 1 : /../includes/header.php
for dir in categorie compte application guides; do
    for file in /home/user/visuprint/$dir/*.php; do
        [ -f "$file" ] || continue
        sed -i "s|include __DIR__ \. '/includes/header\.php'|include __DIR__ . '/../includes/header.php'|g" "$file"
        sed -i "s|include __DIR__ \. '/includes/footer\.php'|include __DIR__ . '/../includes/footer.php'|g" "$file"
    done
done

echo "✅ Chemins d'includes corrigés"
