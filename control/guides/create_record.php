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
        <button id="add-record" class="pure-button pure-button-primary" type="button">Create Record</button>
    </fieldset>
    <div class="notify"></div>
</form>

<script>
    $('#add-record').on("click", function() {
        var record = {
            "title_id": null,
            "title": $('#record-title').val(),
            "alternate_title": null,
            "description": $('#description').val(),
            "pre": $('#prefix').val(),
            "last_modified_by": "",
            "last_modified": "",
            "subjects": [{
                "subject_id": "1"
            }],
            "locations": [{
                "location_id": "",
                "format": "1",
                "call_number": "",
                "location": $('#location').val(),
                "access_restrictions": "1",
                "eres_display": "N",
                "display_note": "",
                "helpguide": "",
                "citation_guide": "",
                "ctags": ""
            }]
        };

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