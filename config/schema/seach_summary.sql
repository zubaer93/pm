CREATE TABLE IF NOT EXISTS `search_summary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `search_tag` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content_type` enum('company','user','trader') COLLATE utf8_unicode_ci DEFAULT NULL,
  `content_id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `params` text COLLATE utf8_unicode_ci,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
