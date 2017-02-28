--
-- Pop Bootstrap PostgreSQL Database
--

-- --------------------------------------------------------

--
-- Table structure for table "roles"
--

CREATE SEQUENCE role_id_seq START 2001;

DROP TABLE IF EXISTS "[{prefix}]roles" CASCADE;
CREATE TABLE IF NOT EXISTS "[{prefix}]roles" (
  "id" integer NOT NULL DEFAULT nextval('role_id_seq'),
  "parent_id" integer,
  "name" varchar(255) NOT NULL,
  "verification" integer,
  "approval" integer,
  "permissions" text,
  PRIMARY KEY ("id"),
  CONSTRAINT "fk_role_parent_id" FOREIGN KEY ("parent_id") REFERENCES "[{prefix}]roles" ("id") ON DELETE SET NULL ON UPDATE CASCADE
) ;

ALTER SEQUENCE role_id_seq OWNED BY "[{prefix}]roles"."id";
CREATE INDEX "role_name" ON "[{prefix}]roles" ("name");

--
-- Dumping data for table "roles"
--

INSERT INTO "[{prefix}]roles" ("parent_id", "name", "verification", "approval", "permissions") VALUES
(NULL, 'Admin', 1, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table "users"
--

CREATE SEQUENCE user_id_seq START 1001;

DROP TABLE IF EXISTS "[{prefix}]users" CASCADE;
CREATE TABLE IF NOT EXISTS "[{prefix}]users" (
  "id" integer NOT NULL DEFAULT nextval('user_id_seq'),
  "role_id" integer,
  "username" varchar(255) NOT NULL,
  "password" varchar(255) NOT NULL,
  "email" varchar(255),
  "active" integer,
  "verified" integer,
  "last_login" timestamp,
  "last_ip" varchar(255),
  "last_ua" varchar(255),
  "total_logins" integer,
  "failed_attempts" integer,
  PRIMARY KEY ("id"),
  CONSTRAINT "fk_user_role" FOREIGN KEY ("role_id") REFERENCES "[{prefix}]roles" ("id") ON DELETE CASCADE ON UPDATE CASCADE
) ;

ALTER SEQUENCE user_id_seq OWNED BY "[{prefix}]users"."id";
CREATE INDEX "role_id" ON "[{prefix}]users" ("role_id");
CREATE INDEX "username" ON "[{prefix}]users" ("username");

--
-- Dumping data for table "users"
--

INSERT INTO "[{prefix}]users" ("role_id", "username", "password", "active", "verified") VALUES
(2001, 'admin', '$2y$08$ckh6UXNYdjdSVzhlcWh2OOCrjBWHarr8Fxf3i2BYVlC29Ag/eoGkC', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table "user_sessions"
--

CREATE SEQUENCE user_session_id_seq START 3001;

CREATE TABLE "[{prefix}]user_sessions" (
  "id" integer NOT NULL DEFAULT nextval('user_session_id_seq'),
  "user_id" integer DEFAULT NULL,
  "session_id" varchar(255) NOT NULL,
  "ip" varchar(255) NOT NULL,
  "ua" varchar(255) NOT NULL,
  "start" integer NOT NULL,
  PRIMARY KEY ("id"),
  UNIQUE ("id", "user_id", "session_id"),
CONSTRAINT "fk_user_session_id" FOREIGN KEY ("user_id") REFERENCES "[{prefix}]users" ("id") ON DELETE CASCADE ON UPDATE CASCADE
) ;

ALTER SEQUENCE user_session_id_seq OWNED BY "[{prefix}]user_sessions"."id";

-- --------------------------------------------------------

--
-- Table structure for table "user_logins"
--

CREATE SEQUENCE user_login_id_seq START 4001;

DROP TABLE IF EXISTS "[{prefix}]user_logins" CASCADE;
CREATE TABLE "[{prefix}]user_logins" (
  "id" integer NOT NULL DEFAULT nextval('user_login_id_seq'),
  "user_id" integer DEFAULT NULL,
  "ip" varchar(255) NOT NULL,
  "ua" varchar(255) NOT NULL,
  "timestamp" timestamp NOT NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "fk_user_login_id" FOREIGN KEY ("user_id") REFERENCES "[{prefix}]users" ("id") ON DELETE CASCADE ON UPDATE CASCADE
) ;

ALTER SEQUENCE user_login_id_seq OWNED BY "[{prefix}]user_logins"."id";
