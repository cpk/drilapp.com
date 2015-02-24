
INSERT INTO dril_book
(id, name, description, question_lang_id, answer_lang_id, level_id, downloaded, user_id, created, is_shared )
SELECT 
_id, name, descr, lang, lang_a, level, downloads, id_user, `create`, shared 
FROM import_book 
LIMIT 102, 1000