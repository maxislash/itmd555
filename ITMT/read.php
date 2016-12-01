<?php
  include("connect.php");

  $link=Connection();
  $link->real_query("SELECT * FROM sensor");
  $res = $link->use_result();
  //$result=mysql_query("SELECT * FROM `sensors` ORDER BY `timeStamp` DESC",$link); ?>

  <html>
    <head>
      <title>Sensor Data</title>
    </head>
    <body>
    <h1>Luminosity / temperature sensor readings</h1>
    <table border="1" cellspacing="1" cellpadding="1">
    <tr>
    <td>&nbsp;Timestamp&nbsp;</td>
	<td>&nbsp;ID&nbsp;</td>
    <td>&nbsp;Luminosity&nbsp;</td>
    <td>&nbsp;Temperature&nbsp;</td>
	<td>&nbsp;X&nbsp;</td>
	<td>&nbsp;Y&nbsp;</td>
	<td>&nbsp;Z&nbsp;</td>
	<td>&nbsp;Alert&nbsp;</td>
    </tr>

   <?php
     if($result!==FALSE){
       while($row = $res->fetch_assoc()) {
         printf("<tr><td> &nbsp;%s </td><td> &nbsp;%s&nbsp;
         </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp;
         </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp;
         </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp;</tr>",
         $row["timeStamp"], $row["id"], $row["luminosity"],
         $row["temperature"], $row["x"], $row["y"], $row["z"], $row["alert"]);
     }

     //mysql_free_result($result);
     $link->close();
   }
   ?>
   </table> </body> </html>
