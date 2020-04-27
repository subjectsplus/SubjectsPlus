/**
 * Created by jlittle on 3/23/16.
 */

var createRecord = {
    options : {
      notifyElement : '.notify'
    },
    insertRecord : function(record, callback) {
        var notification = "";

        // Insert the record object
        $.post('../records/insert_record.php', JSON.stringify(record), function (data) {})
            .done(function(res) {
                res = JSON.parse(res);

                if (res.response !== "error") {
                    notification = "<a target='_blank' href='" + res.response + "'>" + record.title + "</a>";
                    createRecord.insertNotify(notification);
                    callback(res);
                } else {
                    notification = "There was an error inserting the record";
                    createRecord.insertNotify(notification);
                }
            })
    },
    insertNotify : function(notification) {
        console.log("Insert notification");
        $(createRecord.options.notifyElement).html(notification);
    }
};
