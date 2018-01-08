<?php
/**
 * @file TextTokenizer
 *
 * @author acarrasco
 * @date December 2017
 */

namespace SubjectsPlus\Control;

class TextTokenizer
{
    private $_original_text;
    private $_tokenized_text;
    private $_show_description;
    private $_show_icon;
    private $_show_note;

    public function __construct($source_text)
    {
        $this->_original_text = $source_text;
        $this->_tokenized_text = "";
        $this->_show_description = "";
        $this->_show_icon = "";
        $this->_show_note = "";
    }

    public function getTokenizedText()
    {
        return $this->_tokenized_text;
    }

    public function tokenizeText()
    {
        global $proxyURL;
        global $FAQPath;
        global $UserPath;
        global $IconPath;

        $db = new Querier();

        $icons = "";
        $target = targetBlanker();
        $tokenized = "";

        $parts = preg_split('/<span[^>]*>{{|}}<\/span>/', $this->_original_text);
        if (count($parts) == 1) {
            $parts = preg_split('/{{|}}/', $this->_original_text);
        }

        if (count($parts) > 1) { // there are tokens in $body
            foreach ($parts as $part) {
                if ($this->isProperlyFormedToken($part)) { // $part is a properly formed token
                    $fields = preg_split('/},\s?{/', $part);
                    $prefix = substr($part, 0, 3);
                    switch ($prefix) {
                        case "faq":
                            $tokenized .= $this->processFAQ($db, $fields, $target, $FAQPath);
                            break;
                        case "fil":
                            $tokenized .= $this->processFIL($fields, $target, $UserPath, $IconPath);
                            break;
                        case "cat":
                            $tokenized .= $this->processCat($fields, $target);
                            break;
                        case "dab":
                            $tokenized .= $this->processDAB($fields, $db, $proxyURL, $target, $icons);
                            break;
                        case 'sss':
                            $tokenized .= $this->processSSS($fields);
                            break;
                        case 'toc':
                            $tokenized .= $this->processTOC($fields);
                            break;
                        default:
                            echo 'Error processing tokens';
                            break;
                    }
                } else {
                    $tokenized .= $part;
                }
            } // end foreach
            $this->_tokenized_text = $tokenized;
        } else {
            $this->_tokenized_text = $this->_original_text;
        }
    }

    private function isProperlyFormedToken($text)
    {
        return preg_match('/^dab},\s?{\d+},\s?{.+},\s?{[01]{3}$/', $text)
            || preg_match('/^dab},\s?{\d+},\s?{.+},\s?{[01]{2}$/', $text)
            || preg_match('/^faq},\s?{(\d+,)*\d+$/', $text)
            || preg_match('/^cat},\s?{.+},\s?{.*},\s?{\w+$/', $text)
            || preg_match('/^fil},\s?{.+},\s?{.+$/', $text)
            || preg_match('/^sss},\s?{[^}]*/', $text)
            || preg_match('/^toc},\s?{[^}]*/', $text);
    }

    private function processFAQ($db, $fields, $target, $FAQPath)
    {
        $tokenized = "";
        $query = "SELECT faq_id, question FROM `faq` WHERE faq_id IN(" . $fields[1] . ") ORDER BY question";
        $result = $db->query($query);
        $tokenized .= "<ul>";
        foreach ($result as $myrow) {
            $tokenized .= "<li><a href=\"$FAQPath" . "?faq_id=$myrow[0]\" $target>" . stripslashes(htmlspecialchars_decode($myrow[1])) . "</a></li>";
        }
        return $tokenized .= "</ul>";
    }

    private function processFIL($fields, $target, $UserPath, $IconPath)
    {
        $ext = explode(".", $fields[1]);
        $i = count($ext) - 1;
        $our_icon = showDocIcon($ext[$i]);
        $file = "$UserPath/$fields[1]";
        return "<a href=\"$file\" $target>$fields[2]</a> <img style=\"position:relative; top:.3em;\" src=\"$IconPath/$our_icon\" alt=\"$ext[$i]\" />";
    }

    private function processCat($fields, $target)
    {
        global $open_string;
        global $close_string;
        global $open_string_kw;
        global $close_string_kw;
        global $open_string_cn;
        global $close_string_cn;
        global $open_string_bib;

        $tokenized = "";
        $pretext = "";
        switch ($fields[3]) {
            case "subject":
                $cat_url = $open_string . $fields[1] . $close_string;
                $pretext = $fields[2] . " ";
                $linktext = $fields[1];
                break;
            case "keywords":
                $cat_url = $open_string_kw . $fields[1] . $close_string_kw;
                $linktext = $fields[2];
                break;
            case "call_num":
                $cat_url = $open_string_cn . $fields[1] . $close_string_cn;
                $linktext = $fields[2];
                break;
            case "bib":
                $cat_url = $open_string_bib . $fields[1];
                $linktext = $fields[2];
                break;
            default:
                echo 'Error processing Cat';
                break;
        }
        return $tokenized .= "$pretext<a href=\"$cat_url\" $target>$linktext</a>";
    }

    private function processSSS($fields)
    {
        global $tel_prefix;

        $tokenized = "";
        $querier = new Querier();
        $qs = "SELECT lname, fname, email, tel, title from staff WHERE email IN ('" . str_replace(',', "','", $fields[1]) . "') ORDER BY lname, fname";

        $staffArray = $querier->query($qs);

        foreach ($staffArray as $value) {
            // get username from email
            $truncated_email = explode("@", $value[2]);

            $staff_picture = $this->_relative_asset_path . "users/_" . $truncated_email[0] . "/headshot.jpg";

            // Output Picture and Contact Info
            $tokenized .= "
                    			<div class=\"clearboth\"><img src=\"$staff_picture\" alt=\"Picture: $value[1] $value[0]\"  class=\"staff_photo2\" align=\"left\" style=\"margin-bottom: 5px;\" />
                    			<p><a href=\"mailto:$value[2]\">$value[1] $value[0]</a><br />$value[4]<br />
                    			Tel: $tel_prefix $value[3]</p>\n</div>\n";
        }
        return $tokenized;
    }

    private function processTOC($fields)
    {
        $tokenized = "";
        $lobjTocPluslet = new Pluslet_TOC('', '', $this->_subject_id);
        $lobjTocPluslet->setTickedItems(explode(',', $fields[1]));
        $lobjTocPluslet->setHideTitleBar(1);
        return $tokenized .= $lobjTocPluslet->output();
    }

    private function setShowDescription($showDescription){
        $this->_show_description = $showDescription;
    }

    private function setShowIcon($showIcon){
        if ($showIcon == 1) {
            $this->_show_icon = "yes";
        }
    }

    private function setShowNote($showNote){
        $this->_show_note = $showNote;
    }

    private function processDAB($fields, $db, $proxyURL, $target, $icons)
    {
        $tokenized = "";
        $description = "";
        ///////////////////
        // Check for icons or descriptions in fields[3]
        // 00 = neither; 10 = icons no desc; 01 = desc no icons; 11 = both
        ///////////////////

        if (isset($fields["3"])) {
            // Transform the number into an array of values
            $options = str_split($fields["3"]);

            $this->setShowIcon($options[0]);
            $this->setShowDescription($options[1]);

            // This option was not in previous version so it needs to be checked
            if (isset($options[2])) {
                $this->setShowNote($options[2]);
            }
        }

        $query = "SELECT location, access_restrictions, format, ctags, helpguide, citation_guide, description, call_number, t.title, display_note, t.pre
                                    FROM location l, location_title lt, title t
                                    WHERE l.location_id = lt.location_id
                                    AND lt.title_id = t.title_id
                                    AND t.title_id = $fields[1]";

        $result = $db->query($query);

        foreach ($result as $myrow) {
            // eliminate final line breaks -- offset fixed 11/15/2011 agd
            $myrow[6] = preg_replace('/(<br \/>)+/', '', $myrow[6]);
            // See if it's a web format
            if ($myrow[2] == 1) {
                if ($myrow[1] == 1) {
                    $url = $myrow[0];
                    $rest_icons = "unrestricted";
                } else {
                    $url = $proxyURL . $myrow[0];
                    $rest_icons = "restricted";
                }

                $current_ctags = explode("|", $myrow[3]);

                // add our $rest_icons info to this array at the beginning
                array_unshift($current_ctags, $rest_icons);

                if ($this->_show_icon == "yes") {
                    $icons = showIcons($current_ctags);
                } else {
                    $icons = "";
                }

                if ($this->_show_description == 1) {
                    // if we know the subject_id, good; for public, must look up
                    $subject_id = '';
                    if (isset($_GET["subject_id"])) {
                        $subject_id = $_GET["subject_id"];
                    } elseif (isset($_GET["subject"])) {
                        $q1 = "SELECT subject_id FROM subject WHERE shortform = '" . $_GET["subject"] . "'";

                        $r1 = $db->query($q1);
                        $subject_id = $r1[0]["subject_id"];
                    } elseif (isset($_POST["this_subject_id"])) {
                        $subject_id = $_POST["this_subject_id"];
                    }

                    $override = findDescOverride($subject_id, $fields[1]);
                    // if they do want to display the description:
                    if ($override != "") {
                        // show the subject-specific "description_override" if it exists
                        $description = "<br />" . scrubData($override);
                    } else {
                        $description = "<br />" . scrubData($myrow[6]);
                    }
                }

                if (isset($this->_show_note) && $this->_show_note == 1) {
                    if ($myrow[9] != "") {
                        $note = "<br />" . _("Note: ") . $myrow[9];
                    }
                } else {
                    $note = "";
                }

                if (!isset($note)) {
                    $note = "";
                }

                if ($myrow['10']) {
                    $prefixed_label = $myrow['10'] . ' ' . $myrow['8'];
                } else {
                    $prefixed_label = $myrow['8'];
                }

                $tokenized .= "<a href=\"$url\" $target>$prefixed_label</a> $icons $description $note";
            } else {
                // It's print

                $current_ctags = explode("|", $myrow[3]);

                if ($this->_show_icon == "yes") {
                    $icons = showIcons($current_ctags);
                } else {
                    $icons = "";
                }

                // added Diane Z fall 2014

                if ($this->_show_description == 1) {
                    // if we know the subject_id, good; for public, must look up
                    $subject_id = '';
                    if (isset($_GET["subject_id"])) {
                        $subject_id = $_GET["subject_id"];
                    } elseif (isset($_GET["subject"])) {
                        $q1 = "SELECT subject_id FROM subject WHERE shortform = '" . $_GET["subject"] . "'";

                        $r1 = $db->query($q1);
                        $subject_id = $r1[0]["subject_id"];
                    }

                    $override = findDescOverride($subject_id, $fields[1]);
                    // if they do want to display the description:
                    if ($override != "") {
                        // show the subject-specific "description_override" if it exists
                        $description = "<br />" . $override;
                    } else {
                        $description = "<br />" . $myrow[6];
                    }
                }
                // end diane fall 2014

                if (isset($this->_show_note) && $this->_show_note == 1) {
                    if ($myrow[9] != "") {
                        $note = "<br />" . _("Note: ") . $myrow[9];
                    }

                } else {
                    $note = "";
                }

                // Simple Print (2), or Print with URL (3)
                if ($myrow[2] == 3) {
                    $tokenized .= "<em>$myrow[8]</em><br />" . _("") . "
                                        <a href=\"$myrow[0]\" $target>$myrow[7]</a>
                                        $icons $description";
                } else {

                    // check if it's a url
                    if (preg_match('/^(https?|www)/', $myrow[0])) {
                        $tokenized .= "<a href=\"$myrow[0]\" $target>$myrow[8]</a> $icons $description $note";
                    } else {
                        $tokenized .= "$myrow[8] <em>$myrow[0]</em> $icons $description $note";
                    }
                }
            }
        }

        return $tokenized;
    }

}