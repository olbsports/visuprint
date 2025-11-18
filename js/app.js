/**
 * IMPRIXO - Application JavaScript Principale
 * Gestion panier, APIs, configurateur
 */

// ============================================
// CONFIGURATION
// ============================================

const API_BASE = '/api';
const CART_STORAGE_KEY = 'imprixo_cart';

// ============================================
// GESTION PANIER (LocalStorage)
// ============================================

class Cart {
    constructor() {
        this.items = this.load();
        this.updateDisplay();
    }

    load() {
        const data = localStorage.getItem(CART_STORAGE_KEY);
        return data ? JSON.parse(data) : [];
    }

    save() {
        localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(this.items));
        this.updateDisplay();
    }

    add(product) {
        // Vérifier si produit existe déjà
        const existing = this.items.find(item =>
            item.code === product.code &&
            JSON.stringify(item.config) === JSON.stringify(product.config)
        );

        if (existing) {
            existing.quantite += product.quantite;
        } else {
            this.items.push({
                ...product,
                id: Date.now()
            });
        }

        this.save();
        this.showNotification('Produit ajouté au panier');
    }

    remove(itemId) {
        this.items = this.items.filter(item => item.id !== itemId);
        this.save();
    }

    update(itemId, quantite) {
        const item = this.items.find(i => i.id === itemId);
        if (item) {
            item.quantite = quantite;
            this.save();
        }
    }

    clear() {
        this.items = [];
        this.save();
    }

    getTotal() {
        return this.items.reduce((sum, item) => sum + (item.prix * item.quantite), 0);
    }

    getCount() {
        return this.items.reduce((sum, item) => sum + item.quantite, 0);
    }

    updateDisplay() {
        const countEl = document.getElementById('cartCount');
        if (countEl) {
            const count = this.getCount();
            countEl.textContent = count;
            countEl.style.display = count > 0 ? 'flex' : 'none';
        }
    }

    showNotification(message) {
        // Créer notification temporaire
        const notif = document.createElement('div');
        notif.className = 'notification';
        notif.textContent = message;
        notif.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #27ae60;
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            z-index: 10000;
            animation: slideIn 0.3s ease;
        `;

        document.body.appendChild(notif);

        setTimeout(() => {
            notif.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notif.remove(), 300);
        }, 3000);
    }
}

// Instance globale du panier
const cart = new Cart();

// ============================================
// API HELPERS
// ============================================

async function apiCall(endpoint, method = 'GET', data = null) {
    const options = {
        method,
        headers: {
            'Content-Type': 'application/json'
        }
    };

    if (data && method !== 'GET') {
        options.body = JSON.stringify(data);
    }

    try {
        const response = await fetch(`${API_BASE}${endpoint}`, options);
        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.error || 'Erreur API');
        }

        return result;
    } catch (error) {
        console.error('API Error:', error);
        throw error;
    }
}

// ============================================
// CHARGEMENT PRODUITS
// ============================================

async function loadProducts(filters = {}) {
    try {
        const queryParams = new URLSearchParams(filters).toString();
        const endpoint = queryParams ? `/produits.php?${queryParams}` : '/produits.php';

        const response = await apiCall(endpoint);
        return response.produits || [];
    } catch (error) {
        console.error('Erreur chargement produits:', error);
        return [];
    }
}

async function loadProduct(code) {
    try {
        const response = await apiCall(`/produits.php?code=${code}`);
        return response.produit || null;
    } catch (error) {
        console.error('Erreur chargement produit:', error);
        return null;
    }
}

// ============================================
// CALCULATEUR PRIX DÉGRESSIF
// ============================================

function calculatePrice(product, surface) {
    if (!product) return 0;

    if (surface <= 10) return product.prix_0_10;
    if (surface <= 50) return product.prix_11_50;
    if (surface <= 100) return product.prix_51_100;
    if (surface <= 300) return product.prix_101_300;
    return product.prix_300_plus;
}

// ============================================
// CONFIGURATEUR PRODUIT
// ============================================

class ProductConfigurator {
    constructor(productCode) {
        this.productCode = productCode;
        this.product = null;
        this.config = {
            largeur: 100,
            hauteur: 100,
            quantite: 1,
            impression: 'simple',
            oeillets: false,
            decoupe: false,
            lamination: false
        };

        this.init();
    }

    async init() {
        this.product = await loadProduct(this.productCode);
        if (this.product) {
            this.render();
            this.attachEvents();
            this.calculate();
        }
    }

    getSurface() {
        return (this.config.largeur * this.config.hauteur) / 10000; // cm² -> m²
    }

    getSurfaceTotal() {
        return this.getSurface() * this.config.quantite;
    }

    calculate() {
        const surface = this.getSurface();
        const surfaceTotal = this.getSurfaceTotal();
        const prixM2 = calculatePrice(this.product, surfaceTotal);

        let total = prixM2 * surfaceTotal;

        // Options
        if (this.config.impression === 'double') {
            total += (this.product.prix_double_face - this.product.prix_simple_face) * surfaceTotal;
        }

        if (this.config.oeillets) {
            total += 2 * surfaceTotal;
        }

        if (this.config.decoupe) {
            total += 1.5 * surfaceTotal;
        }

        if (this.config.lamination) {
            total += 5 * surfaceTotal;
        }

        this.updateDisplay(prixM2, total);
    }

    updateDisplay(prixM2, total) {
        const surfaceEl = document.getElementById('surface');
        const prixM2El = document.getElementById('prixM2');
        const totalEl = document.getElementById('total');

        if (surfaceEl) surfaceEl.textContent = this.getSurface().toFixed(2);
        if (prixM2El) prixM2El.textContent = prixM2.toFixed(2);
        if (totalEl) totalEl.textContent = total.toFixed(2);
    }

    render() {
        // Le HTML du configurateur est déjà dans la page
        // On remplit juste les valeurs
        const nomEl = document.getElementById('productName');
        if (nomEl) nomEl.textContent = this.product.nom;
    }

    attachEvents() {
        // Écouter les changements
        ['largeur', 'hauteur', 'quantite'].forEach(field => {
            const el = document.getElementById(field);
            if (el) {
                el.addEventListener('input', (e) => {
                    this.config[field] = parseFloat(e.target.value) || 0;
                    this.calculate();
                });
            }
        });

        const impressionEl = document.getElementById('impression');
        if (impressionEl) {
            impressionEl.addEventListener('change', (e) => {
                this.config.impression = e.target.value;
                this.calculate();
            });
        }

        ['oeillets', 'decoupe', 'lamination'].forEach(option => {
            const el = document.getElementById(option);
            if (el) {
                el.addEventListener('change', (e) => {
                    this.config[option] = e.target.checked;
                    this.calculate();
                });
            }
        });

        // Bouton ajouter au panier
        const addBtn = document.getElementById('addToCart');
        if (addBtn) {
            addBtn.addEventListener('click', () => this.addToCart());
        }
    }

    addToCart() {
        const surfaceTotal = this.getSurfaceTotal();
        const prixM2 = calculatePrice(this.product, surfaceTotal);

        let prix = prixM2 * this.getSurface();

        if (this.config.impression === 'double') {
            prix += (this.product.prix_double_face - this.product.prix_simple_face) * this.getSurface();
        }

        if (this.config.oeillets) prix += 2 * this.getSurface();
        if (this.config.decoupe) prix += 1.5 * this.getSurface();
        if (this.config.lamination) prix += 5 * this.getSurface();

        cart.add({
            code: this.product.code,
            nom: this.product.nom,
            config: { ...this.config },
            surface: this.getSurface(),
            prix: prix,
            quantite: this.config.quantite
        });
    }
}

// ============================================
// UPLOAD FICHIER
// ============================================

async function uploadFile(file, panierLigneId = null) {
    const formData = new FormData();
    formData.append('fichier', file);

    if (panierLigneId) {
        formData.append('ligne_panier_id', panierLigneId);
    }

    try {
        const response = await fetch('/api/upload-fichier.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.error || 'Erreur upload');
        }

        return result;
    } catch (error) {
        console.error('Upload error:', error);
        throw error;
    }
}

// ============================================
// CHECKOUT / COMMANDE
// ============================================

async function createOrder(orderData) {
    try {
        const response = await apiCall('/commandes.php', 'POST', orderData);
        return response;
    } catch (error) {
        console.error('Erreur création commande:', error);
        throw error;
    }
}

async function processPayment(commandeId) {
    try {
        const response = await apiCall('/paiement.php', 'POST', { commande_id: commandeId });

        if (response.checkout_url) {
            // Rediriger vers Stripe
            window.location.href = response.checkout_url;
        }

        return response;
    } catch (error) {
        console.error('Erreur paiement:', error);
        throw error;
    }
}

// ============================================
// AUTHENTIFICATION CLIENT
// ============================================

async function loginClient(email, password) {
    try {
        const response = await apiCall('/auth-client.php', 'POST', { email, password, action: 'login' });
        return response;
    } catch (error) {
        console.error('Erreur connexion:', error);
        throw error;
    }
}

async function registerClient(data) {
    try {
        const response = await apiCall('/auth-client.php', 'POST', { ...data, action: 'register' });
        return response;
    } catch (error) {
        console.error('Erreur inscription:', error);
        throw error;
    }
}

// ============================================
// UTILITAIRES
// ============================================

function formatPrice(price) {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'EUR'
    }).format(price);
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// ============================================
// INITIALISATION
// ============================================

document.addEventListener('DOMContentLoaded', () => {
    // Mettre à jour le compteur panier au chargement
    cart.updateDisplay();

    // Si on est sur une page produit, initialiser le configurateur
    const urlParams = new URLSearchParams(window.location.search);
    const productCode = urlParams.get('code');

    if (productCode && document.getElementById('productConfigurator')) {
        new ProductConfigurator(productCode);
    }
});

// Exposer les fonctions globalement si besoin
window.Imprixo = {
    cart,
    loadProducts,
    loadProduct,
    uploadFile,
    createOrder,
    processPayment,
    loginClient,
    registerClient,
    formatPrice
};
