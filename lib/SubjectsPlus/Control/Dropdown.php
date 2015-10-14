<?php
   namespace SubjectsPlus\Control;
/**
 *   @file
 *   @brief
 *
 *   @author adarby
 *   @date Nov 2010
 *   @todo remove inline CSS
 */
class Dropdown {

    private $_dd_name;
    private $_optionsArray;
    private $_selected;
    private $_truncation;
    private $introselect;
    private $show_keys;

    public function __construct($dd_name, $optionsArray, $selected="", $truncation="", $introselect="", $show_keys = FALSE, $dropdown_type = 'single') {
        $this->_dd_name = $dd_name;
        $this->_optionsArray = $optionsArray;
        $this->_selected = $selected;
        $this->_truncation = $truncation;
        $this->_introselect = $introselect;
        $this->_show_keys = $show_keys;
        $this->_dropdown_type = $dropdown_type;
    }

    public function display() {
        $array_type = "";

        if (is_array($this->_optionsArray[0])) {
            $array_type = "multi";
        }

        if ($this->_dropdown_type == "multi") {
            $dd_text = '<select multiple name= "' .  $this->_dd_name .'">';  // added benton
        } else {
            $dd_text = "<select name=\"" . $this->_dd_name . "\">";
        }
        
        

        // Add a first empty line if desired
        if ($this->_introselect != "") {
            $dd_text .= "<option value=\"\">$this->_introselect</option>";
        }

        $sorter = "";
        $current_type = "";

        if( !empty( $this->_optionsArray) )
        {
        	foreach ($this->_optionsArray as $key => $value) {

        		// deal with multi vs. primitive arrays
        		if ($array_type == "multi") {
        			$our_selected = $value[0];
        			$label = $value[1];


        		} elseif ($this->_show_keys == TRUE) {
        			// we want to show both key and value
        			$our_selected = $key;
        			$label = $value;
        		} else {
        			// show the value in both places
        			$our_selected = $value;
        			$label = $value;
        		}

        		//print "<p>Our selected = $our_selected || sorter = $sorter || label = $label</p>";
        		if ($this->_truncation != "") {
        			$label = Truncate($label, $this->_truncation, '');
        		}

        		if ($current_type != $sorter) {
        			$dd_text .= "<option value=\"\" class=\"dropdown_list\"> -- " . strtoupper($sorter) . " -- </option>";
        		}

                // check if our values are coming in a multidimensional array
                if (is_array($this->_selected)) {
                    if (in_array($our_selected, $this->_selected)) {
                        $dd_text .= "<option value=\"$our_selected\" selected=\"selected\">$label</option>";
                    } else {
                        $dd_text .= "<option value=\"$our_selected\">$label</option>";
                    }
                } else {
                    if ($our_selected === $this->_selected) {
                        $dd_text .= "<option value=\"$our_selected\" selected=\"selected\">$label</option>";
                    } else {
                        $dd_text .= "<option value=\"$our_selected\">$label</option>";
                    }                    
                }


        		$current_type = $sorter;
        	}
        }

        $dd_text .= "</select>";

        return $dd_text;
    }

}

?>