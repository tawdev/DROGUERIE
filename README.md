# Site E-commerce de Droguerie - Maroc

Site e-commerce complet pour une droguerie en ligne destiné au marché marocain.

## Technologies utilisées

- **PHP** (procédural avec structure MVC simple)
- **MySQL** pour la base de données
- **HTML/CSS** pour la mise en page
- **JavaScript** pour les interactions

## Structure du projet

```
droguerie/
├── admin/              # Zone d'administration
│   ├── includes/       # Header et footer admin
│   ├── dashboard.php   # Tableau de bord
│   ├── produits.php    # Gestion des produits
│   ├── commandes.php   # Gestion des commandes
│   └── ...
├── assets/
│   ├── css/
│   │   ├── style.css   # CSS principal
│   │   └── admin.css   # CSS admin
│   └── js/
│       └── main.js     # JavaScript principal
├── config/
│   ├── config.php      # Configuration générale
│   └── database.php    # Configuration DB
├── includes/
│   ├── functions.php   # Fonctions utilitaires
│   ├── header.php      # Header du site
│   └── footer.php      # Footer du site
├── sql/
│   └── database.sql    # Script de création de la base de données
├── uploads/            # Dossier pour les images (à créer)
├── index.php           # Page d'accueil
├── catalogue.php       # Catalogue des produits
├── produit.php         # Page détail produit
├── panier.php          # Panier
├── commande.php        # Page de commande
└── panier_ajax.php     # Gestion AJAX du panier
```

## Installation

### 1. Base de données

1. Créer une base de données MySQL nommée `droguerie_db`
2. Exécuter le script SQL : `sql/database.sql`
3. Modifier les paramètres de connexion dans `config/database.php` si nécessaire

### 2. Configuration

Modifier si nécessaire les paramètres dans `config/config.php` :
- `SITE_URL` : URL de base du site
- `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` : Paramètres de connexion MySQL

### 3. Permissions

Assurez-vous que le dossier `uploads/` existe et est accessible en écriture :
```bash
mkdir uploads
chmod 777 uploads
```

### 4. Accès administrateur

Par défaut, un compte admin est créé :
- **Username** : `admin`
- **Password** : `admin123`

⚠️ **Important** : Changez le mot de passe après la première connexion !

## Fonctionnalités

### Site public

- ✅ Page d'accueil avec slider et catégories
- ✅ Catalogue de produits avec filtres par catégorie
- ✅ Page détaillée de produit
- ✅ Panier d'achat (sessions PHP)
- ✅ Système de commande (sans authentification)
- ✅ Design responsive et moderne

### Zone d'administration

- ✅ Connexion sécurisée
- ✅ Tableau de bord avec statistiques
- ✅ Gestion des produits (CRUD complet)
- ✅ Gestion des catégories
- ✅ Gestion des commandes (visualisation et modification du statut)
- ✅ Upload d'images pour les produits

## Exemples de produits inclus

Le script SQL inclut des exemples de produits typiques de droguerie au Maroc :

- **Ménage** : Javel, détergents, nettoyants
- **Hygiène** : Savon de Marseille, shampoings, dentifrices
- **Parfums d'ambiance** : Encens (Oud, Jasmin), bougies parfumées
- **Accessoires** : Éponges, balais, serpillières, gants
- **Lessive et Entretien** : Lessives, adoucissants, détachants

## Personnalisation

### Couleurs

Les couleurs principales sont définies dans `assets/css/style.css` via les variables CSS :
```css
:root {
    --primary-color: #2c3e50;
    --secondary-color: #3498db;
    --accent-color: #e74c3c;
    ...
}
```

### Ajout de produits

1. Se connecter à l'administration
2. Aller dans "Produits" > "Ajouter un produit"
3. Remplir le formulaire et uploader une image

## Notes importantes

- Le système de panier utilise les sessions PHP (pas de base de données)
- Les commandes sont stockées en base de données
- Les images sont stockées dans le dossier `uploads/`
- Le site est optimisé pour le marché marocain (prix en MAD, produits locaux)

## Support

Pour toute question ou problème, vérifiez :
1. Les permissions du dossier `uploads/`
2. La configuration de la base de données
3. Les logs d'erreur PHP

## Licence

Ce projet est fourni tel quel pour usage éducatif et commercial.

