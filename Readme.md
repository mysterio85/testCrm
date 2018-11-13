

<div align="center">
  <h1>TEST TECHNIQUE EQUIPE CRM </h1>
</div>

<h2 align="center">Installation</h2>

```bash
1 - cloner le repository [Dézipper le .zip ]
2 - initialiser composer et mettre à jour
3 - Créartion d'une base de données
4 - modifier le fichier de configuration (leboncoin\config\config.php)
5 - Créer un domaine : leboncoin.local qui pointe sur le dossier public 
```

<h2 align="center">creation domaine</h2>

### `Cloner le projet`
```git
Repository :  
git clone git@github.com:mysterio85/testCrm.git
```

<h2 align="center">composer</h2>

### `Chargement des dépendances `
```composer
lancer les commandes suivantes en etant à la racine du projet  :  
composer init
composer update
```

<h2 align="center">creation domaine</h2>

### `Windows`
```js
1 - Dans le dossier : Windows\System32\drivers\etc, éditer en mode administrateur le fichier
hosts et y ajouter les ligne suivantes: 

127.0.0.1	leboncoin.local
::1	leboncoin.local

2 - rechercher le fichier bin/apache/apache2.4.27/conf/extra/httpd-vhosts.conf de votre serveur apache 
ajouter le code suivant en remplaçant "c:/wamp/www/" par le path de votre projet  : 

<VirtualHost *:80>
	ServerName leboncoin.local
	DocumentRoot "c:/wamp/www/leboncoin/public"
	<Directory  "c:/wamp/www/leboncoin/public/">
		Options +Indexes +Includes +FollowSymLinks +MultiViews
		AllowOverride All
		Require local
	</Directory>
</VirtualHost>


```


### `Linux`
```js
sudo nano /etc/apache2/sites-available/leboncoin.local.conf
```

```
<VirtualHost *:80>
    ServerName leboncoin.local
    ServerAlias leboncoin.local
    DocumentRoot /var/www/leboncoin/public
    <Directory /var/www/leboncoin/public>
        AllowOverride All
        Require all granted
        

        <IfModule mod_rewrite.c>
            Options -MultiViews
            RewriteEngine On
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteRule ^index-dev.html [QSA,L]

            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteRule ^(.*)app_dev.php [QSA,L]
            
            RewriteCond %{HTTP:Authorization} ^(.*)
            RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]
        </IfModule>

    </Directory>

    ErrorLog /var/log/apache2/leboncoin_error.log
    CustomLog /var/log/apache2/leboncoin_access.log combined
</VirtualHost>
```

```
sudo a2ensite leboncoin.local.conf
```


##### Ajouter le host

```
sudo nano /etc/hosts
```

Ajouter la ligne :

```
127.0.0.1       leboncoin.local
```



<h2 align="center">Sql pour la création de la base d données</h2>

### `Sql - table users`
```js
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(225) NOT NULL,
  `email` varchar(225) NOT NULL,
  `password` varchar(225) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

### `Sql - table contacts`
```js
DROP TABLE IF EXISTS `contacts`;
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(225) NOT NULL,
  `prenom` varchar(225) NOT NULL,
  `email` varchar(225) NOT NULL,
  `userId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

### `Sql - table addresses`
```js
DROP TABLE IF EXISTS `addresses`;
CREATE TABLE IF NOT EXISTS `addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` int(4) NOT NULL,
  `street` varchar(225) NOT NULL,
  `postalCode` int(6) NOT NULL,
  `city` varchar(225) NOT NULL,
  `country` varchar(225) NOT NULL,
  `idContact` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idContact` (`idContact`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

### `Sql - contraintes`
```js
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`idContact`) REFERENCES `contacts` (`id`) ON DELETE CASCADE;

ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;
```

### `Sql - initialisation user de test `
```js
INSERT INTO `users` (`id`, `login`, `email`, `password`) VALUES
(1, 'admin', 'lebonoin@test.fr', '21232f297a57a5a743894a0e4a801fc3');
COMMIT;
```

### `Configuration`
```conf
fichier : dans le fichier config\config.php du projet, 
modifier le contenu comme suit : 
"db_user" => "utilisateur de la base de données",
"db_pass" => "Mot de passe de l utilisateur de la base de donnée",
"db_host" => "domaine ou ip du serveur de base de données ",
"db_name" => "nom de la base de données"
```

> ℹ️ une fois le domaine et la base de donnée créer, lancer   `leboncoin.local` Un compte utilisateur a été initialisé avec pour paramètres :
Login : admin
Mot de passe : admin  [leboncoin.local](http://leboncoin.local).

