ALTER TABLE `book` ADD `lang_question` TINYINT UNSIGNED NOT NULL ,
ADD `lang_answer` TINYINT UNSIGNED NOT NULL ;
update book set lang_question=1, lang_answer=6 where _id=2;
update book set lang_question=1, lang_answer=6 where _id=3;
update book set lang_question=6, lang_answer=1 where _id=4;
update book set lang_question=1, lang_answer=6 where _id=6;
update book set lang_question=1, lang_answer=6 where _id=7;
update book set lang_question=6, lang_answer=1 where _id=9;
update book set lang_question=6, lang_answer=1 where _id=14;
update book set lang_question=1, lang_answer=6 where _id=15;
update book set lang_question=2, lang_answer=6 where _id=16;
update book set lang_question=2, lang_answer=6 where _id=17;
update book set lang_question=6, lang_answer=2 where _id=18;
update book set lang_question=2, lang_answer=6 where _id=19;
update book set lang_question=2, lang_answer=6 where _id=20;

INSERT INTO `config` (`id_config` ,`key` ,`val`) VALUES ( 15 , 'dril_web_auth', '3mYxaMXa8pwrXZsdf');

ALTER TABLE `book` ADD `sync` BOOLEAN NOT NULL DEFAULT FALSE AFTER `lang_answer` ,
ADD `enabled` BOOLEAN NOT NULL DEFAULT TRUE AFTER `sync` ;


ALTER TABLE `level` CHANGE `name` `name_en` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `level` ADD `name_sk` VARCHAR(50) NOT NULL AFTER `name_en`;
ALTER TABLE `level` CHANGE `name_en` `name_en` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

UPDATE `level` SET `id_level` = 1,`name_en` = 'Starter / Beginner',`name_sk` = 'Úplný začiatočník',`descr` = '<ul>\r\n<li>žádná či velice malá znalost angličtiny</li>\r\n<li>student se učí základní slovní zásobu a gramatiku, seznamuje se s anglickou výslovností</li>\r\n<li>učí se odpovídat na běžné osobní otázky (jméno, věk apod.)</li>\r\n</ul>' WHERE `level`.`id_level` = 1;
UPDATE `level` SET `id_level` = 2,`name_en` = 'Elementary',`name_sk` = 'Začiatočník',`descr` = '<ul>\r\n<li>student již ovládá základní slovní zásobu a gramatiku <br></li>\r\n<li>umí vyslovovat anglická slova<br></li>\r\n<li>pasivně rozumí psanému a mluvenému projevu týkajícího se osobních informací a základních každodenních situací (za předpokladu, že mluvčí mluví pomalu a zřetelně)</li>\r\n<li>dokáže jednoduše odpovědět na otázky týkající se jeho samého a jeho každodenního života (moje rodina, moje záliby, můj dům apod.)</li>\r\n<li>dovede používat angličtinu k uspokojení základních potřeb (zeptat se na cestu, kopit si jídlo apod.), dovede klást jednoduché otázky</li>\r\n</ul>' WHERE `level`.`id_level` = 2;
UPDATE `level` SET `id_level` = 3,`name_en` = 'Pre-Intermediate',`name_sk` = 'Mierne pokročilý',`descr` = '<ul>\r\n<li> student rozumí mluvenému i psanému projevu v oblastech, které se ho bezprostředně týkají</li>\r\n<li> dovede na tato témata vést rozhovor</li>\r\n<li> dokáže se v běžných situacích domluvit v cizí zemi</li>\r\n<li> dovede mluvit o svých zážitcích, zkušenostech, plánech, ambicích, umí vysvětlit a zdůvodnit své jednání a to i písemnou formou</li>\r\n<li> jeho mluvený projev je plynulejší, psaný projev komplexnější (dovede používat jednodušší souvětí) a slovní zásoba se prohlubuje a konkretizuje</li>\r\n</ul>' WHERE `level`.`id_level` = 3;
UPDATE `level` SET `id_level` = 4,`name_en` = 'Intermediate',`name_sk` = 'Stredne pokročilý I.',`descr` = '<ul>\r\n<li> student rozumí hlavním myšlenkám běžného mluveného i psaného projevu obsahujícího i neznámé výrazy a to i těch, které se týkají abstraktních témat</li>\r\n<li> dokáže spontánně reagovat při rozhovoru s rodilým mluvčím</li>\r\n<li> jeho mluvený projev je víceméně plynulý</li>\r\n<li> dokáže se plynně vyjadřovat psanou formou, vyjádřit a vysvětlit tak svůj názor na současné problémy</li>\r\n<li> rozlišuje formální a neformální jazyk</li>\r\n</ul>' WHERE `level`.`id_level` = 4;
UPDATE `level` SET `id_level` = 5,`name_en` = 'Upper-Intermediate',`name_sk` = 'Stredne pokročilý II.',`descr` = '<ul>\r\n<li> student rozumí hlavním myšlenkám běžného mluveného i psaného projevu obsahujícího i neznámé výrazy a to i těch, které se týkají abstraktních témat</li>\r\n<li> dokáže spontánně reagovat při rozhovoru s rodilým mluvčím</li>\r\n<li> jeho mluvený projev je víceméně plynulý</li>\r\n<li> dokáže se plynně vyjadřovat psanou formou, vyjádřit a vysvětlit tak svůj názor na současné problémy</li>\r\n<li> rozlišuje formální a neformální jazyk</li>\r\n</ul>' WHERE `level`.`id_level` = 5;
UPDATE `level` SET `id_level` = 6,`name_en` = 'Advanced',`name_sk` = 'Pokročilý',`descr` = '<ul>\r\n<li> bez problému rozumí delšímu autentickému mluvenému i psanému projevu, rozeznává skrytý význam textu</li>\r\n<li>mluví rychle a plynule, aniž by hledal neznámá slova</li>\r\n<li>flexibilně používá angličtinu pro společenské, studijní a profesní potřeby</li>\r\n<li>jeho psaný text je jasný, vhodně strukturovaný a členěný</li>\r\n</ul>' WHERE `level`.`id_level` = 6;
UPDATE `level` SET `id_level` = 7,`name_en` = 'Proficient',`name_sk` = 'Proficient',`descr` = '<ul>\r\n<li> bez námahy rozumí téměř všemu, co slyší či čte</li>\r\n<li>jeho mluvený i písemný projev je velice plynulý, srozumitelný a přesný rozlišuje drobné významové rozdíly mezi slovy či frázemi</li>\r\n<li>rozlišuje velké množství odborných termínů, regionálních výrazů, slangu</li>\r\n<li>dovede zpracovávat informace a ty dále interpretovat a reagovat na ně, kritizovat je apod.</li>\r\n<li>rodilý mluvčí si v jeho mluveném či psaném projevu nevšimne chyb v gramatice, použití slov a frází, výslovnosti, apod. a na první pohled nepozná, že se nejedná o rodilého mluvčího</li>\r\n</ul>' WHERE `level`.`id_level` = 7;

DROP VIEW `book_view`;
CREATE VIEW `book_view` AS 
SELECT `book`.`_id` AS `_id`, `book`.`name` AS `name`, `book`.`id_user` as `id_user`
	, `book`.`lang` AS `lang`, `book`.`lang_a` AS `lang_a`, `book`.`level` AS
	`level`, `book`.`descr` AS `descr`, `book`.`author` AS
	`author`, `book`.`import_id` AS `import_id`, `book`.`email` AS `email`, `book`.`create` AS `create`,
	`book`.`downloads` AS `downloads`, `lang_answer`.`name_sk` AS `lang_answer`,
	`lang_question`.`name_sk` AS `lang_question`, count(`words`.`token`) AS `count`,
	`l`.`name_en` AS `level_name`, `book`.`shared` AS `shared`, `u`.`login`
FROM `import_book` `book` 
JOIN `lang` `lang_question` ON `book`.`lang` = `lang_question`.`id_lang`
JOIN `lang` `lang_answer` ON `book`.`lang_a` = `lang_answer`.`id_lang`
JOIN `level` `l` ON `book`.`level` = `l`.`id_level`
LEFT JOIN `import_word` `words` ON `book`.`import_id` = `words`.`token`
LEFT JOIN `user` `u` ON `u`.`id_user`=`book`.`id_user` 
LEFT OUTER JOIN `user_has_favorite`  f on f.`id_book`=`book`.`_id` and f.`id_user`=1
GROUP BY `book`.`_id` 
ORDER BY `book`.`_id` DESC ;

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


ALTER TABLE `import_book` ADD `transmitted` BOOLEAN NOT NULL DEFAULT FALSE AFTER `shared`;