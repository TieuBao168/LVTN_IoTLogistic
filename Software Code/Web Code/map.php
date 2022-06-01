<?php
require_once 'config.php';
?>
<?php
	
		$dbhost = 'localhost';
	$dbname = 'highalln_iot_project';
	$dbuser = 'highalln_iot_project';
	$dbpass = 'Hihomhinh99';
		$dbcon = new PDO("mysql:host={$dbhost};dbname={$dbname}",$dbuser,$dbpass);
		$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt=$dbcon->prepare("SELECT * FROM info1");
	$stmt->execute();
	$json = [];
			while($row = $stmt-> fetch(PDO::FETCH_ASSOC))
			{
				extract($row);		
			}
			
	?>
<?php
	
		$dbhost = 'localhost';
	$dbname = 'highalln_iot_project';
	$dbuser = 'highalln_iot_project';
	$dbpass = 'Hihomhinh99';
		$dbcon = new PDO("mysql:host={$dbhost};dbname={$dbname}",$dbuser,$dbpass);
		$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt=$dbcon->prepare("SELECT * FROM location1");
	$stmt->execute();
	$json = [];
			while($row = $stmt-> fetch(PDO::FETCH_ASSOC))
			{
				extract($row);		
			}
			
	?>	
	
<html>

<head>
    <title> Draw route between two locations</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script defer
            src="https://maps.googleapis.com/maps/api/js?libraries=places&language=<?= $_SESSION['lang'] ?>&key=AIzaSyBgOJatl4prb-WP9-7sXaptQQkwF9F4-FM"
            type="text/javascript"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">

</head>

<body>
<div class="container">
        <!-- Language -->
        

        <div class="row">
        <div class="col-sm-4">
            <!-- form -->
            <form id="distance_form">
                

                <div class="hidden">
                    <input class="form-control" id="from_places" placeholder="nhập nơi bắt đầu"/>
                    <input id="origin" name="origin" required="" type="hidden" value ="<?php echo $xuat_phat; ?>"/>                    
                </div>
                    
                <div class="hidden"><label></label>
                    <input class="form-control" id="to_places" placeholder="nhập điểm đến"/>
                    <input id="destination" name="destination" required="" type="hidden" value ="<?php echo $dich_den; ?>" /></div>

                <div class="form-group">
                    
                    
                        <option id="travel_mode" name="travel_mode" value="DRIVING"></option>
                       
            
                </div>

                 <input class="btn btn-primary" type="submit" value="Hiển thị" id="submitBtn" />

            </form>
        </div>

        <div class="col-12">
         <div id="map" style="height: 100vh; width: 100%" ></div>
         <div id = "sample" style = "width:580px; height:400px;"></div>
        </div>
            <!-- result -->
        <div class="col-sm-4">
            <div style="margin-left: 123px;" id="result" class="hide">
                <ul class="list-group">
                    
                    <br>
                    <br>
                    <li id="in_kilo" class="list-group-item d-flex justify-content-between align-items-center"></li> <br>
                    <br>
                    <br>

                    <li id="duration_text" class="list-group-item d-flex justify-content-between align-items-center"></li> <br>
                    <br>

                </ul>
            </div>
        </div>
           
        </div>
        
    </div>


</div>

<script>
    $(function () {
       

        var origin, destination, map;

        // add input listeners
        google.maps.event.addDomListener(window, 'load', function (listener) {
            setDestination();
            initMap();
        });

        // init or load map
        function initMap() {

            var myLatLng = {
                lat: <?php echo $kinh_do; ?>,
                lng:  <?php echo $vi_do; ?>
            };
            map = new google.maps.Map(document.getElementById('map'), {zoom: 16, center: myLatLng,});
            new google.maps.Marker({
    position: myLatLng,
    map,
    title: "Hello World!",
  });

        }
         function loadMap() {
            
            var mapOptions = {
               center:new google.maps.LatLng(19.373341, 78.662109),
               zoom:7
            }
                
            var map = new google.maps.Map(document.getElementById("sample"),mapOptions);
            
            
         }    
        function setDestination() {
            var from_places = new google.maps.places.Autocomplete(document.getElementById('from_places'));
            var to_places = new google.maps.places.Autocomplete(document.getElementById('to_places'));

            google.maps.event.addListener(from_places, 'place_changed', function () {
                var from_place = from_places.getPlace();
                var from_address = from_place.formatted_address;
                $('#origin').val(from_address);
            });

            google.maps.event.addListener(to_places, 'place_changed', function () {
                var to_place = to_places.getPlace();
                var to_address = to_place.formatted_address;
                $('#destination').val(to_address);
            });


        }

        function displayRoute(travel_mode, origin, destination, directionsService, directionsDisplay) {
            directionsService.route({
                origin: origin,
                destination: destination,
                travelMode: travel_mode,
                avoidTolls: true
            }, function (response, status) {
                if (status === 'OK') {
                    directionsDisplay.setMap(map);
                    directionsDisplay.setDirections(response);
                } else {
                    directionsDisplay.setMap(null);
                    directionsDisplay.setDirections(null);
                    alert('Could not display directions due to: ' + status);
                }
            });
        }

        // calculate distance , after finish send result to callback function
        function calculateDistance(travel_mode, origin, destination) {

            var DistanceMatrixService = new google.maps.DistanceMatrixService();
            DistanceMatrixService.getDistanceMatrix(
                {
                    origins: [origin],
                    destinations: [destination],
                    travelMode: google.maps.TravelMode[travel_mode],
                    unitSystem: google.maps.UnitSystem.IMPERIAL, // miles and feet.
                    // unitSystem: google.maps.UnitSystem.metric, // kilometers and meters.
                    avoidHighways: false,
                    avoidTolls: false
                }, save_results);
        }

        // save distance results
        function save_results(response, status) {

            if (status != google.maps.DistanceMatrixStatus.OK) {
                $('#result').html(err);
            } else {
                var origin = response.originAddresses[0];
                var destination = response.destinationAddresses[0];
                if (response.rows[0].elements[0].status === "ZERO_RESULTS") {
                    $('#result').html("Sorry , not available to use this travel mode between " + origin + " and " + destination);
                } else {
                    var distance = response.rows[0].elements[0].distance;
                    var duration = response.rows[0].elements[0].duration;
                    var distance_in_kilo = distance.value / 1000; // the kilo meter
                    var distance_in_mile = distance.value / 1609.34; // the mile
                    var duration_text = duration.text;
                    appendResults(distance_in_kilo, distance_in_mile, duration_text);
                    sendAjaxRequest(origin, destination, distance_in_kilo, distance_in_mile, duration_text);
                }
            }
        }

        // append html results

            var them_vao_day = document.getElementById("them_vao_day")
            var h1 = document.createElement("h1")
            
           

        function appendResults(distance_in_kilo, distance_in_mile, duration_text) {
            $("#result").removeClass("hide");
           
            $('#in_kilo').html(h1.innerHTML = "Khoảng cách (km) : " + distance_in_kilo.toFixed(2));
            $('#duration_text').html(h1.innerHTML = "Thời gian : " + duration_text + "</span>");
        }

        // send ajax request to save results in the database
        function sendAjaxRequest(origin, destination, distance_in_kilo, distance_in_mile, duration_text) {
            var username =   $('#username').val();
            var travel_mode =  $('#travel_mode').find(':selected').text();
            $.ajax({
                url: 'common.php',
                type: 'POST',
                data: {
                    username,
                    travel_mode,
                    origin,
                    destination,
                    distance_in_kilo,
                    distance_in_mile,
                    duration_text
                },
                success: function (response) {
                    console.info(response);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        }

        // on submit  display route ,append results and send calculateDistance to ajax request
        $('#distance_form').submit(function (e) {
            e.preventDefault();
            console.log("form trigger");
            var origin = $('#origin').val();
            var destination = $('#destination').val();
            var travel_mode = $('#travel_mode').val();
            var directionsDisplay = new google.maps.DirectionsRenderer({'draggable': false});
            var directionsService = new google.maps.DirectionsService();
           displayRoute(travel_mode, origin, destination, directionsService, directionsDisplay);
            calculateDistance(travel_mode, origin, destination);
        });

         $("#distance_form").submit();

    });

    // get current Position
    function getCurrentPosition() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(setCurrentPosition);
        } else {
            alert("Geolocation is not supported by this browser.")
        }
    }

    // get formatted address based on current position and set it to input
    function setCurrentPosition(pos) {
        var geocoder = new google.maps.Geocoder();
        var latlng = {lat: parseFloat(pos.coords.latitude), lng: parseFloat(pos.coords.longitude)};
        geocoder.geocode({ 'location' :latlng  }, function (responses) {
            console.log(responses);
            if (responses && responses.length > 0) {
                $("#origin").val(responses[1].formatted_address);
                $("#from_places").val(responses[1].formatted_address);
                //    console.log(responses[1].formatted_address);
            } else {
                alert("Cannot determine address at this location.")
            }
        });
    }



</script>

  

</body>

</html>
