DROP TABLE IF EXISTS `reports`;
CREATE TABLE `reports` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `who` varchar(100) NOT NULL,
  `received` datetime NOT NULL,
  `types` json NOT NULL,
  `report` json NOT NULL,
  PRIMARY KEY (`id`),
  KEY `received` (`received`),
  KEY `who` (`who`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
