<ul id="special-course-codes-list">
    <?php foreach ($special_courses as $course_data): ?>
        <li data-special-course-id="<?php echo $course_data['id']; ?>"
            data-special-unique-code="<?php echo $course_data['custom_code']; ?>"
            data-description="<?php echo $course_data['description']; ?>"
            data-associated-course-codes="<?php echo $course_data['associated_course_codes']; ?>">
            <a class="edit-special-course-btn"
               title="Edit"><i
                        class="fa fa-pencil fa-lg"></i></a>
            <?php echo $course_data['custom_code']; ?> <?php if (!empty($course_data['description'])) echo "(" . $course_data['description'] .")"; ?>
            <a class="delete-special-course-code-btn" title="Delete"><i
                        class="fa fa-trash"></i></a>
        </li>
    <?php endforeach; ?>
</ul>
