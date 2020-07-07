/**
 * Created by acarrasco on 6/19/20.
 */

var spBBSpecialCodesModule = (function SpBbSpecialCodes() {
    const spBBSpecialCodesActionUrl = "sp-bb-special-codes/controller/sp-bb-special-codes-helper.php";
    const specialCourseCodesFormListSelector = "#special-course-codes-list";
    const editFormSelector = "#edit-special-course-code-form";
    const addFormSelector = "#add-special-course-code-form";

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
        $(".edit-special-course-btn").off('click').on("click", function () {
            const clickedElementData = $(this).parents("li").data();
            $(editFormSelector + " input[name='special-course-code']").val(clickedElementData.specialUniqueCode);
            $(editFormSelector + " textarea[name='associated-course-codes']").val(clickedElementData.associatedCourseCodes);
            $(editFormSelector + " textarea[name='description']").val(clickedElementData.description);
            $(editFormSelector + " input[name='editing-course-code-id']").val(clickedElementData.specialCourseId);
            $(editFormSelector).show();
        });
    }

    function removeButtonsUI() {
        $(".delete-special-course-code-btn").off('click').on("click", function () {

            if (window.confirm("Are you sure you want to delete the Custom Course Code. This action can not be undone and the data will be lost.")) {

                const clickedElementData = $(this).parents("li").data();
                const elementId = clickedElementData.specialCourseId;
                const removedCourseCode = clickedElementData.specialUniqueCode;

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
                            showMessage("Course Code " + removedCourseCode + " has been removed.");
                            $(editFormSelector).hide().find("input[type=text], input[type=hidden], textarea").val("");
                        }
                    },
                    error: function (errorData) {
                        console.log('error');
                        console.log(errorData);
                    }
                });
            }
        });
    }

    function removeLiFromSpecialCaseCodesList(id) {
        $(specialCourseCodesFormListSelector + ` li[data-special-course-id='${id}']`).remove();
    }

    function validateForm(formData) {
        const newCode = $(formData).find("input[name=special-course-code]").val();
        const newAssociatedCodes = $(formData).find("textarea[name=associated-course-codes]").val();

        if (newCode.length === 0 || newAssociatedCodes.length === 0) {
            showMessage("Custom Course Code and Associated Course Codes are required fields.");
            return false;
        }

        return true;
    }

    function insertButton() {
        $("#add-special-course-code").on("click", function () {
            const formData = $(addFormSelector);

            if (!validateForm(formData)) return;

            let values = formDataToValues(formData.serializeArray());
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
                        showMessage("New Course Code " + data.data["special-course-code"] + " added.");
                        $(addFormSelector).find("input[type=text], textarea").val("");
                    } else {
                        if (data.errorCode == 23000) { //Non unique constrain error code from MySQL
                            showMessage("Course Code " + data.nonUniqueCourseCode + " already exists. Please try a different one.")
                        }
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

    function showMessage(message) {
        const messageSelector = "#sp-custom-codes-feedback";
        $(messageSelector).text(message).show();
        setTimeout(function () {
            $(messageSelector).hide('blind', {}, 700)
        }, 8000);
    }

    function saveChangesEditFormButton() {
        $("#save-special-course-code-changes").on("click", function () {
            const formData = $("#edit-special-course-code-form");

            if (!validateForm(formData)) return;

            let values = formDataToValues(formData.serializeArray());
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
                        showMessage("Course Code " + data.data["special-course-code"] + " has been edited.");
                        $(editFormSelector).hide().find("input[type=text], input[type=hidden], textarea").val("");
                    } else {
                        if (data.errorCode == 23000) { //Non unique constrain error code from MySQL
                            showMessage("Course Code " + data.nonUniqueCourseCode + " already exists. Please try a different one.")
                        }
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
        let description = "";
        if (data.description.length !== 0) {
            description = "(" + data["description"] + ")";
        }
        return `
        <li 
            data-special-course-id=\"${data.id}\" 
            data-special-unique-code=\"${data["special-course-code"]}\" 
            data-description=\"${data.description}\" 
            data-associated-course-codes=\"${data["associated-course-codes"]}\">
                    <a class=\"edit-special-course-btn\" title=\"Edit\">
                        <i class=\"fa fa-pencil fa-lg\"></i>
                    </a>
                    ${data["special-course-code"]} ${description}
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
