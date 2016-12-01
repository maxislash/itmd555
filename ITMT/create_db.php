<?php
  include("connect.php");

  $link=Connection();

  $create_table = 'CREATE TABLE IF NOT EXISTS sensor 
    (
 	timeStamp TIMESTAMP NOT NULL PRIMARY KEY,
 	id int(2) NOT NULL,
 	luminosity int(4) NOT NULL,
 	temperature float(5,2) NOT NULL,
 	x int(4) NOT NULL,
 	y int(4) NOT NULL,
 	z int(4) NOT NULL,
 	alert int(2) NOT NULL
    )';

   $create_tbl = $link->query($create_table);
   if ($create_table) {
        //echo "\n Table has created \n";
   }
   else {
       // echo "error creation table!";
   }

   $link->close();
?>
