<?php
$id = uniqid();

//declare gloabl email for broken record report
global $administrator_email;


//set title if exists
if( (isset($this->_title)) && (!empty($this->_title)) ) {
    $title = $this->_title;
} else {
    $title = "Link List";
}

?>

<style>
    .link-list-display {
        display: none;
    }
</style>


<div class='sp-modal link-list' id="LinkList-body" data-link-list-id="<? echo $id; ?>">
    <div class="pure-g">

        <div class="pure-u-1-3">
            <label for="link-list-title-input">Title
                <input type="text" id="link-list-title-input" value="<?php echo $title; ?>" />
            </label>

            <h3>Record Search Box</h3>

            <!--display db results list-->
            <div class="dblist-display">
                <div class="databases-results-display">
                    <input class="databases-search" type="text" placeholder="Enter database title...">
                    <label for="limit-az">
                        <input id="limit-az" type="checkbox" checked="">
                        Limit to AZ List
                    </label>
                    <ul class="databases-searchresults" id="databases-searchresults"></ul>
                </div>
            </div>

        </div>

        <div class="pure-u-1-3">
            <h3>Selected Records</h3>

            <span class="db-list-input-label">Show all: </span> <input type="checkbox" name="show_all_icons_input" id="show_all_icons_input" class="pure-checkbox">
            <span class="db-list-input-label"> Icons </span>
            <input type="checkbox" name="show_all_desc_input" id="show_all_desc_input" class="pure-checkbox"> <span class="db-list-input-label">Descriptions</span>
            <input type="checkbox" name="show_all_notes_input" id="show_all_notes_input" class="pure-checkbox"> <span class="db-list-input-label">Notes</span>


            <!--display results selected-->
            <div class="db-list-content" style="display: block;">
                <?php if( ( isset($this->_topText) ) && ( !empty($this->_topText) ) ) {
                    echo $this->_topText;
                } ?>
                <div class="link-list-draggable" id="record-sortable-list">

                </div>
                <?php if( ( isset($this->_bottomText) ) && ( !empty($this->_bottomText) ) ) {
                    echo $this->_bottomText;
                } ?>
            </div>

            <br>
            <button class="pure-button pure-button-primary" id="show-linklist-textarea-btn">Add Text</button>

            <div id="link-list-textarea-container">
                <label for="link-list-textarea">Link List Context:
                    <textarea id="link-list-textarea" name="LinkList-extra-textarea" cols="34" rows="7"></textarea>
                </label>
                <br>
                <div>
                    <label for="top-text-radio">
                        <input id="top-text-radio" type="radio" name="LinkList-extra-radio" value="top"> Above List
                    </label> <br>
                    <label for="bottom-text-radio">
                        <input id="bottom-text-radio" type="radio" name="LinkList-extra-radio" value="bottom" checked=""> Below List
                    </label><br>

                </div>    
            </div>

            <!--buttons-->
            <div class="db-list-buttons" style="display: block;">
                <button class="pure-button pure-button-primary" id="sort-list-alpha-btn">Sort List Alphabetically</button>
                <button class="pure-button pure-button-primary dblist-reset-button">Reset List Box</button>
            </div>
        </div>

        <span class="pure-u-1-3">
            <span class="close-trigger pure-button pure-button-primary">Close Modal</span>
            <span class="delete-trigger"><a class="pure-button pure-button-primary" id="delete-<?php echo $this->_pluslet_id; ?>">Delete List</a></span>
            <span class="dblist-button pure-button pure-button-primary">Save List</span>

            <h3>Add New Record</h3>
            <form id="create-record-form" class="pure-form pure-form-stacked">
                <fieldset>

                    <label for="record-title">
                        Record Title                    <input id="record-title" type="text" value="" required="">
                    </label>


                    <label for="alternate-title">
                        Alternate Title                    <input id="alternate-title" value="" type="text">
                    </label>
                    <label for="location">
                        Location (Enter URL)                    <input id="location" type="text" value="" required="">
                    </label>

                    <label for="checkurl">
                        <span id="checkurl" class="checkurl_img_wrapper"><i alt="Check URL" title="Check URL" border="0" class="fa fa-globe fa-2x clickable"></i></span>
                    </label>


                    <button class="pure-button pure-button-primary" id="show-record-description-btn">Add Description</button>
                    <div id="record-description-container">
                        <label for="description">
                            Description                    <textarea id="description"></textarea>
                        </label>
                    </div>
                    <br>

                    <button id="add-record" class="pure-button pure-button-primary" type="submit">Create Record</button>
                </fieldset>
                <div class="notify"></div>
            </form>


            <button class="pure-button pure-button-primary" id="show-broken-record-form-btn">Report Broken Record</button>

            <div id="report-broken-record-container" style="display: none;">
                <form action="mailto:<?php echo $administrator_email; ?>?Subject='Broken Record Link'" method="post" enctype="text/plain">
                    <label for="broken-record-name">Record: </label><br>
                    <input id="broken-record-name" name="broken-record-name" type="text"><br>
                    <label id="broken-record-msg">Comments: </label><br>
                    <textarea id="broken-record-msg" name="broken-record-msg" rows="7" cols="34"></textarea><br>
                    <input type="submit" value="Send" class="pure-button pure-button-primary" id="report-broken-record">
                    <input type="reset" value="Reset" class="pure-button pure-button-primary">
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    LinkList();
    
    
    var topText = $('.link-list-text-top').html();
    var bottomText = $('.link-list-text-bottom').html();
    
    if(topText != "") {
        CKEDITOR.instances['link-list-textarea'].setData( $('.link-list-text-top').html() );
        $('input:radio[name="LinkList-extra-radio"][value="top"]').prop('checked', true);
    }

    if(bottomText != "") {
        CKEDITOR.instances['link-list-textarea'].setData( $('.link-list-text-bottom').html() );
        $('input:radio[name="LinkList-extra-radio"][value="bottom"]').prop('checked', true);
    }



    $('#link-list-title-input').bind('keypress keyup blur', function() {

        $("input[name='new_pluslet_title']").val($(this).val());

        $("input[id^='pluslet-update-title-']").val($(this).val());

    });


    $('body').on('click', '#sort-list-alpha-btn', function() {
        console.log('sort list');
        $( "li", "#db-list-results" ).sort(function( a, b ) {
            return $( a ).text() > $( b ).text();

        }).appendTo( "#db-list-results" );
    });



</script>
