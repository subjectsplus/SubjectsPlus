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

if( (isset($this->_linkText)) && (!empty($this->_linkText)) ) {

    echo "<div id='link-text' style='display: none;'>{$this->_linkText}</div>";
}



?>

<style>
    .link-list-display {
        display: none;
    }
</style>

<div class='sp-modal link-list' id="LinkList-body" data-link-list-id="<?php echo $id; ?>">
    <div class="modal-container">

        <div class="modal-header">
            <div class="pure-g">
                <div class="pure-u-3-4">
                    <label for="link-list-title-input">Title:
                        <input type="text" id="link-list-title-input" value="<?php echo $title; ?>" />
                    </label>
                    <button class="dblist-button pure-button pure-button-primary modal-save">Save List</button>
                    <button class="delete-trigger modal-delete" id="delete-linklist-btn">Delete List</button>
                </div>
                <div class="pure-u-1-4 modal-header-controls">                     
                    <span class="close-trigger"><i class="fa fa-times" aria-hidden="true"></i>Close Window</span>
                </div>
            </div>
        </div>

        <div class="pure-g">
        <div class="pure-u-1-3">
            <div class="modal-subs">             

                <h3>Find a Link / Record</h3>

                <label for="limit-az">
                    <input id="limit-az" type="checkbox" checked="">
                    Limit to AZ List
                </label>

                <!--display db results list-->
                <div class="dblist-display">
                    <div class="databases-results-display">
                        <input class="databases-search" type="text" placeholder="Enter database title...">
                        <ul class="databases-searchresults" id="databases-searchresults"></ul>
                    </div>
                </div>

            </div>
        </div>

        <div class="pure-u-1-3">
            <div class="modal-subs">

                <h3>Selected Records</h3>
                <span class="db-list-input-label">Show all: </span>
                <input type="checkbox" name="show_all_icons_input" id="show_all_icons_input" class="pure-checkbox">
                <span class="db-list-input-label" for="show_all_icons_input"> Icons </span>
                
                <input type="checkbox" name="show_all_desc_input" id="show_all_desc_input" class="pure-checkbox">
                <span class="db-list-input-label" for="show_all_desc_input">Descriptions</span>
                
                <input type="checkbox" name="show_all_notes_input" id="show_all_notes_input" class="pure-checkbox">
                <span class="db-list-input-label" for="show_all_notes_input">Notes</span>

                <!--buttons-->
                <div class="records-sort">
                    <button class="pure-button pure-button-secondary" id="sort-list-alpha-btn">Sort List Alphabetically</button>
                    <button class="pure-button pure-button-secondary dblist-reset-button"><?php echo _('Delete All Items'); ?></button>
                </div>


                <!--display results selected-->
                <div class="db-list-content" style="display: block;">
                    <?php if( ( isset($this->_topText) ) && ( !empty($this->_topText) ) ) {
                        echo "<div class='link-list-text-top'>" . $this->_topText . "</div>";
                    } ?>
                    <div class="link-list-draggable" id="record-sortable-list">

                    </div>
                    <?php if( ( isset($this->_bottomText) ) && ( !empty($this->_bottomText) ) ) {
                        echo "<div class='link-list-text-bottom'>" . $this->_bottomText . "</div>";
                    } ?>
                </div>


                <label for="link-list-textarea" class="label-context">Link List Context:</label>
                <button class="pure-button pure-button-outline" id="show-linklist-textarea-btn"><i class="fa fa-pencil" aria-hidden="true"></i>Add Text</button>

                <div id="link-list-textarea-container">                   
                    <textarea id="link-list-textarea" name="LinkList-extra-textarea" cols="34" rows="7"></textarea>
                    
                    <div class="label-context-options">
                        <label for="top-text-radio">
                            <input id="top-text-radio" type="radio" name="linkList-text-radio" value="top"> Above List
                        </label> <br />
                        <label for="bottom-text-radio">
                            <input id="bottom-text-radio" type="radio" name="linkList-text-radio" value="bottom"> Below List
                        </label>
                    </div>    
                </div>                

            </div>
        </div>

        <div class="pure-u-1-3">
            <div class="modal-subs">            

                <h3 class="show-create-record"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i> Add New Record</h3>
                <form id="create-record-form" class="pure-form pure-form-stacked" style="display:none;">
                    <fieldset>

                        <label for="record-title">
                            Record Title <input id="record-title" type="text" value="" required="">
                        </label>


                        <label for="alternate-title">
                            Alternate Title <input id="alternate-title" value="" type="text">
                        </label>


                        <label for="location">
                            Location (Enter URL) <input id="location" type="text" value="" required="">
                             <span id="checkurl" class="checkurl_img_wrapper"><i alt="Check URL" title="Check URL" border="0" class="fa fa-globe fa-2x clickable"></i> Check URL</span>
                        </label>

                        <label for="description">
                            Description 
                        </label>
                        
                        <button class="pure-button pure-button-outline" id="show-record-description-btn"><i class="fa fa-pencil" aria-hidden="true"></i>Add Description</button>
                        
                        <div id="record-description-container">                            
                            <textarea id="description"></textarea>                           
                        </div>                        

                        <button id="add-record" class="pure-button pure-button-primary modal-create-record-btn" type="submit">Create Record</button>
                    </fieldset>
                    
                    <div class="notify"></div>

                </form>

                <h3 class="show-report-broken-record"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i> Broken Link / Record</h3>
                <div id="report-broken-record-container" style="display: none;">
                    <form action="mailto:<?php echo $administrator_email; ?>?Subject='Broken Record Link'" method="post" enctype="text/plain" class="pure-form pure-form-stacked">
                        <fieldset>
                            <label for="broken-record-name">Record         
                                <input id="broken-record-name" name="broken-record-name" type="text">
                            </label>
                            
                            <label id="broken-record-msg">Comments 
                                <textarea id="broken-record-msg" name="broken-record-msg" rows="7" cols="34"></textarea>
                            </label>

                           
                            <button type="submit" class="pure-button pure-button-primary" id="report-broken-record">Report Broken Record</button>
                            <button type="reset" class="pure-button pure-button-primary">Reset</button>
                        </fieldset>
                    </form>
                </div>

            </div>
        </div>
        </div>


    </div>
</div>


<script>


    $( document ).ready(function() {
        LinkList();
        var linkText = $('#link-text').children().html();

        if(CKEDITOR.instances['link-list-textarea']) {

            if( $('#link-text').find('div.link-list-text-top').html() !== undefined ) {

                $('input:radio[name="linkList-text-radio"][value="top"]').prop('checked', true);
                CKEDITOR.instances['link-list-textarea'].setData( linkText );
            }

            if( $('#link-text').find('div.link-list-text-bottom').html() !== undefined ) {

                $('input:radio[name="linkList-text-radio"][value="bottom"]').prop('checked', true);
                CKEDITOR.instances['link-list-textarea'].setData( linkText );
            }

        }
        

        $('#link-list-title-input').bind('keypress keyup blur', function() {
            $("input[name='new_pluslet_title']").val($(this).val());
            $("input[id^='pluslet-update-title-']").val($(this).val());
        });


        $('body').on('click', '#sort-list-alpha-btn', function() {

            tinysort('ul#db-list-results > li',{attr:'data-title'});

        });

        //Add custom js scrollbar for list containers
        $('.db-list-results, .databases-searchresults').enscroll({
            verticalTrackClass: 'track3',
            verticalHandleClass: 'handle3',
            minScrollbarLength: 28
        });

        // Expand/collapse "Add record containers"
        $(".show-create-record").click(function() {
            $("#create-record-form ").toggle();
            $(".show-create-record .fa").toggleClass('fa-chevron-circle-down fa-chevron-circle-up');
        });

        $(".show-report-broken-record").click(function() {
            $("#report-broken-record-container ").toggle();
            $(".show-report-broken-record .fa").toggleClass('fa-chevron-circle-down fa-chevron-circle-up');
        });


        $('.link-list-description-override-textarea').hide();


    });

</script>
