<?php
// ================ CRC ================
// version: 1.39.02
// hash: 6950fbf4c7867d1e8036639f167ce6dee91d9d72f9eb86480f18d787c5a033bb
// date: 20 September 2019 15:39
// ================ CRC ================
itMySQL::_request(
"CREATE TABLE `".DB_PREFIX."sessions2` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `user_id` int(11) NOT NULL,
 `user_ip` varchar(15) DEFAULT NULL,
 `user_agent` varchar(64) DEFAULT NULL,
 `datetime` datetime NOT NULL DEFAULT current_timestamp(),
 `closetime` datetime DEFAULT NULL,
 `uuid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-C000-000000000046',
 `status` enum('PUBLISHED','BLOCKED','EXPIRED','CLOSE') NOT NULL DEFAULT 'PUBLISHED',
 PRIMARY KEY (`id`),
 KEY `user_id-user_ip-user_agent-uuid-status-datetime-closetime` (`user_id`,`user_ip`,`user_agent`,`uuid`,`status`,`datetime`,`closetime`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8"
);
?>