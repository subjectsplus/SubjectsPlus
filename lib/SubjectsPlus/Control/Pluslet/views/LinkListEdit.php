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
        <h2>Pluslet id: <?php echo $this->_pluslet_id; ?></h2>
        <div class="pure-g">
            <div class="pure-u-1-3">

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
                <h3>Selected Records</h3>

                <span class="db-list-input-label">Show all: </span> <input type="checkbox" name="show_all_icons_input" id="show_all_icons_input" class="pure-checkbox" /> <span class="db-list-input-label"> Icons </span>
                <input type="checkbox" name="show_all_desc_input" id="show_all_desc_input" class="pure-checkbox" /> <span class="db-list-input-label">Descriptions</span>
                <input type="checkbox" name="show_all_notes_input" id="show_all_notes_input" class="pure-checkbox" /> <span class="db-list-input-label">Notes</span>

                <!--display results selected-->
                <div class="db-list-content">

                    <div id='LinkList-body'>

                        <ul class="db-list-results ui-sortable">

                        </ul>

                    </div>

                </div>


                <?php if( (isset($this->_pluslet_id)) && (!empty($this->_pluslet_id)) ) {

                    $data_linklist_tmp_pluslet_id = $this->_pluslet_id;
                } else {
                    $data_linklist_tmp_pluslet_id = "";

                } ?>


                <!--buttons-->
                <div class="db-list-buttons">
                    <button data-linklist-tmp-pluslet-id="<?php echo  $data_linklist_tmp_pluslet_id ?>" class="pure-button pure-button-primary dblist-button"><?php print _("Create List Box"); ?></button>
                    <button class="pure-button pure-button-primary dblist-reset-button"><?php print _("Reset List Box"); ?></button>
                </div>

            </div>
            <div class="pure-u-1-3">
                <h3>Add New Record</h3>

                <form id="create-record-form" class="pure-form pure-form-stacked">
                    <fieldset>

                        <label for="record-title"><?php echo _('Record Title'); ?>
                            <input id="record-title" type="text" value="" required/>
                        </label>


                        <label for="alternate-title"><?php echo _('Alternate Title'); ?>
                            <input id="alternate-title" value="" type="text"/>
                        </label>
                        <label for="location"><?php echo _('Location (Enter URL)'); ?>
                            <input id="location" type="text"  value="" required/>
                        </label>

                        <label for="checkurl">
            <span id="checkurl" class="checkurl_img_wrapper"><i alt="Check URL" title="Check URL" border="0"
                                                                class="fa fa-globe fa-2x clickable"></i></span>
                        </label>

                        <label for="description"><?php echo _('Description'); ?>
                            <textarea id="description" value=""></textarea>
                        </label>

                        <button id="add-record" class="pure-button pure-button-primary"
                                type="submit"><?php echo _('Create Record'); ?></button>
                    </fieldset>
                    <div class="notify"></div>
                </form>

                <script>
                    $('#create-record-form').on('submit', function (event) {
                        submitRecordForm(event);
                    });

                    $('#checkurl').on('click', function () {
                        checkUrl();
                    });

                    function submitRecordForm(event) {
                        // Override the form submit action. Doing this lets you use the html5 form validation
                        // techniques/controls
                        if (!document.getElementById('create-record-form').checkValidity()) {
                            event.preventDefault();
                        } else {
                            event.preventDefault();

                            // Insert the record object
                            createRecord.insertRecord({
                                "title_id": null,
                                "title": $('#record-title').val(),
                                "alternate_title": null,
                                "description": $('#description').val(),
                                "pre": null,
                                "last_modified_by": "",
                                "last_modified": "",
                                "subjects": [{'subject_id': $('#guide-parent-wrap').data().subjectId}],
                                "locations" : [$('#location').val()]
                            });

                            // Reset the form
                            document.getElementById('create-record-form').reset();
                            // Reset the CKEditor description content
                            CKEDITOR.instances.description.setData("");
                        }
                    }

                    function checkUrl() {
                        var location = $('#location').val();

                        $.post("<?php echo getControlURL(); ?>/records/record_bits.php", {
                            type: "check_url",
                            checkurl: location
                        }, function (data) {
                            $('#checkurl').html(data);
                        });
                    }

                    CKEDITOR.replace('description', {
                        toolbar : 'Basic'
                    });
                </script>

            </div>
        </div>
    </div>
</div>


<script>



    var rL =  resourceList();
    rL.init();
    rL.bindUiActions();





</script>


