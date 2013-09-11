<?php
  include 'header.php';
  $schoolID = $_POST["school"] ?: $testIds[0];
  $surveyID = $_POST["survey"] ?: 4;
?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="helper/jquery.dataTables_themeroller.css">
    <link rel="stylesheet" type="text/css" href="helper/jquery-ui.css">

    <script src="helper/jquery.js"></script>
    <script src="helper/jquery-ui.js"></script>
    <script src="helper/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf-8">
      $(document).ready(function() {
        $('#sortable').dataTable( {
          "aaSorting": [],
          "bJQueryUI": true,
          "sPaginationType": "full_numbers"
        } );
      } );
  
      $(document).ready(function() {
        $('#sortable2').dataTable( {
          "aaSorting": [[ 0, "asc" ]],
          "bJQueryUI": true,
          "sPaginationType": "full_numbers"
        } );
      } );

      $(document).ready(function() {
        $('#normal').dataTable( {
          "bJQueryUI": true,
          "sPaginationType": "full_numbers",
          "bSort": false
        } );
      } );
    </script>

    <title>School Reports</title>
  </head>

  <body>
    <div id="background"></div>
    <div class="back"><p><a href="launchreports.php">Back</a></p></div>
    <div class="logout"><p><a href="?logout=">Logout</a></p></div>
    <h1 class="top" style="clear:both">School Report</h1>
    <?php checkUser($validUser); ?>

    <div class="center">
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
                if(intval($row["ID"]) == $schoolID){
                  $selected = "selected";
                }
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
                if(intval($row["ID"]) == $surveyID){
                  $selected = "selected";
                }
                echo "<option value=\"" . $row["ID"] . "\" " . $selected . ">" . $row["SURVEY"] . "</option>\n";
              }
            }
          ?>
        </select>
        <input type="submit" value="GO">
      </form>
    </div>

    <div>
      <h2>Priority Report</h2>
      <table id="sortable" cellpadding="0" cellspacing="0">
        <thead class="center">
          <tr>
            <th>Priority</th>
            <th>Uncontacted</th>
            <th>In Progress</th>
            <th>Completed</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody> 
          <?php 
              $totals = array();
              $sumPUn = 0; $sumPIn = 0; $sumPCom = 0; $sumPTotal = 0;
              $priorityTitles = array("","Hot", "Medium", "Mild", "Not Interested", "N/A");
              if ($priStmt = $mysqli->prepare("select civicrm_activity.`priority_id` as PRIORITY, 
                count(CASE civicrm_activity.status_id WHEN 4 then 1 ELSE NULL END) as 'UNCONTACTED', 
                count(CASE civicrm_activity.status_id WHEN 3 then 1 ELSE NULL END) as 'IN PROGRESS',
                count(CASE civicrm_activity.status_id WHEN 2 then 1 ELSE NULL END) as 'COMPLETED',
                count(*) as 'TOTAL' from civicrm_activity
                inner join civicrm_activity_target on civicrm_activity.id = civicrm_activity_target.activity_id
                inner join civicrm_contact a on civicrm_activity_target.target_contact_id = a.id 
                inner join civicrm_relationship on a.id = civicrm_relationship.contact_id_a
                inner join civicrm_contact school on civicrm_relationship.`contact_id_b` = school.id
                where activity_date_time > '2013-08-01' and activity_type_id = 32 and civicrm_relationship.`relationship_type_id` = 10
                and school.id = ? and civicrm_activity.`source_record_id` = ?
                group by civicrm_activity.`priority_id`")){
                $priStmt->bind_param("ii", $schoolID, $surveyID);
                $priStmt->execute();
                $priStmt->bind_result($priority_bind, $uncontacted_bind, $inProgress_bind, $completed_bind, $total_bind);
                while ($priStmt->fetch()) {
                  $totals[$priority_bind] = array("UNCONTACTED" => $uncontacted_bind, "IN PROGRESS" => $inProgress_bind, "COMPLETED" => $completed_bind, "TOTAL" => $total_bind);
                }
                for($i = 1; $i <= 5; $i++){ 
                  $pRow = array("UNCONTACTED" => 0, "IN PROGRESS" => 0, "COMPLETED" => 0, "TOTAL" => 0);
                  if(isset($totals[$i])){
                    $pRow = $totals[$i];
                  }
                  ?>
                    <tr>
                      <td><?php print($priorityTitles[$i]); ?></td>
                      <td><?php print($pRow["UNCONTACTED"]); $sumPUn += intval($pRow["UNCONTACTED"]); ?></td>
                      <td><?php print($pRow["IN PROGRESS"]); $sumPIn += intval($pRow["IN PROGRESS"]); ?></td>
                      <td><?php print($pRow["COMPLETED"]); $sumPCom += intval($pRow["COMPLETED"]); ?></td>
                      <td><?php print($pRow["TOTAL"]); $sumPTotal += intval($pRow["TOTAL"]); ?></td>
                    </tr>
                  <?php
                }
              }
          ?>
        </tbody>
        <tfoot>     
          <tr>
            <td>Total</td>
            <td><?php print($sumPUn); ?></td>
            <td><?php print($sumPIn); ?></td>
            <td><?php print($sumPCom); ?></td>
            <td><?php print($sumPTotal); ?></td>
          </tr>
        </tfoot>
      </table>
    </div>

    <div>
      <h2>Follow-up Progress by Person</h2>
      <table id="sortable2" cellpadding="0" cellspacing="0">
        <thead class="center">
          <tr>
            <th>School</th>
            <th>Uncontacted</th>
            <th>In Progress</th>
            <th>Completed</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody> 
          <?php 
              $sumUn = 0; $sumIn = 0; $sumCom = 0; $sumSTotal = 0;
              if ($stuStmt = $mysqli->prepare("select b.`sort_name` as 'NAME', 
                count(CASE civicrm_activity.status_id WHEN 4 then 1 ELSE NULL END) as 'UNCONTACTED', 
                count(CASE civicrm_activity.status_id WHEN 3 then 1 ELSE NULL END) as 'IN PROGRESS',
                count(CASE civicrm_activity.status_id WHEN 2 then 1 ELSE NULL END) as 'COMPLETED',
                count(*) as 'TOTAL' from civicrm_activity
                inner join civicrm_activity_target on civicrm_activity.id = civicrm_activity_target.activity_id
                inner join civicrm_contact a on civicrm_activity_target.target_contact_id = a.id 
                left join civicrm_activity_assignment on civicrm_activity.id = civicrm_activity_assignment.activity_id
                left join civicrm_contact b on civicrm_activity_assignment.assignee_contact_id = b.id
                inner join civicrm_relationship on a.id = civicrm_relationship.contact_id_a
                inner join civicrm_contact school on civicrm_relationship.`contact_id_b` = school.id
                where activity_date_time > '2013-08-01' and activity_type_id = 32 and civicrm_relationship.`relationship_type_id` = 10
                and school.id = ? and civicrm_activity.`source_record_id` = ?                
                group by b.`sort_name`;")){
                $stuStmt->bind_param("ii", $schoolID, $surveyID);
                $stuStmt->execute();
                $stuStmt->bind_result($name_bind, $uncontacted_bind, $inProgress_bind, $completed_bind, $total_bind);
                while ($stuStmt->fetch()) {
                  $row = array("NAME" => $name_bind, "UNCONTACTED" => $uncontacted_bind, "IN PROGRESS" => $inProgress_bind, "COMPLETED" => $completed_bind, "TOTAL" => $total_bind);
                  if(!isset($row["NAME"])){
                    $row["NAME"] = "Unassigned";
                  }
                  ?>
                    <tr>
                      <td><?php print($row["NAME"]); ?></td>
                      <td><?php print($row["UNCONTACTED"]); $sumUn += intval($row["UNCONTACTED"]); ?></td>
                      <td><?php print($row["IN PROGRESS"]); $sumIn += intval($row["IN PROGRESS"]); ?></td>
                      <td><?php print($row["COMPLETED"]); $sumCom += intval($row["COMPLETED"]); ?></td>
                      <td><?php print($row["TOTAL"]); $sumSTotal += intval($row["TOTAL"]); ?></td>
                    </tr>
                  <?php
                }
              }
          ?>
        </tbody>
      </table>
    </div>
    <br>

    <div class="nonDT">
      <h2>Rejoiceables</h2>
      <table cellspacing="0px">
        <tbody> 
          <?php
              $totals = array(); $rejTotal = 0;
              $titles = array("","Interaction", "Spiritual Conversation", "Gospel Presentation", "Indicated Decision", "Shared Spirit-Filled Life");
              if ($rejStmt = $mysqli->prepare("select civicrm_value_rejoiceable_16.rejoiceable_143 as 'TYPE', count(*) as 'COUNT' from civicrm_activity
                inner join civicrm_value_rejoiceable_16 on civicrm_activity.id = civicrm_value_rejoiceable_16.entity_id
                inner join civicrm_activity_target on civicrm_activity.id = civicrm_activity_target.activity_id
                inner join civicrm_contact a on civicrm_activity_target.target_contact_id = a.id 
                inner join civicrm_relationship on a.id = civicrm_relationship.contact_id_a
                inner join civicrm_contact school on civicrm_relationship.`contact_id_b` = school.id
                where activity_date_time > '2013-08-01' and activity_type_id = 47 and civicrm_value_rejoiceable_16.related_survey_160 is not null
                and civicrm_value_rejoiceable_16.rejoiceable_143 is not null and school.id = ? and civicrm_value_rejoiceable_16.related_survey_160 = ?
                group by civicrm_value_rejoiceable_16.rejoiceable_143")){
                $rejStmt->bind_param("ii", $schoolID, $surveyID);
                $rejStmt->execute();
                $rejStmt->bind_result($type_bind, $count_bind);
                while ($rejStmt->fetch()) {
                  $totals[$type_bind] = $count_bind;
                }
                for($i = 1; $i <= 5; $i++){ 
                  $val = 0;
                  if(isset($totals[$i])){
                    $val = $totals[$i];
                  }
                    ?>
                      <tr <?php if($i % 2 != 0) { print("class=\"odd\"");}?> >
                        <td><?php print($titles[$i]); ?></td>
                        <td style="padding-left:10px"></td>
                        <td><?php print($val); $rejTotal += intval($val);?></td>
                      </tr>
                    <?php
                }
                $rejStmt->close();
              }
          ?>
        </tbody>
      </table>
      <p><?php print($rejTotal); ?> Rejoiceables</p>
    </div>

    <br><br>

    <div class="nonDT">
      <?php
              $rTotals = array(); $badTotal = 0; $goodTotal = 0;
              $titles = array(0 => "Bad Info", 1 => "No Response", 2=> "No Longer Interested", 5 => "Request Fulfilled Digital", 
                7 => "Request Fulfilled Face-to-Face", 8 => "Digital Interaction and Wants to Continue", 10 => "Face-to-face Interaction and Wants to Continue");
              if ($resStmt = $mysqli->prepare("select civicrm_activity.engagement_level as TYPE, count(*) as COUNT from civicrm_activity
                inner join civicrm_activity_target on civicrm_activity.id = civicrm_activity_target.activity_id
                inner join civicrm_contact a on civicrm_activity_target.target_contact_id = a.id
                inner join civicrm_relationship on a.id = civicrm_relationship.contact_id_a
                inner join civicrm_contact school on civicrm_relationship.`contact_id_b` = school.id 
                where activity_date_time > '2013-08-01' and activity_type_id = 32
                and civicrm_activity.engagement_level is not null
                and school.id = ? and civicrm_activity.`source_record_id` = ?
                group by civicrm_activity.engagement_level")){
                $resStmt->bind_param("ii", $schoolID, $surveyID);
                $resStmt->execute();
                $resStmt->bind_result($type_bind, $count_bind);
                while ($resStmt->fetch()) {
                  if(isset($type_bind)){
                    $rTotals[$type_bind] = $count_bind;
                  }
                }
      ?>
      <h2>Results</h2>
      <p>Reached Out But...</p>
      <table cellspacing="0px">
        <tbody>
          <?php
                for($i = 0; $i <= 2; $i++){
                  if(!isset($rTotals[$i])){
                    $rTotals[$i] = 0;
                  }
                    ?>
                      <tr <?php if($i % 2 != 0) { print("class=\"odd\"");}?> >
                        <td><?php print($titles[$i]); ?></td>
                        <td style="padding-left:10px"></td>
                        <td><?php print($rTotals[$i]); $badTotal += intval($rTotals[$i]);?></td>
                      </tr>
                    <?php
                    unset($titles[$i]);
                }
                ?>
                  <tr class="odd">
                    <td>Total</td>
                    <td style="padding-left:10px"></td>
                    <td><?php print($badTotal); ?></td>
                  </tr>
        </tbody>
      </table>
      <br>
      <p>Met And...</p>
      <table cellspacing="0px">
        <tbody>
          <?php
                $j = 0;
                foreach ($titles as $key => &$val) {
                  if(!isset($rTotals[$key])){
                    $rTotals[$key] = 0;
                  }
                    ?>
                      <tr <?php if($j % 2 != 0) { print("class=\"odd\"");}?> >
                        <td><?php print($titles[$key]); ?></td>
                        <td style="padding-left:10px"></td>
                        <td><?php print($rTotals[$key]); $goodTotal += intval($rTotals[$key]);?></td>
                      </tr>
                    <?php
                    $j += 1;
                }
                ?>
                  <tr>
                    <td>Total</td>
                    <td style="padding-left:10px"></td>
                    <td><?php print($goodTotal); ?></td>
                  </tr>
                <?php
                $resStmt->close();
              }
          ?>
        </tbody>
      </table>
    </div>
    <br><br><br>
  </body>
</html>
