<!--find boxes-->
<div id="findbox_options_content" class="second-level-content" style="display:none;">
    <h3><?php print _("Find Boxes"); ?></h3>
    <!--Find Box Tabs-->
    <div id="find-box-tabs">
        <ul class="find-box-tab-list">
            <li><a href="#browse-tab"><?php print _("Browse"); ?></a></li>
            <li><a href="#search-tab"><?php print _("Search"); ?></a></li>
        </ul>
        <div id="browse-tab" class="find-box-tab-list-content">
            <div class="guides-display">
                <select class="guide-list">
                    <option>Please select a guide</option>
                </select>
                <ul class="pluslet-list"></ul>
            </div>
        </div>
        <div id="search-tab" class="find-box-tab-list-content">
            <div class="searchbox-results-display">
                <input class="findbox-search" type="text" placeholder="<?php print _("Enter box title..."); ?>" />

                <div class="sp-custom-radio">
                  <input id="find_box_option1" type="radio" name="search_scope" value="my_pluslets" checked="checked">
                  <label for="find_box_option1"><span><span></span></span>My Boxes</label>
                </div>
                <div class="sp-custom-radio">
                  <input id="find_box_option2" type="radio" name="search_scope" value="current_guide">
                  <label for="find_box_option2"><span><span></span></span>This Guide</label>
                </div>
                <div class="sp-custom-radio">
                  <input id="find_box_option3" type="radio" name="search_scope" value="allguides">
                  <label for="find_box_option3"><span><span></span></span>All</label>
                </div>
                <ul id="findbox-searchresults" class="findbox-searchresults"></ul>
            </div>
        </div>
    </div>
</div>