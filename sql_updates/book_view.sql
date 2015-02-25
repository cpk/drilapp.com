CREATE VIEW dril_view AS SELECT `book`.*,
		`lang_question`.`name_en` AS `answer_lang_name`,
		`lang_answer`.`name_en` AS `aquestion_lang_name`,
		`l`.`name` AS `level_name`,
		 count(`word`.`dril_lecture_id`) AS `count_of_words`,
		 `u`.`givenname`,
		 `u`.`surname`,
		 `u`.`login`
FROM `dril_book` `book` 
INNER JOIN `lang` `lang_question` ON `book`.`question_lang_id` = `lang_question`.`id_lang`
INNER JOIN `lang` `lang_answer` ON `book`.`answer_lang_id` = `lang_answer`.`id_lang`
INNER JOIN `level` `l` ON `book`.`level_id` = `l`.`id_level`
LEFT JOIN `dril_book_has_lecture` `lecture` ON `book`.id = `lecture`.`dril_book_id`
LEFT JOIN `dril_lecture_has_word` `word` ON `lecture`.`id` = `word`.`dril_lecture_id`
LEFT JOIN `user` `u` ON `u`.`id_user`=`book`.`user_id` 
LEFT OUTER JOIN `user_has_favorite`  f on f.`id_book`=`book`.`id` and f.`id_user`=1
GROUP BY `book`.`id` 
ORDER BY `book`.`id` DESC 


CREATE VIEW `book_view` AS 
SELECT `book`.`_id` AS `_id`, `book`.`name` AS `name`, `book`.`id_user` as `id_user`
	, `book`.`lang` AS `lang`, `book`.`lang_a` AS `lang_a`, `book`.`level` AS
	`level`, `book`.`descr` AS `descr`, `book`.`author` AS
	`author`, `book`.`import_id` AS `import_id`, `book`.`email` AS `email`, `book`.`create` AS `create`,
	`book`.`downloads` AS `downloads`, `lang_answer`.`name_sk` AS `lang_answer`,
	`lang_question`.`name_sk` AS `lang_question`, count(`words`.`token`) AS `count`,
	`l`.`name` AS `level_name`, `book`.`shared` AS `shared`, `u`.`login`
FROM `import_book` `book` 
JOIN `lang` `lang_question` ON `book`.`lang` = `lang_question`.`id_lang`
JOIN `lang` `lang_answer` ON `book`.`lang_a` = `lang_answer`.`id_lang`
JOIN `level` `l` ON `book`.`level` = `l`.`id_level`
LEFT JOIN `import_word` `words` ON `book`.`import_id` = `words`.`token`
LEFT JOIN `user` `u` ON `u`.`id_user`=`book`.`id_user` 
LEFT OUTER JOIN `user_has_favorite`  f on f.`id_book`=`book`.`_id` and f.`id_user`=1
GROUP BY `book`.`_id` 
ORDER BY `book`.`_id` DESC 


