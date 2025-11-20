<?php
$pageTitle = 'Catalogue Produits - Imprixo | 54 Supports d\'Impression';
$pageDescription = 'Découvrez nos 54 supports d\'impression professionnels : Forex, Dibond, Bâches, Textiles. Prix dégressifs • Livraison Express • Qualité Pro';
include __DIR__ . '/includes/header.php';
?>

<div id="root"></div>

    <script type="text/babel">
    const { useState, useEffect, useMemo } = React;

    const PRODUCTS_DATA = [
        { code: 'FX-2MM', nom: 'Forex 2mm', categorie: 'Supports rigides PVC', prixMin: 20, prixMax: 32, tags: ['pvc', 'leger', 'interieur'] },
        { code: 'FX-3MM', nom: 'Forex 3mm', categorie: 'Supports rigides PVC', prixMin: 20, prixMax: 32, tags: ['pvc', 'standard', 'populaire'] },
        { code: 'FX-5MM', nom: 'Forex 5mm', categorie: 'Supports rigides PVC', prixMin: 25, prixMax: 40, tags: ['pvc', 'rigide', 'exterieur'] },
        { code: 'FX-8MM', nom: 'Forex 8mm', categorie: 'Supports rigides PVC', prixMin: 30, prixMax: 48, tags: ['pvc', 'epais', 'premium'] },
        { code: 'FX-10MM', nom: 'Forex 10mm', categorie: 'Supports rigides PVC', prixMin: 35, prixMax: 55, tags: ['pvc', 'ultra-rigide'] },
        { code: 'DB-2MM', nom: 'Dibond 2mm', categorie: 'Supports rigides PVC', prixMin: 30, prixMax: 50, tags: ['aluminium', 'premium', 'exterieur'] },
        { code: 'DB-3MM', nom: 'Dibond 3mm', categorie: 'Supports rigides PVC', prixMin: 35, prixMax: 58, tags: ['aluminium', 'rigide'] },
        { code: 'DB-3MM-BRUSH', nom: 'Dibond 3mm Brossé', categorie: 'Supports rigides PVC', prixMin: 40, prixMax: 65, tags: ['aluminium', 'premium', 'brosse'] },
        { code: 'CP-8MM', nom: 'Channel Plate 8mm', categorie: 'Supports rigides PVC', prixMin: 45, prixMax: 70, tags: ['panneau', 'alveolaire'] },
        { code: 'HIPS-3MM', nom: 'HIPS 3mm', categorie: 'Supports rigides PVC', prixMin: 22, prixMax: 38, tags: ['plastique', 'economique'] },
        { code: 'PALB-3MM', nom: 'Palboard 3mm', categorie: 'Supports rigides PVC', prixMin: 25, prixMax: 42, tags: ['carton', 'leger'] },
        { code: 'ACRYL-3MM', nom: 'Acrylique 3mm', categorie: 'Supports rigides PVC', prixMin: 50, prixMax: 80, tags: ['transparent', 'premium', 'plexiglas'] },
        { code: 'POLYGLANS-115G', nom: 'Polyglans 115g', categorie: 'Supports légers', prixMin: 15, prixMax: 25, tags: ['papier', 'mat', 'interieur'] },
        { code: 'POLYGLANS-115G-B1', nom: 'Polyglans 115g B1', categorie: 'Supports légers', prixMin: 18, prixMax: 28, tags: ['papier', 'b1', 'ignifuge'] },
        { code: 'AIR-POLYGLANS-110G', nom: 'Air Polyglans 110g', categorie: 'Supports légers', prixMin: 16, prixMax: 26, tags: ['papier', 'leger'] },
        { code: 'AIR-POLYGLANS-110G-B1', nom: 'Air Polyglans 110g B1', categorie: 'Supports légers', prixMin: 19, prixMax: 29, tags: ['papier', 'b1'] },
        { code: 'SATIN-140G-B1', nom: 'Satin 140g B1', categorie: 'Supports légers', prixMin: 20, prixMax: 32, tags: ['papier', 'satin', 'b1'] },
        { code: 'DECOR-205G-B1', nom: 'Decor 205g B1', categorie: 'Supports légers', prixMin: 22, prixMax: 35, tags: ['papier', 'decor', 'b1'] },
        { code: 'UV-DECOR-205-B1', nom: 'UV Decor 205 B1', categorie: 'Supports légers', prixMin: 24, prixMax: 38, tags: ['papier', 'uv', 'b1'] },
        { code: 'DECOR-BLOCKOUT-BLACK-BACK-250-', nom: 'Decor Blockout 250', categorie: 'Supports légers', prixMin: 26, prixMax: 40, tags: ['blockout'] },
        { code: 'UV-DECOR-BLOCKOUT-BLACK-BACK-2', nom: 'UV Decor Blockout 250', categorie: 'Supports légers', prixMin: 28, prixMax: 42, tags: ['blockout', 'uv'] },
        { code: 'DECOR-BLOCKOUT-400G-DOUBLE-SID', nom: 'Decor Blockout 400g', categorie: 'Supports légers', prixMin: 30, prixMax: 45, tags: ['blockout', 'double-face'] },
        { code: 'SAMBA-195G-B1', nom: 'Samba 195g B1', categorie: 'Supports légers', prixMin: 21, prixMax: 34, tags: ['papier', 'b1'] },
        { code: 'STRETCH-240G-B1', nom: 'Stretch 240g B1', categorie: 'Supports légers', prixMin: 25, prixMax: 38, tags: ['textile', 'stretch', 'b1'] },
        { code: 'DECOR-BACKLITE-165-B1', nom: 'Decor Backlite 165 B1', categorie: 'Supports légers', prixMin: 23, prixMax: 36, tags: ['backlite', 'b1'] },
        { code: 'STRETCH-BACKLITE-210G-B1', nom: 'Stretch Backlite 210g B1', categorie: 'Supports légers', prixMin: 27, prixMax: 40, tags: ['backlite', 'stretch'] },
        { code: 'MAYA-115-B1', nom: 'Maya 115 B1', categorie: 'Supports légers', prixMin: 19, prixMax: 30, tags: ['papier', 'b1'] },
        { code: 'POLYTENT-220', nom: 'Polytent 220', categorie: 'Bâches & Mesh', prixMin: 18, prixMax: 30, tags: ['bache', 'exterieur'] },
        { code: 'POLYTENT-SUN-285', nom: 'Polytent Sun 285', categorie: 'Bâches & Mesh', prixMin: 22, prixMax: 35, tags: ['bache', 'sun'] },
        { code: 'KLIMT-320-B1', nom: 'Klimt 320 B1', categorie: 'Bâches & Mesh', prixMin: 25, prixMax: 40, tags: ['bache', 'b1'] },
        { code: 'DISCOVERY-FR-275-B1', nom: 'Discovery FR 275 B1', categorie: 'Bâches & Mesh', prixMin: 24, prixMax: 38, tags: ['bache', 'ignifuge'] },
        { code: 'DISCOVERY-FR-275', nom: 'Discovery FR 275', categorie: 'Bâches & Mesh', prixMin: 22, prixMax: 35, tags: ['bache'] },
        { code: 'FRONTLIT-COATED-500G-B1', nom: 'Frontlit 500g B1', categorie: 'Bâches & Mesh', prixMin: 28, prixMax: 45, tags: ['bache', 'frontlit', 'b1'] },
        { code: 'MESH-330-B1', nom: 'Mesh 330 B1', categorie: 'Bâches & Mesh', prixMin: 20, prixMax: 32, tags: ['mesh', 'b1'] },
        { code: 'MESH-270-B1-LIGHT', nom: 'Mesh 270 B1 Light', categorie: 'Bâches & Mesh', prixMin: 18, prixMax: 28, tags: ['mesh', 'leger'] },
        { code: 'EASY-MESH-270', nom: 'Easy Mesh 270', categorie: 'Bâches & Mesh', prixMin: 17, prixMax: 27, tags: ['mesh', 'economique'] },
        { code: 'MESH-330-B1-DOUBLE-SIDED', nom: 'Mesh 330 B1 Double', categorie: 'Bâches & Mesh', prixMin: 25, prixMax: 38, tags: ['mesh', 'double-face'] },
        { code: 'SOUND-MESH-250-B1', nom: 'Sound Mesh 250 B1', categorie: 'Bâches & Mesh', prixMin: 22, prixMax: 35, tags: ['mesh', 'acoustique'] },
        { code: 'BLOCKOUT-650-B1', nom: 'Blockout 650 B1', categorie: 'Bâches & Mesh', prixMin: 30, prixMax: 48, tags: ['blockout', 'b1'] },
        { code: 'POLYCANVAS-270', nom: 'Polycanvas 270', categorie: 'Bâches & Mesh', prixMin: 24, prixMax: 38, tags: ['canvas', 'textile'] },
        { code: 'PVC-BACKLITE-150-B1', nom: 'PVC Backlite 150 B1', categorie: 'Bâches & Mesh', prixMin: 21, prixMax: 33, tags: ['pvc', 'backlite'] },
        { code: 'PVC-BACKLITE-510', nom: 'PVC Backlite 510', categorie: 'Bâches & Mesh', prixMin: 28, prixMax: 45, tags: ['pvc', 'backlite', 'lourd'] },
        { code: 'PVC-POLYTENT-330-B1', nom: 'PVC Polytent 330 B1', categorie: 'Bâches & Mesh', prixMin: 26, prixMax: 40, tags: ['pvc', 'b1'] },
        { code: 'BLOCKOUT-ROLL-UP-440', nom: 'Blockout Roll-UP 440', categorie: 'Bâches & Mesh', prixMin: 29, prixMax: 45, tags: ['rollup', 'blockout'] },
        { code: 'ROLL-UP-FILM-205', nom: 'Roll-Up Film 205', categorie: 'Bâches & Mesh', prixMin: 20, prixMax: 32, tags: ['rollup', 'film'] },
        { code: 'FLOORING-FOAM-1000-M2', nom: 'Flooring Foam 1000', categorie: 'Supports légers', prixMin: 35, prixMax: 55, tags: ['sol', 'foam'] },
        { code: 'ECO-POLYGLANS-115-B1', nom: 'ECO Polyglans 115 B1', categorie: 'Supports légers', prixMin: 17, prixMax: 27, tags: ['eco', 'papier', 'b1'] },
        { code: 'ECO-AIR-POLYGLANS-110-B1', nom: 'ECO Air Polyglans 110 B1', categorie: 'Supports légers', prixMin: 18, prixMax: 28, tags: ['eco', 'papier'] },
        { code: 'ECO-DECOR-205-B1', nom: 'ECO Decor 205 B1', categorie: 'Supports légers', prixMin: 21, prixMax: 33, tags: ['eco', 'decor', 'b1'] },
        { code: 'TEXTIEL-MESH-220-B1', nom: 'Textiel Mesh 220 B1', categorie: 'Bâches & Mesh', prixMin: 23, prixMax: 36, tags: ['textile', 'mesh'] },
        { code: 'POLYMESH-180', nom: 'Polymesh 180', categorie: 'Bâches & Mesh', prixMin: 16, prixMax: 26, tags: ['mesh', 'leger'] },
        { code: 'KAVALAN-360-B1', nom: 'Kavalan 360 B1', categorie: 'Bâches & Mesh', prixMin: 27, prixMax: 42, tags: ['bache', 'premium', 'b1'] },
        { code: 'POLYGLANS-CICLO-115-B1', nom: 'Polyglans CiCLO 115 B1', categorie: 'Supports légers', prixMin: 19, prixMax: 30, tags: ['eco', 'ciclo', 'b1'] },
        { code: 'AIR-POLYGLANS-CICLO-110-B1', nom: 'Air Polyglans CiCLO 110 B1', categorie: 'Supports légers', prixMin: 20, prixMax: 31, tags: ['eco', 'ciclo'] }
    ];

    function CataloguePage() {
        const [searchQuery, setSearchQuery] = useState('');
        const [selectedCategory, setSelectedCategory] = useState('Toutes');
        const [selectedTags, setSelectedTags] = useState([]);
        const [sortBy, setSortBy] = useState('nom');
        const [priceRange, setPriceRange] = useState([0, 100]);

        const categories = ['Toutes', ...new Set(PRODUCTS_DATA.map(p => p.categorie))];
        const allTags = [...new Set(PRODUCTS_DATA.flatMap(p => p.tags))].sort();

        const filteredProducts = useMemo(() => {
            let filtered = PRODUCTS_DATA;

            // Filtre catégorie
            if (selectedCategory !== 'Toutes') {
                filtered = filtered.filter(p => p.categorie === selectedCategory);
            }

            // Filtre recherche
            if (searchQuery) {
                const query = searchQuery.toLowerCase();
                filtered = filtered.filter(p =>
                    p.nom.toLowerCase().includes(query) ||
                    p.code.toLowerCase().includes(query) ||
                    p.tags.some(tag => tag.includes(query))
                );
            }

            // Filtre tags
            if (selectedTags.length > 0) {
                filtered = filtered.filter(p =>
                    selectedTags.every(tag => p.tags.includes(tag))
                );
            }

            // Filtre prix
            filtered = filtered.filter(p =>
                p.prixMin >= priceRange[0] && p.prixMax <= priceRange[1]
            );

            // Tri
            filtered.sort((a, b) => {
                if (sortBy === 'nom') return a.nom.localeCompare(b.nom);
                if (sortBy === 'prix-asc') return a.prixMin - b.prixMin;
                if (sortBy === 'prix-desc') return b.prixMax - a.prixMax;
                return 0;
            });

            return filtered;
        }, [searchQuery, selectedCategory, selectedTags, sortBy, priceRange]);

        const toggleTag = (tag) => {
            setSelectedTags(prev =>
                prev.includes(tag)
                    ? prev.filter(t => t !== tag)
                    : [...prev, tag]
            );
        };

        const resetFilters = () => {
            setSearchQuery('');
            setSelectedCategory('Toutes');
            setSelectedTags([]);
            setSortBy('nom');
            setPriceRange([0, 100]);
        };

        return (
            <div className="container mx-auto px-4 py-8">
                <div className="max-w-7xl mx-auto">

                    <div className="mb-8">
                        <h1 className="text-4xl md:text-5xl font-black text-gray-900 mb-3">Catalogue Produits</h1>
                        <p className="text-xl text-gray-600">54 supports d'impression professionnels pour tous vos besoins</p>
                    </div>

                    <div className="grid lg:grid-cols-4 gap-8">

                        <div className="lg:col-span-1">
                            <div className="filter-sidebar bg-white rounded-xl shadow-lg p-6">
                                <div className="flex items-center justify-between mb-6">
                                    <h2 className="text-xl font-bold">Filtres</h2>
                                    <button onClick={resetFilters} className="text-sm text-red-600 hover:text-red-700 font-bold">
                                        Réinitialiser
                                    </button>
                                </div>

                                <div className="space-y-6">

                                    <div>
                                        <label className="block text-sm font-bold mb-3">🔍 Recherche</label>
                                        <input
                                            type="text"
                                            value={searchQuery}
                                            onChange={(e) => setSearchQuery(e.target.value)}
                                            placeholder="Nom, code, tag..."
                                            className="w-full px-4 py-2 border-2 rounded-lg focus:border-red-600 focus:outline-none"
                                        />
                                    </div>

                                    <div>
                                        <label className="block text-sm font-bold mb-3">📁 Catégorie</label>
                                        <select
                                            value={selectedCategory}
                                            onChange={(e) => setSelectedCategory(e.target.value)}
                                            className="w-full px-4 py-2 border-2 rounded-lg focus:border-red-600 focus:outline-none">
                                            {categories.map(cat => (
                                                <option key={cat} value={cat}>{cat}</option>
                                            ))}
                                        </select>
                                    </div>

                                    <div>
                                        <label className="block text-sm font-bold mb-3">🏷️ Tags ({selectedTags.length})</label>
                                        <div className="space-y-2 max-h-64 overflow-y-auto">
                                            {allTags.map(tag => (
                                                <label key={tag} className="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded">
                                                    <input
                                                        type="checkbox"
                                                        checked={selectedTags.includes(tag)}
                                                        onChange={() => toggleTag(tag)}
                                                        className="mr-2"
                                                    />
                                                    <span className="text-sm">{tag}</span>
                                                </label>
                                            ))}
                                        </div>
                                    </div>

                                    <div>
                                        <label className="block text-sm font-bold mb-3">💰 Prix (€/m²)</label>
                                        <div className="space-y-2">
                                            <input
                                                type="range"
                                                min="0"
                                                max="100"
                                                value={priceRange[1]}
                                                onChange={(e) => setPriceRange([priceRange[0], parseInt(e.target.value)])}
                                                className="w-full"
                                            />
                                            <div className="text-sm text-gray-600">
                                                {priceRange[0]}€ - {priceRange[1]}€
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div className="lg:col-span-3">

                            <div className="bg-white rounded-xl shadow-sm p-4 mb-6">
                                <div className="flex items-center justify-between">
                                    <div className="text-sm text-gray-600">
                                        <strong>{filteredProducts.length}</strong> produits trouvés
                                    </div>
                                    <select
                                        value={sortBy}
                                        onChange={(e) => setSortBy(e.target.value)}
                                        className="px-4 py-2 border-2 rounded-lg focus:border-red-600 focus:outline-none text-sm">
                                        <option value="nom">Trier par nom</option>
                                        <option value="prix-asc">Prix croissant</option>
                                        <option value="prix-desc">Prix décroissant</option>
                                    </select>
                                </div>
                            </div>

                            <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                                {filteredProducts.map(product => (
                                    <a
                                        key={product.code}
                                        href={`/produit/${product.code}.html`}
                                        className="product-card bg-white rounded-xl shadow-lg overflow-hidden">

                                        <div className="h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                            <div className="text-6xl">📄</div>
                                        </div>

                                        <div className="p-6">
                                            <div className="text-xs text-gray-500 mb-2">{product.code}</div>
                                            <h3 className="text-lg font-bold text-gray-900 mb-2">{product.nom}</h3>
                                            <div className="text-xs text-gray-600 mb-3">{product.categorie}</div>

                                            <div className="flex flex-wrap gap-1 mb-4">
                                                {product.tags.slice(0, 3).map(tag => (
                                                    <span key={tag} className="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded">
                                                        {tag}
                                                    </span>
                                                ))}
                                            </div>

                                            <div className="flex items-center justify-between">
                                                <div>
                                                    <div className="text-xs text-gray-500">À partir de</div>
                                                    <div className="text-2xl font-black text-red-600">{product.prixMin}€</div>
                                                    <div className="text-xs text-gray-500">/ m²</div>
                                                </div>
                                                <button className="btn-primary text-white px-4 py-2 rounded-lg text-sm font-bold">
                                                    Voir →
                                                </button>
                                            </div>
                                        </div>
                                    </a>
                                ))}
                            </div>

                            {filteredProducts.length === 0 && (
                                <div className="bg-white rounded-xl shadow-lg p-12 text-center">
                                    <div className="text-6xl mb-4">🔍</div>
                                    <h3 className="text-2xl font-bold mb-2">Aucun produit trouvé</h3>
                                    <p className="text-gray-600 mb-6">Essayez de modifier vos critères de recherche</p>
                                    <button onClick={resetFilters} className="btn-primary text-white px-6 py-3 rounded-lg font-bold">
                                        Réinitialiser les filtres
                                    </button>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        );
    }

    ReactDOM.render(<CataloguePage />, document.getElementById('root'));
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
