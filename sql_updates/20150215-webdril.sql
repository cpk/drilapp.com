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



