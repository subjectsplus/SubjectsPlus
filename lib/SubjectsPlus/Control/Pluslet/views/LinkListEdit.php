<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 3/18/16
 * Time: 3:41 PM
 */



?>


<!-- colorbox  -->
<div style='display:none'>
    <div id="linklist_edit_colorbox_<?php echo $this->_pluslet_id; ?>" >
        <div class="pure-g">
            <div class="pure-u-1-3">
                <h2>Pluslet id: <?php echo $this->_pluslet_id; ?></h2>
                <h3><?php print _("Record Search Box"); ?></h3>

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


            </div>
            <div class="pure-u-1-3">
                <h3>Your List</h3>
<?php echo $this->_body; ?>
                <!--display results selected-->
                <div class="db-list-content">

                    <h4>Records Selected:</h4>

                    <span class="db-list-input-label">Show all: </span> <input type="checkbox" name="show_all_icons_input" id="show_all_icons_input" class="pure-checkbox" /> <span class="db-list-input-label"> Icons </span>
                    <input type="checkbox" name="show_all_desc_input" id="show_all_desc_input" class="pure-checkbox" /> <span class="db-list-input-label">Descriptions</span>
                    <input type="checkbox" name="show_all_notes_input" id="show_all_notes_input" class="pure-checkbox" /> <span class="db-list-input-label">Notes</span>

                    <ul class="db-list-results">

                        <?php if($this->_body) {

                            echo $this->_body;
                        } ?>

                    </ul>
                </div>

                <!--buttons-->
                <div class="db-list-buttons">
                    <button class="pure-button pure-button-primary dblist-button"><?php print _("Create List Box"); ?></button>
                    <button class="pure-button pure-button-primary dblist-reset-button"><?php print _("Reset List Box"); ?></button>
                </div>

            </div>
            <div class="pure-u-1-3">
                <h3>Add New Record</h3>
                <form id="create-record-from" class="pure-form pure-form-stacked">
                    <fieldset>

                        <label for="prefix"><?php echo _('Prefix'); ?>
                            <input id="prefix" required />
                        </label>


                        </label>
                        <label for="record-title"><?php echo _('Record Title'); ?></label>
                        <input id="record-title" type="text" required />
                        </label>

                        <label for="location"><?php echo _('Location (Enter URL)'); ?></label>
                        <input id="location" type="text" required/>
                        </label>
                        <label for="description"><?php echo _('Description'); ?>
                            <textarea id="description" required></textarea>
                        </label>

                        <button id="advanced" class="pure-button" type="button"><?php echo _('Advanced Options'); ?></button>
                        <button id="add-record" class="pure-button pure-button-primary" type="button"><?php echo _('Create Record'); ?></button>
                    </fieldset>
                    <div class="notify"></div>
                </form>

            </div>
        </div>
    </div>
</div>


<script>



    var rL =  resourceList();
    rL.init();
    rL.bindUiActions();



    $('#add-record').on("click", function() {
        record.title =  $('#record-title').val();
        record.description = $('#description').val();
        record.pre =  $('#description').val();
        location.location = $('#location').val()
        record.locations.push(location);
        $.post("<?php echo getControlURL(); ?>/records/insert_record.php",JSON.stringify(record), function(data){
            res = JSON.parse(data);
            if (res.response !== "error") {
                $('.notify').html("<a href='" + res.response + "'>"+ record.title + "</a>")
            } else {
            }
        });
    });

</script>


