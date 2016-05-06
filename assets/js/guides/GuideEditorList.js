/**
 * Created by cbrownroberts on 5/3/16.
 */


function guideEditorList() {
    "use strict";

    var myGuideEditorList = {

        settings : {
            
        },
        strings : {
        },
        bindUiActions : function() {

            myGuideEditorList.saveGuideEditor();
            myGuideEditorList.deleteGuideEditor();
            myGuideEditorList.closeModalWindow();
            myGuideEditorList.createGuideEditorList();
        },
        init : function() {

            myGuideEditorList.bindUiActions();
        },

        saveGuideEditor : function () {
            $('body').on('click', '#save-guide-editor-btn', function() {
                console.log('save');


                var thisPlusletModal = $(this).closest('div[name*="-pluslet-GuideEditorList"]');
                //thisPlusletModal.remove();

                saveSetup().saveGuide();
            });
        },

        deleteGuideEditor : function () {
            $('body').on('click', '#delete-guide-editor-btn', function() {
                console.log('delete');
                var thisPlusletModal = $(this).closest('div[name*="-pluslet-GuideEditorList"]');
                //thisPlusletModal.remove();
            });
        },
        
        closeModalWindow : function() {
            
            $('body').on('click', '#close-guide-editor-btn', function() {
                var thisPlusletModal = $(this).closest('div[name*="-pluslet-GuideEditorList"]');
                thisPlusletModal.remove();
            });
        },
        
        createGuideEditorList : function() {
            $('body').on('click', '#create-guide-editor-list-btn', function() {

                var checkboxes = $('div#guide-editor-settings > input[type="checkbox"]');

                $.each(checkboxes, function() {

                    console.log($(this).next('input').val());

                    $('#guide-editor-list').append('<li>' + $(this).next('input').val() + '</li>');
                })




            });
        }

    };

    return myGuideEditorList;
}