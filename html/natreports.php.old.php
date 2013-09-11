<?php
  include '../header.php';
?>
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
          "aaSorting": [[ 0, "asc" ]],
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
    </script>

    <title>National Reports</title>
  </head>

  <body>
    <div id="background"></div>
    <div class="back"><p><a href="launchreports.php">Back</a></p></div>
    <div class="logout"><p><a href="?logout=">Logout</a></p></div>
    <h1 class="top" style="clear:both">National Survey Report</h1>
    <?php checkUser($validUser); ?>

    <div class="nonDT">
      <?php
        $count = "select count(distinct a.id) as 'TOTAL', count(CASE civicrm_activity.status_id WHEN 2 then 1 ELSE NULL END) as 'COMPLETED',
          count(distinct b.id) as 'VOLUNTEERS' from civicrm_activity
          inner join civicrm_activity_target on civicrm_activity.id = civicrm_activity_target.activity_id
          inner join civicrm_contact a on civicrm_activity_target.target_contact_id = a.id 
          left join civicrm_activity_assignment on civicrm_activity.id = civicrm_activity_assignment.activity_id
          left join civicrm_contact b on civicrm_activity_assignment.assignee_contact_id = b.id
          where activity_date_time > '2013-08-01' and activity_type_id = 32;";
        if ($countResult = $mysqli->query($count)) {
          while ($row = mysqli_fetch_assoc($countResult)) {
            echo "<p>Contacts Exposed:   " . $row["TOTAL"] . "</p>\n";
            echo "<p>Contacts Completed: " . $row["COMPLETED"] . "</p>\n";
            echo "<p>Volunteers Helping: " . $row["VOLUNTEERS"] . "</p>\n";
          }
        }
      ?>
    </div>

    <div>
      <h2>Priority Report</h2>
      <table id="sortable" cellpadding="0" cellspacing="0">
        <thead class="center">
          <tr>
            <th>School</th>
            <th>Hot</th>
            <th>Medium</th>
            <th>Mild</th>
            <th>Not Interested</th>
            <th>N/A</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody> 
          <?php 
              $sumHot = 0; $sumMedium = 0; $sumMild = 0; $sumNo = 0; $sumNA = 0; $sumTotal = 0;
              $priority = "select school.`organization_name` as 'SCHOOL', count(CASE civicrm_activity.priority_id WHEN 1 then 1 ELSE NULL END) as 'HOT', 
                  count(CASE civicrm_activity.priority_id WHEN 2 then 1 ELSE NULL END) as 'MEDIUM',
                  count(CASE civicrm_activity.priority_id WHEN 3 then 1 ELSE NULL END) as 'MILD',
                  count(CASE civicrm_activity.priority_id WHEN 4 then 1 ELSE NULL END) as 'NOT INTERESTED',
                  count(CASE civicrm_activity.priority_id WHEN 5 then 1 ELSE NULL END) as 'N/A',
                  count(*) as 'TOTAL' from civicrm_activity
                  inner join civicrm_activity_target on civicrm_activity.id = civicrm_activity_target.activity_id
                  inner join civicrm_contact a on civicrm_activity_target.target_contact_id = a.id 
                  left join civicrm_activity_assignment on civicrm_activity.id = civicrm_activity_assignment.activity_id
                  left join civicrm_contact b on civicrm_activity_assignment.assignee_contact_id = b.id
                  inner join civicrm_relationship on a.id = civicrm_relationship.contact_id_a
                  inner join civicrm_contact school on civicrm_relationship.`contact_id_b` = school.id
                  where activity_date_time > '2013-08-01' and activity_type_id = 32 and civicrm_relationship.`relationship_type_id` = 10
                  and civicrm_relationship.is_active = 1
                  group by school.`organization_name`;";
              if ($result = $mysqli->query($priority)) {
                while ($row = mysqli_fetch_assoc($result)) {
                  ?>
                    <tr>
                      <td><?php print($row["SCHOOL"]); ?></td>
                      <td><?php print($row["HOT"]); $sumHot += intval($row["HOT"]); ?></td>
                      <td><?php print($row["MEDIUM"]); $sumMedium += intval($row["MEDIUM"]); ?></td>
                      <td><?php print($row["MILD"]); $sumMild += intval($row["MILD"]); ?></td>
                      <td><?php print($row["NOT INTERESTED"]); $sumNo += intval($row["NOT INTERESTED"]); ?></td>
                      <td><?php print($row["N/A"]); $sumNA += intval($row["N/A"]); ?></td>
                      <td><?php print($row["TOTAL"]); $sumTotal += intval($row["TOTAL"]); ?></td>
                    </tr>
                  <?php
                }
              }
          ?>
        </tbody>
        <tfoot>     
          <tr>
            <td>Totals</td>
            <td><?php print($sumHot); ?></td>
            <td><?php print($sumMedium); ?></td>
            <td><?php print($sumMild); ?></td>
            <td><?php print($sumNo); ?></td>
            <td><?php print($sumNA); ?></td>
            <td><?php print($sumTotal); ?></td>
          </tr>
        </tfoot>
      </table>
    </div>

    <div>
      <h2>Follow-up Progress</h2>
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
              $status = "select school.`organization_name` as 'SCHOOL', count(CASE civicrm_activity.status_id WHEN 4 then 1 ELSE NULL END) as 'UNCONTACTED', 
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
                and civicrm_relationship.is_active = 1
                group by school.`organization_name`;";
              if ($result2 = $mysqli->query($status)) {
                while ($row = mysqli_fetch_assoc($result2)) {
                  ?>
                    <tr>
                      <td><?php print($row["SCHOOL"]); ?></td>
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
        <tfoot>     
          <tr>
            <td>Totals</td>
            <td><?php print($sumUn); ?></td>
            <td><?php print($sumIn); ?></td>
            <td><?php print($sumCom); ?></td>
            <td><?php print($sumSTotal); ?></td>
          </tr>
        </tfoot>
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
              $rejoice = "select `civicrm_value_rejoiceable_16`.`rejoiceable_143` as TYPE, count(*) as COUNT from civicrm_activity
                inner join `civicrm_value_rejoiceable_16` on `civicrm_activity`.id = `civicrm_value_rejoiceable_16`.`entity_id`
                where activity_date_time > '2013-08-01' and activity_type_id = 47 and `civicrm_value_rejoiceable_16`.`related_survey_160` is not null
                and `civicrm_value_rejoiceable_16`.`rejoiceable_143` is not null
                group by `civicrm_value_rejoiceable_16`.`rejoiceable_143`;";
              if ($result3 = $mysqli->query($rejoice)) {
                while ($row = mysqli_fetch_assoc($result3)) {
                    $totals[$row["TYPE"]] = $row["COUNT"];
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
              }
          ?>
        </tbody>
      </table>
      <p><?php print($rejTotal); ?> Rejoiceables Nationwide</p>
    </div>

    <br><br>

    <div class="nonDT">
      <?php 
              $rTotals = array(); $badTotal = 0; $goodTotal = 0;
              $titles = array(0 => "Bad Info", 1 => "No Response", 2=> "No Longer Interested", 5 => "Request Fulfilled Digital", 
                7 => "Request Fulfilled Face-to-Face", 8 => "Digital Interaction and Wants to Continue", 10 => "Face-to-face Interaction and Wants to Continue");
              $results = "select civicrm_activity.engagement_level as 'TYPE', count(*) as 'COUNT' from civicrm_activity
                inner join civicrm_activity_target on civicrm_activity.id = civicrm_activity_target.activity_id
                inner join civicrm_contact a on civicrm_activity_target.target_contact_id = a.id
                where activity_date_time > '2013-08-01' and activity_type_id = 32
                group by civicrm_activity.engagement_level;";
              if ($result3 = $mysqli->query($results)) {
                while ($row = mysqli_fetch_assoc($result3)) {
                  if(isset($row["TYPE"])){
                    $rTotals[$row["TYPE"]] = $row["COUNT"];
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
              }
          ?>
        </tbody>
      </table>
    </div>

    <br><br><br>
  </body>
</html>
