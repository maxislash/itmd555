<?php

require 'vendor/autoload.php';
/*
 * Following code will create a new club row
 * All product details are read from HTTP Post Request
 */

// array for JSON response
$response = array();

// check for required fields
if (isset($_POST['name']) && isset($_POST['city']) && isset($_POST['music']) && isset($_POST['url'])) {

    $name = $_POST['name'];
    $city = $_POST['city'];
    $music = $_POST['music'];
    $url = $_POST['url'];

//   use Aws\Rds\RdsClient;

  // INSERT SQL record of job information
  $rdsclient = new Aws\Rds\RdsClient([
    'region'            => 'us-west-2',
    'version'           => 'latest'
  ]);
  $rdsresult = $rdsclient->describeDBInstances([
    'DBInstanceIdentifier' => 'db-nighters'
  ]);
  $endpoint = $rdsresult['DBInstances'][0]['Endpoint']['Address'];
  //echo $endpoint . "\n";
  $link = mysqli_connect($endpoint,"nighter","nighter-password","nighters") or die("Error " . mysqli_error($link));
  /* check connection */
  if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
  }
  // code to insert new record
  /* Prepared statement, stage 1: prepare */
  if (!($stmt = $link->prepare("INSERT INTO items(id, name, city, music, url) VALUES (NULL,?,?,?,?)"))) {
    echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
  }
  // prepared statements will not accept literals (pass by reference) in bind_params, you need to declare variables
  $stmt->bind_param("ssss",$name,$city,$music,$url);


  if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    // failed to insert row
    $response["success"] = 0;
    $response["message"] = "Oops! An error occurred.";

    // echoing JSON response
    echo json_encode($response);
  }
  else {
      // successfully inserted into database
    $response["success"] = 1;
    $response["message"] = "Product successfully created.";

        // echoing JSON response
    echo json_encode($response);
  }

  $stmt->close();
  $link->close();
}
?>
