<?php
  include 'config/dbconstants.php';
  include 'config/pulse_constants.php';

  //CAS WORK
  include 'CAS/cas_handler.php';
  $user = phpCAS::getAttributes();

  //DATEBASE WORK
  $mysqli = new mysqli(CONNECT_HOST, CONNECT_USER, CONNECT_PASSWD, CONNECT_DB);
  if (mysqli_connect_errno()) {
    throw new Exception($mysqli->connect_error);
  }

  //PERMISSIONS CHECK
  $testIds = array(); $isStaff = false; $validUser = false;
  $url ="https://pulse.powertochange.com/api/ministry_involvements?guid=4EF08047-D57E-44AD-6CBA-2DE769FC443B&api_key=087b1cd5-1629-4a1f-9693-be403067dbjk";
  //$url ="https://pulse.powertochange.com/api/ministry_involvements?guid=" . $user["ssoGuid"] . "&api_key=" . PULSE_API_KEY;
  $xml = simplexml_load_file($url);
  foreach ($xml->ministry_involvement as $minInfo) {
    if($minInfo->role[0]['role_id'] != 8){
      $validUser = true;
    }
    if(strcmp($minInfo->role[0]['type'], "StaffRole") == 0){
      $isStaff = true;
    }
    foreach ($minInfo->ministry[0]->campus as $campus){
      $testIds[] = intval($campus['campus_id']);
    }
  }
  $testIds = array_unique($testIds);

  function checkUser($valid){
    print_r($user);
    if(!$valid){
      echo "<h2 style=\"color: red; text-align: center\">No Leader or Staff Privileges</h2>";
      echo "</body></html>";
      exit;
    }
  }

?>
