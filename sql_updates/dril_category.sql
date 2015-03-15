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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dril_category`
--
ALTER TABLE `dril_category`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dril_category`
--
ALTER TABLE `dril_category`
MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=91;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
