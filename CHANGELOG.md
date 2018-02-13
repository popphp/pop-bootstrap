CHANGELOG
=========

## 4.1.0

* Migrated JS/CSS and UI elements to nodejs.

## 4.0.0

The focus of this version was to strip the application down to simple, core
features and allow a developer to scale up to whatever they may need. The UI
was rewritten to use Bootstrap v4, Material Icons and Font Awesome. A simple
API was added to allow support for an OAUTH-style authentication and
managing tokens.
 
* Requires PHP v7
* Updated to Bootstrap v4
* Added an API
* Added Material Icons
* Removed Features:
    - Roles management
    - Session management
    - Login management
* Removed support for PostgreSQL and SQLite
