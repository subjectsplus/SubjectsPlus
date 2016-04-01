<div id="LinkList-body">
    <script>
    $('#save_guide').hide();

    $('#report-broken-record-container').hide();

    $('#show-broken-record-form').on('click', function() {
        $('#report-broken-record-container').show();

    })




</script>
<style>
    #report-broken-record-container {
        margin-top:20px;
        margin-bottom:20px;
    }

</style>

<div class="pure-g">
    <div class="pure-u-1-3">

        <h3><?php print _("Record Search Box"); ?></h3>

        <!--display db results list-->
        <div class="dblist-display">

            <div class="databases-results-display">
                <input class="databases-search" type="text"
                       placeholder="<?php print _("Enter database title..."); ?>"/>
                <label for="limit-az"> <input id="limit-az" type="checkbox" checked/>
                    Limit to AZ List
                </label>
                <ul class="databases-searchresults"></ul>
            </div>
        </div>



    </div>
    <div class="pure-u-1-3">
        <h3>Selected Records</h3>

        <span class="db-list-input-label">Show all: </span> <input type="checkbox" name="show_all_icons_input"
                                                                   id="show_all_icons_input" class="pure-checkbox"/>
        <span class="db-list-input-label"> Icons </span>
        <input type="checkbox" name="show_all_desc_input" id="show_all_desc_input" class="pure-checkbox"/> <span
            class="db-list-input-label">Descriptions</span>
        <input type="checkbox" name="show_all_notes_input" id="show_all_notes_input" class="pure-checkbox"/> <span
            class="db-list-input-label">Notes</span>

        <!--display results selected-->
        <div class="db-list-content">

            <div class="link-list-draggable">

                <ul class="db-list-results ui-sortable" data-link-list-pluslet-id="<?php echo $this->_pluslet_id; ?>">

                </ul>


            </div>

        </div>


        <br>

        <textarea id="link-list-textarea" name="LinkList-extra-textarea" cols="34" rows="7"></textarea>
        <br>
        <div>
            <input type="radio" name="LinkList-extra-radio" value="top" > Above List<br>
            <input type="radio" name="LinkList-extra-radio" value="bottom" checked> Below List<br>
        </div>

        <!--buttons-->
        <div class="db-list-buttons">
            <button data-link-list-pluslet-id=""
                    class="pure-button pure-button-primary dblist-button"><?php print _("Create List Box"); ?></button>
            <button
                class="pure-button pure-button-primary dblist-reset-button"><?php print _("Reset List Box"); ?></button>
        </div>

        <script>
            $('.db-list-content').show();
            $('.db-list-buttons').show();
        </script>

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
                    <input id="location" type="text" value="" required/>
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
                        "locations": [$('#location').val()]
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
                toolbar: 'Basic'
            });

            CKEDITOR.replace('link-list-textarea', {
                toolbar: 'Basic'
            });
        </script>

        <a id="show-broken-record-form">Report Broken Record</a>
        <div id="report-broken-record-container">
            <form action="mailto:cgb37@miami.edu?Subject='Broken Record Link'" method="post" enctype="text/plain">
                <label for="broken-record-name">Record: </label><br>
                <input id="broken-record-name" name="broken-record-name" type="text" /><br>
                <label id="broken-record-msg">Comments: </label><br>
                <textarea id="broken-record-msg" name="broken-record-msg" rows="7" cols="34"></textarea><br>
                <input type="submit" value="Send" class="pure-button pure-button-primary" id="report-broken-record" />
                <input type="reset" value="Reset" class="pure-button pure-button-primary">
            </form>
        </div>
    </div>
</div>

<script>
    var rL = resourceList();
    rL.init();
    rL.bindUiActions();

    rL.editLinkList("<?php echo $this->getLinkListId();?>");
</script>

</div>