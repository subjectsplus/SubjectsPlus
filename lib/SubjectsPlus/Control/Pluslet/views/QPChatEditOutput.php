<form class="pure-form pure-form-stacked" id="qpEditForm">
<label for="qp_color"><?php echo _("Color"); ?></label>
<select name="QP-extra-color">
<?php
$possible_colors = array("blue" => "Blue",
        "black" => "Black",
        "gray" => "Gray",
        "green" => "Green",
        "mint" => "Mint",
        "popsicle" => "Popsicle",
        "red" => "Red");
if (($this->_extra != null) && (isset($this->_extra['color']))) {
        echo '<option selected value="' . $this->_extra['color'] . '">'. _($possible_colors[$this->_extra['color']]) . '</option>';
        unset($possible_colors[$this->_extra['color']]);
}
foreach ($possible_colors as $code => $english_string) {
        echo '<option value="' . $code . '">'. _($english_string) . '</option>';
}
?>
</select>
<label for="qp_lang"><?php echo _("Language"); ?></label>
<select name="QP-extra-language">
<?php
$possible_languages = array(1 => "English",
        24 => "Catalan",
        5 => "Chinese (simplified)",
        4 => "Chinese (traditional)",
        7 => "Dutch",
        2 => "French",
        10 => "German",
        25 => "Italian",
        12 => "Korean",
        13 => "Portuguese",
        18 => "Russian",
        8 => "Slovenian",
        3 => "Spanish",
        26 => "Welsh");
if (($this->_extra != null) && (isset($this->_extra['language']))) {
        echo '<option selected value="' . $this->_extra['language'] . '">'. _($possible_languages[$this->_extra['language']]) . '</option>';
        unset($possible_languages[$this->_extra['language']]);
}
foreach ($possible_languages as $code => $language_name) {
        echo '<option value="' . $code . '">'. _($language_name) . '</option>';
}
?>
</select>
</form>
