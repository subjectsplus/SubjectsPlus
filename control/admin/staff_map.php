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

// ----- FETCH INFO FROM THE DB + PASS IT TO THE CLIENT-SIDE JS ----------------------------------------------------

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
  <!-- Using local copies of JS and CSS files for development; will switch to CDN for production -->
  <!-- <script src='../../assets/js/mapbox-gl.js'></script> -->
  <!-- <script src='../../assets/css/mapbox-gl.css'></script> -->

  <script src='https://api.mapbox.com/mapbox-gl-js/v1.0.0/mapbox-gl.js'></script>
  <link href='https://api.mapbox.com/mapbox-gl-js/v1.0.0/mapbox-gl.css' rel='stylesheet' />

  <script id='markers-container'>
    let markers = [];
    let popup, el, newPopup;
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
      markers.push(
        {
          type: 'Feature',
          id: " . $key . ",
          geometry: {
            type: 'Point',
            coordinates: [" . $value["lat_long"] . "].reverse(),
          },
          properties: {
            id: " . $key . ",
            icon: 'pulsing-dot',
            name: '" . $value["fullname"] . "'
          }
        }
      )
    ";

  }
}

print "
  </script>
";

?>

<!-- ===== START OF MAPBOX JS SCRIPT TO DRAW MAP =============================================================== -->
  
<h1>STILL TESTING</h1>
<div id='map' style='width: 800px; height: 600px;'></div>
<script id="map-drawing">

  <?php
    global $mapbox_access_token;
    
    // Use home location coordinates for map centering; this is set in config.php
    global $home_coords;
  ?>

  mapboxgl.accessToken = "<?php echo $mapbox_access_token ?>";

  console.log('$mapbox_access_token :', "<?php echo $mapbox_access_token; ?>");

  // MapBox uses longitude + latitude, while we use lat-long, so have to reverse the array order
  let homeCoords = [<?php echo $home_coords[1] ?>,<?php echo $home_coords[0] ?>];

  console.log('================== markers :', markers);

  const map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/light-v10',
    center: homeCoords,
    zoom: 9
  });

  // for(let popup of popupArray){
  //   console.log('popup :', popup);
  //   popup.addTo(map);
  // };

  // Start of code for pulsing red dot as marker
  let dotSize = 75;

  let pulsingDot = {
    width: dotSize,
    height: dotSize,
    data: new Uint8Array(dotSize * dotSize * 4),
    
    onAdd: function() {
      let canvas = document.createElement('canvas');
      canvas.width = this.width;
      canvas.height = this.height;
      this.context = canvas.getContext('2d');
    },
    
    render: function() {
      let duration = 1000;
      let t = (performance.now() % duration) / duration;
      
      let radius = dotSize / 2 * 0.3;
      let outerRadius = dotSize / 2 * 0.7 * t + radius;
      let context = this.context;
      
      // draw outer circle
      context.clearRect(0, 0, this.width, this.height);
      context.beginPath();
      context.arc(this.width / 2, this.height / 2, outerRadius, 0, Math.PI * 2);
      context.fillStyle = 'rgba(255, 200, 200,' + (1 - t) + ')';
      context.fill();
      
      // draw inner circle
      context.beginPath();
      context.arc(this.width / 2, this.height / 2, radius, 0, Math.PI * 2);
      context.fillStyle = 'rgba(255, 100, 100, 1)';
      context.strokeStyle = 'white';
      context.lineWidth = 2 + 4 * (1 - t);
      context.fill();
      context.stroke();
      
      // update this image's data with data from the canvas
      this.data = context.getImageData(0, 0, this.width, this.height).data;
      
      // keep the map repainting
      map.triggerRepaint();
      
      // return `true` to let the map know that the image was updated
      return true;
    }
  };
  
  // Set up static elements of the map on completion of map loading
  map.on('load', function () {
  
    map.addImage('pulsing-dot', pulsingDot, { pixelRatio: 2 });

    map.addSource("staff_locations", {
      type: "geojson",
      data: {
        "type": "FeatureCollection",
        "features": markers
      },
      cluster: false,
      clusterMaxZoom: 1, // Max zoom to cluster points on
      clusterRadius: 1, // Radius of each cluster when clustering points (defaults to 50)
    });
    
    map.addLayer({
      "id": "staff",
      "type": "symbol",
      "source": "staff_locations",
      "layout": {
        "icon-image": "pulsing-dot",
        "icon-ignore-placement": true,
        "text-field": "{title}",
        "text-font": ["Open Sans Semibold", "Arial Unicode MS Bold"],
        "text-offset": [0, 0.6],
        "text-anchor": "top"
      }
    });

    map.addLayer({
      "id": "hover-fills",
      "type": "fill",
      "source": "staff_locations",
      "layout": {},
      "paint": {
        "fill-color": ["case",
          ["boolean", ["feature-state", "hover"], false],
          "#ff6464",
          "#ffffff"
        ]
      }
    })

  });

  // Set up click event for staff member icons
  map.on('click', 'staff', function (e) {

    console.log('e :', e);

    let coordinates = e.features[0].geometry.coordinates.slice();
    let name = e.features[0].properties.name;
    
    // Ensure that if the map is zoomed out such that multiple
    // copies of the feature are visible, the popup appears
    // over the copy being pointed to.
    while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
      coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
    }
    
    new mapboxgl.Popup()
      .setLngLat(coordinates)
      .setHTML(name)
      .addTo(map);
  });

  // Change the cursor to a pointer when the mouse is over the places layer.
  map.on('mouseenter', 'staff', function () {
    map.getCanvas().style.cursor = 'pointer';
  });
  
  // Change it back to a pointer when it leaves.
  map.on('mouseleave', 'staff', function () {
    map.getCanvas().style.cursor = '';
  });

  let hoveredStateId = null;

  // When the user moves their mouse over the state-fill layer, we'll update the
  // feature state for the feature under the mouse.
  map.on("mousemove", "staff", function(e) {
    if (e.features.length > 0) {
      if (hoveredStateId) {
        map.setFeatureState({source: 'staff_locations', id: hoveredStateId}, { "hover": false});
      }
      hoveredStateId = e.features[0].id;
      map.setFeatureState({source: 'staff_locations', id: hoveredStateId}, { "hover": true});
    }

    console.log('hoveredStateId :', hoveredStateId);

    console.log(map.getFeatureState({source: 'staff_locations', id: hoveredStateId}));
    console.log('83 hovered: ', map.getFeatureState({source: 'staff_locations', id: 83}));

  });

  // When the mouse leaves the state-fill layer, update the feature state of the
  // previously hovered feature.
  map.on("mouseleave", "staff", function() {
    if (hoveredStateId) {
      map.setFeatureState({source: 'staff_locations', id: hoveredStateId}, { "hover": false});
    }
    hoveredStateId =  null;

    console.log('hoveredStateId :', hoveredStateId);

    console.log(map.getFeatureState({source: 'staff_locations', id: hoveredStateId}));
    console.log('83 hovered: ', map.getFeatureState({source: 'staff_locations', id: 83}));

  });

</script>