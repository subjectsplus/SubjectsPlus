<?php
   namespace SubjectsPlus\Control;
/**
 *   @file
 *   @brief creates the staff listing
 *
 *   @author adarby, d lowder
 *   @date
 *   @todo
 */
    
use PDO;
    
    
class StaffDisplay {

  function writeTable($qualifier, $get_assoc_subs = 1, $print_display = 0) {

    global $tel_prefix;
    global $mod_rewrite;

    // sanitize submission
    $selected = scrubData($qualifier);

    switch ($qualifier) {
        case "Departments":
        	$items = "";
			$search_term = isset($_GET["search_term"]) ? scrubData($_GET["search_term"]) : "";

            $q = "SELECT DISTINCT d.department_sort,
                s.staff_sort,
                name,
                lname,
                fname,
                title,
                s.tel,
                s.email,
                d.department_id,
                d.telephone,
                d.email,
                d.url,
                s.staff_id,
                s.ptags
FROM staff s,
     staff_department sd,
     department d
WHERE s.staff_id = sd.staff_id
  AND sd.department_id = d.department_id
  AND s.user_type_id = '1'
  AND s.active = 1
  AND (lname LIKE :search_term OR fname LIKE :search_term OR title LIKE :search_term OR name LIKE :search_term OR
       CONCAT(fname, ' ', lname) LIKE :search_term OR (SELECT GROUP_CONCAT(subject)
                                                       FROM subject,
                                                            staff_subject
                                                       WHERE subject.subject_id = staff_subject.subject_id
                                                         AND staff_subject.staff_id = s.staff_id
                                                         AND subject.active = 1
                                                         AND type = 'Subject') like :search_term)
ORDER BY department_sort, d.name, staff_sort DESC, lname";

            $db = new Querier;

			$query_params = [':search_term'=>'%'.$search_term.'%'];

            $r = $db->queryWithPreparedStatement($q, NULL, $query_params);

			if (!empty($search_term)){
				$items .= "<div class=\"feature-light p-3 mb-3\"><p class=\"mb-0\">Search results for <strong><em>$search_term</em></strong></p></div>";
			}

            $items .= "<ul class=\"list-unstyled d-md-flex flex-md-row flex-md-wrap staff-departments\">";
            $current_dept = "";

            foreach ($r as $myrow) {

                $dept_name = $myrow["2"];
                $lname = $myrow["3"];
                $fname = $myrow["4"];
                $title = $myrow["5"];
                $tel = $myrow["6"];
                $email = $myrow["7"];
                $dept_id = $myrow["8"];
                $dept_tel = $myrow["9"];
                $dept_email = $myrow["10"];
                $dept_url = $myrow["11"];
                $name_id = explode("@", $email);

                $staff_id = $myrow["12"];
                $ptags = $myrow["13"];

                if ($get_assoc_subs == 1) {
                    // Grab our subjects, if any
                    $assoc_subjects = self::getAssocSubjects($staff_id, $ptags);
                } else {
                    $assoc_subjects = "";
                }
                // end subject listing

                if ($mod_rewrite == 1) {
                    //$link_to_details = "/subjects/profile/" . $name_id[0]; // um custom
                    $link_to_details = "staff/" . $name_id[0];
                } else {
                    $link_to_details = "staff_details.php?name=" . $name_id[0];
                }

                if ($current_dept != $dept_id) {
                    $site_url = "https://library.miami.edu";
                    $items .= "<a name=\"$dept_id\"></a><h2>$dept_name</h2><div class=\"dept-info d-sm-flex flex-sm-row flex-sm-nowrap\">";

                    if ($dept_tel != "") {
                        $items .= "<p><i class=\"fas fa fa-phone\"></i>$tel_prefix $dept_tel</p>";
                    }

                    if ($dept_email != ""){
                        $items .="<p><i class=\"fas fa fa-envelope\"></i><a href=\"mailto:$dept_email\" class=\"no-decoration default\">$dept_email</a></p>";
                    }

                    if ($dept_url != ""){
                        $items .= "<p><i class=\"fas fa fa-file-alt\"></i><a href=\"$site_url$dept_url\" class=\"no-decoration default\">Department Page</a></p>";
                    }

                    $items .="</div>";
                }

                $items .= "<li><div class=\"staff-pic\"><a href=\"$link_to_details\">";

                // Here we stick in their headshot; comment out if you don't want; maybe later this should be an admin parameter
                $items .= getHeadshot($email, 'medium', "", true);

                $items .= "</a></div><div class=\"staff-meta\"><h4>";

                if ($print_display != 1) {
                    $items .= "<a href=\"$link_to_details\">$lname, $fname</a>";
                } else {
                    $items .= "$lname, $fname";
                }

                $items .= "</h4>
                <p><em>$title</em></p>";
                
                if ($tel !== null && trim($tel) !== '') {
                  $items .= "<p>$tel_prefix $tel </p>";
                }
                
                $items .= "<p><a href=\"mailto:$email\">$email</a></p>
                <p class=\"staff-subjects\">$assoc_subjects</p></div></li>";

                $current_dept = $dept_id;
            }

            $items .= "</ul>";

            break;

        case "Subject Librarians":

            $q = "select distinct lname, fname, title, tel, email, staff.staff_id
                from staff, staff_subject ss, subject su
                where staff.staff_id = ss.staff_id
                AND ss.subject_id = su.subject_id
                AND staff.active = 1
                AND type = 'Subject'
                AND su.active = '1'
                AND user_type_id = '1'
                AND su.type != 'Placeholder'
                AND ptags like '%librarian%'
                order by lname, fname";
            $db = new Querier;
            $r = $db->query($q);

            $items = "<ul class=\"list-unstyled staff-librarians\">";

            foreach ($r as $myrow) {

                $items .= "<li class=\"d-lg-flex flex-lg-row flex-lg-nowrap\"><div class=\"staff-info d-flex flex-row flex-nowrap\">";

                $items .= showStaff($myrow[4], '', '', 1);

                $items .= "</div><div class=\"staff-subjects\"><p><strong>Subjects</strong></p>";

                $sub_query = "select subject, shortform from subject, staff_subject
                    WHERE subject.subject_id = staff_subject.subject_id
                    AND staff_id =  '$myrow[5]'
                    AND type = 'Subject'
                    AND active = '1'
                    AND type != 'Placeholder'
                    ORDER BY subject";

                /* Select all active records (this is based on a db connection made above) */

                $sub_result = $db->query($sub_query);

                $num_rows = (count($sub_result) - 1);

                // Loop through all items, sticking commas in between

                $subrowcount = 0;

                foreach ($sub_result as $subrow) {

                    if ($mod_rewrite == 1) {
                        $linky = $subrow[1];
                    } else {
                        $linky = "guide.php?subject=" . $subrow[1];
                    }

                    $items .= "<a href=\"$linky\">$subrow[0]</a>";
                    if ($subrowcount < $num_rows) {
                        $items .= ", ";
                    }
                    $subrowcount++;
                }

                $items .= "</div></li>";
            }

            $items .= "</ul>";
            break;

        case "Faculty Profiles":
        $q = "select lname, fname, title, tel, email, staff_id, ptags
            FROM staff
            WHERE active = 1
            AND ptags like '%librarian%'
            AND user_type_id = '1'
            order by lname, fname";

        $db = new Querier;

        $r = $db->query($q);

        $items = "<table width=\"100%\" class=\"footable foo1\">";

        $items .= "<thead><tr><th data-sort-ignore=\"true\">&nbsp;</th>
          <th data-sort-ignore=\"true\" data-hide=\"phone,mid\">&nbsp;</th>
          <th data-sort-ignore=\"true\">&nbsp;</th>
          <th data-sort-ignore=\"true\" data-hide=\"phone,\">&nbsp;</th></tr></thead>";

        $row_count = 0;
        $colour1 = "oddrow";
        $colour2 = "evenrow";
        $current_dept = "";

        foreach ($r as $myrow) {

          $row_colour = ($row_count % 2) ? $colour1 : $colour2;

          $lname = $myrow["0"];
          $fname = $myrow["1"];
          $title = $myrow["2"];
          $tel = $myrow["3"];
          $email = $myrow["4"];
          $name_id = explode("@", $email);
          $staff_id = $myrow["5"];
          $ptags = $myrow["6"];

          if ($get_assoc_subs == 1) {
            // Grab our subjects, if any
            $assoc_subjects = self::getAssocSubjects($staff_id, $ptags);
          } else {
            $assoc_subjects = "";
          }

          if ($mod_rewrite == 1) {
            //$link_to_details = "/subjects/profile/" . $name_id[0]; // um custom
            $link_to_details = "staff/" . $name_id[0];
          } else {
            $link_to_details = "staff_details.php?name=" . $name_id[0];
          }

          $items .= "<tr class=\"$row_colour zebra\">
              <td class=\"$row_colour staff-name-row\"><span class=\"staff_contact\">";
          if ($print_display != 1) {
            $items .= "<a href=\"$link_to_details\">$lname, $fname</a>";
          } else {
            $items .= "$lname, $fname";
          }

          $items .= "</span></td>
            <td class=\"$row_colour\">$title $assoc_subjects</td>";

          if ($tel !== null && trim($tel) !== '') {
            $items .= "<td class=\"$row_colour staff-tel-row\">$tel_prefix $tel </td>";
          }

          $items .= "<td class=\"$row_colour\"><a href=\"mailto:$email\">$email</a></td></tr>";

          $row_count++;
        }

        $items .= "</table>";


        break;
        case "By Department":
        $q = "SELECT DISTINCT d.department_sort, s.staff_sort, name, lname, fname, title, s.tel, s.email, d.department_id, d.telephone, s.staff_id, s.ptags
        FROM staff s, staff_department sd, department d
        WHERE s.staff_id = sd.staff_id
        AND sd.department_id = d.department_id
        AND user_type_id = '1'
        AND active =1
        ORDER BY department_sort, d.name, staff_sort DESC, lname";

        $db = new Querier;
        $r = $db->query($q);

        $items = "<table class=\"footable foo2 staff-by-dept\"><thead><tr><th data-sort-ignore=\"true\">&nbsp;</th><th data-sort-ignore=\"true\">&nbsp;</th><th data-sort-ignore=\"true\" data-hide=\"phone,mid\">&nbsp;</th><th data-sort-ignore=\"true\" data-hide=\"phone\">&nbsp;</th><th data-hide=\"phone,mid\" data-sort-ignore=\"true\">&nbsp;</th></tr></thead>";

        $row_count = 0;
        $colour1 = "oddrow";
        $colour2 = "evenrow";
        $current_dept = "";

           foreach ($r as $myrow) {

          $row_colour = ($row_count % 2) ? $colour1 : $colour2;

          $dept_name = $myrow["2"];
          $lname = $myrow["3"];
          $fname = $myrow["4"];
          $title = $myrow["5"];
          $tel = $myrow["6"];
          $email = $myrow["7"];
          $dept_id = $myrow["8"];
          $dept_tel = $myrow["9"];
          $name_id = explode("@", $email);

          $staff_id = $myrow["10"];
          $ptags = $myrow["11"];

          if ($get_assoc_subs == 1) {
            // Grab our subjects, if any
            $assoc_subjects = self::getAssocSubjects($staff_id, $ptags);
          } else {
            $assoc_subjects = "";
          }

          // end subject listing

          if ($mod_rewrite == 1) {
            //$link_to_details = "/subjects/profile/" . $name_id[0]; // um custom
            $link_to_details = "staff/" . $name_id[0];
          } else {
            $link_to_details = "staff_details.php?name=" . $name_id[0];
          }

          if ($current_dept != $dept_id) {
            $items .= "<tr><td class=\"dept_label\" colspan=\"5\"><a name=\"$dept_id\"></a><h2 class=\"dept_header\">$dept_name&nbsp; &nbsp;";
            if ($dept_tel !== null && trim($dept_tel) !== '') {
              $items .=  $tel_prefix . " " . $dept_tel;
            } 
            $items .= "</h2></td></tr>";
          }

          $items .= "<tr class=\"$row_colour\">
          <td class=\"$row_colour staffpic\">";

          // Here we stick in their headshot; comment out if you don't want; maybe later this should be an admin parameter
          $items .= getHeadshot($email, 'medium');

          $items .= "</td>
              <td class=\"$row_colour staff-name-row\"><span class=\"staff_contact\">";

          if ($print_display != 1) {
            $items .= "<a href=\"$link_to_details\">$lname, $fname</a>";
          } else {
            $items .= "$lname, $fname";
          }

          $items .= "</span></td>
                <td class=\"$row_colour\">$title $assoc_subjects</td>";
          
          if ($tel !== null && trim($tel) !== '') {
            $items .= "<td class=\"$row_colour staff-tel-row\">$tel_prefix $tel </td>";
          } else {
            $items .= "<td class=\"$row_colour staff-tel-row\"></td>";
          }
                
          $items .= "<td class=\"$row_colour\"><a href=\"mailto:$email\">$email</a></td></tr>";

          $row_count++;
          $current_dept = $dept_id;
        }


        $items .= "</table>";

        break;
        case "Subject Librarians A-Z":

        $q = "select distinct lname, fname, title, tel, email, staff.staff_id
                from staff, staff_subject ss, subject su
                where staff.staff_id = ss.staff_id
                AND ss.subject_id = su.subject_id
                AND staff.active = 1
                AND type = 'Subject'
                AND su.active = '1'
                AND user_type_id = '1'
                AND su.type != 'Placeholder'
                order by lname, fname";
        $db = new Querier;
        $r = $db->query($q);

        $items = "<table class=\"footable foo3 table table-borderless table-responsive librarians-az\" width=\"100%\">
        <thead><tr class=\"staff-heading\"><th><strong>" . _("Librarian") . "</strong></th><th data-hide=\"phone,mid\" data-sort-ignore=\"true\"><strong>" . _("Subject Responsibilities") . "</strong></th></tr></thead>";

        $row_count = 0;
        $colour1 = "oddrow";
        $colour2 = "evenrow";

          foreach ($r as $myrow) {
          $row_colour = ($row_count % 2) ? $colour1 : $colour2;

          $items .= "<tr class=\"$row_colour\">\n";
          $items .= "<td class=\"librarian-info\">";
          $items .= showStaff($myrow[4], '', '', 1);
          $items .= "</td>";

          $items .= "<td class=\"librarian-subjects\">";

          $sub_query = "select subject, shortform from subject, staff_subject
                    WHERE subject.subject_id = staff_subject.subject_id
                    AND staff_id =  '$myrow[5]'
                    AND type = 'Subject'
                    AND active = '1'
                    AND type != 'Placeholder'
                    ORDER BY subject";

          /* Select all active records (this is based on a db connection made above) */

          $sub_result = $db->query($sub_query);

          $num_rows = (count($sub_result) - 1);

          // Loop through all items, sticking commas in between

          $subrowcount = 0;

          foreach ($sub_result as $subrow) {

            if ($mod_rewrite == 1) {
              $linky = $subrow[1];
            } else {
              $linky = "guide.php?subject=" . $subrow[1];
            }

            $items .= "<a href=\"$linky\">$subrow[0]</a>";
            if ($subrowcount < $num_rows) {
              $items .= ", ";
            }
            $subrowcount++;
          }

          $items .= "</td>\n
                    </tr>";

          $row_count++;
        }

        $items .= "</table>";
        break;
        case "Librarians by Subject Specialty":
        $q = "select lname, fname, title, tel, email, subject, staff.staff_id, shortform from
                    staff, staff_subject, subject
            where staff.staff_id = staff_subject.staff_id
            AND staff_subject.subject_id = subject.subject_id
            AND type = 'Subject'
        AND staff.active = 1
        AND subject.active = 1
        AND staff.user_type_id = 1
        AND staff.ptags like '%librarian%'
        AND type != 'Placeholder'
            order by subject, lname, fname";

        $hf1 = array("label"=>"Subject","hide"=>false,"nosort"=>false);
        $hf2 = array("label"=>"Library Liaison","hide"=>false,"nosort"=>false);
        $hf3 = array("label"=>"Phone","hide"=>true,"nosort"=>true);
        $hf4 = array("label"=>"Email","hide"=>true,"nosort"=>true);

        $head_fields = array($hf1, $hf2, $hf3, $hf4);
        $db = new Querier;
        $r = $db->query($q);

        $items = prepareTHUM($head_fields);

        $row_count = 0;
        $colour1 = "oddrow";
        $colour2 = "evenrow";
        $subrowsubject = "";

        foreach ($r as $myrow) {

          $row_colour = ($row_count % 2) ? $colour1 : $colour2;

          $full_name = $myrow["lname"] . ", " . $myrow["fname"];
          $title = $myrow["title"];
          $tel = $tel_prefix . " " . $myrow["tel"];
          $email = $myrow["email"];
          $name_id = explode("@", $email);

          if ($subrowsubject == $myrow["subject"]) {
            //$psubject = " ";
            $psubject = $myrow["subject"];

          } else {
            $subrowsubject = $myrow["subject"];
            $psubject = $myrow["subject"];
            $shortsub = $myrow["shortform"];
          }



          $items .= "<tr class=\"zebra $row_colour\">
                    <td>";

          if ($mod_rewrite == 1) {
            $linky = $shortsub;
          } else {
            $linky = "guide.php?subject=" . $shortsub;
          }
          $items .= "<a href=\"$linky\">$psubject</a>";
          $items .= "</td>";
          $items .= "<td class=\"staff-name-row\">";

          if ($mod_rewrite == 1) {
            //$linky = "/subjects/profile/" . $name_id[0]; // um custom
            $linky = "staff/" . $name_id[0];
          } else {
            $linky = "staff_details.php?name=" . $name_id[0];
          }

          $items .= "<a href=\"$linky\">$full_name</a></td>";

          $items .= "<td class=\"staff-tel-row\">";
          if ($myrow["tel"] !== null && trim($myrow["tel"]) !== '') {
            $items .= $tel;
          }
          $items .= "</td>";

          $items .= "<td>";
          $items .= "<a href=\"mailto:$email\">$email</a>";
          $items .= "</td>
                    </tr>";

          $row_count++;
        }

        $items .= "</table>";
        break;
        case "Librarians by Specialty":
            $q = "select lname, fname, title, tel, email, subject, staff.staff_id, shortform from
                    staff, staff_subject, subject
            where staff.staff_id = staff_subject.staff_id
            AND staff_subject.subject_id = subject.subject_id
            AND type = 'Liaison'
        AND staff.active = 1
        AND subject.active = 0
        AND staff.user_type_id = 1
        AND staff.ptags like '%librarian%'
        AND type != 'Placeholder'
            order by subject, lname, fname";

            $hf1 = array("label"=>"Specialty","hide"=>false,"nosort"=>false);
            $hf2 = array("label"=>"Library Liaison","hide"=>false,"nosort"=>false);
            $hf3 = array("label"=>"Phone","hide"=>true,"nosort"=>true);
            $hf4 = array("label"=>"Email","hide"=>true,"nosort"=>true);

            $head_fields = array($hf1, $hf2, $hf3, $hf4);
            $db = new Querier;
            $r = $db->query($q);

            $items = prepareTHUM($head_fields);

            $row_count = 0;
            $colour1 = "oddrow";
            $colour2 = "evenrow";
            $subrowsubject = "";

            foreach ($r as $myrow) {

                $row_colour = ($row_count % 2) ? $colour1 : $colour2;

                $full_name = $myrow["lname"] . ", " . $myrow["fname"];
                $title = $myrow["title"];
                $tel = $tel_prefix . " " . $myrow["tel"];
                $email = $myrow["email"];
                $name_id = explode("@", $email);

                if ($subrowsubject == $myrow["subject"]) {
                    //$psubject = " ";
                    $psubject = $myrow["subject"];

                } else {
                    $subrowsubject = $myrow["subject"];
                    $psubject = $myrow["subject"];
                    $shortsub = $myrow["shortform"];
                }



                $items .= "<tr class=\"zebra $row_colour\">
                    <td>";

                if ($mod_rewrite == 1) {
                    $linky = $shortsub;
                } else {
                    $linky = "guide.php?subject=" . $shortsub;
                }
                //$items .= "<a href=\"$linky\">$psubject</a>";
                $items .= "$psubject";
                $items .= "</td>";
                $items .= "<td class=\"staff-name-row\">";

                if ($mod_rewrite == 1) {
                    //$linky = "/subjects/profile/" . $name_id[0]; // um custom
                    $linky = "staff/" . $name_id[0];
                } else {
                    $linky = "staff_details.php?name=" . $name_id[0];
                }

                $items .= "<a href=\"$linky\">$full_name</a></td>";

                $items .= "<td class=\"staff-tel-row\">";
                if ($myrow["tel"] !== null && trim($myrow["tel"]) !== '') {
                    $items .= $tel;
                }
                $items .= "</td>";

                $items .= "<td>";
                $items .= "<a href=\"mailto:$email\">$email</a>";
                $items .= "</td>
                    </tr>";

                $row_count++;
            }

            $items .= "</table>";
            break;
        case "Liaison Librarians":

            $q = "select distinct lname, fname, title, tel, email, staff.staff_id
                from staff, staff_subject ss, subject su
                where staff.staff_id = ss.staff_id
                AND ss.subject_id = su.subject_id
                AND staff.active = 1
                AND type = 'Liaison'
                AND su.active = 0
                AND user_type_id = '1'
                AND su.type != 'Placeholder'
                AND ptags like '%librarian%'
                order by lname, fname";
            $db = new Querier;
            $r = $db->query($q);

            $items = "<ul class=\"list-unstyled staff-librarians\">";

            foreach ($r as $myrow) {

                $items .= "<li class=\"d-lg-flex flex-lg-row flex-lg-nowrap\"><div class=\"staff-info d-flex flex-row flex-nowrap\">";

                $items .= showStaff($myrow[4], '', '', 1);

                    $items .= "</div><div class=\"staff-subjects\"><p><strong>Liaison Areas</strong></p>";

                $sub_query = "select subject, shortform from subject, staff_subject
                    WHERE subject.subject_id = staff_subject.subject_id
                    AND staff_id =  '$myrow[5]'
                    AND type = 'Liaison'
                    AND active = 0
                    AND type != 'Placeholder'
                    ORDER BY subject";

                /* Select all active records (this is based on a db connection made above) */

                $sub_result = $db->query($sub_query);

                $num_rows = (count($sub_result) - 1);

                // Loop through all items, sticking commas in between

                $subrowcount = 0;

                foreach ($sub_result as $subrow) {

                    if ($mod_rewrite == 1) {
                        $linky = $subrow[1];
                    } else {
                        $linky = "guide.php?subject=" . $subrow[1];
                    }

                    //$items .= "<a href=\"$linky\">$subrow[0]</a>";
                    $items .= "$subrow[0]";
                    if ($subrowcount < $num_rows) {
                        $items .= ", ";
                    }
                    $subrowcount++;
                }

                $items .= "</div></li>";
            }

            $items .= "</ul>";
            break;
        case "A-Z":
        default:

        $q = "SELECT s.staff_id, lname, fname, title, tel, s.email, name, ptags
            FROM staff s
            LEFT JOIN department d on s.department_id = d.department_id
            WHERE user_type_id = '1'
        AND active = 1
            ORDER BY s.lname, s.fname";

        $hf1 = array("label"=>"Name","hide"=>false,"nosort"=>false);
        $hf2 = array("label"=>"Title","hide"=>true,"nosort"=>false);
        $hf3 = array("label"=>"Phone","hide"=>false,"nosort"=>true);
        $hf4 = array("label"=>"Email","hide"=>true,"nosort"=>true);

        $head_fields = array($hf1, $hf2, $hf3, $hf4);



        $db = new Querier;
            $r = $db->query($q,PDO::FETCH_ASSOC);

        $items = prepareTHUM($head_fields);



        $row_count = 0;
        $colour1 = "oddrow";
        $colour2 = "evenrow";

        foreach ($r as $myrow) {

          $row_colour = ($row_count % 2) ? $colour1 : $colour2;

          $staff_id = $myrow["staff_id"];
          $full_name = $myrow["lname"] . ", " . $myrow["fname"];
          $title = $myrow["title"];
          $tel = $tel_prefix . " " . $myrow["tel"];
          $email = $myrow["email"];
          $name_id = explode("@", $email);
          $department = $myrow["name"];
          $ptags = $myrow["ptags"];

          if ($get_assoc_subs == 1) {
            // Grab our subjects, if any
            $assoc_subjects = self::getAssocSubjects($staff_id, $ptags);
          } else {
            $assoc_subjects = "";
          }

          if ($mod_rewrite == 1) {
            //$link_to_details = "/subjects/profile/" . $name_id[0]; // um custom
            $link_to_details = "staff/" . $name_id[0];
          } else {
            $link_to_details = "staff_details.php?name=" . $name_id[0];
          }

          //$headshot = getHeadshot($email, "medium");

          $items .= "
        <tr class=\"zebra $row_colour\">
            <td class=\"staff-name-row\">";

          if ($print_display != 1) {
            $items .= "<a href=\"$link_to_details\" class=\"no_link\">$full_name</a>";
          } else {
            $items .= "$full_name";
          }

          $items .= "</td>
            <td class=\"staff-title-row\">$title $assoc_subjects</td>";
          
          $items .= "<td class=\"staff-tel-row\">";
          if ($myrow["tel"] !== null && trim($myrow["tel"]) !== '') {
            $items .= "$tel &nbsp;";
          }
          $items .= "</td>";
            
          $items .= "<td class=\"staff-email-row\"><a href=\"mailto:$email\">$email</a></td></tr>";

          $row_count++;
        }

        $items .= "</table>";
        break;


    }

    return $items;
  }

  function searchFor($qualifier) {
    
  }

  function getAssocSubjects($staff_id, $ptags) {
    global $mod_rewrite;
    $assoc_subjects = "";

    // See if they're a librarian, and then check for subjects

    $islib = preg_match('/librarian/', $ptags);

    if ($islib == 1) {
      // UM hack in query
      $q2 = "SELECT subject, shortform 
              FROM subject, staff_subject 
              WHERE subject.subject_id = staff_subject.subject_id
              AND staff_subject.staff_id = $staff_id
              AND subject.active = 1
              AND type = 'Subject'
              ORDER BY subject";
      //print $q2;
        $db = new Querier;
      $r2 = $db->query($q2);

      foreach ($r2 as $myrow2) {

        if ($mod_rewrite == 1) {
          $link_to_guide = $myrow2[1];
        } else {
          $link_to_guide = "guide.php?subject=" . $myrow2[1];
        }

        $assoc_subjects .= "<a href=\"$link_to_guide\">$myrow2[0]</a>, ";
      }
    }

    if ($assoc_subjects != "") {
      $assoc_subjects = rtrim($assoc_subjects, ", ");
      $assoc_subjects = "<br /><span class=\"smaller\">$assoc_subjects</span>";
    } else {
      $assoc_subjects = "";
    }
    return $assoc_subjects;
  }

}

?>