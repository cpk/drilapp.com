ALTER TABLE `level` CHANGE `id_level` `id_level` TINYINT(4) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `lang` ADD `code` VARCHAR(2) NOT NULL AFTER `name_en`;
UPDATE `lang` SET `code`='en' WHERE id_lang = 1;
UPDATE `lang` SET `code`='de' WHERE id_lang = 2;
UPDATE `lang` SET `code`='fr' WHERE id_lang = 3;
UPDATE `lang` SET `code`='es' WHERE id_lang = 4;
UPDATE `lang` SET `code`='sk' WHERE id_lang = 5;
UPDATE `lang` SET `code`='cs' WHERE id_lang = 6;
UPDATE `lang` SET `code`='sv' WHERE id_lang = 7;
ALTER TABLE `lang` ADD UNIQUE(`code`);

CREATE TABLE IF NOT EXISTS `dril_book` (
`id` int(11) unsigned NOT NULL,
  `name` varchar(200) COLLATE utf8_czech_ci NOT NULL,
  `question_lang_id` int(11) NOT NULL,
  `answer_lang_id` int(11) NOT NULL,
  `level_id` int(11) NOT NULL,
  `downloaded` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dril_book_has_lecture`
--

CREATE TABLE IF NOT EXISTS `dril_book_has_lecture` (
`id` int(10) unsigned NOT NULL,
  `dril_book_id` int(10) unsigned NOT NULL,
  `name` varchar(150) NOT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dril_book_has_tag`
--

CREATE TABLE IF NOT EXISTS `dril_book_has_tag` (
  `dril_book_id` int(10) unsigned NOT NULL,
  `dril_tag_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `avg_rating` float unsigned DEFAULT NULL,
  `is_learned` tinyint(1) NOT NULL DEFAULT '0',
  `is_favorite` tinyint(1) NOT NULL DEFAULT '0',
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dril_lecture_id` int(10) unsigned NOT NULL
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
 ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`), ADD KEY `question_lang_id` (`question_lang_id`), ADD KEY `answer_lang_id` (`answer_lang_id`), ADD KEY `level_id` (`level_id`);

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
-- Indexes for table `dril_lecture_has_word`
--
ALTER TABLE `dril_lecture_has_word`
 ADD PRIMARY KEY (`id`), ADD KEY `dril_lecture_id` (`dril_lecture_id`);

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
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dril_book_has_lecture`
--
ALTER TABLE `dril_book_has_lecture`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dril_lecture_has_word`
--
ALTER TABLE `dril_lecture_has_word`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dril_tag`
--
ALTER TABLE `dril_tag`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `dril_book`
--
ALTER TABLE `dril_book`
ADD CONSTRAINT `lang_a_fk` FOREIGN KEY (`answer_lang_id`) REFERENCES `lang` (`id_lang`),
ADD CONSTRAINT `lang_q_fk` FOREIGN KEY (`question_lang_id`) REFERENCES `lang` (`id_lang`);

--
-- Constraints for table `dril_book_has_lecture`
--
ALTER TABLE `dril_book_has_lecture`
ADD CONSTRAINT `dril_book_id_fk` FOREIGN KEY (`dril_book_id`) REFERENCES `dril_book` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dril_book_has_tag`
--
ALTER TABLE `dril_book_has_tag`
ADD CONSTRAINT `dril_tag_book_id_fk` FOREIGN KEY (`dril_book_id`) REFERENCES `dril_book` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `dril_tag_id_fk` FOREIGN KEY (`dril_tag_id`) REFERENCES `dril_tag` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `dril_lecture_has_word`
--
ALTER TABLE `dril_lecture_has_word`
ADD CONSTRAINT `lecture_id_fk` FOREIGN KEY (`dril_lecture_id`) REFERENCES `dril_book_has_lecture` (`id`) ON DELETE CASCADE;
