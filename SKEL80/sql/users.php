<?php
// ================ CRC ================
// version: 1.38.02
// hash: e19d06f74eaeea2ba14e7de379bd8553698c99608d74d2bfc7c2034bb85daa7e
// date: 17 September 2019 17:56
// ================ CRC ================
itMySQL::_request(
"CREATE TABLE `".DB_PREFIX."users` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `group_id` enum('USER','ADMIN') DEFAULT 'USER',
 `name` varchar(128) DEFAULT NULL,
 `sex` set('M','W') DEFAULT NULL,
 `b_date` date DEFAULT NULL,
 `login` varchar(128) NOT NULL,
 `password` varchar(64) NOT NULL,
 `email` varchar(128) DEFAULT NULL,
 `avatar` varchar(255) DEFAULT NULL,
 `description` mediumtext NOT NULL,
 `wall_id` int(11) DEFAULT NULL,
 `status` enum('ACTIVE','NOACTIVE','EXPIRED','BLOCKED') NOT NULL DEFAULT 'ACTIVE',
 `referral_id` int(11) DEFAULT NULL,
 `datetime` datetime NOT NULL,
 `social` mediumtext NOT NULL,
 `used` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
 PRIMARY KEY (`id`),
 KEY `login-password-status-used-datetime` (`login`,`password`,`status`,`used`,`datetime`) USING BTREE,
 FULLTEXT KEY `FULLTEXT` (`login`,`email`,`description`,`social`,`name`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 PACK_KEYS=0"
);
itMySQL::_insert_rec('users', [
	'login'		=> 'admin',
	'group_id'	=> 'ADMIN',
	'password'	=> sqlPassword('admin'),
	'datetime'	=> mysql_now(),
	]);
?>