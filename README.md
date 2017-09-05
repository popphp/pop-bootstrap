Pop Bootstrap
=============

Release Information
-------------------
Version 4.0.0  
TBD

Overview
--------

A skeleton web application for the Pop Web Application Framework,
using the Bootstrap and Font Awesome frameworks. 

Requirements
------------

* Minimum of PHP 7.0.0
* Apache 2+, IIS 7+, or any web server with URL rewrite support
* MySQL 5.0+

Installation
------------

The command below will install all of the necessary components and
take you through the installation steps automatically:

```console
$ composer create-project popphp/pop-bootstrap project-folder
```

Get Started
-----------

Either create a vhost on your web server or start the PHP web server
and point the document root to the `public` folder:

```console
$ sudo php -S localhost:8000 -t public
```

Visit the main web address. If you are using the PHP web server like
above, you would visit `http://localhost:8000`. You will be redirected
to a login screen. The default credentials are:

* Username: `admin`
* Password: `password`

Demo Pages
----------

Most of the navigation displayed is not active, with the exception of
the `Orders` page, the `Users` page and the `Logout` icon. The `Orders`
page just demonstrates a mock layout with side navigation. The `Users`
page will let you manage users. The `Logout` icon executes a user logout. 

Console Access
--------------

The application comes with a simple console interface to assist
with application management from the CLI as well. You can build
upon this to add console-level features and functionality

```console
$ ./app help                Show this help screen
```