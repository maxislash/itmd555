
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
   if (!($stmt = $link->prepare("INSERT INTO items (id, email, phone, filename, s3rawurl, s3finishedurl, status, receipt) VALUES (NULL,?,?,?,?,?,?,?)"))) {
     //   echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
   }

   $email = 'mdescos@hawk.iit.edu';
   $phone = '3127316493';
   $status = 0;

   $filenames = array('mountain-bw.jpg', 'eartrumpet-bw.jpg', 'Knuth-bw.jpg', 'mountain.jpg','eartrumpet.jpg', 'Knuth.jpg');

   foreach($urls as $url) {
     $cell = array_search($url, $urls);
     $filename = $filenames[$cell];
     if($cell < 3) {
       $s3rawurl = $url;
       $s3finishedurl = '';
     }
     else {
       $s3rawurl= '';
       $s3finishedurl = $url;
     }
     $receipt = md5($url);

     $stmt->bind_param("sssssis",$email,$phone,$filename,$s3rawurl, $s3finishedurl, $status, $receipt);

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
      echo $row['id'] . " " . $row['email']. " " . $row['phone']. " " . $row['filename']. " " . $row['s3rawurl']. " " . $row['s3finishedurl']. " " . $row['status']. " " .$row['receipt'];
      echo "\n \n";
   }

   $link->close();

?>
