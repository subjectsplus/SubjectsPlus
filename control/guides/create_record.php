<?php include("../includes/header.php"); ?>

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

<script>
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

</body>
</html>