# Plateforme Coworking

Description
-----------

Plateforme Coworking est une application web de gestion d'espaces de coworking. Elle permet d'administrer les clients, les espaces, les réservations, et les avis, avec une interface administrateur et des vues publiques pour les utilisateurs.

Fonctionnalités clés
--------------------

- Gestion des utilisateurs et rôles (admin, client)
- Gestion des espaces (création, modification, localisation)
- Réservations avec disponibilités et numéro de siège
- Système d'avis et notation
- API interne pour intégration frontend via Axios

Stack technique
---------------

- Langage : PHP 8.2+
- Framework : Laravel 12
- Frontend : Vite, Tailwind CSS
- Client HTTP : Axios
- Base de données : SQLite (par défaut, migrations incluses)
- Tests : PHPUnit
- Outils dev : Composer, npm, Laravel Sail (optionnel)

Prérequis
---------

- PHP 8.2 ou supérieur
- Composer
- Node.js (>=16) et npm

Installation et configuration (développement)
-------------------------------------------

1. Cloner le dépôt et se placer dans le dossier :

```bash
git clone <repo-url>
cd platforme-coworking2
```

2. Installer les dépendances PHP :

```bash
composer install
```

3. Copier et configurer l'environnement :

```bash
cp .env.example .env
php artisan key:generate
# éditer .env au besoin (DB, MAIL, APP_URL)
```

4. Préparer la base de données (SQLite par défaut) :

```bash
mkdir -p database
touch database/database.sqlite
php artisan migrate --seed
```

5. Installer les dépendances JavaScript et lancer Vite (dev) :

```bash
npm install
npm run dev
```

6. Lancer l'application localement :

```bash
php artisan serve
```

Exécution des tests
-------------------

```bash
php artisan test
```

Commandes utiles
----------------

- `php artisan migrate` — exécute les migrations
- `php artisan migrate:fresh --seed` — réinitialise la base et reseed
- `php artisan queue:work` — traite la file d'attente
- `npm run build` — build production frontend

Configuration et variables d'environnement
-----------------------------------------

Vérifier et modifier le fichier `.env` pour :

- `DB_CONNECTION` (sqlite/mysql/postgres)
- `APP_URL` et `APP_ENV`
- Paramètres SMTP pour l'envoi d'emails

Structure du projet (emplacements importants)
-------------------------------------------

- `app/Models` — modèles Eloquent (Client, Espace, Reservation, Avis, User)
- `app/Http/Controllers` — contrôleurs applicatifs
- `app/Services` — services métier (ex : AiService)
- `resources/views` — vues Blade
- `routes/web.php` — routes web
- `database/migrations` — migrations de la base
- `database/seeders` — seeders (AdminSeeder, DatabaseSeeder)

Déploiement
----------

Pour la production :

1. Configurer un hôte avec PHP 8.2+, Composer et Node.js
2. Configurer `.env` pour la base de données production
3. Exécuter :

```bash
composer install --no-dev --optimize-autoloader
npm ci --production
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
```

Contributions
-------------

Merci pour votre intérêt. Pour contribuer :

1. Ouvrir une issue pour discuter de la fonctionnalité ou du bug
2. Créer une branche de feature/bugfix
3. Ajouter des tests pour les nouvelles fonctionnalités
4. Soumettre une Pull Request avec une description claire

Licence
-------

Ce projet est distribué sous licence MIT.

Contact
-------

Pour toute question relative au projet, ouvrir une issue ou contacter l'équipe via le canal de support du dépôt.

Fichier mis à jour : [README.md](README.md)
