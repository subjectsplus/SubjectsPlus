/**
 * Created by acarrasco on 6/19/20.
 */

var spBBSpecialCodesModule = (function SpBbSpecialCodes() {
    const spBBSpecialCodesActionUrl = "sp-bb-special-codes/controller/sp-bb-special-codes-helper.php";
    const specialCourseCodesFormListSelector = "#special-course-codes-list";
    const editFormSelector = "#edit-special-course-code-form";

    function init() {
        bindUiActions();
    }

    function bindUiActions() {
        insertButton();
        saveChangesEditFormButton();
        editAndRemoveButtonsUI();
    }

    function editAndRemoveButtonsUI() {
        editButtonsUI();
        removeButtonsUI();
    }

    function editButtonsUI() {
        $(".edit-special-course-btn").on("click", function () {
            const clickedElementData = $(this).parents("li").data();
            $(editFormSelector + " input[name='special-course-code']").val(clickedElementData.specialUniqueCode);
            $(editFormSelector + " textarea[name='associated-course-codes']").val(clickedElementData.associatedCourseCodes);
            $(editFormSelector + " textarea[name='description']").val(clickedElementData.description);
            $(editFormSelector + " input[name='editing-course-code-id']").val(clickedElementData.specialCourseId);
            $(editFormSelector).show();
        });
    }

    function removeButtonsUI() {
        $(".delete-special-course-code-btn").on("click", function () {
            const clickedElementData = $(this).parents("li").data();
            const elementId = clickedElementData.specialCourseId;

            const payload = {
                'spBBCodesAction': 'remove-special-course-code',
                'data': {
                    "removing-course-code-id": elementId
                }
            };
            $.ajax({
                url: spBBSpecialCodesActionUrl,
                type: "POST",
                data: payload,
                dataType: "json",
                success: function (data) {
                    if (data.result) {
                        removeLiFromSpecialCaseCodesList(data.data);
                    }
                },
                error: function (errorData) {
                    console.log('error');
                    console.log(errorData);
                }
            });
        });
    }

    function removeLiFromSpecialCaseCodesList(id) {
        $(specialCourseCodesFormListSelector + ` li[data-special-course-id='${id}']`).remove();
    }

    function insertButton() {
        $("#add-special-course-code").on("click", function () {
            const formData = $("#add-special-course-code-form").serializeArray();
            let values = formDataToValues(formData);
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
                        editAndRemoveButtonsUI();
                    }
                },
                error: function (errorData) {
                    console.log('error');
                    console.log(errorData);
                }
            });
        });
    }

    function formDataToValues(formData) {
        let values = {};
        $.each(formData, function (i, field) {
            values[field.name] = field.value;
        });
        return values;
    }

    function saveChangesEditFormButton() {
        $("#save-special-course-code-changes").on("click", function () {
            const formData = $("#edit-special-course-code-form").serializeArray();
            let values = formDataToValues(formData);
            const payload = {
                'spBBCodesAction': 'edit-special-course-code',
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
                        updateSpecialCaseCodeData(data.data.id, renderedLi);
                        editAndRemoveButtonsUI();
                    }
                },
                error: function (errorData) {
                    console.log('error');
                    console.log(errorData);
                }
            });
        });
    }

    function updateSpecialCaseCodeData(liId, renderedLi) {
        $(specialCourseCodesFormListSelector + ` li[data-special-course-id='${liId}']`).replaceWith(renderedLi);
    }

    function addNewCodeToSpecialCaseCodesList(li) {
        $(specialCourseCodesFormListSelector).append(li);
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
