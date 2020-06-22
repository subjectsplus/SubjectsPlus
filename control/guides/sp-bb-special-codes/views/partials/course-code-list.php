<ul id="special-course-codes-list">
    <?php foreach ($special_courses as $course_data): ?>
        <li data-special-course-id="<?php echo $course_data['id']; ?>"
            data-special-unique-code="<?php echo $course_data['custom_code']; ?>"
            data-description="<?php echo $course_data['description']; ?>"
            data-associated-course-codes="<?php echo $course_data['associated_course_codes']; ?>">
            <a class="edit-special-course-btn"
               title="Edit"><i
                        class="fa fa-pencil fa-lg"></i></a>
            <?php echo $course_data['custom_code']; ?>
            <a class="delete-special-course-code-btn" title="Delete"><i
                        class="fa fa-trash"></i></a>
        </li>
    <?php endforeach; ?>
</ul>

<style>
    #special-course-codes-list {
        list-style-position: outside;
        list-style-type: none;
        margin: 0;
        padding: 0 !important;
        display: block;
    }

    #special-course-codes-list li {
        background: #f6e3e7;
        margin: 0 0 2px 0;
        padding: 5px;
    }

    #special-course-codes-list li:nth-child(odd) {
        background: transparent;
    }

    #special-course-codes-list a[class^="delete-special"] {
        float: right;
    }

    #special-course-codes-list i {
        margin: 0 8px 0 0;
    }
</style>


