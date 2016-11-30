<?php

/*
 * Following code will list all the nightclubs
 */

//Connect to the database
  require 'vendor/autoload.php';

   use Aws\Rds\RdsClient;
   $client = RdsClient::factory(array(
        'version' => 'latest',
        'region'  => 'us-west-2'
   ));

   $result = $client->describeDBInstances(array(
        'DBInstanceIdentifier' => 'db-nighters',
   ));

   $endpoint = $result->search('DBInstances[0].Endpoint.Address');

   $db = mysqli_connect($endpoint,"nighter","nighter-password","nighters") or die("Error " . mysqli_error($db));

   /* check connection */
   if (mysqli_connect_errno()) {
       printf("Connect failed: %s\n", mysqli_connect_error());
       exit();
   }

// array for JSON response
  $response = array();

// get all products from items  table
  $db->real_query("SELECT * from items");
  $result = $db->use_result();

  $response["nightclubs"] = array();

  while($row = $result->fetch_assoc()) {
    $nightclub = array();
    $nightclub["id"] = $row["id"];
    $nightclub["name"] = $row["name"];
    $nightclub["city"] = $row["city"];
    $nightclub["music"] = $row["music"];

     // push single product into final response array
     array_push($response["nightclubs"], $nightclub);
    }
    // success
    $response["success"] = 1;

    // echoing JSON response
    echo json_encode($response);
  $db->close();
?>
