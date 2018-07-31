# Pop Bootstrap

Release Information
-------------------
Version 4.1.1  
July 31, 2018

Overview
--------

This repository is a skeleton web application for the Pop Web Application
Framework using the Bootstrap v4, Material Icons and Font Awesome frameworks.
The concept behind this skeleton framework focuses on a minimal feature set
that allows access view a basic web interface, an API and also the console.

* [Requirements](#requirements)
* [Installation](#installation)
* [Getting Started](#getting-started)
* [Web Access](#web-access)
* [API Access](#api-access)
    + [Authentication](#authentication)
    + [Validate the Token](#validate-the-token) 
    + [Refresh the Token](#refresh-the-token) 
    + [Revoke the Token](#revoke-the-token)
    + [Manage Users](#manage-users)
* [Console Access](#console-access)


## Requirements

* Minimum of PHP 7.0.0
* Apache 2+, IIS 7+, or any web server with URL rewrite support
* MySQL 5.0+

[Top](#pop-bootstrap)

## Installation

The command below will install all of the necessary components and
take you through the installation steps automatically:

```console
$ composer create-project popphp/pop-bootstrap project-folder
$ cd project-folder
$ npm install
$ npm run build
```

[Top](#pop-bootstrap)

## Getting Started

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

[Top](#pop-bootstrap)

## Web Access 

Once logged in via a web browser, you will see that most of the navigation
displayed is not active, with the exception of the `Orders` page, the `Users`
page and the `Logout` icon. The `Orders` page demonstrates a mock layout with
side navigation. The `Users` page will let you manage users. The `Logout`
icon executes a user logout. 

[Top](#pop-bootstrap)

## API Access

You can access the API to authenticate a user or manage users as well. The
following examples use cURL to demonstrate the accessing the API:

#### Authentication

```bash
curl -i -X POST -d"username=admin&password=password" \
    http://localhost:8000/api/auth
```
Upon a successful authentication, you will receive a JSON response that looks
like this:

```text
HTTP/1.1 200 OK
Host: localhost:8000
Connection: close
X-Powered-By: PHP/7.0.8
Content-Type: application/json
Access-Control-Allow-Origin: *
Access-Control-Allow-Headers: Authorization, Content-Type
Access-Control-Allow-Methods: HEAD, OPTIONS, GET, PUT, POST, PATCH, DELETE

{
    "id": 1,
    "username": "admin",
    "token": "449d8625fb26753ebce8acbbf38ba2321dd21621",
    "refresh": "a5bba1af879c64e591307b48e1fdd7f2d85cba5f",
    "expires": 1504754891
}

```
With that, you'll be able to continue accessing the API.

[Top](#pop-bootstrap)

#### Validate the Token

```bash
curl -i -X POST --header "Authorization: Bearer 449d8625fb26753ebce8acbbf38ba2321dd21621" \
    http://localhost:8000/api/auth/token
```

```text
HTTP/1.1 200 OK
Host: localhost:8000
Connection: close
X-Powered-By: PHP/7.0.8
Content-Type: application/json
Access-Control-Allow-Origin: *
Access-Control-Allow-Headers: Authorization, Content-Type
Access-Control-Allow-Methods: HEAD, OPTIONS, GET, PUT, POST, PATCH, DELETE

```

[Top](#pop-bootstrap)

#### Refresh the Token

```bash
curl -i -X POST --header "Authorization: Bearer 449d8625fb26753ebce8acbbf38ba2321dd21621" \
    -d"refresh=a5bba1af879c64e591307b48e1fdd7f2d85cba5f" http://localhost:8000/api/auth/token/refresh
```

```text
HTTP/1.1 200 OK
Host: localhost:8000
Connection: close
X-Powered-By: PHP/7.0.8
Content-Type: application/json
Access-Control-Allow-Origin: *
Access-Control-Allow-Headers: Authorization, Content-Type
Access-Control-Allow-Methods: HEAD, OPTIONS, GET, PUT, POST, PATCH, DELETE

{
    "token": "8012796bbedb79fc4cecedcf174640f1b5796f08",
    "refresh": "a5bba1af879c64e591307b48e1fdd7f2d85cba5f",
    "expires": 1504754891
}

```

[Top](#pop-bootstrap)

#### Revoke the Token

```bash
curl -i -X POST --header "Authorization: Bearer 8012796bbedb79fc4cecedcf174640f1b5796f08" \
    http://localhost:8000/api/auth/token/revoke
```

```text
HTTP/1.1 200 OK
Host: localhost:8000
Connection: close
X-Powered-By: PHP/7.0.8
Content-Type: application/json
Access-Control-Allow-Origin: *
Access-Control-Allow-Headers: Authorization, Content-Type
Access-Control-Allow-Methods: HEAD, OPTIONS, GET, PUT, POST, PATCH, DELETE

```

[Top](#pop-bootstrap)

#### Manage Users

###### Get Users

```bash
curl -i -X GET --header "Authorization: Bearer 8012796bbedb79fc4cecedcf174640f1b5796f08" \
    http://localhost:8000/api/users
```

###### Get a User

```bash
curl -i -X GET --header "Authorization: Bearer 8012796bbedb79fc4cecedcf174640f1b5796f08" \
    http://localhost:8000/api/users/1
```

###### Create User

```bash
curl -i -X POST --header "Authorization: Bearer 8012796bbedb79fc4cecedcf174640f1b5796f08" \
    -d"username=testuser1&password=123456" http://localhost:8000/api/users
```

###### Update a User

```bash
curl -i -X PUT --header "Authorization: Bearer 8012796bbedb79fc4cecedcf174640f1b5796f08" \
    -d"username=testuser1&active=1" http://localhost:8000/api/users/2
```

###### Delete a User

```bash
curl -i -X DELETE --header "Authorization: Bearer 8012796bbedb79fc4cecedcf174640f1b5796f08" \
    http://localhost:8000/api/users/2
```

###### Delete Users

```bash
curl -i -X DELETE --header "Authorization: Bearer 8012796bbedb79fc4cecedcf174640f1b5796f08" \
    -d"rm_users[]=2&rm_users=3" http://localhost:8000/api/users
```

[Top](#pop-bootstrap)

## Console Access

The application comes with a simple console interface to assist
with application management from the CLI as well. You can build
upon this to add console-level features and functionality

```console
$ ./app help                       Show this help screen
$ ./app users                      List users
$ ./app users add                  Add a user
$ ./app users username <user>      Change a user's username
$ ./app users password <user>      Change a user's password
$ ./app users -a <user>            Activate a user
$ ./app users -d <user>            Deactivate a user
$ ./app users clear <user>         Clear a user's failed login attempts
$ ./app users revoke <user>        Revoke a user's auth tokens
$ ./app users remove <user>        Remove a user
```

[Top](#pop-bootstrap)