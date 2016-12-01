<?php

  include("connect.php");

  $link=Connection();
  $id=$_GET["id"];
  $luminosity=$_GET["luminosity"];
  $temperature=$_GET["temperature"];
  $x=$_GET["x"];
  $y=$_GET["y"];
  $z=$_GET["z"];
  $alert=$_GET["alert"];
  //$query = "INSERT INTO `tutorial_database`.`sensors` (`timeStamp`, `id`, `luminosity`, `temperature`, `x`, `y`, `z`, `alert`)
  //VALUES (CURRENT_TIMESTAMP, '".$id."','".$luminosity."','".$temperature."','".$x."','".$y."','".$z."','".$alert."')";


  // code to insert new record
  /* Prepared statement, stage 1: prepare */
  if (!($stmt = $link->prepare("INSERT INTO sensor(timeStamp, id, luminosity, temperature, x, y, z, alert) VALUES (CURRENT_TIMESTAMP,?,?,?,?,?,?,?)"))) {
    echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
  }
  // prepared statements will not accept literals (pass by reference) in bind_params, you need to declare variables
  $stmt->bind_param("iidiiii",$id,$luminosity,$temperature,$x,$y,$z,$alert);

  if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  //printf("%d Row inserted.\n", $stmt->affected_rows);


  /* explicit close recommended */
  $stmt->close();


  //mysql_query($query,$link);
  //mysql_close($link);
  $link->close();
  
// Send SNS notification to the customer of succeess.
       $sns = new Aws\Sns\SnsClient([
          'version' => 'latest',
          'region'  => 'us-west-2'
       ]);

       $snsresult = $sns->listTopics([
       ]);
       $topicArn = $snsresult['Topics'][0]['TopicArn'];

       $sns->publish([
         'TopicArn' => $topicArn,
         'Message' => 'success'
       ]);

  header("Location: read.php");

?>
