CREATE TABLE `pin` (
 `url` varchar(50) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL,
 `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL,
 `lang` varchar(10) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL,
 `ts` int(12) NOT NULL,
 UNIQUE KEY `url` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
