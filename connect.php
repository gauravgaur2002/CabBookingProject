<?php
if (isset($_POST['save'])) {
  // Get form data and sanitize
  $ownerName = filter_input(INPUT_POST, 'OwnerName', FILTER_SANITIZE_STRING);
  $mobileNumber = filter_input(INPUT_POST, 'EnterMobileNumber', FILTER_SANITIZE_STRING);
  $vehicleNumber = filter_input(INPUT_POST, 'VehicleNumber', FILTER_SANITIZE_STRING);
  $licenseNumber = filter_input(INPUT_POST, 'LicenseNumber', FILTER_SANITIZE_STRING);
  $aadharNumber = filter_input(INPUT_POST, 'AadharNumber', FILTER_SANITIZE_STRING);

  // Validate form data
  if (empty($ownerName) || empty($mobileNumber) || empty($vehicleNumber) || empty($licenseNumber) || empty($aadharNumber)) {
    $message = "All fields are required.";
  } elseif (!preg_match('/^[0-9]{10}$/', $mobileNumber)) {
    $message = "Invalid mobile number. Must be 10 digits.";
  } else {
    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = ""; // Empty password for XAMPP
    $dbname = "cabservice";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO cabdetails (owner_name, mobile_number, vehicle_number, license_number, aadhar_number) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $ownerName, $mobileNumber, $vehicleNumber, $licenseNumber, $aadharNumber);

    // Execute statement
    if ($stmt->execute()) {
      $message = "Cab details attached successfully!";
    } else {
      $message = "Error attaching cab details: " . $stmt->error;
    }

    // Close connection
    $stmt->close();
    $conn->close();
  }
} else {
  $message = "An error occurred. Please try again.";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Attach Cab Result</title>
</head>
<body>

<p><?php echo $message; ?></p>

</body>
</html>
