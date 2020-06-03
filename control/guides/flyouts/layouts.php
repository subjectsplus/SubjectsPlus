
<div id="layout_options_content" class="second-level-content"
     style="display: none;">
    <h3><?php print _("Choose Layout"); ?></h3>
    <p id="select_section_message"><?php print _("Please select a section first. Use the 4 arrows icon on the appropriate section."); ?> <i class="fa fa-arrows"></i> </p>
    <div id="layout_options_container">
        <ul class="layout_options">
            <li class="layout-icon" id="col-single"
                title="<?php print _("1 Column"); ?>"></li>
            <li class="layout-icon" id="col-double"
                title="<?php print _("2 Columns"); ?>"></li>
            <li class="layout-icon" id="col-48"
                title="<?php print _("Sidebar + Column"); ?>"></li>
            <li class="layout-icon" id="col-84"
                title="<?php print _("Column + Sidebar"); ?>"></li>
            <li class="layout-icon" id="col-triple"
                title="<?php print _("3 Columns"); ?>"></li>
            <li class="layout-icon" id="col-363"
                title="<?php print _("2 Sidebars"); ?>"></li>
        </ul>
    </div>


    <h3><?php print _("Add New Section"); ?></h3>
    <ul class="layout_options">
        <li class="top-panel-option-item"><a id="add_section" href="#"><img
                    src="<?php print $AssetPath; ?>images/icons/section2.svg"
                    title="<?php print _("New Section"); ?>" class="custom-icon" /></a></li>
    </ul>
</div>