Pop Bootstrap
=============

An alternate skeleton application for the Pop Web Application Framework,
using Bootstrap and Font Awesome frameworks.

Installation
============

```console
$ composer create-project popphp/pop-bootstrap project-folder
```

1. Copy `app/config/application.orig.php` to `app/config/application.php`
2. Fill in the database information in that file
3. Install the appropriate database SQL file located in `app/data`

Get Started
===========

Start the PHP server and point it to the `public` folder.

```console
$ sudo php -S localhost:8000 -t public
```

Visit `http://localhost:8000` and you'll be redirected to a login
screen. The initial credentials are:

* Username: `admin`
* Password: `password`
