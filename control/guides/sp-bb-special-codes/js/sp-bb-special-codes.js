/**
 * Created by acarrasco on 6/19/20.
 */

var spBBSpecialCodesModule = (function SpBbSpecialCodes() {
    const spBBSpecialCodesActionUrl = "sp-bb-special-codes/controller/sp-bb-special-codes-helper.php";

    function init() {
        bindUiActions();
    }

    function bindUiActions() {
        insertButton();
        editButtonsUI();
    }

    function editButtonsUI() {
        $(".edit-special-course-btn").on("click", editButtonsUIAction());
    }

    function editButtonsUIAction(event) {
        const editFormSelector = "#edit-special-course-code-form";

    }

    function insertButton() {
        $("#add-special-course-code").on("click", function () {
            const formData = $("#add-special-course-code-form").serializeArray();
            let values = {};
            $.each(formData, function (i, field) {
                values[field.name] = field.value;
            });
            const payload = {
                'spBBCodesAction': 'insert-special-course-code',
                'data': values
            };
            $.ajax({
                url: spBBSpecialCodesActionUrl,
                type: "POST",
                data: payload,
                dataType: "json",
                success: function (data) {
                    if (data.result) {
                        const renderedLi = specialCaseCodesListTemplate(data.data);
                        addNewCodeToSpecialCaseCodesList(renderedLi);
                    }
                },
                error: function (errorData) {
                    console.log('error');
                    console.log(errorData);
                }
            });
        });
    }

    function addNewCodeToSpecialCaseCodesList(li) {
        $("#special-course-codes-list").append(li);
    }

    function specialCaseCodesListTemplate(data) {
        return `
        <li 
            data-special-course-id=\"${data.id}\" 
            data-special-unique-code=\"${data["special-course-code"]}\" 
            data-description=\"${data.description}\" 
            data-associated-course-codes=\"${data["associated-course-codes"]}\">
                    <a class=\"edit-special-course-btn\" title=\"Edit\">
                        <i class=\"fa fa-pencil fa-lg\"></i>
                    </a>
                    ${data["special-course-code"]}
                    <a class=\"delete-special-course-code-btn\" title=\"Delete\">
                        <i class=\"fa fa-trash\"></i>
                    </a>
        </li>`;
    }

    return {
        init: init
    }
})();

$(document).ready(function () {
    spBBSpecialCodesModule.init();
});
