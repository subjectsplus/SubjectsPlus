select d.subject_id, d.subject
from
    pluslet a
        inner join
    pluslet_tab b
        on a.pluslet_id = b.pluslet_id
        inner join 
    tab c
        on b.tab_id = c.tab_id 
	    inner join
	subject d
		on c.subject_id = d.subject_id 

	WHERE a.body LIKE '%test%';