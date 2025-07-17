<?php
// ================ CRC ================
// version: 1.39.02
// hash: 63a2574c560fd67f190f50908b921a4f8a17945b5c326f123c9f6edf88210894
// date: 20 September 2019 15:39
// ================ CRC ================
itMySQL::_request(
"CREATE TABLE `".DB_PREFIX."settings` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(64) NOT NULL,
 `user_id` int(11) DEFAULT NULL,
 `value` longtext DEFAULT NULL,
 PRIMARY KEY (`id`),
 KEY `user_id-name` (`id`,`name`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4"
);
?>