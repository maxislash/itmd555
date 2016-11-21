
<?php

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

   $link = mysqli_connect($endpoint,"nighter","nighter-password","nighters") or die("Error " . mysqli_error($link));

   /* check connection */
   if (mysqli_connect_errno()) {
       printf("Connect failed: %s\n", mysqli_connect_error());
       exit();
   }

   $delete_table = 'DROP TABLE IF EXISTS list';
   $del_tbl = $link->query($delete_table);
   if ($delete_table) {
        //echo "Table items has been deleted";
   }
   else {
        //echo "error!!";
   }

   $create_table = 'CREATE TABLE IF NOT EXISTS items  
   (
      id INT NOT NULL AUTO_INCREMENT,
      name VARCHAR(255) NOT NULL,
      city VARCHAR(255) NOT NULL,
      music VARCHAR(255) NOT NULL,
      PRIMARY KEY(id)
   )';

   $create_tbl = $link->query($create_table);
   if ($create_table) {
        //echo "\n Table has created \n";
   }
   else {
       // echo "error creation table!";
   }

   /* Prepared statement, stage 1: prepare */
   if (!($stmt = $link->prepare("INSERT INTO items (id, name, city, music) VALUES (NULL,?,?,?)"))) {
     //   echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
   }

   $names = array('apollo', 'frog', 'hop');
   $cities = array('chi','eva','ny');
   $musics = array('blues','rock','pop');

   foreach($names as $name) {
     $cell = array_search($name, $names);
     $city = $cities[$cell];
     $music = $musics[$cell];

     $stmt->bind_param("sss",$name,$city,$music);

     if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
     }
     //printf("%d Row inserted.\n", $stmt->affected_rows);
   }

   /* explicit close recommended */
   $stmt->close();
   $link->real_query("SELECT * FROM items");
   $res = $link->use_result();
   //echo "Result set order...\n";
   while ($row = $res->fetch_assoc()) {
      echo $row['id'] . " " . $row['name']. " " . $row['city']. " " . $row['music'];
      echo "\n \n";
   }

   $link->close();

?>
