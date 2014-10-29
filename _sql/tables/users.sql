CREATE TABLE `users` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hash` char(40) NOT NULL COMMENT 'user sha1 hash (40 carac)',
  `email` varchar(100) DEFAULT NULL COMMENT 'Email servant d''identifiant unique',
  `password` varchar(255) NOT NULL,
  `level` tinyint(1) unsigned NOT NULL,
  `creatorUid` int(10) unsigned NOT NULL,
  `validated` tinyint(1) DEFAULT '0' COMMENT 'Compte validé ou pas',
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création du compte',
  `loggedAt` datetime NOT NULL COMMENT 'Date de dernière connexion',
  `updatedAt` datetime DEFAULT NULL COMMENT 'Date de dernière mise à jour',
  `updatedBy` int(10) unsigned NOT NULL COMMENT 'Auteur de la mise à jour',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `unique` (`hash`),
  KEY `creatorUid` (`creatorUid`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`creatorUid`) REFERENCES `users` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=16611 DEFAULT CHARSET=utf8 COMMENT='Table des infos de base de l''utilisateur';
