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

INSERT INTO `config` (
`id_config` ,
`key` ,
`val`
)
VALUES (
NULL , 'dril_auth', 'b379c5f65387e482844779a5626a01c7'
);

ALTER TABLE `book` ADD `sync` BOOLEAN NOT NULL DEFAULT FALSE AFTER `lang_answer` ,
ADD `enabled` BOOLEAN NOT NULL DEFAULT TRUE AFTER `sync` ;