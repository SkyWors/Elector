--
-- Table structure for table `group`
--

CREATE TABLE `group` (
  `uid` varchar(32) NOT NULL,
  `name` varchar(100) NOT NULL,
  `update_date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `create_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uid` varchar(32) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `identifiant` varchar(255) NOT NULL,
  `password` varchar(100) NOT NULL,
  `uid_group` varchar(32) DEFAULT NULL,
  `date_update` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `date_create` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`uid`),
  UNIQUE KEY `users_unique` (`identifiant`),
  KEY `users_group_FK` (`uid_group`),
  CONSTRAINT `users_group_FK` FOREIGN KEY (`uid_group`) REFERENCES `group` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Trigger for table `users`
--

CREATE OR REPLACE TRIGGER add_role
AFTER INSERT
ON users FOR EACH ROW
BEGIN
	INSERT INTO user_role (uid_user) VALUES (NEW.uid);
END;

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Data for table `roles`
--

INSERT INTO `roles` VALUES
(1,'user'),
(2,'teacher'),
(10,'administrator');

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `uid_user` varchar(32) NOT NULL,
  `id_role` int(11) NOT NULL DEFAULT 1,
  `date_update` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`uid_user`,`id_role`),
  KEY `user_role_roles_FK` (`id_role`),
  CONSTRAINT `user_role_roles_FK` FOREIGN KEY (`id_role`) REFERENCES `roles` (`id`),
  CONSTRAINT `user_role_users_FK` FOREIGN KEY (`uid_user`) REFERENCES `users` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `state`
--

CREATE TABLE `state` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Data for table `state`
--

INSERT INTO `state` VALUES (1, "First"), (2, "Second"), (3, "Close");

--
-- Table structure for table `election`
--

CREATE TABLE `election` (
  `uid` varchar(32) NOT NULL,
  `uid_group` varchar(32) NOT NULL,
  `id_state` int(11) NOT NULL DEFAULT 1,
  `update_date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `create_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`uid`),
  KEY `election_group_FK` (`uid_group`),
  KEY `election_state_FK` (`id_state`),
  CONSTRAINT `election_group_FK` FOREIGN KEY (`uid_group`) REFERENCES `group` (`uid`),
  CONSTRAINT `election_state_FK` FOREIGN KEY (`id_state`) REFERENCES `state` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `vote`
--

CREATE TABLE `vote` (
  `uid` varchar(32) NOT NULL,
  `uid_user` varchar(32) NOT NULL,
  `uid_candidat` varchar(32) DEFAULT NULL,
  `uid_election` varchar(32) NOT NULL,
  `round` int(11) NOT NULL,
  `update_date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `create_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`uid`),
  KEY `vote_user_FK` (`uid_user`),
  KEY `vote_user_FK_1` (`uid_candidat`),
  KEY `vote_election_FK` (`uid_election`),
  CONSTRAINT `vote_election_FK` FOREIGN KEY (`uid_election`) REFERENCES `election` (`uid`),
  CONSTRAINT `vote_user_FK` FOREIGN KEY (`uid_user`) REFERENCES `users` (`uid`),
  CONSTRAINT `vote_user_FK_1` FOREIGN KEY (`uid_candidat`) REFERENCES `users` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Data for table `users`
--

INSERT INTO `users` (uid, name, surname, identifiant, password) VALUES ('MXpmeiaC_wWt7VjX', 'admin', 'admin', 'admin', '$2a$12$PeubcU86iHfnRSI6c2jWz.ooynyAcJb8aRgZVJT2/Naai1x1nYXty');

--
-- Data for table `user_role`
--

UPDATE user_role SET id_role = 10 WHERE uid_user = 'MXpmeiaC_wWt7VjX';
