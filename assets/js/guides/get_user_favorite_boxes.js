/**
 * Created by cbrownroberts on 8/10/15.
 */


function get_user_favorite_boxes(staff_id) {

    $(".fav-boxes-list").empty();
    jQuery.ajax({
        url: "helpers/favorite_pluslets_data.php?staff_id=" +staff_id,
        type: "GET",
        dataType: "json",
        data: {staff_id: staff_id},
        success: function(data) {

            $.each(data, function(idx, obj) {
                $(".fav-boxes-list").append( "<li data-pluslet-id='" + obj.id +"' class='clone-favorite fav-box-item' title='" + obj.title + "'>" + obj.title + "</li>");

            });

        }
    });

}