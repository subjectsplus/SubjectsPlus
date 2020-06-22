<form class="edit-special-course-code-form pure-form pure-form-stacked" style="<?php echo $style; ?>">
    <label for="unique-code-label"><?php echo $uniqueCodeLabel; ?></label>
    <input type="text" class='unique-code-label' name="unique-code-label" class="required_field" required>
    <label for="description"><?php echo $description; ?></label>
    <textarea name="description" id="description"></textarea>
    <button class="button pure-button
        pure-button-primary" id="add-special-course-code"
            name="edit-special-course-code"><?php echo $saveSpecialCourseCode; ?></button>
</form>
