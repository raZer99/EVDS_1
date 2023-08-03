<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Emergency Vehicle Dispatch System</title>
</head>
<body>
<header class="text-gray-600 body-font">
    <div class="container mx-auto flex flex-wrap p-5 flex-col md:flex-row items-center">
        <a class="flex title-font font-medium items-center text-gray-900 mb-4 md:mb-0">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-linecap="round"
                 stroke-linejoin="round" stroke-width="2" class="w-10 h-10 text-white p-2 bg-black-500 rounded-full"
                 viewBox="0 0 24 24">
                <path
                    d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
            </svg>
            <span class="ml-3 text-xl">Emergency Vehicle Dispatch System</span>
        </a>
        <nav class="md:ml-auto flex flex-wrap items-center text-base justify-center">
            <a class="mr-5 hover:text-gray-900">Home</a>
            <a class="mr-5 hover:text-gray-900">About</a>
            <a class="mr-5 hover:text-gray-900">Services</a>
            <a class="mr-5 hover:text-gray-900">Contact</a>
        </nav>
        <button
            class="inline-flex items-center bg-gray-100 border-0 py-1 px-3 focus:outline-none hover:bg-gray-200 rounded text-base mt-4 md:mt-0">
            Login
            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                 class="w-4 h-4 ml-1" viewBox="0 0 24 24">
                <path
                    d="M5 12h14M12 5l7 7-7 7"></path>
            </svg>
        </button>
    </div>
</header>

<!-- if get request then a form will be viewed, it will have three buttons to select from once clicked, user
  location will be taken and post request will be made to the result page -->
<div class="container mx-auto flex flex-wrap p-5 flex-col md:flex-row items-center">
    <form action="dispatch.php" method="post">
        <label for="location">Location:</label>
        <input type="text" id="location" name="location" readonly><br><br>
        <input class="inline-flex text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg"
               type="submit" name="vehicle_type" value="Fire">
        <input class="inline-flex text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg"
               type="submit" name="vehicle_type" value="Police">
        <input class="inline-flex text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg"
               type="submit" name="vehicle_type" value="Ambulance">

    </form>
</div>
<script>
    // get location of user
    navigator.geolocation.getCurrentPosition(function (position) {
        var lat = position.coords.latitude;
        var long = position.coords.longitude;
        document.getElementById("location").value = lat + "," + long;
    });
</script>

<!-- if post request is sent then connect to the database -->
<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $location = $_POST["location"];
    $vehicleType = $_POST["vehicle_type"]; // Add a name attribute to your submit buttons for each vehicle type

    // Database connection details
    $host = "localhost";
    $username = "root"; // Use the root user
    $password = ""; // No password for the root user
    $dbname = "emergency_vehicle_dispatch"; // Use the correct database name

    // Create connection
    $conn = new mysqli($host, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve vehicle details of the chosen type from the database
    $sql = "SELECT id, vehicle_name, latitude, longitude, plate_number, hospital, contact_number FROM vehicles WHERE vehicle_type = '$vehicleType'";
    $result = $conn->query($sql);

    $closestVehicleId = null;
    $minDistance = PHP_INT_MAX;

    list($loc_lat, $loc_long) = explode(",", $location); // Split the location into latitude and longitude (assuming the location is in the format "latitude,longitude"
    // Calculate distances and find the closest vehicle
    while ($row = $result->fetch_assoc()) {
        list($lat, $long) = explode(",", $row["latitude"] . "," . $row["longitude"]);
        $distance = calculateDistance($lat, $long, $loc_lat, $loc_long);

        if ($distance < $minDistance) {
            $minDistance = $distance;
            $closestVehicleId = $row["id"];
            $closestVehicleName = $row["vehicle_name"];
            $closestVehiclePlate = $row["plate_number"];
            $closestVehicleHospital = $row["hospital"];
            $closestVehicleContact = $row["contact_number"];
        }
    }

    // Now you have the information of the closest vehicle, show in a table with distence in Km as integer, show borders of the table
    echo "<table border='1'>";
    echo "<tr><th>Vehicle Name</th><th>Plate Number</th><th>Hospital</th><th>Contact Number</th><th>Distance (Km)</th></tr>";
    echo "<tr><td>" . $closestVehicleName . "</td><td>" . $closestVehiclePlate . "</td><td>" . $closestVehicleHospital . "</td><td>" . $closestVehicleContact . "</td><td>" . intval($minDistance) . "</td></tr>";
    echo "</table>";
  
    $conn->close();
}

// Function to calculate distance between two coordinates using Haversine formula
function calculateDistance($lat1, $long1, $lat2, $long2) {
    $earthRadius = 6371; // Radius of the Earth in kilometers
    $latDiff = deg2rad($lat2 - $lat1);
    $longDiff = deg2rad($long2 - $long1);

    $a = sin($latDiff / 2) * sin($latDiff / 2) +
        cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
        sin($longDiff / 2) * sin($longDiff / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    $distance = $earthRadius * $c; // Distance in kilometers
    return $distance;
}
?>

</body>
</html>

