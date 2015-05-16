START TRANSACTION;
--
-- Table structure for table `dril_book`
--

CREATE TABLE IF NOT EXISTS `dril_book` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(200) COLLATE utf8_czech_ci NOT NULL,
  `description` varchar(250) COLLATE utf8_czech_ci DEFAULT NULL,
  `question_lang_id` int(11) NOT NULL,
  `answer_lang_id` int(11) NOT NULL,
  `level_id` int(11) NOT NULL,
  `downloaded` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `download` smallint(5) unsigned NOT NULL DEFAULT '0',
  `is_shared` tinyint(1) NOT NULL DEFAULT '0',
  `dril_category_id` smallint(1) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dril_book_has_lecture`
--

CREATE TABLE IF NOT EXISTS `dril_book_has_lecture` (
`id` int(10) unsigned NOT NULL,
  `dril_book_id` int(10) unsigned NOT NULL,
  `name` varchar(150) NOT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `no_of_words` smallint(5) unsigned NOT NULL DEFAULT '0',
  `downloaded` mediumint(8) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dril_book_has_tag`
--

CREATE TABLE IF NOT EXISTS `dril_book_has_tag` (
  `dril_book_id` varchar(32) NOT NULL,
  `dril_tag_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dril_book_is_favorited`
--

CREATE TABLE IF NOT EXISTS `dril_book_is_favorited` (
  `dril_book_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dril_category`
--

CREATE TABLE IF NOT EXISTS `dril_category` (
`id` smallint(5) unsigned NOT NULL,
  `parent_id` smallint(6) unsigned DEFAULT NULL,
  `ordering` smallint(5) unsigned NOT NULL,
  `name_en` varchar(150) NOT NULL,
  `name_sk` varchar(150) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=91 ;

--
-- Dumping data for table `dril_category`
--

INSERT INTO `dril_category` (`id`, `parent_id`, `ordering`, `name_en`, `name_sk`) VALUES
(1, NULL, 1, 'The Animals', 'Zvieratá'),
(2, 1, 100, 'The Animals', 'Zvieratá (ostatné)'),
(3, 1, 101, 'Mammals', 'Cicavce'),
(5, 1, 103, 'Water Animals', 'Zvieratá žijúce vo vode'),
(6, 1, 104, 'Birds', 'Vtáctvo'),
(7, 1, 105, 'Insects & Reptiles', 'Hmyz a plazy'),
(8, 1, 106, 'Pets', 'Domáce zvieratá'),
(9, NULL, 2, 'The Health', 'Zdravie'),
(10, 9, 201, 'Health Problems', 'Zdravotné problémy'),
(11, 9, 202, 'Medicines & Remedies', 'Lieky a liečivá'),
(12, 9, 203, 'The Hospital', 'Nemocnice'),
(13, 9, 204, 'The Human Body', 'Ľudské telo'),
(14, 9, 200, 'The Health', 'Zdravie'),
(15, NULL, 3, 'Food & Meals', 'Jedlo a stravovanie'),
(16, 15, 300, 'Food & Meals', 'Jedlo a stravovanie (všeobecne)'),
(17, 15, 301, 'Cooking Verbs', 'Základné slovesá o varení'),
(18, 15, 302, 'Drinks & Beverages', 'Nápoje'),
(19, 15, 303, 'Fish & Seafood', 'Ryby a morské plody'),
(20, 15, 304, 'General Meals', 'Klasické jedlá'),
(21, 15, 305, 'Meats & Poultry', 'Mäso a hydina'),
(22, 15, 306, 'Spices & Seasonings ', 'Koreniny a prísady'),
(23, 15, 307, 'The Fruits', 'Ovocie'),
(24, 15, 308, 'The Vegetables', 'Zelenina'),
(25, NULL, 4, 'Leisure & Fun', 'Voľný čas a zábava'),
(26, 25, 400, 'Leisure & Fun', 'Zábava a voľný čas'),
(27, 25, 401, 'At The Beach', 'Na plážy'),
(28, 25, 402, 'Camping & Fishing ', 'Kempovanie a rybolov'),
(29, 25, 403, 'Musical Instruments', 'Hudobné nástroje'),
(30, 25, 404, 'Recreation & Hobbies', 'Rekreácia a koníčky'),
(31, 25, 405, 'Sport Activities', 'Športové kativity'),
(32, NULL, 5, 'The City', 'Mesto'),
(33, 32, 500, 'The City', 'Mesto'),
(34, 32, 501, 'Buildings & Dwellings', 'Budovy a byty'),
(35, 32, 502, 'City Parts', 'Mestské časti'),
(36, 32, 503, 'Shops & Stores', 'Obchody a predajne'),
(37, NULL, 6, 'The House', 'Dom'),
(38, 37, 600, 'The House (other)', 'Dom (všeobecne)'),
(39, 37, 601, 'Parts Of The House', 'Časti domu'),
(40, 37, 602, 'The Bedroom', 'Spálňa'),
(41, 37, 603, 'The Garden', 'Záhrada'),
(42, 37, 604, 'The Kitchen', 'Kuchyňa'),
(43, 37, 605, 'The Living Room', 'Obyvačka'),
(44, 37, 606, 'The Workshop', 'Dielňa'),
(45, NULL, 7, 'The Nature', 'Príroda'),
(46, 45, 701, 'Flowers', 'Kvety'),
(47, 45, 700, 'The Nature (other)', 'Príroda (ostatné)'),
(48, 45, 702, 'Geography', 'Geografia'),
(49, 45, 703, 'Plants & Trees', 'Rastliny a stromy'),
(50, 45, 704, 'The Universe', 'Vesmír'),
(51, 45, 705, 'The Weather', 'Počasie'),
(52, NULL, 8, 'The People', 'Ľudia'),
(60, 52, 800, 'The People (odher)', 'Ľudia (ostatné)'),
(61, 52, 801, 'Family & Relatives', 'Rodina a príbuzní'),
(62, 52, 802, 'Feelings & Emotions', 'Pocity a emócie'),
(63, 52, 803, 'Jobs & Professions', 'Zamestnanie a profesie'),
(64, 52, 804, 'Clothing', 'Oblečenie'),
(65, 52, 805, 'Moods', 'Nálady'),
(66, 52, 806, 'Personality', 'Osobnosť'),
(67, NULL, 9, 'Time & Calendar', 'Čas a kalendár'),
(68, 67, 900, 'Time & Calendar', 'Čas dátum a kalendár'),
(69, NULL, 10, 'Education', 'Vzdelávanie'),
(70, 69, 1000, 'Education (Other)', 'Vzdelávanie (ostatné)'),
(71, 69, 1001, 'Business English', 'Obchodná angličtina'),
(72, 69, 1002, 'Legal English', 'Právnická angličtina'),
(73, 69, 1003, 'Information technology', 'Informačné technológie'),
(74, NULL, 11, 'The Grammar', 'Gramatika'),
(75, 74, 1100, 'The Grammer (other)', 'Gramatika (Ostatné)'),
(76, 74, 1101, 'The definite article - the and a/an', 'Určitné a neurčité členy'),
(77, 74, 1102, 'Irregular Verbs', 'Nepravidelné slovesa'),
(78, 74, 1103, 'Modals', 'Modálne slovesá'),
(79, 74, 1104, 'Conditionals', 'Kondicionály'),
(80, 74, 1105, 'Active/Passive ', 'Aktiv / Pasív'),
(81, 74, 1106, 'Present Simple', 'Jednoduchý prítomný čas'),
(82, 74, 1107, 'Past Simple', 'Jednoduchý minulý čas'),
(83, 74, 1108, 'Future simple', 'Jednoduchý budúci čas'),
(84, 74, 1109, 'Present Continuous', 'Prítomný priebehový čas'),
(85, 74, 1110, 'Pas Continuous ', 'Minulý priebehový čas'),
(86, 74, 1111, 'Future Continuous', 'Budúci priebehový čas'),
(87, 74, 1112, 'Present Perfect', 'Predprítomný čas'),
(88, 74, 1113, 'Pas Perfect ', 'Predminulý čas'),
(89, 74, 1114, 'Future Perfect ', 'Predbudúci čas'),
(90, 74, 1115, 'Phrasal Verbs', 'Frázové slovesá');

-- --------------------------------------------------------

--
-- Table structure for table `dril_error`
--

CREATE TABLE IF NOT EXISTS `dril_error` (
`id` int(10) unsigned NOT NULL,
  `user_agent` text CHARACTER SET latin1,
  `cause` text CHARACTER SET latin1,
  `error_message` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `error_url` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `stack_trace` text CHARACTER SET latin1,
  `version` varchar(7) CHARACTER SET latin1 DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dril_lecture_has_word`
--

CREATE TABLE IF NOT EXISTS `dril_lecture_has_word` (
`id` int(10) unsigned NOT NULL,
  `question` varchar(200) NOT NULL,
  `answer` varchar(200) NOT NULL,
  `last_rating` tinyint(3) unsigned DEFAULT NULL,
  `viewed` smallint(5) unsigned NOT NULL DEFAULT '0',
  `last_viewd` timestamp NULL DEFAULT NULL,
  `avg_rating` float unsigned DEFAULT '0',
  `is_activated` tinyint(1) NOT NULL DEFAULT '0',
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dril_lecture_id` varchar(32) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dril_report`
--

CREATE TABLE IF NOT EXISTS `dril_report` (
`id` smallint(5) unsigned NOT NULL,
  `id_user` int(10) unsigned DEFAULT NULL,
  `name` varchar(40) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `message` text NOT NULL,
  `user_agent` text NOT NULL,
  `ip` varchar(32) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dril_session`
--

CREATE TABLE IF NOT EXISTS `dril_session` (
`id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `date` date NOT NULL,
  `learned_cards` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hits` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dril_tag`
--

CREATE TABLE IF NOT EXISTS `dril_tag` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(50) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dril_book`
--
ALTER TABLE `dril_book`
 ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`), ADD KEY `question_lang_id` (`question_lang_id`), ADD KEY `answer_lang_id` (`answer_lang_id`), ADD KEY `level_id` (`level_id`), ADD KEY `dril_category_id` (`dril_category_id`);

--
-- Indexes for table `dril_book_has_lecture`
--
ALTER TABLE `dril_book_has_lecture`
 ADD PRIMARY KEY (`id`), ADD KEY `dril_book_id` (`dril_book_id`);

--
-- Indexes for table `dril_book_has_tag`
--
ALTER TABLE `dril_book_has_tag`
 ADD KEY `dril_book_id` (`dril_book_id`), ADD KEY `dril_tag_id` (`dril_tag_id`);

--
-- Indexes for table `dril_book_is_favorited`
--
ALTER TABLE `dril_book_is_favorited`
 ADD KEY `dril_book_id` (`dril_book_id`), ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `dril_category`
--
ALTER TABLE `dril_category`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dril_error`
--
ALTER TABLE `dril_error`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dril_lecture_has_word`
--
ALTER TABLE `dril_lecture_has_word`
 ADD PRIMARY KEY (`id`), ADD KEY `dril_lecture_id` (`dril_lecture_id`);

--
-- Indexes for table `dril_report`
--
ALTER TABLE `dril_report`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dril_session`
--
ALTER TABLE `dril_session`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dril_tag`
--
ALTER TABLE `dril_tag`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dril_book`
--
ALTER TABLE `dril_book`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dril_book_has_lecture`
--
ALTER TABLE `dril_book_has_lecture`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dril_category`
--
ALTER TABLE `dril_category`
MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=91;
--
-- AUTO_INCREMENT for table `dril_error`
--
ALTER TABLE `dril_error`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dril_lecture_has_word`
--
ALTER TABLE `dril_lecture_has_word`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dril_report`
--
ALTER TABLE `dril_report`
MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dril_session`
--
ALTER TABLE `dril_session`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dril_tag`
--
ALTER TABLE `dril_tag`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--


-- ALTER TABLE `book` ADD `lang_question` TINYINT UNSIGNED NOT NULL , ADD `lang_answer` TINYINT UNSIGNED NOT NULL ;
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

-- ALTER TABLE `book` ADD `sync` BOOLEAN NOT NULL DEFAULT FALSE AFTER `lang_answer` , ADD `enabled` BOOLEAN NOT NULL DEFAULT TRUE AFTER `sync` ;


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





ALTER TABLE `user` ADD `token` VARCHAR(50) NULL AFTER `edit`;
ALTER TABLE `user` ADD `token_created` DATETIME NULL AFTER `token`;
ALTER TABLE `user` CHANGE `login` `login` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `user` CHANGE `givenname` `givenname` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `user` CHANGE `surname` `surname` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `user` ADD `locale_id` SMALLINT UNSIGNED NOT NULL DEFAULT '1' AFTER `id_user`, ADD `target_lang_id` SMALLINT UNSIGNED NULL AFTER `locale_id`;
ALTER TABLE `user` CHANGE `target_lang_id` `target_locale_id` SMALLINT(5) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `user` ADD `word_limit` SMALLINT NOT NULL DEFAULT '1000' AFTER `donated`;


ALTER TABLE `import_book` ADD `transmitted` BOOLEAN NOT NULL DEFAULT FALSE AFTER `shared`;

ALTER TABLE `lang` ADD PRIMARY KEY (`id_lang`), ADD UNIQUE KEY `code` (`code`);
ALTER TABLE `lang` MODIFY `id_lang` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- Constraints for table `dril_book`
--
ALTER TABLE `dril_book`
ADD CONSTRAINT `category_id_fk` FOREIGN KEY (`dril_category_id`) REFERENCES `dril_category` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
ADD CONSTRAINT `lang_a_fk` FOREIGN KEY (`answer_lang_id`) REFERENCES `lang` (`id_lang`),
ADD CONSTRAINT `lang_q_fk` FOREIGN KEY (`question_lang_id`) REFERENCES `lang` (`id_lang`);

CREATE VIEW `dril_view_en`AS SELECT `book`.*, 
    UNIX_TIMESTAMP(`book`.`changed`) as `changed_timestamp`,
    UNIX_TIMESTAMP(`book`.`created`) as `created_timestamp`,
    `lang_question`.`name_en` AS `question_lang_name`,
    `lang_answer`.`name_en` AS `answer_lang_name`,
    `lang_question`.`code` AS `question_lang_code`,
    `lang_answer`.`code` AS `answer_lang_code`,
    `l`.`name_en` AS `level_name`,
     count(`lecture`.`id`) AS `no_of_lectures`,
         sum(`lecture`.`no_of_words`) AS `no_of_words`,
     `u`.`givenname`,
     `u`.`surname`,
     `u`.`login`
FROM `dril_book` `book` 
INNER JOIN `lang` `lang_question` ON `book`.`question_lang_id` = `lang_question`.`id_lang`
INNER JOIN `lang` `lang_answer` ON `book`.`answer_lang_id` = `lang_answer`.`id_lang`
INNER JOIN `level` `l` ON `book`.`level_id` = `l`.`id_level`
LEFT JOIN `dril_book_has_lecture` `lecture` ON `book`.`id` = `lecture`.`dril_book_id`
LEFT JOIN `user` `u` ON `u`.`id_user`=`book`.`user_id` 
GROUP BY `book`.`id` 
ORDER BY `book`.`id` DESC;


CREATE VIEW `dril_view_sk`AS SELECT `book`.*, 
    UNIX_TIMESTAMP(`book`.`changed`) as `changed_timestamp`,
    UNIX_TIMESTAMP(`book`.`created`) as `created_timestamp`,
    `lang_question`.`name_sk` AS `question_lang_name`,
    `lang_answer`.`name_sk` AS `answer_lang_name`,
    `lang_question`.`code` AS `question_lang_code`,
    `lang_answer`.`code` AS `answer_lang_code`,
    `l`.`name_sk` AS `level_name`,
     count(`lecture`.`id`) AS `no_of_lectures`,
         sum(`lecture`.`no_of_words`) AS `no_of_words`,
     `u`.`givenname`,
     `u`.`surname`,
     `u`.`login`
FROM `dril_book` `book` 
INNER JOIN `lang` `lang_question` ON `book`.`question_lang_id` = `lang_question`.`id_lang`
INNER JOIN `lang` `lang_answer` ON `book`.`answer_lang_id` = `lang_answer`.`id_lang`
INNER JOIN `level` `l` ON `book`.`level_id` = `l`.`id_level`
LEFT JOIN `dril_book_has_lecture` `lecture` ON `book`.`id` = `lecture`.`dril_book_id`
LEFT JOIN `user` `u` ON `u`.`id_user`=`book`.`user_id` 
GROUP BY `book`.`id` 
ORDER BY `book`.`id` DESC;


DROP VIEW `book_view`;
CREATE VIEW `book_view` AS 
SELECT `book`.`_id` AS `_id`, `book`.`name` AS `name`, `book`.`id_user` as `id_user`
  , `book`.`lang` AS `lang`, `book`.`lang_a` AS `lang_a`, `book`.`level` AS
  `level`, `book`.`descr` AS `descr`, `book`.`author` AS
  `author`, `book`.`import_id` AS `import_id`, `book`.`email` AS `email`, `book`.`create` AS `create`,
  `book`.`downloads` AS `downloads`, `lang_answer`.`name_sk` AS `lang_answer`,
  `lang_question`.`name_sk` AS `lang_question`, count(`words`.`token`) AS `count`,
  `l`.`name_en` AS `level_name`, `book`.`shared` AS `shared`, `u`.`login`,
  `book`.`transmitted` AS `transmitted`
FROM `import_book` `book` 
JOIN `lang` `lang_question` ON `book`.`lang` = `lang_question`.`id_lang`
JOIN `lang` `lang_answer` ON `book`.`lang_a` = `lang_answer`.`id_lang`
JOIN `level` `l` ON `book`.`level` = `l`.`id_level`
LEFT JOIN `import_word` `words` ON `book`.`import_id` = `words`.`token`
LEFT JOIN `user` `u` ON `u`.`id_user`=`book`.`id_user` 
LEFT OUTER JOIN `user_has_favorite`  f on f.`id_book`=`book`.`_id` and f.`id_user`=1
GROUP BY `book`.`_id` 
ORDER BY `book`.`_id` DESC;

COMMIT;