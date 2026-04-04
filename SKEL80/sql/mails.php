<?php
// ================ CRC ================
// version: 1.38.02
// hash: 7e3c3b115746d404153ce66de8d5c1ce3cdb8dba1de31a545a0a558c349ef97d
// date: 17 September 2019 17:56
// ================ CRC ================
itMySQL::_request(
"CREATE TABLE `".DB_PREFIX."mails` (
 `id` bigint(20) NOT NULL AUTO_INCREMENT,
 `from` varchar(128) NOT NULL,
 `to` varchar(128) NOT NULL,
 `reply` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
 `subject` varchar(256) NOT NULL,
 `message` longtext NOT NULL,
 `code` longtext DEFAULT NULL,
 `files_xml` longtext DEFAULT NULL,
 `datetime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
 `mailing_id` int(11) DEFAULT NULL,
 `status` enum('PREPARED','WAIT','SEND','RECIEVED','ERROR') NOT NULL DEFAULT 'PREPARED',
 PRIMARY KEY (`id`),
 KEY `from` (`from`),
 KEY `to` (`to`),
 KEY `status` (`status`),
 KEY `datetime` (`datetime`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4"
);
?>