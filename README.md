Yii 2 Starter Application Template
================================

Yii 2 Starter Application Template is a skeleton Yii 2 application best for
rapidly creating small to medium sized projects.

It is built upon the official [Yii 2 Basic Application Template](https://github.com/yiisoft/yii2-app-basic)
but provides two separate applications, for the frontend and the backend. Unlike in the
[Yii 2 Advanced Application Template](https://github.com/yiisoft/yii2-app-advanced), here the
two apps share the same webroot, and are accessed using different entry scripts.

The template contains basic backend functionality such as password reset and administrator management.

The backend is built upon the [xenon admin theme](http://xenontheme.com/) (not included).

DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      gii/                contains code generator templates
      mail/               contains view files for e-mails
      messages/           contains the translation files
      migrations/         contains the database migrations
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry scripts (index.php and back.php) and Web resources



REQUIREMENTS
------------

The minimum requirement by this application template that your Web server supports PHP 5.4.0.


INSTALLATION
------------

### Install via Composer

If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

You can then install this application template using the following command:

~~~
php composer.phar global require "fxp/composer-asset-plugin:1.0.0"
php composer.phar create-project --prefer-dist --stability=dev guillemc/yii2starter myapp
~~~


CONFIGURATION
-------------

### Filesystem permissions

The webserver needs write access to the `runtime` and `web/assets` directories.

### Database

This project assumes that a mysql database will be used. It must be created beforehand.

### Environment configuration

Copy the file `.env-sample.php` from the root directory to `.env.php` and customize the environment
variables, as well as the database connection details. This file can also be used to store any sensitive
information that must not end up in the repository, such as api keys, passwords, etc.

Edit the files in the `config/` directory and customize your application (id, name, language, timezone...).

### Apply migration

Run `./yii migrate` from the console. This will create the administrators table, and the root administration
user `admin` with password `admin`.

### Application URLs

If installed inside your webserver's document root, the frontend will be available at `http://localhost/myapp/web/`
and the backend at `http://localhost/myapp/web/back.php`. In production it is recommended that you set a virtual
host for your application that points to the `web` directory, so that the access urls become `http://myapp.com/`
and `http://myapp.com/back.php`.


