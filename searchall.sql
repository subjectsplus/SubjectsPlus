SELECT subject_id AS 'id', subject AS 'matching_text', description as 'additonal_text', 'Subject Guide' as 'content_type' FROM subject 
WHERE description LIKE '%%'
OR subject LIKE '%%'
OR keywords LIKE '%%'
UNION 
SELECT pluslet_id AS 'id', title AS 'matching_text', body as 'additonal_text', 'Pluslet' AS 'content_type' FROM pluslet 
WHERE title LIKE '%%'
OR body LIKE '%%'
UNION
SELECT faq_id AS 'id', question AS 'matching_text', answer as 'additonal_text','FAQ' as 'content_type' FROM faq 
WHERE question LIKE '%%'
OR answer LIKE '%%'
OR keywords LIKE '%%'
UNION
SELECT talkback_id AS 'id', question AS 'matching_text' , answer as 'additonal_text', 'Talkback' as 'content_type' FROM talkback 
WHERE question LIKE '%%'
OR answer LIKE '%%'
UNION
SELECT staff_id AS 'id', email AS 'matching_text' , fname as 'additional_text', 'Staff' as 'content_type' FROM staff 
WHERE fname LIKE '%%'
OR lname LIKE '%%'
OR email LIKE '%%'
OR tel LIKE '%%'
UNION
SELECT department_id AS 'id', name AS 'matching_text' , telephone as 'additional_text', 'Department' as 'content_type' FROM department 
WHERE name LIKE '%%'
OR telephone LIKE '%%'
UNION
SELECT video_id AS 'id', title AS 'matching_text' , description as 'additional_text', 'Video' as 'content_type' FROM video 
WHERE title LIKE '%%'
OR description LIKE '%%'
OR vtags LIKE '%%'