[![Release version](https://img.shields.io/badge/release-v1.0.0-blue)]()
[![Symfony version](https://img.shields.io/badge/symfony-6.3-blue)]()
[![PHP version](https://img.shields.io/badge/php-8.2-blue)]()
# Data Breach Checker

Il s'agit d'une application de démonstration, développé par Vincent VELOSO Développeur Backend.

Pour plus d'informations : https://it-dc.fr/

Application avec Symfony 6.3 permettant de vérifier si des données personnelles ont été révélées dans un piratage informatique.
Comme :
* une adresse email,
* un nom d'utilisateur,
* un mot de passe,
* une adresse IP,
* etc.

Cette application s'appuie sur la base de données du site Have I Been Pwned (HIBP) au travers de leur API. Pour vérifier si une adresse email
a été compromise, il faut disposer d'une clef API fournie par HIBP.

## Environnement de développement

### Pré-requis

* Symfony 6.3
* EasyAdmin 3
* PHP 8.2
* Symfony CLI 5.5.10
* Composer 2.4.4
* BootStrap 5.3

Pour vérifier que les pré-requis sont respectés :
```bash
symfony check:requirements
```

Pour vérifier qu'aucun packages ne présentent des vulnérabilités :
```bash
symfony security:check
```

Créer le fichier **.env** en s'appuyant du fichier **.env-template** avant d'exécuter les commandes suivantes.
Vous devez définir certains paramètres présents dans le fichier **.env** :
* **HIBP_API** : Correspond à la clef API.

### Pour démarrer l'environnement de développement

```bash
composer install
chmod +x bin/*
symfony server:start
```

Des adresses emails de tests sont disponibles afin d'avoir un aperçu de l'outil :
* account-exists@hibp-integration-tests.com
* multiple-breaches@hibp-integration-tests.com
* opt-out-breach@hibp-integration-tests.com

### Pour démarrer l'environnement de production

Mettre à jour le fichier **.env** avant d'exécuter les commandes suivantes.

```bash
composer install --no-dev --optimize-autoloader
chmod +x bin/*
```