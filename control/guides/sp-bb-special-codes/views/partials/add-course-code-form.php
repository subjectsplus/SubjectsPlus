<form class="add-special-course-code-form pure-form pure-form-stacked" <?php if (isset($style)) echo $style; ?>>
    <label for="unique-code-label"><?php echo $uniqueCodeLabel; ?></label>
    <input type="text" class='unique-code-label' name="unique-code-label" class="required_field" required>
    <label for="description"><?php echo $description; ?></label>
    <textarea name="description" id="description"></textarea>
    <button class="button pure-button
        pure-button-primary" id="add-special-course-code"
            name="add-special-course-code"><?php echo $addSpecialCourseCode; ?></button>
</form>
