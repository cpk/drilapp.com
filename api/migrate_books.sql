

CREATE TABLE IF NOT EXISTS `dril_deleted_rows` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table` varchar(7) CHARACTER SET latin2 COLLATE latin2_czech_cs NOT NULL,
  `deleted_id` int(11) NOT NULL,
  `deleted_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DELIMITER $$
CREATE TRIGGER dril_book_trigger
BEFORE DELETE
   ON dril_book FOR EACH ROW
BEGIN
   INSERT INTO dril_deleted_rows (`table`,`deleted_id`,`user_id`) VALUES ( 'book',  OLD.id, OLD.user_id);
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER dril_lecture_trigger
BEFORE DELETE
   ON dril_book_has_lecture FOR EACH ROW
BEGIN
    (
        SELECT b.user_id INTO @uid 
        FROM dril_book_has_lecture l
        INNER JOIN dril_book b ON b.id = l.dril_book_id
        WHERE l.id = OLD.id
    ); 
   INSERT INTO dril_deleted_rows (`table`,`deleted_id`,`user_id`) VALUES ('lecture', OLD.id, @uid);
END$$
DELIMITER ;


DELIMITER $$
CREATE TRIGGER dril_word_trigger
BEFORE DELETE
   ON dril_lecture_has_word FOR EACH ROW
BEGIN
    (
        SELECT b.user_id INTO @uid
		FROM  dril_lecture_has_word w
		INNER JOIN dril_book_has_lecture l ON l.id = w.dril_lecture_id
		INNER JOIN dril_book b ON b.id = l.dril_book_id
		WHERE w.id = OLD.id
    ); 
   INSERT INTO dril_deleted_rows (`table`,`deleted_id`,`user_id`) VALUES ('word', OLD.id, @uid);
END$$
DELIMITER ;