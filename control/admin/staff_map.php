<?php

use SubjectsPlus\Control\Querier;

$subsubcat = "";
$subcat = "admin";
$page_title = "Staff Map";

// You must set this to your own coords:
// This is Coral Gables, FL
$map_center = "25.71828,-80.27875";

include("../includes/header.php");

//check if they have the view_map permission
if (isset($_SESSION['view_map']) && $_SESSION['view_map'] == 1) {
  //okey

} else {
  // shouldn't be here
  echo "<br /><br /><p class=\"box\">" . _("You are not authorized to view this.") . "</p>";
  include("../includes/footer.php");
  exit;
}

$querier = new Querier();
$q1 = 'SELECT staff_id, CONCAT( fname, " ", lname ) AS fullname, email, CONCAT( emergency_contact_name, " (", emergency_contact_relation, ")", ": ", emergency_contact_phone ) AS contact, CONCAT( street_address, "<br />", city, " ", state, " ", zip ) AS full_address, home_phone, cell_phone, lat_long
FROM staff
WHERE lat_long != ""
AND active = 1' ;

if (isset($_GET["fac_only"])  && $_GET["fac_only"] == 1 ) {
  $q1 = 'SELECT staff_id, CONCAT( fname, " ", lname ) AS fullname, email, CONCAT( emergency_contact_name, " (", emergency_contact_relation, ")", ": ", emergency_contact_phone ) AS contact, CONCAT( street_address, "<br />", city, " ", state, " ", zip ) AS full_address, home_phone, cell_phone, lat_long
FROM staff
WHERE lat_long != ""
AND ptags LIKE "%librarian%"';
}


  $db = new Querier;
  $staffArray = $db->query($q1);

?>
<div id="map" style="width: 100%; height: 800px; border: 1px solid #333;"></div>

<?php include("../includes/footer.php"); ?>

<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
  
  google.maps.event.addDomListener(window, 'load', function() {
    var requested = "";
    
    /////////////////////
    // This map has Coral Gables as its center
    /////////////////////
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 11,
      center: new google.maps.LatLng(<?php print $map_center; ?>),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    
    /////////////////
    // our all-purpose infoWindow
    /////////////////
    var infoWindow = new google.maps.InfoWindow;

    var onMarkerClick = function() {
      var marker = this;
      
      infoWindow.setContent('<h3>' + marker.fullname + '</h3><p>' + marker.address + 
        '</p><p><strong>email:</strong> ' + marker.email + '<br /><strong>home</strong> ' + marker.home_phone + '<br /><strong>cell</strong> ' + marker.cell_phone + '<br /><h3>Emergency Contact</strong></h3><p>' + marker.e_contact + '</p>');

      infoWindow.open(marker.map, marker);
    };
    


    ////////////////////
    // Our event listener
    ////////////////////
    
    google.maps.event.addListener(map, 'click', function() {
      infoWindow.close();
    });

    var markers = new Array();

<?php
// Now we slip in da markers!
foreach ($staffArray as $key => $value) {
  if ($value["lat_long"] != '') {
    print "markers[" . $key . "] = new google.maps.Marker({
      map: map,
      position: new google.maps.LatLng(" . $value["lat_long"] . "),
      fullname: '" . $value["fullname"] . "',
      address: '" . $value["full_address"] . "',
      e_contact: '" . $value["contact"] . "',
      home_phone: '" . $value["home_phone"] . "',
      cell_phone: '" . $value["cell_phone"] . "',
      email: '" . $value["email"] . "'
    });
            
            ";
  }
}
?>
    // This code block makes sure the map is done loading before listening
    // fixes a panning issue
    google.maps.event.addListenerOnce(map, 'idle', function(){

      // Set up our event listeners
      for (var i = 1; i < markers.length; i++) {
  
        google.maps.event.addListener(markers[i], 'click', onMarkerClick);
  
        // check if someone wanted to link directly to an infoWindow
        if (requested == i) {

          centerMap(i);
        
        }
 
      }
  
    });
    function centerMap(id) {
      google.maps.event.trigger(markers[id], 'click');
    }

    // Some jquery
    $(document).ready(function(){
      $("a[rel*=pop-]").click(function() {
        var pop_id = $(this).attr("rel").split("-");
        centerMap(pop_id[1]);
        return false;
      });
    
    });
  });
  
</script>
