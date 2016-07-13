--
-- Pop Bootstrap MySQL Database
--

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `[{prefix}]roles`;
CREATE TABLE IF NOT EXISTS `[{prefix}]roles` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `parent_id` int(16),
  `name` varchar(255) NOT NULL,
  `permissions` text,
  PRIMARY KEY (`id`),
  INDEX `role_name` (`name`),
  CONSTRAINT `fk_role_parent_id` FOREIGN KEY (`parent_id`) REFERENCES `[{prefix}]roles` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2002 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `[{prefix}]roles` (`id`, `parent_id`, `name`, `permissions`) VALUES
(2001, NULL, 'Admin', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `[{prefix}]users`;
CREATE TABLE IF NOT EXISTS `[{prefix}]users` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `role_id` int(16),
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `email` varchar(255),
  `active` int(1),
  `verified` int(1),
  `last_login` datetime,
  `last_ip` varchar(255),
  `last_ua` varchar(255),
  `total_logins` int(16),
  `failed_attempts` int(16),
  PRIMARY KEY (`id`),
  INDEX `role_id` (`role_id`),
  INDEX `username` (`username`),
  CONSTRAINT `fk_user_role` FOREIGN KEY (`role_id`) REFERENCES `[{prefix}]roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1002 ;

--
-- Dumping data for table `users`
--
INSERT INTO `[{prefix}]users` (`id`, `role_id`, `username`, `password`, `active`, `verified`) VALUES
(1001, 2001, 'admin', '$2y$08$ckh6UXNYdjdSVzhlcWh2OOCrjBWHarr8Fxf3i2BYVlC29Ag/eoGkC', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `[{prefix}]user_sessions`
--

CREATE TABLE `[{prefix}]user_sessions` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `user_id` int(16) DEFAULT NULL,
  `session_id` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `ua` varchar(255) NOT NULL,
  `start` int(16) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE `session_key` (`id`, `user_id`, `session_id`),
  CONSTRAINT `fk_user_session_id` FOREIGN KEY (`user_id`) REFERENCES `[{prefix}]users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=3002 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_logins`
--

DROP TABLE IF EXISTS `[{prefix}]user_logins`;
CREATE TABLE `[{prefix}]user_logins` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `user_id` int(16) DEFAULT NULL,
  `ip` varchar(255) NOT NULL,
  `ua` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_user_login_id` FOREIGN KEY (`user_id`) REFERENCES `[{prefix}]users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=4002 ;
