Pop Bootstrap
=============

An alternate skeleton application for the Pop Web Application Framework,
using Bootstrap and Font Awesome frameworks.

Installation
============

```console
$ composer create-project popphp/pop-bootstrap project-folder
```

1. Install the appropriate database SQL file located in `app/data`
2. Copy `app/config/application.orig.php` to `app/config/application.php`
3. Fill in the database information in that file

Get Started
===========

Start the PHP server and point it to the `public` folder.

```console
$ sudo php -S localhost:8000 -t public
```

Visit `http://localhost:8000` and you'll be redirected to a login
screen. The default credentials are:

* Username: `admin`
* Password: `password`
