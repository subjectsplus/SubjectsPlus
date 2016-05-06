/**
 * Created by cbrownroberts on 5/5/16.
 */
var GuideEditor = (function() {

    function GuideEditor(settings) {
        this.fname = settings.fname;
        this.lname = settings.lname;
        this.tel = settings.tel;
        this.title = settings.title;
        this.email = settings.email;
    }

    return GuideEditor;

}());