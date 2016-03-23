<div id="dblist_options_content" class="second-level-content"
	style="display: none;">
	<h3><?php print _("Create Database List Box"); ?></h3>

	<!--display db results list-->
	<div class="dblist-display">

		<div class="databases-results-display">
			<input class="databases-search" type="text"
				placeholder="<?php print _("Enter database title..."); ?>" />
			<label for="limit-az"> <input id="limit-az" type="checkbox" checked />
				Limit to AZ List
			</label>
			<ul class="databases-searchresults"></ul>
		</div>
	</div>

	<!--display results selected-->
	<div class="db-list-content">

		<h4>Databases Selected:</h4>

		<span class="db-list-input-label">Show all: </span> <input type="checkbox" name="show_all_icons_input" id="show_all_icons_input" class="pure-checkbox" /> <span class="db-list-input-label"> Icons </span>
		 <input type="checkbox" name="show_all_desc_input" id="show_all_desc_input" class="pure-checkbox" /> <span class="db-list-input-label">Descriptions</span>
		 <input type="checkbox" name="show_all_notes_input" id="show_all_notes_input" class="pure-checkbox" /> <span class="db-list-input-label">Notes</span>
		<ul class="db-list-results">
		</ul>
	</div>

	<!--buttons-->
	<div class="db-list-buttons">
		<button class="pure-button pure-button-primary dblist-button"><?php print _("Create List Box"); ?></button>
		<button class="pure-button pure-button-primary dblist-reset-button"><?php print _("Reset List Box"); ?></button>
	</div>

</div>
