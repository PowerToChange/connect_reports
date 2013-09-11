<?php
  //Initializes CAS and database calls
  include 'header.php';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Connect Reports</title>
<link rel="stylesheet" href="css/home.css" type="text/css" media="screen"/>
<script src="html/js/jquery-1.9.1.js"></script>
<link rel="stylesheet" href="html/css/colorbox.css" />

<script src="html/js/jquery.colorbox-min.js"></script>
<script src="html/js/jquery.colorbox.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $(".inline").colorbox({inline:true, width:"50%"});
  });
</script>
</head>

<body>
<div id="header">
  <div class="right_link"><a href="?logout="> Logout</a></div>
</div>
<div class="title">P2C Launch Survey Results</div>
<?php checkUser($validUser); ?>

<div class="welcome">Welcome <?php echo $user["firstName"] . " " . $user["lastName"]; ?>, please choose one:</div>
<div id="options">
  <a href="html/schoolreport.php"><div class="button-link">School Reports</div></a>
  <a href="html/nationalreport.php"><div class="button-link">National Report</div></a>
</div>
<div class="clear"></div>
<div id="national_info">

      <?php
      $totalExposed = 0; $totalInMotion = 0; $totalVolunteers = 0;
      $count = "select count(distinct a.id) as 'TOTAL', count(CASE civicrm_activity.status_id WHEN 2 then 1 ELSE NULL END) as 'COMPLETED',
        count(CASE civicrm_activity.status_id WHEN 3 then 1 ELSE NULL END) as 'IN PROGRESS',
        count(distinct b.id) as 'VOLUNTEERS' from civicrm_activity
        inner join civicrm_activity_target on civicrm_activity.id = civicrm_activity_target.activity_id
        inner join civicrm_contact a on civicrm_activity_target.target_contact_id = a.id 
        left join civicrm_activity_assignment on civicrm_activity.id = civicrm_activity_assignment.activity_id
        left join civicrm_contact b on civicrm_activity_assignment.assignee_contact_id = b.id
        where activity_date_time > '2013-08-01' and activity_type_id = 32 and priority_id <> 4";
      if ($countResult = $mysqli->query($count)) {
        while ($row = mysqli_fetch_assoc($countResult)) {
          $totalExposed = $row["TOTAL"];
          $totalInMotion = $row["COMPLETED"] + $row["IN PROGRESS"];
          $totalVolunteers = $row["VOLUNTEERS"];
        }
      }
      ?>
  
      <h1>National Report Overview</h1>
       <div class="container">
      <div class="box">
        <h2><?php echo $totalExposed; ?></h2>
        <p>Interested Contacts</p>
      </div>
      
      <div class="box">
        <h2><?php echo $totalInMotion; ?></h2>
        <p>Contacts In Motion</p>
      </div>
    
      <div class="box">
        <h2><?php echo $totalVolunteers; ?></h2>
        <p>Volunteers Helping</p>
      </div>
      <a class='inline' href="#nat_report" onmouseover="this.style.opacity=0.8;this.filters.alpha.opacity=80" onmouseout="this.style.opacity=1;this.filters.alpha.opacity=100" style="opacity: 1;"><img src="html/images/questionmark.png" / style=" border:none;" ></a>
        </div>
</div>
<div style='display:none'>
  <div id='nat_report' style='padding:10px; background:#fff;'>
      <p><strong>National Numbers:</strong></p>
     <p style="font-family:Arial, Helvetica, sans-serif;">Interested Contacts: Total number of contacts with priority not set to "Not Interested". </p>
     <p style="font-family:Arial, Helvetica, sans-serif;">Contacts In Motion: Total number of contacts with status "In Progress" or "Completed" and priority not set to "Not Interested". </p>
     <p style="font-family:Arial, Helvetica, sans-serif;">Volunteers: Total number of people assigned to follow-up a contact. </p>
  </div>
</div>
</body>
</html>
