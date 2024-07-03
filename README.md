# Gestions d'inventaire des moyens materiels et logiciels

## Configuration de l'environnement
### Installer docker et docker-compose
Pour commencer, vous avez besoin d'installer [docker et docker-compose](https://docs.docker.com/compose/install/).


### Configurer la base de données
Créer le fichier `.env` dans le dossier parent. Voici un exemple de son contenu :  
```
MYSQL_ROOT_PASSWORD=MYSQL_ROOT_PASSWORD
MYSQL_DATABASE=MY_DATABASE
MYSQL_USER=MYSQL_USER
MYSQL_PASSWORD=MYSQL_PASSWORD
```

N'oubliez pas de modifier le fichier `src/app/application/config/database.php` avec les nouvelles variables d'environnement.

### Créer les images docker et lancer l'application

**Code :** Créer les images docker et lancer les services  
```shell
docker-compose up -d
```

En cas d'erreur pour la relance du service `db`, lancez la commande suivante :
```shell
rm -f data/db/mysql.sock && docker-compose up -d
```

**Code :** Stopper les services  
```shell
docker-compose down
```

### Migration de la base des données**
Tout d'abord, il faut assurer que vous avez activé la migration dans le fichier `application/config/migration.php`

```php
$config['migration_enabled'] = TRUE;
```

Après connectez vous au terminal de `app` avec :
```shell
docker-compose exec app bash
```

Puis lancez la migration avec :
```shell
php index.php migrate
```

Dans le cas où vous voulez annuler une migration :
```shell
php index.php migrate undo
```

Dans le cas où vous voulez réinitialiser la migration :
```shell
php index.php migrate reset
```

Vous pouvez maintenant accéder aux urls suivants :  
* Url pour accéder à l'application : http://localhost:7700  
* Url pour accéder à phpmyadmin: http://localhost:8800

Le compte par défaut de l'administrateur est :  
```
username: administrator
email: admin@example.com
password: password
```

### Autres informations à savoir

* Librairie d'authentification intégrée : [ion_auth](http://github.com/benedmunds/CodeIgniter-Ion-Auth) 
* Méthode d'encryptage utilisée : [Argon2](http://php.net/manual/en/function.password-hash.php)
* Information sur la migration de la base des données : [The migrations in CodeIgniter](https://avenir.ro/the-migrations-in-codeigniter-or-how-to-have-a-git-for-your-database/)
* Librairies CSS et Javascript utilisées : [Tailwindcss](https://tailwindcss.com), [Alpine.js](https://alpinejs.dev)