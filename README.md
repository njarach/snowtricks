# SnowTricks - Site communautaire d'apprentissage des figures de snowboard

Bienvenue sur **SnowTricks**, un projet Symfony 7.1 développé pour aider les passionnés de snowboard à apprendre et partager des figures (tricks) avec la communauté.
Ce projet a été réalisé dans le cadre du projet n°6 de la formation Concepteur/Développeur d'application PHP Symfony : *Développez de A à Z le site communautaire Snowtricks.*

## 🚀 Objectif du projet
Jimmy Sweat, entrepreneur et passionné de snowboard, souhaite créer un site collaboratif pour démocratiser ce sport et faciliter l’apprentissage des tricks. Ce site permet aux utilisateurs de consulter et ajouter des figures, ainsi que d’échanger via un espace de discussion commun.

## 📌 Fonctionnalités principales
- 📖 **Annuaire des figures** : affichage d'une liste de tricks (10 préchargés, le reste ajouté par les utilisateurs).
- 🛠 **Gestion des figures** : création, modification et consultation des tricks.
- 💬 **Espace de discussion** : commentaires et échanges sur chaque figure.
- 🔑 **Gestion des utilisateurs** : inscription avec vérification de l'email, connexion sécurisée.
- 🔐 **Droits des utilisateurs** : un utilisateur doit être connecté pour ajouter, modifier ou supprimer un trick, ainsi que pour publier un commentaire.
- 🖼️ **Médias** : possibilité d'ajouter des images et vidéos à un trick.

## 📄 Pages disponibles
- 🏠 **Page d’accueil** : liste des figures disponibles.
- ➕ **Création d’une nouvelle figure**.
- ✏️ **Modification d’une figure existante**.
- 📌 **Détail d’une figure** : description + espace de discussion.

## 🔧 Installation et utilisation
1. **Cloner le projet**
   ```sh
   git clone https://github.com/votre-repo/snowtricks.git
   cd snowtricks
   ```
2. **Installer les dépendances**
   ```sh
   composer install
   npm install
   ```
3. **Configurer l’environnement**
    - Copier le fichier `.env.example` en `.env`
    - Modifier les variables de configuration (base de données, etc.)
4. **Créer la base de données et charger les figures initiales**
   ```sh
   symfony console doctrine:database:create
   symfony console doctrine:migrations:migrate
   symfony console doctrine:fixtures:load
   ```
5. **Lancer le serveur Symfony**
   ```sh
   symfony server:start
   ```
6. **Accéder au projet** : http://127.0.0.1:8000

## 📜 Contraintes techniques
- ❌ **Aucun bundle tiers autorisé**, sauf pour les données initiales.
- 🌍 **SEO-friendly** : URL claires et lisibles.
- 📱 **Responsive Design** : navigation optimisée pour desktop et mobile.

## 📌 TODO & Suivi
Le projet est géré via un système d’**issues** et **tickets** sur GitHub afin de structurer le développement.

## 🎨 Design & Wireframes
Le design est libre tout en respectant les wireframes fournis. L’application est responsive pour une accessibilité optimale sur tous les appareils.

---
👨‍💻 **Développé avec ❤️ et Symfony 7.1**