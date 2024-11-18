--
-- Table structure for table `group`
--

CREATE TABLE `group` (
  `uid` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `update_date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `create_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`uid`)
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
-- Table data for table `state`
--

INSERT INTO `state` VALUES (1, "First"), (2, "Second"), (3, "Close");

--
-- Table structure for table `election`
--

CREATE TABLE `election` (
  `uid` varchar(20) NOT NULL,
  `uid_group` varchar(20) NOT NULL,
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
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table data for table `role`
--

INSERT INTO `role` VALUES (1, "student"), (10, "teacher");

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `uid` varchar(20) NOT NULL,
  `identifiant` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `uid_group` varchar(20) NOT NULL,
  `id_role` int(11) NOT NULL DEFAULT 1,
  `update_date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `create_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`uid`),
  KEY `user_group_FK` (`uid_group`),
  KEY `user_role_FK` (`id_role`),
  CONSTRAINT `user_group_FK` FOREIGN KEY (`uid_group`) REFERENCES `group` (`uid`),
  CONSTRAINT `user_role_FK` FOREIGN KEY (`id_role`) REFERENCES `role` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `vote`
--

CREATE TABLE `vote` (
  `uid` varchar(20) NOT NULL,
  `uid_user` varchar(20) NOT NULL,
  `uid_candidat` varchar(20) DEFAULT NULL,
  `uid_election` varchar(20) NOT NULL,
  `round` int(11) NOT NULL,
  `update_date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `create_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`uid`),
  KEY `vote_user_FK` (`uid_user`),
  KEY `vote_user_FK_1` (`uid_candidat`),
  KEY `vote_election_FK` (`uid_election`),
  CONSTRAINT `vote_election_FK` FOREIGN KEY (`uid_election`) REFERENCES `election` (`uid`),
  CONSTRAINT `vote_user_FK` FOREIGN KEY (`uid_user`) REFERENCES `user` (`uid`),
  CONSTRAINT `vote_user_FK_1` FOREIGN KEY (`uid_candidat`) REFERENCES `user` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
