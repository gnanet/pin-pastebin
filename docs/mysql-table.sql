CREATE TABLE `pin` (
  `url` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `rawtext` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `finaltext` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `lang` varchar(10) COLLATE utf8mb4_bin NOT NULL,
  `ts` int(12) NOT NULL,
  UNIQUE KEY `url` (`url`),
  KEY `ts` (`ts`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin
