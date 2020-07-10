<form id="add-special-course-code-form" class="pure-form pure-form-stacked" <?php if (isset($style)) echo $style; ?>>
	<p>(*) required</p>
    <label for="unique-code-label"><?php echo $uniqueCodeLabel; ?></label>
    <input type="text" class='special-code-label' name="special-course-code">
    <label for="course-codes"><?php echo $associatedCourseCodesLabel; ?></label>
    <textarea name="associated-course-codes"></textarea>
    <label for="description"><?php echo $description; ?></label>
    <textarea name="description"></textarea>
    <button type="button" class="button pure-button
        pure-button-primary" id="add-special-course-code"
            name="add-special-course-code"><?php echo $addSpecialCourseCode; ?></button>
</form>
