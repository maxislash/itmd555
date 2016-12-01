<?php
function Connection(){

  require 'vendor/autoload.php';

  use Aws\Rds\RdsClient;

  $client = RdsClient::factory(array(
        'version' => 'latest',
        'region'  => 'us-west-2'
  ));

  $result = $client->describeDBInstances(array(
        'DBInstanceIdentifier' => 'db-itmo544-mdescos',
  ));

  $endpoint = $result->search('DBInstances[0].Endpoint.Address');
  #echo $endpoint;
  $server=$endpoint;
  $user="mdescos";
  $pass="mdescos-assword";
  $db="itmt593";

  $connection = mysql_connect($server, $user, $pass);

  if (!$connection) {
    die('MySQL ERROR: ' . mysql_error());
  }

  mysql_select_db($db) or die( 'MySQL ERROR: '. mysql_error() );

  return $connection;
}
?>
