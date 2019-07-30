<p><strong>* Indicates required fields</strong></p>
<form action="<?php echo $form_action; ?>" method="post">
	<div class="form-group">
		<label for="user_name">* Name:</label>
		<input name="user_name" size="" value="" class="form-control" />
	</div>
	<div class="form-group">
		<label for="user_email">* Email:</label>
		<input name="user_email" size="" value="" class="form-control" />
	</div>
	<div class="form-group">
		<label for="affiliation">UM Affiliation:</label>
		<select name="affiliation" class="form-control">
			<option value="Student">Student</option>
			<option value="Faculty/Staff">Faculty/Staff</option>
			<option value="Unaffiliated">Not Affiliated with UM</option>
		</select>
	</div>
	<div class="form-group">
		<label for="item_title">Problem Item <?php echo $item_title; ?></label>
		<input name="item_title" id="item_title" size="" value="" class="form-control" />
	</div>
	<div class="form-group">
		<label for="item_permalink">Problem Item Permalink</label>
		<input name="item_permalink" id="item_permalink" size="" value="" readonly class="form-control" />
	</div>
	<input type="hidden" name="item_view" value=""/>
	<div class="form-group">
		<label for="problem_type">* Type of Problem</label>
		<select name="problem_type" class="form-control">
			<option value="Connecting">Connecting to resources (login errors, browser errors)</option>
			<option value="Viewing Full-text">Viewing full-text (broken links; asked for password/payment)</option>
			<option value="Description Error">Catalog description error</option>
			<option value="Not on Shelf">Item not on shelf</option>
			<option value="Other">Other</option>
		</select>
	</div>
	<div class="form-group">
		<label for="description">* Please describe the problem you encountered in as much detail as possible.</label>
		<textarea name="description" rows="8" cols="55" class="form-control"></textarea>
	</div>
	<input type="hidden" id="primo_view" name="primo_view" value=""/>
	<input type="hidden" id="problem_report_form" name="problem_report_form"/>
	<input type="submit" name="submit" value="Submit" class="btn btn-default" />
	&nbsp;<input type="reset" value="Reset" class="btn btn-alt">
</form>

<script>

    $( document ).ready(function() {
        console.log( "ready!" );

        function getUrlVars() {
            var vars = {};
            var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
                vars[key] = value;
            });
            return vars;
        }

        function getUrlParam(parameter, defaultvalue){
            var urlparameter = defaultvalue;
            if(window.location.href.indexOf(parameter) > -1){
                urlparameter = getUrlVars()[parameter];
            }
            return urlparameter;
        }

        var title = getUrlParam('item_title', 'No Title');
        var permalink = getUrlParam('item_permalink', 'No Permalink');
        var primo_view = getUrlParam('v', 'richter');

        $("#item_title").val(title);
        $("#item_permalink").val(permalink);
        $('#primo_view').val(primo_view);
    });

</script>