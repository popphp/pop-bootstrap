--
-- Pop Bootstrap Application MySQL Database
--

-- --------------------------------------------------------

--
-- Table structure for table `auth_users`
--

DROP TABLE IF EXISTS `auth_users`;
CREATE TABLE IF NOT EXISTS `auth_users` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `active` int(1) DEFAULT '0',
  `attempts` int(16) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE `username` (`username`),
  INDEX `active` (`active`),
  INDEX `attempts` (`attempts`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `auth_users`
--

INSERT INTO `auth_users` (`username`, `password`, `active`, `attempts`) VALUES
('admin', '$2y$08$ckh6UXNYdjdSVzhlcWh2OOCrjBWHarr8Fxf3i2BYVlC29Ag/eoGkC', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `auth_tokens`
--

DROP TABLE IF EXISTS `auth_tokens`;
CREATE TABLE IF NOT EXISTS `auth_tokens` (
  `user_id` int(16) NOT NULL,
  `token` varchar(255) NOT NULL,
  `refresh` varchar(255) NOT NULL,
  `expires` int(16) NOT NULL, -- 0, never expires
  `requests` int(16) DEFAULT '0',
  UNIQUE `access_token` (`user_id`, `token`, `refresh`),
  CONSTRAINT `fk_token_user_id` FOREIGN KEY (`user_id`) REFERENCES `auth_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

