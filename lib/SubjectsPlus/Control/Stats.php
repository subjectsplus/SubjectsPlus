<?php
/**
 *   @file Stats
 *   @brief
 *
 *   @author Jamie Little (little9)
 *   @date Aug 2016
 *   
 *  Limiter -- //WHERE FROM_UNIXTIME(stats.date) BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()";
 */

namespace SubjectsPlus\Control;

class Stats
{
    private $db;

    public function __construct(Querier $db) {
	$this->db = $db; 
    }

    public function getTotalViews() {
	$query = "SELECT count(*) as 'total_views_last_month' from stats";
	return $this->db->query($query);
    }

    public function getTotalViewsPerGuide() {
	$query = "SELECT page_title, count(*) as num, subject_short_form from stats WHERE event_type = 'view' and 
FROM_UNIXTIME(stats.date) BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()
 group by page_title order by count(*) desc";
	return $this->db->query($query);
    }
    public function getTopExternalLinks() {
	$query = "SELECT count(*) as num, link_url, subject_short_form, page_title from stats  
	WHERE event_type = 'link' AND FROM_UNIXTIME(stats.date) BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()
AND link_url NOT LIKE '#%' AND subject_short_form != '' group by link_url, subject_short_form order by subject_short_form ASC";
	return $this->db->query($query);
    }
    public function  getTopTabsPerGuide() {
	$query = "SELECT tab_name, trim(subject_short_form) as subject_short_form, count(*) as num from stats  
	WHERE event_type = 'tab_click' AND FROM_UNIXTIME(stats.date) BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()
and subject_short_form != '' GROUP BY tab_name, subject_short_form ORDER BY trim(subject_short_form) ASC";
	return $this->db->query($query);
    }

    public function getStaffShortForms($staffId) {
	(int) $staffId = $staffId;
	$query = "SELECT staff_subject.subject_id as subject_id, s.subject as subject, s.shortform as shortform FROM staff_subject INNER JOIN subject as s on s.subject_id = staff_subject.subject_id WHERE staff_subject.staff_id = $staffId AND s.active = 1";
	return $this->db->query($query);
    }

    public function getShortFormCount($shortForm) {
	$shortForm = $this->db->quote($shortForm); 
	$query = "SELECT count(*) as view_count FROM stats WHERE subject_short_form = $shortForm AND FROM_UNIXTIME(stats.date) BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()";
	return $this->db->query($query);
    }
    
    
}
