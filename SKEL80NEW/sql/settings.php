<?
itMySQL::_request(
"CREATE TABLE `".DB_PREFIX."settings` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(64) NOT NULL,
 `user_id` int(11) DEFAULT NULL,
 `value` longtext DEFAULT NULL,
 PRIMARY KEY (`id`),
 KEY `user_id-name` (`id`,`name`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci"
);
?>