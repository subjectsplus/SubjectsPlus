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
                

                
                <?php if($this->_extra) {  ?>
                <?php
                    $data = json_decode($this->_extra, true);
                ?>

                <?php } ?>


                <div class="db-list-content" style="display: block;">

                    <h4>Records Selected:</h4>

                    <span class="db-list-input-label">Show all: </span> <input type="checkbox" name="show_all_icons_input" id="show_all_icons_input" class="pure-checkbox"> <span class="db-list-input-label"> Icons </span>
                    <input type="checkbox" name="show_all_desc_input" id="show_all_desc_input" class="pure-checkbox"> <span class="db-list-input-label">Descriptions</span>
                    <input type="checkbox" name="show_all_notes_input" id="show_all_notes_input" class="pure-checkbox"> <span class="db-list-input-label">Notes</span>

                    <ul class="db-list-results ui-sortable">
                        <?php foreach($data as $datum): ?>
                            <?php foreach($datum as $token): ?>
                        <li class="db-list-item-draggable" value="<?php echo $token['record_id']; ?>">
                            <span class="db-list-label"><?php echo $token['title']; ?></span>
                            <div>
                                <span class="show-icons-toggle db-list-toggle"><i class="fa fa-minus"></i><i class="fa fa-check" style="display: none;"></i> Icons  </span>
                                <span class="show-description-toggle db-list-toggle"><i class="fa fa-minus"></i> <i class="fa fa-check" style="display: none;"></i> Description </span>
                                <span class="include-note-toggle db-list-toggle"><i class="fa fa-minus"></i><i class="fa fa-check" style="display: none;"></i> Note </span>
                            </div>
                        </li>
                        <?php endforeach; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>


                <!--display results selected-->
                <div class="db-list-content">

                    <h4>Records Selected:</h4>

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
            <div class="pure-u-1-3">
                <h3>Add New Record</h3>

                <form id="create-record-from" class="pure-form pure-form-stacked">
                    <fieldset>

                        <label for="record-title"><?php echo _('Record Title'); ?>
                            <input id="record-title" type="text" required/>
                        </label>


                        <label for="alternate-title"><?php echo _('Alternate Title'); ?>
                            <input id="alternate-title" type="text"/>
                        </label>
                        <label for="location"><?php echo _('Location (Enter URL)'); ?>
                            <input id="location" type="text" required/>
                        </label>

                        <label for="checkurl">
            <span id="checkurl" class="checkurl_img_wrapper"><i alt="Check URL" title="Check URL" border="0"
                                                                class="fa fa-globe fa-2x clickable"></i></span>
                        </label>

                        <label for="description"><?php echo _('Description'); ?>
                            <textarea id="description"></textarea>
                        </label>

                        <button id="add-record" class="pure-button pure-button-primary"
                                type="submit"><?php echo _('Create Record'); ?></button>
                    </fieldset>
                    <div class="notify"></div>
                </form>

                <script>
                    $('#create-record-from').on('submit', function (e) {
                        if (!this.checkValidity()) {
                            console.log(this.checkValidity());
                            e.preventDefault();
                        } else {
                            e.preventDefault();

                            record.title = $('#record-title').val();
                            record.description = $('#description').val();
                            record.pre = $('#prefix').val();
                            location.location = $('#location').val();
                            record.subjects.push({'subject_id': $('#guide-parent-wrap').data().subjectId});
                            record.locations.push(location);

                            $.post("<?php echo getControlURL(); ?>/records/insert_record.php", JSON.stringify(record), function (data) {
                                res = JSON.parse(data);
                                if (res.response !== "error") {
                                    $('.notify').html("<a target='_blank' href='" + res.response + "'>" + record.title + "</a>")
                                } else {
                                    $('.notify').html("<?php echo _('There was an error inserting the record'); ?>")
                                }
                            });
                        }
                    });


                    $('#checkurl').on('click', function () {
                        var location = $('#location').val();

                        $.post("<?php echo getControlURL(); ?>/records/record_bits.php", {
                            type: "check_url",
                            checkurl: location
                        }, function (data) {
                            $('#checkurl').html(data);
                        });

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


