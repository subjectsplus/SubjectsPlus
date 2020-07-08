<form id="edit-special-course-code-form" class="pure-form pure-form-stacked" style="<?php echo $style; ?>">
	<p>(*) required</p>
    <input type="hidden" name="editing-course-code-id"/>
    <label for="unique-code-label"><?php echo $uniqueCodeLabel; ?></label>
    <input type="text" class='special-code-label' name="special-course-code"/>
    <label for="course-codes"><?php echo $associatedCourseCodesLabel; ?></label>
    <textarea name="associated-course-codes"></textarea>
    <label for="description"><?php echo $description; ?></label>
    <textarea name="description"></textarea>
    <button type="button" class="button pure-button
        pure-button-primary" id="save-special-course-code-changes"
            name=""save-special-course-code-changes"><?php echo $saveSpecialCourseCode; ?></button>
</form>
