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
    <div class="pure-g modal-container">

        <div class="modal-header">
            <div class="pure-g">
                <div class="pure-u-3-4">
                    <label for="link-list-title-input">Title:
                        <input type="text" id="link-list-title-input" value="<?php echo $title; ?>" />
                    </label>
                    <button class="dblist-button pure-button pure-button-primary modal-save">Save List</button>
                    <button class="delete-trigger modal-delete"><a id="delete-<?php echo $this->_pluslet_id; ?>">Delete List</a></button>
                </div>
                <div class="pure-u-1-4 modal-header-controls">                     
                    <span class="close-trigger"><i class="fa fa-times" aria-hidden="true"></i>Close Window</span>
                </div>
            </div>
        </div>


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
                <input type="checkbox" name="show_all_icons_input" id="show_all_icons_input" class="pure-checkbox"> <span class="db-list-input-label"> Icons </span>
                <input type="checkbox" name="show_all_desc_input" id="show_all_desc_input" class="pure-checkbox"> <span class="db-list-input-label">Descriptions</span>
                <input type="checkbox" name="show_all_notes_input" id="show_all_notes_input" class="pure-checkbox"> <span class="db-list-input-label">Notes</span>

                <!--buttons-->
                <div class="records-sort">
                    <button class="pure-button pure-button-secondary" id="sort-list-alpha-btn">Sort List Alphabetically</button>
                    <button class="pure-button pure-button-secondary dblist-reset-button">Reset List Box</button>
                </div>


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
                
                <label for="link-list-textarea" class="label-context">Link List Context:</label>
                <button class="pure-button pure-button-outline" id="show-linklist-textarea-btn"><i class="fa fa-pencil" aria-hidden="true"></i>Add Text</button>

                <div id="link-list-textarea-container">                   
                    <textarea id="link-list-textarea" name="LinkList-extra-textarea" cols="34" rows="7"></textarea>
                    
                    <div class="label-context-options">
                        <label for="top-text-radio">
                            <input id="top-text-radio" type="radio" name="LinkList-extra-radio" value="top"> Above List
                        </label> <br />
                        <label for="bottom-text-radio">
                            <input id="bottom-text-radio" type="radio" name="LinkList-extra-radio" value="bottom" checked=""> Below List
                        </label>
                    </div>    
                </div>                

            </div>
        </div>

        <div class="pure-u-1-3">
            <div class="modal-subs">            

                <h3>Add New Record</h3>
                <form id="create-record-form" class="pure-form pure-form-stacked">
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

                <h3>Broken Link / Record</h3>
                <button class="pure-button pure-button-secondary" id="show-broken-record-form-btn">Report Broken Record</button>
                
                <div id="report-broken-record-container" style="display: none;">
                    <form action="mailto:<?php echo $administrator_email; ?>?Subject='Broken Record Link'" method="post" enctype="text/plain" class="pure-form pure-form-stacked">
                        <fieldset>
                            <label for="broken-record-name">Record         
                                <input id="broken-record-name" name="broken-record-name" type="text">
                            </label>
                            
                            <label id="broken-record-msg">Comments 
                                <textarea id="broken-record-msg" name="broken-record-msg" rows="7" cols="34"></textarea>
                            </label>

                           
                            <button type="submit" class="pure-button pure-button-primary" id="report-broken-record">Send</button>
                            <button type="reset" class="pure-button pure-button-primary">Reset</button>
                        </fieldset>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>


<script>
    LinkList();
    
    
    var topText = $('.link-list-text-top').html();
    var bottomText = $('.link-list-text-bottom').text();

    console.log('top: ' + topText);
    console.log('bottom: ' + bottomText);
    
    if(topText != "") {
        CKEDITOR.instances['link-list-textarea'].setData( topText );
        $('input:radio[name="LinkList-extra-radio"][value="top"]').prop('checked', true);
    }

    if(bottomText != "") {
        CKEDITOR.instances['link-list-textarea'].setData( bottomText );
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


    $('.db-list-results, .databases-searchresults').enscroll({
        verticalTrackClass: 'track3',
        verticalHandleClass: 'handle3',
        minScrollbarLength: 28
    }); 



</script>
