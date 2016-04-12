<?php
$id = uniqid();
?>

<style>
    .link-list-display {
        display: none;
    }
</style>

<div class='link-list' id="LinkList-body" data-link-list-id="<? echo $id; ?>">

    <div class="pure-g">
        <div class="pure-u-1-3">

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
                <div class="link-list-draggable" id="record-sortable-list">

                </div>
            </div>


            <br>

            <textarea id="link-list-textarea" name="LinkList-extra-textarea" cols="34" rows="7"></textarea>
            <br>
            <div>
                <input type="radio" name="LinkList-extra-radio" value="top"> Above List<br>
                <input type="radio" name="LinkList-extra-radio" value="bottom" checked=""> Below List<br>
            </div>

            <!--buttons-->
            <div class="db-list-buttons" style="display: block;">
                <button data-link-list-pluslet-id="" class="pure-button pure-button-primary dblist-button">Create List Box</button>
                <button class="pure-button pure-button-primary dblist-reset-button">Reset List Box</button>
            </div>
        </div>
        <div class="pure-u-1-3">
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

                    <label for="description">
                        Description                    <textarea id="description"></textarea>
                    </label>

                    <button id="add-record" class="pure-button pure-button-primary" type="submit">Create Record</button>
                </fieldset>
                <div class="notify"></div>
            </form>

            <a id="show-broken-record-form">Report Broken Record</a>
            <div id="report-broken-record-container" style="display: none;">
                <form action="mailto:?Subject='Broken Record Link'" method="post" enctype="text/plain">
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
</script>
