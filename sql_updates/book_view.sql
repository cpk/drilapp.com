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


