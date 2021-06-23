<?php

use SubjectsPlus\Control\Querier;

$subsubcat = "";

// Commented out to make sure that view_map is the only thing being checked for access to this view
// $subcat = "admin";
$page_title = "Staff Map";

include_once(__DIR__ . "/../includes/header.php");

//check if they have the view_map permission
if (! (isset($_SESSION['view_map']) && $_SESSION['view_map'] == 1) ){
  // shouldn't be here
  echo "<br /><br /><p class=\"box\">" . _("You are not authorized to view this.") . "</p>";
  include("../includes/footer.php");
  exit;
};

// ----- FETCH INFO FROM THE DB + PASS IT TO THE CLIENT-SIDE JS ----------------------------------------------------

global $stats_encryption_enabled;
global $AssetPath;
$db = new Querier;
$connection = $db->getConnection();

$faculty_only = isset( $_GET["fac_only"] ) && $_GET["fac_only"] == 1;

if ( $stats_encryption_enabled ) {
  $statement = $connection->prepare('SELECT staff_id,
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
    lat_long,
    title AS position
  FROM staff
  WHERE lat_long LIKE "%,%"
  AND active = 1');
	if ( $faculty_only ) {
		$statement = $connection->prepare('SELECT staff_id,
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
      lat_long,
      title AS position
    FROM staff
    WHERE lat_long LIKE "%,%"
    AND ptags LIKE "%librarian%"');
  }
} else {
  $statement = $connection->prepare('SELECT staff_id,
    CONCAT( fname, " ", lname ) AS fullname,
    email,
    emergency_contact_name,
    emergency_contact_relation,
    emergency_contact_phone,
    CONCAT( street_address, "<br/>", city, " ",state, " ", zip ) AS full_address,
    home_phone,
    cell_phone,
    lat_long,
    title AS position
  FROM staff
  WHERE lat_long LIKE "%,%"
  AND active = 1');
	if ( $faculty_only ) {
    $statement = $connection->prepare('SELECT staff_id,
      CONCAT( fname, " ", lname ) AS fullname,
      email,
      emergency_contact_name,
      emergency_contact_relation,
      emergency_contact_phone,
      CONCAT( street_address, "<br/>", city, " ", state, " ", zip ) AS full_address,
      home_phone,
      cell_phone,
      lat_long,
      title AS position
    FROM staff
    WHERE lat_long LIKE "%,%"
    AND ptags LIKE "%librarian%"');
	}
}

$statement->execute();
$staffArray = $statement->fetchAll();

include_once(__DIR__ . "/../includes/footer.php");

print "
  <script src='" . $AssetPath . "jquery/libs/mapbox-gl.js'></script>
  <link href='" . $AssetPath . "css/shared/mapbox-gl.css' rel='stylesheet' />

  <style>
    .mapboxgl-popup-content{
      width: 300px;
      border-radius: 5px;
      padding: 20px;
    }
    .mapboxgl-popup-content button {
      font-size: large;
      padding: inherit;
    }
    @media (min-width: 1280px){
      #map {
        width: 100%;
      }
      .pluslet {
        width: 50%;
      }
    }
    @media (max-width: 1280px){
      #map {
        width: 100%;
      }
      .pluslet {
        width: 100%;
      }
    }
    .switch-field {
      display: flex;
      margin-bottom: 36px;
      overflow: hidden;
    }    
    .switch-field input {
      position: absolute !important;
      clip: rect(0, 0, 0, 0);
      height: 1px;
      width: 1px;
      border: 0;
      overflow: hidden;
    }    
    .switch-field label {
      background-color: #e4e4e4;
      color: rgba(0, 0, 0, 0.6);
      font-size: 14px;
      line-height: 1;
      text-align: center;
      padding: 8px 16px;
      margin-right: -1px;
      border: 1px solid rgba(0, 0, 0, 0.2);
      box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.1);
      transition: all 0.1s ease-in-out;
    }    
    .switch-field label:hover {
      cursor: pointer;
    }    
    .switch-field input:checked + label {
      background-color: #c03956;
      box-shadow: none;
      color: rgba(255, 255, 255, 1);
    }    
    .switch-field label:first-of-type {
      border-radius: 4px 0 0 4px;
    }    
    .switch-field label:last-of-type {
      border-radius: 0 4px 4px 0;
    }
  </style>

  <script id='markers-container'>
    let markers = [];
";

foreach ($staffArray as $key => $value) {
  if (!empty($value["lat_long"])) {
    
    if ($stats_encryption_enabled){
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
      $value["position"] =          !empty($value["position"])                    ? decryptIt($value["position"])                     : "";
      
      $value["full_address"] =      $street_address . "<br/>" . $city . " " . $state . " " . $zip;
      $value["contact"] =           $emergency_contact_name . " (" . $emergency_contact_relation . "): " . "<span>" . $emergency_contact_phone . "</span>";
    };
    
    // Put everything behind a RegExp check so we don't get junk coordinates coming through
    // Need this second RegExp check post-decryption -- even though we're doing a LIKE in the queries already --
    // to make sure we don't send junk coordinates in case data ISN'T encrypted, but flag is set to ON

    $coords_regexp_match = preg_match('/^(\-?\d+(\.\d+)?),(\-?\d+(\.\d+)?)$/', $value["lat_long"]);
    
    if($coords_regexp_match){
      print "
          markers.push(
            {
              type: 'Feature',
              id: " . $value["staff_id"] . ",
              geometry: {
                type: 'Point',
                coordinates: [" . $value["lat_long"] . "].reverse(),
              },
              properties: {
                id:                 " . $value["staff_id"] . ",
                fullname:           '" . $value["fullname"] . "',
                full_address:       '" . $value["full_address"] . "',
                email:              '" . $value["email"] . "',
                home_phone:         '" . $value["home_phone"] . "',
                cell_phone:         '" . $value["cell_phone"] . "',
                position:           '" . $value["position"] . "',
                contactName:        '" . $value["emergency_contact_name"] . "',
                contactRelation:    '" . $value["emergency_contact_relation"] . "',
                contactPhone:       '" . $value["emergency_contact_phone"] . "'
              }
            }
          )
      ";
    }
  }
};

print "
  if(!markers.length){
    alert(`No valid staff location coordinates found.\n\nPlease check the staff table in your database, and your encryption settings.`);
  };
  </script>
";
?>

<div class="pure-g" style="margin-top: 25px; padding: 0 50px;">
  <div class="pure-u-3-5 map-container">
    <div id='map' style='height: 75vh; float: right; border: 5px solid #FFFFFF; box-shadow: 0px 0px 10px #000000; border-radius: 10px;'></div>
  </div>
  <div class="pure-u-2-5 pluslet-container">
    <?php
      $staff_map_infobox = "
        <p>Please note that this information is confidential, and should not be shared. It is intended to help with emergency situation planning.</p>
        <hr style='width: 50%;'/>
        <h3><i class='fa fa-map-marker' aria-hidden='true'></i>&nbsp;&nbsp;Marker Type</h3>
        <div class='switch-field'>
          <input onclick='toggleMarker()' type='radio' id='radio-one' name='marker_select' value='pulsing' checked/>
          <label for='radio-one'>Pulsing</label>
          <input onclick='toggleMarker()' type='radio' id='radio-two' name='marker_select' value='static' />
          <label for='radio-two'>Static</label>
        </div>
      ";
      makePluslet(_("Staff Disaster Map"), $staff_map_infobox , "no_overflow", true, 'margin-left: 25px; margin-top: 0; box-shadow: 0px 0px 10px #000000;');
    ?>
  </div>
</div>

<!-- ===== START OF MAPBOX JS SCRIPT TO DRAW MAP =============================================================== -->

<script id="map-drawing">

  <?php
    global $mapbox_access_token;
    if( ! isset($mapbox_access_token) ){
      print " alert(`Mapbox access token not set in:\nSite Config > API > Mapbox Public API Key\n\nMapbox requires a valid access token to display map content.`)";
    };    
    // Use home location coordinates for map centering; this is set in config.php
    global $mapbox_home_coords;
    if(isset($mapbox_home_coords) && $mapbox_home_coords != ''){
      // If $mapbox_home_coords is set and not empty, split it on comma
      $mapbox_home_coords = preg_split("/,/", $mapbox_home_coords);
    } else {
      // If $mapbox_home_coords isn't set or is an empty string, substitute UM coords as default
      $mapbox_home_coords = [25.721266,-80.278496];
      print " alert(`Home coordinates not set; using default home coordinates for University of Miami (25.721266, -80.278496).\n\nYou can change this setting on the Admin > Config Site > API page.`) ";
    };

  ?>

  mapboxgl.accessToken = "<?php echo $mapbox_access_token ?>";
  // MapBox uses longitude + latitude, while we use lat-long, so have to reverse the array order
  let homeCoords = [<?php echo $mapbox_home_coords[1] ?>,<?php echo $mapbox_home_coords[0] ?>];
  
  if(!homeCoords.length){
    // Backstop in case above PHP validations somehow don't work to check for valid home coords
    alert(`Home coordinates not set; using default home coordinates for University of Miami (25.721266, -80.278496).\n\nYou can change this setting on the Admin > Config Site > API page.`);
  };

  const map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/light-v10',
    center: homeCoords,
    zoom: 9
  });

  // Start of code for pulsing red dot as marker
  let dotSize = 75;

  const pulsingDot = {
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

  // ------------------------------------------------- SETTING UP MARKER CHANGE SELECTION UI FEATURES AND FUNCTION
  
  let selectedMarkerString = $("input[name='marker_select']:checked").val();

  const markerMappings = {
    'pulsing': pulsingDot,
    'static': 'static'
  }

  let selectedMarker = markerMappings[selectedMarkerString];

  const toggleMarker = ()=>{
    selectedMarkerString = $("input[name='marker_select']:checked").val();
    selectedMarker = markerMappings[selectedMarkerString];
    map.setLayoutProperty('staff', 'icon-image', selectedMarkerString);
  };

  const formatPhoneNumber = (phoneNumberString)=> {
    let cleaned = ('' + phoneNumberString).replace(/\D/g, '')
    let match = cleaned.match(/^(\d{3})(\d{3})(\d{4})$/)
    if (match) {
      return '(' + match[1] + ') ' + match[2] + '-' + match[3]
    }
    return null
  };
  
  // ------------------------------------------------- END OF MARKER SELECTION CODE
  
  // Set up static elements of the map on completion of map loading
  map.on('load', function () {
    const staticMarkerFilePath = "<?php echo $AssetPath . 'images/simple-pin-pink.png' ?>";

    map.loadImage(staticMarkerFilePath, function(error, image) {
      if (error) throw error;
      map.addImage('static', image, { pixelRatio: 4 });
    });

    map.addImage('pulsing', pulsingDot, { pixelRatio: 2 });

    // Create default 'home' location icon based on home coordinates
    // let homeLocation = new mapboxgl.Marker()
    //   .setLngLat(homeCoords)
    //   .addTo(map);
    
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
        "icon-image": selectedMarkerString,
        "icon-ignore-placement": true,
        "text-field": "{title}",
        "text-font": ["Open Sans Semibold", "Arial Unicode MS Bold"],
        "text-offset": [0, 0.6],
        "text-anchor": "top"
      }
    });
  });

  // Set up click event for staff member icons
  map.on('click', 'staff', function (e) {
    let staffMember = e.features[0].properties;
    let coordinates = e.features[0].geometry.coordinates.slice();
    let assetPath = '<?php echo $AssetPath; ?>';

    let emergencyContact;
    if(!!staffMember.contactName){
      emergencyContact = `
      <strong>${staffMember.contactName}</strong> (${staffMember.contactRelation}):<span style="position: absolute; right: 0px; padding-right: inherit;">${staffMember.contactPhone ? formatPhoneNumber(staffMember.contactPhone) : `<i>Not on File</i>`}</span>
      `;
    } else {
      emergencyContact = `<i>Not on File</i>`;
    };

    let popupHtml = `
      <div style="display: flex;">
        <div id="headshot-container" style="width: 50%; align-content: center;">
          <img src="${assetPath}users/_${staffMember.email.split("@")[0]}/headshot_large.jpg" style="width: 80%; height: auto; border-radius: 5px;">
        </div>
        <div id="name-title-container" style="width: 50%; margin-top: 10px; align-content: center;">
          <h2 style="text-align: right; margin-bottom: 5px;">${staffMember.fullname}</h2>
          <p style="text-align: right; margin-top: 0px;">${staffMember.position}</p>
        </div>
      </div>
      <p>${staffMember.full_address}</p>
      <strong>Email:</strong><span style="position: absolute; right: 0px; padding-right: inherit;">${staffMember.email ? staffMember.email : `<font color="gray">None Found</font>`}</span>
      <br />
      <strong>Home Phone:</strong><span style="position: absolute; right: 0px; padding-right: inherit;">${staffMember.home_phone ? formatPhoneNumber(staffMember.home_phone) : `<i>Not on File</i>`}</span>
      <br />
      <strong>Cell Phone:</strong><span style="position: absolute; right: 0px; padding-right: inherit;">${staffMember.cell_phone ? formatPhoneNumber(staffMember.cell_phone) : `<i>Not on File</i>`}</span>
      <hr>
      <h3>Emergency Contact:</strong></h3>
      ${emergencyContact}
    `;
    
    // Ensure that if the map is zoomed out such that multiple
    // copies of the feature are visible, the popup appears
    // over the copy being pointed to.
    while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
      coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
    }
    
    new mapboxgl.Popup()
      .setLngLat(coordinates)
      .setHTML(popupHtml)
      .setMaxWidth('300px')
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

</script>