<?php
header("Content-Type: application/json");
use SubjectsPlus\Control\Querier;
include("../control/includes/config.php");
include("../control/includes/autoloader.php");

if($_GET["subject"] != null) {

	$db = new Querier;
	$subject_id = $db->quote($_GET["subject"]);

	$guides = $db->query("SELECT * FROM subject
			WHERE subject.subject_id = $subject_id");

	$tabs = $db->query("SELECT * FROM subject
			INNER JOIN tab on tab.subject_id = subject.subject_id
			WHERE subject.subject_id = $subject_id");

			$sections = $db->query("SELECT * FROM subject
	INNER JOIN tab on tab.subject_id = subject.subject_id
	INNER JOIN section on tab.tab_id = section.tab_id
	WHERE subject.subject_id = $subject_id");

	$pluslets = $db->query("SELECT * FROM subject
	INNER JOIN tab on tab.subject_id = subject.subject_id
	INNER JOIN section on tab.tab_id = section.tab_id
	INNER JOIN pluslet_section on section.section_id = pluslet_section.section_id
	INNER JOIN pluslet on pluslet_section.pluslet_id = pluslet.pluslet_id
	WHERE subject.subject_id = $subject_id");
	
	
	$tabs_array = array();
	foreach ($tabs as $tab) {
	$section_array = array();
	$tab_i = array();
	$tab_i["tab_id"] =  $tab["tab_id"];
	$tab_i["label"] =  $tab["label"];
	$tab_i["tab_index"] =  $tab["tab_index"];
	$tab_i["external_url"] =  $tab["external_url"];
	$tab_i["visibility"] =  $tab["visibility"];

	foreach ($sections as $section) {
	$pluslet_array = array();
		$section_i = array();
		$section_i["section_id"] = $section["section_id"];
		$section_i["section_index"] = $section["section_index"];
				$section_i["section_layout"] = $section["layout"];

				foreach ($pluslets as $pluslet) {
						if ($section["section_id"] == $pluslet["section_id"]) {
								$pluslet_i = array();
								$pluslet_i["pluslet_id"] = $pluslet["pluslet_id"];
								$pluslet_i["title"] = $pluslet["title"];
								$pluslet_i["clone"] = $pluslet["clone"];
								$pluslet_i["collapse_body"] = $pluslet["collapse_body"];
								$pluslet_i["titlebar_styling"] = $pluslet["titlebar_styling"];
								$pluslet_i["pcolumn"] = $pluslet["pcolumn"];
										$pluslet_i["prow"] = $pluslet["prow"];
										$pluslet_i["body"] = htmlspecialchars($pluslet["body"]);
												array_push($pluslet_array, $pluslet_i);
								}
								}
										$section_i["pluslets"] = $pluslet_array;

										if ($section["tab_id"] == $tab["tab_id"]) {
										array_push($section_array, $section_i);
								}
								}
								$tab_i["sections"] = $section_array;
										array_push($tabs_array, $tab_i);
								}

								$guide = array("id" => $guides[0]["subject_id"],
								"title" => $guides[0]["subject"],
								"active" => $guides[0]["active"],
								"shortform" =>  $guides[0]["shortform"],
								"redirect_url" => $guides[0]["redirect_url"],
								"description" => $guides[0]["description"],
										"keywords" => $guides[0]["keywords"],
										"last_modified" => $guides[0]["last_modified"],
										"header" => $guides[0]["header"],
										"description" => $guides[0]["description"],
										"type" => $guides[0]["type"],
										"extra" => $guides[0]["extra"],
												"tabs" => $tabs_array
								);

								echo json_encode($guide);
} else {
echo "Please provide a subject_id with your request";
}
