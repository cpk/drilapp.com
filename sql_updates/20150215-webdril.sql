-- UPDATE
ALTER TABLE `level` CHANGE `id_level` `id_level` TINYINT(4) UNSIGNED NOT NULL AUTO_INCREMENT;


DROP TABLE IF EXISTS `lang`;
CREATE TABLE IF NOT EXISTS `lang` (
`id_lang` int(11) NOT NULL,
  `name_sk` varchar(50) NOT NULL,
  `name_en` varchar(50) NOT NULL,
  `code` varchar(2) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

INSERT INTO `lang` (`id_lang`, `name_sk`, `name_en`, `code`) VALUES
(1, 'Angličtina', 'English', 'en'),
(2, 'Nemčina', 'German', 'de'),
(3, 'Francúzština', 'French', 'fr'),
(4, 'Španielčina', 'Spanish', 'es'),
(5, 'Slovenčina', 'Slovak', 'sk'),
(6, 'Čeština', 'Czech', 'cs'),
(7, 'Švédština', 'Swedish', 'sv'),
(8, 'Fínština', 'Finnish', 'fi'),
(9, 'Esperanto', 'Esperanto', 'eo'),
(10, 'Dánština', 'Danish', 'da'),
(11, 'Gréčtina', 'Greek', 'el'),
(12, 'Chrovátština', 'Croatian', 'hr'),
(13, 'Taliančina', 'Italian', 'it'),
(14, 'Holandčina', 'Dutch', 'nl'),
(15, 'Polština', 'Polish', 'pl'),
(16, 'Slovinčina', 'Slovenian', 'sl'),
(17, 'Portugalčina', 'Portuguese', 'pt');

ALTER TABLE `lang` ADD PRIMARY KEY (`id_lang`), ADD UNIQUE KEY `code` (`code`);
ALTER TABLE `lang` MODIFY `id_lang` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;



ALTER TABLE `user` ADD `token` VARCHAR(50) NULL AFTER `edit`;
ALTER TABLE `user` ADD `token_created` DATETIME NULL AFTER `token`;
ALTER TABLE `user` ADD `donated` BOOLEAN NOT NULL DEFAULT FALSE AFTER `token_created`;
ALTER TABLE `user` CHANGE `login` `login` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `user` CHANGE `givenname` `givenname` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `user` CHANGE `surname` `surname` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `user` ADD `locale_id` SMALLINT UNSIGNED NOT NULL DEFAULT '1' AFTER `id_user`, ADD `target_lang_id` SMALLINT UNSIGNED NULL AFTER `locale_id`;
ALTER TABLE `user` CHANGE `target_lang_id` `target_locale_id` SMALLINT(5) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `user` ADD `word_limit` SMALLINT NOT NULL DEFAULT '1000' AFTER `donated`;

--INSERT INTO `db_drilapp_com`.`dril_lecture_has_word`
-- ( `question`, `answer`, `last_rating`, `viewed`, `last_viewd`, `avg_rating`, `is_activated`, `changed`, `created`, `dril_lecture_id`)
--SELECT 
-- `question`, `answer`, 0,   0,  0,  0, 0, `create`, `changed`, 5
--FROM `import_word` where token = 2481385
