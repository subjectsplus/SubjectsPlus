<?php

use SubjectsPlus\Control\Querier;

$subsubcat = "";
$subcat = "admin";
$page_title = "Staff Map";

include("../includes/header.php");

//check if they have the view_map permission
if (! (isset($_SESSION['view_map']) && $_SESSION['view_map'] == 1) ){
  // shouldn't be here
  echo "<br /><br /><p class=\"box\">" . _("You are not authorized to view this.") . "</p>";
  include("../includes/footer.php");
  exit;
};

global $stats_encryption_enabled;

if ( $stats_encryption_enabled ) {
	$q1 = 'SELECT staff_id,
    fname,
    lname,
    email,
    emergency_contact_name,
    emergency_contact_relation,
    emergency_contact_phone,
    street_address,
    city,
    state,
    zip,
    home_phone,
    cell_phone,
    lat_long
  FROM staff
  WHERE lat_long != "" AND lat_long NOT IN ("x","xx")
  AND active = 1';

	if ( isset( $_GET["fac_only"] ) && $_GET["fac_only"] == 1 ) {
		$q1 = 'SELECT staff_id,
      fname,
      lname),
      email,
      emergency_contact_name,
      emergency_contact_relation,
      emergency_contact_phone,
      street_address,
      city,
      state,
      zip,
      home_phone,
      cell_phone,
      lat_long
    FROM staff
    WHERE lat_long != "" AND lat_long NOT IN ("x","xx")
    AND ptags LIKE "%librarian%"';
  }
} else {
	$q1 = 'SELECT staff_id,
    CONCAT( fname, " ", lname ) AS fullname,
    email,
    CONCAT( emergency_contact_name, " (", emergency_contact_relation, ")", ": ", emergency_contact_phone ) AS contact,
    CONCAT( street_address, city, " ",state, " ", zip ) AS full_address,
    home_phone,
    cell_phone,
    lat_long
  FROM staff
  WHERE lat_long != "" AND lat_long NOT IN ("x","xx")
  AND active = 1';

	if ( isset( $_GET["fac_only"] ) && $_GET["fac_only"] == 1 ) {
		$q1 = 'SELECT staff_id,
      CONCAT( fname, " ", lname ) AS fullname,
      email,
      CONCAT( emergency_contact_name, " (", emergency_contact_relation, ")", ": ", emergency_contact_phone ) AS contact,
      CONCAT( street_address, city, " ", state, " ", zip ) AS full_address,
      home_phone,
      cell_phone,
      lat_long
    FROM staff
    WHERE lat_long != "" AND lat_long NOT IN ("x","xx")
    AND ptags LIKE "%librarian%"';
	}
}

$db = new Querier;
$staffArray = $db->query($q1);

include("../includes/footer.php");

print "
  <script>

  let markers = {};

";

foreach ($staffArray as $key => $value) {
  if (!empty($value["lat_long"])) {
    if ($stats_encryption_enabled){
      $value["fullname"] =          $value["fname"] . " " . $value["lname"];
      $value["full_address"] =      $street_address . $city . " " . $state . " " . $zip;
      $value["contact"] =           $emergency_contact_name . " (" . $emergency_contact_relation . "): " . $emergency_contact_phone;

      $value["lat_long"] =          !empty($value["lat_long"])                    ? decryptIt($value["lat_long"])                     : "";
      $street_address =             !empty($value["street_address"])              ? decryptIt($value["street_address"])               : "";
      $city =                       !empty($value["city"])                        ? decryptIt($value["city"])                         : "";
      $state =                      !empty($value["state"])                       ? decryptIt($value["state"])                        : "";
      $zip =                        !empty($value["zip"])                         ? decryptIt($value["zip"])                          : "";
      $emergency_contact_name =     !empty($value["emergency_contact_name"])      ? decryptIt($value["emergency_contact_name"])       : "";
      $emergency_contact_relation = !empty($value["emergency_contact_relation"])  ? decryptIt($value["emergency_contact_relation"])   : "";
      $emergency_contact_phone =    !empty($value["emergency_contact_phone"])     ? decryptIt($value["emergency_contact_phone"])      : "";
      $value["home_phone"] =        !empty($value["home_phone"])                  ? decryptIt($value["home_phone"])                   : "";
      $value["cell_phone"] =        !empty($value["cell_phone"])                  ? decryptIt($value["cell_phone"])                   : "";
    }

    print "
      markers[" . $key . "] = {
        position: [" . $value["lat_long"] . "],
        fullname: '" . $value["fullname"] . "',
        address: `" . $value["full_address"] . "`,
        e_contact: '" . $value["contact"] . "',
        home_phone: '" . $value["home_phone"] . "',
        cell_phone: '" . $value["cell_phone"] . "',
        email: '" . $value["email"] . "'
      };
    ";

//    print "
//      
//      {"type
//    
//    "

    // {
    //   "type": "Feature",
    //   "geometry": {
    //   "type": "Point",
    //   "coordinates": [-122.414, 37.776]
    //   },
    //   "properties": {
    //   "title": "Mapbox SF",
    //   "icon": "harbor"
    //   }
    // },

  }
}

echo "console.log('----------------------- ', markers[0]);";

print "</script>";

?>

<script src="https://api.mapbox.com/mapbox-gl-js/v1.0.0/mapbox-gl.js"></script>
<link href="https://api.mapbox.com/mapbox-gl-js/v1.0.0/mapbox-gl.css" rel="stylesheet" />
  
<h1>STILL TESTING</h1>
<div id='map' style='width: 800px; height: 600px;'></div>
<script>

  <?php global $mapbox_access_token; ?>

  mapboxgl.accessToken = "<?php echo $mapbox_access_token ?>";

  <?php

  // Use home location coordinates for map centering; this is set in config.php
  global $home_coords;

  // If no value has been set in config.php for $home_coords, use Coral Gables as default
  // if(! isset($home_coords)){
  //   echo "NOT SET";
  //   $home_coords = "25.71828,-80.27875";
  // };

  ?>

  console.log('$mapbox_access_token :', "<?php echo $mapbox_access_token; ?>");

  // MapBox uses longitude + latitude, while we use lat-long, so have to reverse the array order
  let homeCoords = [<?php echo $home_coords[1] ?>,<?php echo $home_coords[0] ?>];

  console.log('homeCoords:', homeCoords);
  console.log('homeCoords typeof:', typeof homeCoords);

  console.log('================== markers :', markers);

  const map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/light-v10',
    center: homeCoords,
    zoom: 9
  });
  
  map.on('load', function () {
  
    map.addLayer({
      "id": "points",
      "type": "symbol",
      "source": {
        "type": "geojson",
        "data": {
          "type": "FeatureCollection",
          "features": [
            {
              "type": "Feature",
              "geometry": {
              "type": "Point",
              "coordinates": [-122.414, 37.776]
              },
              "properties": {
              "title": "Mapbox SF",
              "icon": "harbor"
              }
            },
          ]
        }
      },
      "layout": {
        "icon-image": "{icon}-15",
        "text-field": "{title}",
        "text-font": ["Open Sans Semibold", "Arial Unicode MS Bold"],
        "text-offset": [0, 0.6],
        "text-anchor": "top"
      }
    });
  });
</script>