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
-- Table structure for table "user_roles"
--

DROP TABLE IF EXISTS "user_roles";
CREATE TABLE IF NOT EXISTS "user_roles" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "parent_id" integer,
  "name" varchar NOT NULL,
  "verification" integer,
  "approval" integer,
  "permissions" text,
  UNIQUE ("id"),
  CONSTRAINT "fk_role_parent_id" FOREIGN KEY ("parent_id") REFERENCES "user_roles" ("id") ON DELETE SET NULL ON UPDATE CASCADE
) ;

INSERT INTO "sqlite_sequence" ("name", "seq") VALUES ('user_roles', 2000);
CREATE INDEX "user_role_name" ON "user_roles" ("name");

--
-- Dumping data for table "roles"
--

INSERT INTO "user_roles" ("id", "parent_id", "name", "verification", "approval", "permissions") VALUES
(2001, NULL, 'Admin', 1, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table "users"
--

DROP TABLE IF EXISTS "users";
CREATE TABLE IF NOT EXISTS "users" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "role_id" integer,
  "username" varchar NOT NULL,
  "password" varchar NOT NULL,
  "email" varchar(255),
  "active" integer,
  "verified" integer,
  "last_ip" varchar,
  "last_ua" varchar,
  "total_logins" integer,
  "failed_attempts" integer,
  UNIQUE ("id"),
  CONSTRAINT "fk_user_role" FOREIGN KEY ("role_id") REFERENCES "user_roles" ("id") ON DELETE CASCADE ON UPDATE CASCADE
) ;

INSERT INTO "sqlite_sequence" ("name", "seq") VALUES ('users', 1000);
CREATE INDEX "user_role_id" ON "users" ("role_id");
CREATE INDEX "username" ON "users" ("username");

--
-- Dumping data for table "users"
--

INSERT INTO "users" ("id", "role_id", "username", "password", "active", "verified") VALUES
(1001, 2001, 'admin', '$2y$08$ckh6UXNYdjdSVzhlcWh2OOCrjBWHarr8Fxf3i2BYVlC29Ag/eoGkC', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table "user_logins"
--

DROP TABLE IF EXISTS "user_logins";
CREATE TABLE IF NOT EXISTS "user_logins" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "user_id" integer,
  "ip" varchar NOT NULL,
  "ua" varchar NOT NULL,
  "timestamp" datetime NOT NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "fk_login_user_id" FOREIGN KEY ("user_id") REFERENCES "users" ("id") ON DELETE CASCADE ON UPDATE CASCADE
) ;

INSERT INTO "sqlite_sequence" ("name", "seq") VALUES ('user_logins', 3000);

-- --------------------------------------------------------

--
-- Table structure for table "user_sessions"
--

DROP TABLE IF EXISTS "user_sessions";
CREATE TABLE IF NOT EXISTS "user_sessions" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "user_id" integer,
  "ip" varchar NOT NULL,
  "ua" varchar NOT NULL,
  "start" datetime NOT NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "fk_session_user_id" FOREIGN KEY ("user_id") REFERENCES "users" ("id") ON DELETE CASCADE ON UPDATE CASCADE
) ;

INSERT INTO "sqlite_sequence" ("name", "seq") VALUES ('user_sessions', 4000);
