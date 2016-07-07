--
-- Pop Bootstrap PostgreSQL Database
--

-- --------------------------------------------------------

--
-- Table structure for table "roles"
--

CREATE SEQUENCE user_role_id_seq START 2001;

DROP TABLE IF EXISTS "user_roles" CASCADE;
CREATE TABLE IF NOT EXISTS "user_roles" (
  "id" integer NOT NULL DEFAULT nextval('user_role_id_seq'),
  "parent_id" integer,
  "name" varchar(255) NOT NULL,
  "verification" integer,
  "approval" integer,
  "permissions" text,
  PRIMARY KEY ("id"),
  CONSTRAINT "fk_role_parent_id" FOREIGN KEY ("parent_id") REFERENCES "user_roles" ("id") ON DELETE SET NULL ON UPDATE CASCADE
) ;

ALTER SEQUENCE user_role_id_seq OWNED BY "user_roles"."id";
CREATE INDEX "user_role_name" ON "user_roles" ("name");

--
-- Dumping data for table "user_roles"
--

INSERT INTO "user_roles" ("parent_id", "name", "verification", "approval", "permissions") VALUES
(NULL, 'Admin', 1, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table "users"
--

CREATE SEQUENCE user_id_seq START 1001;

DROP TABLE IF EXISTS "users" CASCADE;
CREATE TABLE IF NOT EXISTS "users" (
  "id" integer NOT NULL DEFAULT nextval('user_id_seq'),
  "role_id" integer,
  "username" varchar(255) NOT NULL,
  "password" varchar(255) NOT NULL,
  "active" integer,
  "verified" integer,
  "last_ip" varchar(255),
  "last_ua" varchar(255),
  "total_logins" integer,
  "failed_attempts" integer,
  PRIMARY KEY ("id"),
  CONSTRAINT "fk_user_role" FOREIGN KEY ("role_id") REFERENCES "user_roles" ("id") ON DELETE CASCADE ON UPDATE CASCADE
) ;

ALTER SEQUENCE user_id_seq OWNED BY "users"."id";
CREATE INDEX "user_role_id" ON "users" ("role_id");
CREATE INDEX "username" ON "users" ("username");

--
-- Dumping data for table "users"
--

INSERT INTO "users" ("role_id", "username", "password", "active", "verified") VALUES
(2001, 'admin', '$2y$08$ckh6UXNYdjdSVzhlcWh2OOCrjBWHarr8Fxf3i2BYVlC29Ag/eoGkC', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table "user_logins"
--

CREATE SEQUENCE user_login_id_seq START 3001;

DROP TABLE IF EXISTS "user_logins" CASCADE;
CREATE TABLE IF NOT EXISTS "user_logins" (
  "id" integer NOT NULL DEFAULT nextval('user_login_id_seq'),
  "user_id" integer,
  "ip" varchar(255) NOT NULL,
  "ua" varchar(255) NOT NULL,
  "timestamp" timestamp NOT NULL,
PRIMARY KEY ("id"),
CONSTRAINT "fk_login_user_id" FOREIGN KEY ("user_id") REFERENCES "users" ("id") ON DELETE CASCADE ON UPDATE CASCADE
) ;

ALTER SEQUENCE user_login_id_seq OWNED BY "user_logins"."id";

-- --------------------------------------------------------

--
-- Table structure for table "user_sessions"
--

CREATE SEQUENCE user_session_id_seq START 4001;

DROP TABLE IF EXISTS "user_sessions" CASCADE;
CREATE TABLE IF NOT EXISTS "user_sessions" (
  "id" integer NOT NULL DEFAULT nextval('user_session_id_seq'),
  "user_id" integer,
  "ip" varchar(255) NOT NULL,
  "ua" varchar(255) NOT NULL,
  "start" integer NOT NULL,
PRIMARY KEY ("id"),
CONSTRAINT "fk_session_user_id" FOREIGN KEY ("user_id") REFERENCES "users" ("id") ON DELETE CASCADE ON UPDATE CASCADE
) ;

ALTER SEQUENCE user_session_id_seq OWNED BY "user_sessions"."id";
