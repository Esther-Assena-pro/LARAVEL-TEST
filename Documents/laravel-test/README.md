## Prérequis à installer avant de commencer

Assurez-vous d’avoir installé **ces outils sur votre machine** :

- [PHP 8.1 ou supérieur](https://www.php.net/downloads.php)
- [Composer](https://getcomposer.org/download/)
- [MySQL](https://dev.mysql.com/downloads/)
- [Node.js & NPM](https://nodejs.org/)
- [Git](https://git-scm.com/downloads)

L’application utilise aussi :
- [Flatpickr](https://flatpickr.js.org/) (installé automatiquement via NPM lors de `npm install`)

 **Astuce :** Vérifiez  chaque installation avec :
 - `php -v`
 - `composer -V`
 - `mysql --version`
 - `node -v` et `npm -v`
 - `git --version`

---

## Installation

### Étape 1 : Cloner le dépôt
```bash
git clone https://github.com/Esther-Assena-pro/LARAVEL-TEST.git
cd LARAVEL-TEST

### Étape 2 : Installer les dépendances PHP et JS

```bash
composer install
npm install
```

### Étape 3 : Configurer l’environnement

- Copiez le fichier `.env.example` en `.env` :
  ```bash
  cp .env.example .env
  ```
- Générez la clé d’application :
  ```bash
  php artisan key:generate
  ```
- Configurez vos accès à la base de données dans le fichier `.env`.

 **Remarque :**  
La structure de la base de données est générée automatiquement grâce aux migrations Laravel (`php artisan migrate`).  
Créez une base vide dans MySQL et renseignez vos identifiants dans le fichier `.env` avant de lancer les migrations.

### Étape 4 : Migrer la base de données

```bash
php artisan migrate
```

### Étape 5 : Compiler les assets

```bash
npm run dev
```

### Étape 6 : Lancer le serveur de développement

```bash
php artisan serve
```

---

## Fonctionnalités principales

- Gestion des réservations
- Interface utilisateur moderne (Livewire, Filament, dark mode)
- Sélecteur de dates Flatpickr
- Authentification et gestion des utilisateurs

---

## Auteur

Esther Assena