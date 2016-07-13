--
-- Pop Bootstrap SQLite Database
--

-- --------------------------------------------------------

--
-- Set database encoding
--

PRAGMA encoding = "UTF-8";
PRAGMA foreign_keys = ON;

-- --------------------------------------------------------

--
-- Table structure for table "roles"
--

DROP TABLE IF EXISTS "[{prefix}]roles";
CREATE TABLE IF NOT EXISTS "[{prefix}]roles" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "parent_id" integer,
  "name" varchar NOT NULL,
  "verification" integer,
  "approval" integer,
  "permissions" text,
  UNIQUE ("id"),
  CONSTRAINT "fk_role_parent_id" FOREIGN KEY ("parent_id") REFERENCES "[{prefix}]roles" ("id") ON DELETE SET NULL ON UPDATE CASCADE
) ;

INSERT INTO "sqlite_sequence" ("name", "seq") VALUES ('[{prefix}]roles', 2000);
CREATE INDEX "role_name" ON "[{prefix}]roles" ("name");

--
-- Dumping data for table "roles"
--

INSERT INTO "[{prefix}]roles" ("id", "parent_id", "name", "verification", "approval", "permissions") VALUES
(2001, NULL, 'Admin', 1, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table "users"
--

DROP TABLE IF EXISTS "[{prefix}]users";
CREATE TABLE IF NOT EXISTS "[{prefix}]users" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "role_id" integer,
  "username" varchar NOT NULL,
  "password" varchar NOT NULL,
  "email" varchar(255),
  "active" integer,
  "verified" integer,
  "last_login" datetime,
  "last_ip" varchar,
  "last_ua" varchar,
  "total_logins" integer,
  "failed_attempts" integer,
  UNIQUE ("id"),
  CONSTRAINT "fk_user_role" FOREIGN KEY ("role_id") REFERENCES "[{prefix}]roles" ("id") ON DELETE CASCADE ON UPDATE CASCADE
) ;

INSERT INTO "sqlite_sequence" ("name", "seq") VALUES ('[{prefix}]users', 1000);
CREATE INDEX "role_id" ON "[{prefix}]users" ("role_id");
CREATE INDEX "username" ON "[{prefix}]users" ("username");

--
-- Dumping data for table "users"
--

INSERT INTO "[{prefix}]users" ("id", "role_id", "username", "password", "active", "verified") VALUES
(1001, 2001, 'admin', '$2y$08$ckh6UXNYdjdSVzhlcWh2OOCrjBWHarr8Fxf3i2BYVlC29Ag/eoGkC', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table "[{prefix}]user_sessions"
--

CREATE TABLE "[{prefix}]user_sessions" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "user_id" integer DEFAULT NULL,
  "session_id" varchar NOT NULL,
  "ip" varchar NOT NULL,
  "ua" varchar NOT NULL,
  "start" integer NOT NULL,
  UNIQUE ("id"),
  UNIQUE ("id", "user_id", "session_id"),
  CONSTRAINT "fk_user_session_id" FOREIGN KEY ("user_id") REFERENCES "[{prefix}]users" ("id") ON DELETE CASCADE ON UPDATE CASCADE
) ;

INSERT INTO "sqlite_sequence" ("name", "seq") VALUES ('[{prefix}]user_sessions', 3000);

-- --------------------------------------------------------

--
-- Table structure for table "user_logins"
--

DROP TABLE IF EXISTS "[{prefix}]user_logins";
CREATE TABLE "[{prefix}]user_logins" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "user_id" integer DEFAULT NULL,
  "ip" varchar NOT NULL,
  "ua" varchar NOT NULL,
  "timestamp" datetime NOT NULL,
  UNIQUE ("id"),
  CONSTRAINT "fk_user_login_id" FOREIGN KEY ("user_id") REFERENCES "[{prefix}]users" ("id") ON DELETE CASCADE ON UPDATE CASCADE
) ;

INSERT INTO "sqlite_sequence" ("name", "seq") VALUES ('[{prefix}]user_logins', 4000);
