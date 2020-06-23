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
    }

    function insertButton() {
        $("#add-special-course-code").click(function () {
            // // $("#add-special-course-code-form").valid();
            const formData = $("#add-special-course-code-form").serializeArray();
            let values = {};
            $.each(formData, function(i, field) {
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
                    console.log(data);
                },
                error: function (errorData) {
                    console.log('error');
                    console.log(errorData);
                }
            });
        });
    }

    return {
        init: init
    }
})();

$(document).ready(function () {
    spBBSpecialCodesModule.init();
});
