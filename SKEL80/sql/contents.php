<?php
// ================ CRC ================
// version: 1.39.02
// hash: 27fcf7639f59ecaadf24589f6a96350ff85d3ab817308c5122af1a0cceb3ef61
// date: 20 September 2019 15:39
// ================ CRC ================
itMySQL::_request(
"CREATE TABLE `".DB_PREFIX."contents` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `title_xml` longtext DEFAULT NULL,
 `ed_xml` longtext DEFAULT NULL,
 `html_xml` longtext DEFAULT NULL,
 `avatar` varchar(128) DEFAULT NULL,
 `category_id` int(11) NOT NULL DEFAULT 2,
 `datetime` timestamp NOT NULL DEFAULT current_timestamp(),
 `status` enum('MODERATE','PUBLISHED','DELETED') NOT NULL DEFAULT 'MODERATE',
 `start_msg_id` int(11) DEFAULT NULL,
 `lang` varchar(4) DEFAULT NULL,
 `related_xml` longtext DEFAULT NULL,
 `show_as` enum('COMMON','BIG','TEXT','HIDE') NOT NULL DEFAULT 'COMMON',
 `url` varchar(128) DEFAULT NULL,
 `views` int(11) NOT NULL DEFAULT 0,
 PRIMARY KEY (`id`),
 KEY `category_id-status` (`category_id`,`status`),
 KEY `datetime` (`datetime`),
 FULLTEXT KEY `contents` (`title_xml`,`ed_xml`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4"
);
?>