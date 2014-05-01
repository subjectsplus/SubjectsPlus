SELECT p.pluslet_id, p.title, ps.section_id, s.tab_id, t.subject_id, su.subject FROM pluslet AS p 
	INNER JOIN pluslet_section AS ps 
	ON ps.pluslet_id = p.pluslet_id
	INNER JOIN section AS s 
	ON ps.section_id = s.section_id
	INNER JOIN tab AS t
	ON s.tab_id = t.tab_id
	INNER JOIN subject AS su 
	ON su.subject_id = t.subject_id
WHERE p.body LIKE '%yo%' AND t.subject_id = 4