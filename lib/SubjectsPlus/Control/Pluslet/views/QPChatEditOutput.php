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
" />
