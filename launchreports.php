<?php
  //Initializes CAS and database calls
  include 'header.php';
?>

<html>
  <head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>P2C Survey Reports</title>
  </head>

  <body>
    <div id="background"></div>
    <div class="logout"><p><a href="?logout=">Logout</a></p></div>
    <h1 class="top" style="clear:both">P2C Launch Survey Reports</h1>
    <?php checkUser($validUser); ?>


    <div class="choice">
      <h1>School Report</h1>
      <form name="input" id="reportForm" action="schoolreports.php" method="post">
        <select id="school" name="school">
          <?php
            $schoolExists = false;
            $schoolQuery = "select school.`organization_name` as 'SCHOOL', school.`id` as 'ID', school.external_identifier as 'PULSEID' from civicrm_activity
              inner join civicrm_activity_target on civicrm_activity.id = civicrm_activity_target.activity_id
              inner join civicrm_contact a on civicrm_activity_target.target_contact_id = a.id 
              inner join civicrm_relationship on a.id = civicrm_relationship.contact_id_a
              inner join civicrm_contact school on civicrm_relationship.`contact_id_b` = school.id
              where activity_date_time > '2013-08-01' and civicrm_activity.activity_type_id = 32 and civicrm_relationship.`relationship_type_id` = 10
              group by school.`organization_name`;";
            if ($schools = $mysqli->query($schoolQuery)) {
              while ($row = mysqli_fetch_assoc($schools)) {
                $selected = "";
                if($isStaff || in_array($row["PULSEID"], $testIds)){
                  $schoolExists = true;
                  echo "<option value=\"" . $row["ID"] . "\" " . $selected . ">" . $row["SCHOOL"] . "</option>\n";
                }
              }
            }
            if(!$schoolExists){
              echo "<option value=\"0\" selected>No Schools with Surveys</option>\n";
            }
          ?>
        </select>
        <select id="survey" name="survey">
          <?php
            $surveyQuery = "select civicrm_survey.title as 'SURVEY', civicrm_survey.id as 'ID' from civicrm_activity
              inner join civicrm_survey on civicrm_activity.source_record_id = civicrm_survey.id
              where activity_date_time > '2013-08-01' and civicrm_activity.activity_type_id = 32
              group by `civicrm_survey`.id;";
            if ($surveys = $mysqli->query($surveyQuery)) {
              while ($row = mysqli_fetch_assoc($surveys)) {
                $selected = "";
                echo "<option value=\"" . $row["ID"] . "\" " . $selected . ">" . $row["SURVEY"] . "</option>\n";
              }
            }
          ?>
        </select>
        <input type="submit" value="GO">
      </form>
    </div>

    <a href="natreports.php">
      <div class="choice">
        <h1>National Report</h1>
      </div>
    </a>
  </body>
</html>