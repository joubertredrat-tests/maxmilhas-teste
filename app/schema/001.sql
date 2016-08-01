DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'User''s identificator',
  `username` varchar(100) NOT NULL COMMENT 'User''s username',
  `password` varchar(255) NOT NULL COMMENT 'User''s password',
  `create_date` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'User''s creation date',
  `update_date` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'User''s update date',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Usernames tabledata';

DROP TABLE IF EXISTS `galleries`;
CREATE TABLE `galleries` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Gallery''s identificator',
  `name` varchar(255) NOT NULL COMMENT 'Gallery''s name',
  `create_date` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Gallery''s creation date',
  `update_date` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Gallery''s update date',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Galleries tabledata';

DROP TABLE IF EXISTS `photos`;
CREATE TABLE `photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Photo''s identificator',
  `filename` varchar(15) NOT NULL COMMENT 'Photo''s filename',
  `original_filename` text NOT NULL COMMENT 'Photo''s original filename from upload',
  `order` int(5) NOT NULL COMMENT 'Photo order in the gallery',
  `create_date` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Photo''s creation date',
  `update_date` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Photo''s update date',
  `galleries_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `galleries_id` (`galleries_id`),
  CONSTRAINT `photos_galleries_id_1` FOREIGN KEY (`galleries_id`) REFERENCES `galleries` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Photos tabledata';