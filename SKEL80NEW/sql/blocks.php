<?
itMySQL::_request(
"CREATE TABLE `".DB_PREFIX."blocks` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `content_id` int(11) DEFAULT NULL,
 `status` enum('MODERATE','PUBLISHED','DELETED') NOT NULL DEFAULT 'PUBLISHED',
 PRIMARY KEY (`id`),
 KEY `content_id-status-id` (`content_id`,`status`,`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci"
);
?>