<?php
// ================ CRC ================
// version: 1.39.02
// hash: 459ecf242d9e6504c4c245e64efd4e51bb042a48dd07b309f21b2de0229b4ce1
// date: 20 September 2019 15:39
// ================ CRC ================
itMySQL::_request(
"CREATE TABLE `".DB_PREFIX."blocks` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `content_id` int(11) DEFAULT NULL,
 `status` enum('MODERATE','PUBLISHED','DELETED') NOT NULL DEFAULT 'PUBLISHED',
 PRIMARY KEY (`id`),
 KEY `content_id-status-id` (`content_id`,`status`,`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4"
);
?>