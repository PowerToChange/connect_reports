<?php
  //Initializes CAS and database calls
  include '../header.php';
  $onlyInt = $_POST["intStudents"] ? true : false;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<title>National Report</title>
	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>
	<link href="../html/css/custom-theme/jquery-ui-1.10.3.custom.css" rel="stylesheet">
    	<link rel="stylesheet" href="css/jquery.dataTables_themeroller.css" />
	<link rel="stylesheet" href="css/colorbox.css" />
	<script src="../html/js/jquery-1.9.1.js"></script>
	<script src="../html/js/jquery-ui-1.10.3.custom.js"></script>
	<script src="../html/js/jquery.colorbox-min.js"></script>
	<script src="../html/js/jquery.colorbox.js"></script>
     	<script src="../html/js/jquery.dataTables.js"></script>    
  <script>
$(document).ready(function(){
	$(".inline").colorbox({inline:true, width:"50%"});
	
	    $('#dataTable').dataTable( {
          "aaSorting": [[ 0, "asc" ]],
          "bJQueryUI": true,
          "sPaginationType": "full_numbers"
        } );
		$('#dataTable2').dataTable( {
          "aaSorting": [[ 0, "asc" ]],
          "bJQueryUI": true,
          "sPaginationType": "full_numbers"
        } );
	});
	
$(document).ready(function(){
	$(".inline").colorbox({inline:true, width:"50%"});
});
	// Called directly with HTML
$.colorbox({html:"<h1>Welcome</h1>"});

		
// Accordion - Expand All #01
$(function () {
    $("#accordion").accordion({
        heightStyle: "content",
        collapsible:true,
        active:false
    });
    var icons = $( "#accordion" ).accordion( "option", "icons" );
    $('.open').click(function () {
        $('.ui-accordion-header').removeClass('ui-corner-all').addClass('ui-accordion-header-active ui-state-active ui-corner-top').attr({
            'aria-selected': 'true',
            'tabindex': '0'
        });
        $('.ui-accordion-header-icon').removeClass(icons.header).addClass(icons.headerSelected);
        $('.ui-accordion-content').addClass('ui-accordion-content-active').attr({
            'aria-expanded': 'true',
            'aria-hidden': 'false'
        }).show();
        $(this).attr("disabled","disabled");
        $('.close').removeAttr("disabled");
    });
    $('.close').click(function () {
        $('.ui-accordion-header').removeClass('ui-accordion-header-active ui-state-active ui-corner-top').addClass('ui-corner-all').attr({
            'aria-selected': 'false',
            'tabindex': '-1'
        });
        $('.ui-accordion-header-icon').removeClass(icons.headerSelected).addClass(icons.header);
        $('.ui-accordion-content').removeClass('ui-accordion-content-active').attr({
            'aria-expanded': 'false',
            'aria-hidden': 'true'
        }).hide();
        $(this).attr("disabled","disabled");
        $('.open').removeAttr("disabled");
    });
    $('.ui-accordion-header').click(function () {
        $('.open').removeAttr("disabled");
        $('.close').removeAttr("disabled");
        
    });
});

	</script>
	</head>

	<body>
<div id="header">
      <div class="title">National Report<?php if($onlyInt){ echo " - International Students"; } ?></div>
   <div class="right_link"><a href="../index.php">Home</a> <font color="#3399FF">|</font> <a href="schoolreport.php">School Reports </a><font color="#3399FF">|</font><a href="?logout="> Logout</a> </div>
    </div>
<div class="clear"></div>
<?php checkUser($validUser); ?>

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
  if($onlyInt){
    $count = "select count(distinct a.id) as 'TOTAL', count(CASE civicrm_activity.status_id WHEN 2 then 1 ELSE NULL END) as 'COMPLETED',
      count(CASE civicrm_activity.status_id WHEN 3 then 1 ELSE NULL END) as 'IN PROGRESS',
      count(distinct b.id) as 'VOLUNTEERS' from civicrm_activity
      inner join civicrm_activity_target on civicrm_activity.id = civicrm_activity_target.activity_id
      inner join civicrm_contact a on civicrm_activity_target.target_contact_id = a.id
      inner join civicrm_value_student_demographics_7 on a.id = civicrm_value_student_demographics_7.entity_id 
      left join civicrm_activity_assignment on civicrm_activity.id = civicrm_activity_assignment.activity_id
      left join civicrm_contact b on civicrm_activity_assignment.assignee_contact_id = b.id
      where activity_date_time > '2013-08-01' and activity_type_id = 32 and priority_id <> 4 
      and civicrm_value_student_demographics_7.i_am_an_international_student_61 = \"yes\"";
  }
  if ($countResult = $mysqli->query($count)) {
    while ($row = mysqli_fetch_assoc($countResult)) {
      $totalExposed = $row["TOTAL"];
      $totalInMotion = $row["COMPLETED"] + $row["IN PROGRESS"];
      $totalVolunteers = $row["VOLUNTEERS"];
    }
  }
?>

<div id="national_info"><div class="overview">
  <div class="line"></div>
  <div class="box">
    <h2><?php echo $totalExposed; ?></h2>
    <p>Interested Contacts</p>
  </div>
    <div class="line"> </div>
  <div class="box">
    <h2><?php echo $totalInMotion; ?></h2>
    <p>Contacts In Motion</p>
  </div>
  <div class="line"> </div>
  <div class="box">
    <h2><?php echo $totalVolunteers; ?></h2>
    <p>Volunteers Helping</p>
  </div><a class='inline' href="#nat_report" onmouseover="this.style.opacity=0.8;this.filters.alpha.opacity=80" onmouseout="this.style.opacity=1;this.filters.alpha.opacity=100" style="opacity: 1;"><img src="images/questionmark.png" / style=" border:none;" ></a></div>

<div id="choose"><div class="container">
  <form name="reportForm" id="reportForm" action="nationalreport.php" method="post">
    <div class="int_box">
      <input type="checkbox" id="intStudents" name="intStudents" value="true" <?php if($onlyInt){echo "checked";}?>>
      <label for="intStudents">  Only International Students</label>
    </div>
    <div class="update_link" onClick="document.forms['reportForm'].submit();">View</div>
  </form>
</div></div>

</div>
<div class="accordion-expand-holder"> <a class="open">Expand All</a> <font color="#3399FF"> |</font><a class="close">Collapse All</a> </div>
<div id="accordion">
      <h3>1. Priority Report<a class='inline' href="#priority_report" onmouseover="this.style.opacity=0.8;this.filters.alpha.opacity=80" onmouseout="this.style.opacity=1;this.filters.alpha.opacity=100" style="opacity: 1;"><img src="images/questionmark.png" / style="margin:0 0 0 10px; border:none;" ></a></h3>
      <div class="text">
    
    <table id="dataTable" cellpadding="0" cellspacing="0">
  <thead>
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
      if($onlyInt){
        $priority = "select school.`organization_name` as 'SCHOOL', count(CASE civicrm_activity.priority_id WHEN 1 then 1 ELSE NULL END) as 'HOT', 
          count(CASE civicrm_activity.priority_id WHEN 2 then 1 ELSE NULL END) as 'MEDIUM',
          count(CASE civicrm_activity.priority_id WHEN 3 then 1 ELSE NULL END) as 'MILD',
          count(CASE civicrm_activity.priority_id WHEN 4 then 1 ELSE NULL END) as 'NOT INTERESTED',
          count(CASE civicrm_activity.priority_id WHEN 5 then 1 ELSE NULL END) as 'N/A',
          count(*) as 'TOTAL' from civicrm_activity
          inner join civicrm_activity_target on civicrm_activity.id = civicrm_activity_target.activity_id
          inner join civicrm_contact a on civicrm_activity_target.target_contact_id = a.id 
          inner join civicrm_value_student_demographics_7 on a.id = civicrm_value_student_demographics_7.entity_id 
          left join civicrm_activity_assignment on civicrm_activity.id = civicrm_activity_assignment.activity_id
          left join civicrm_contact b on civicrm_activity_assignment.assignee_contact_id = b.id
          inner join civicrm_relationship on a.id = civicrm_relationship.contact_id_a
          inner join civicrm_contact school on civicrm_relationship.`contact_id_b` = school.id
          where activity_date_time > '2013-08-01' and activity_type_id = 32 and civicrm_relationship.`relationship_type_id` = 10
          and civicrm_relationship.is_active = 1 and civicrm_value_student_demographics_7.i_am_an_international_student_61 = \"yes\" 
          group by school.`organization_name`;";
      }
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

      <h3>2. Follow-up Progress<a class='inline' href="#follow_up" onmouseover="this.style.opacity=0.8;this.filters.alpha.opacity=80" onmouseout="this.style.opacity=1;this.filters.alpha.opacity=100" style="opacity: 1;"><img src="images/questionmark.png" / style="margin:0 0 0 10px; border:none;" ></a></h3>
          <div class="text">
          
  <table id="dataTable2" cellpadding="0" cellspacing="0">
  <thead>
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
      if($onlyInt){
        $status = "select school.`organization_name` as 'SCHOOL', count(CASE civicrm_activity.status_id WHEN 4 then 1 ELSE NULL END) as 'UNCONTACTED', 
          count(CASE civicrm_activity.status_id WHEN 3 then 1 ELSE NULL END) as 'IN PROGRESS',
          count(CASE civicrm_activity.status_id WHEN 2 then 1 ELSE NULL END) as 'COMPLETED',
          count(*) as 'TOTAL' from civicrm_activity
          inner join civicrm_activity_target on civicrm_activity.id = civicrm_activity_target.activity_id
          inner join civicrm_contact a on civicrm_activity_target.target_contact_id = a.id 
          inner join civicrm_value_student_demographics_7 on a.id = civicrm_value_student_demographics_7.entity_id          
          left join civicrm_activity_assignment on civicrm_activity.id = civicrm_activity_assignment.activity_id
          left join civicrm_contact b on civicrm_activity_assignment.assignee_contact_id = b.id
          inner join civicrm_relationship on a.id = civicrm_relationship.contact_id_a
          inner join civicrm_contact school on civicrm_relationship.`contact_id_b` = school.id
          where activity_date_time > '2013-08-01' and activity_type_id = 32 and civicrm_relationship.`relationship_type_id` = 10
          and civicrm_relationship.is_active = 1 and civicrm_value_student_demographics_7.i_am_an_international_student_61 = \"yes\" 
          group by school.`organization_name`;";
      }
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
  <tbody>
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
      <h3>3. Rejoiceables<a class='inline' href="#rejoiceables" onmouseover="this.style.opacity=0.8;this.filters.alpha.opacity=80" onmouseout="this.style.opacity=1;this.filters.alpha.opacity=100" style="opacity: 1;"><img src="images/questionmark.png"  style="margin:0 0 0 10px; border:none;" ></a></h3>
  <div class="text">
    <table class="nonDT" cellspacing="0px">
        <?php 
            $totals = array(); $rejTotal = 0;
            $titles = array("","Interaction", "Spiritual Conversation", "Gospel Presentation", "Indicated Decision", "Shared Spirit-Filled Life");
            $rejoice = "select `civicrm_value_rejoiceable_16`.`rejoiceable_143` as TYPE, count(*) as COUNT from civicrm_activity
              inner join `civicrm_value_rejoiceable_16` on `civicrm_activity`.id = `civicrm_value_rejoiceable_16`.`entity_id`
              where activity_date_time > '2013-08-01' and activity_type_id = 47 and `civicrm_value_rejoiceable_16`.`related_survey_152` is not null
              and `civicrm_value_rejoiceable_16`.`rejoiceable_143` is not null
              group by `civicrm_value_rejoiceable_16`.`rejoiceable_143`;";
            if($onlyInt){
              $rejoice = "select `civicrm_value_rejoiceable_16`.`rejoiceable_143` as TYPE, count(*) as COUNT from civicrm_activity
                inner join `civicrm_value_rejoiceable_16` on `civicrm_activity`.id = `civicrm_value_rejoiceable_16`.`entity_id`
                inner join civicrm_activity_target on civicrm_activity.id = civicrm_activity_target.activity_id
                inner join civicrm_contact a on civicrm_activity_target.target_contact_id = a.id 
                inner join civicrm_value_student_demographics_7 on a.id = civicrm_value_student_demographics_7.entity_id
                where activity_date_time > '2013-08-01' and activity_type_id = 47 and `civicrm_value_rejoiceable_16`.`related_survey_152` is not null
                and `civicrm_value_rejoiceable_16`.`rejoiceable_143` is not null
                and civicrm_value_student_demographics_7.i_am_an_international_student_61 = \"yes\" 
                group by `civicrm_value_rejoiceable_16`.`rejoiceable_143`;";
            }
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
    </table>
    <p class="impText"><?php print($rejTotal); ?> Rejoiceables Nationwide</p>
  </div>
      <h3>4. Results<a class='inline' href="#results" onmouseover="this.style.opacity=0.8;this.filters.alpha.opacity=80" onmouseout="this.style.opacity=1;this.filters.alpha.opacity=100" style="opacity: 1;"><img src="images/questionmark.png" style="margin:0 0 0 10px; border:none;" ></a></h3>
    <div class="text">
    <?php 
      $rTotals = array(); $badTotal = 0; $goodTotal = 0;
      $titles = array(0 => "Bad Info", 1 => "No Response", 2=> "No Longer Interested", 5 => "Request Fulfilled Digital", 
        7 => "Request Fulfilled Face-to-Face", 8 => "Digital Interaction and Wants to Continue", 10 => "Face-to-face Interaction and Wants to Continue");
      $results = "select civicrm_activity.engagement_level as 'TYPE', count(*) as 'COUNT' from civicrm_activity
        inner join civicrm_activity_target on civicrm_activity.id = civicrm_activity_target.activity_id
        inner join civicrm_contact a on civicrm_activity_target.target_contact_id = a.id
        where activity_date_time > '2013-08-01' and activity_type_id = 32
        group by civicrm_activity.engagement_level;";
      if($onlyInt){
        $results = "select civicrm_activity.engagement_level as 'TYPE', count(*) as 'COUNT' from civicrm_activity
          inner join civicrm_activity_target on civicrm_activity.id = civicrm_activity_target.activity_id
          inner join civicrm_contact a on civicrm_activity_target.target_contact_id = a.id
          inner join civicrm_value_student_demographics_7 on a.id = civicrm_value_student_demographics_7.entity_id
          where activity_date_time > '2013-08-01' and activity_type_id = 32
          and civicrm_value_student_demographics_7.i_am_an_international_student_61 = \"yes\" 
          group by civicrm_activity.engagement_level;";
      }
      if ($result3 = $mysqli->query($results)) {
        while ($row = mysqli_fetch_assoc($result3)) {
          if(isset($row["TYPE"])){
            $rTotals[$row["TYPE"]] = $row["COUNT"];
          }
        }
    ?>
      <h4>Reached Out But...</h4>
      <table class="nonDT" cellspacing="0px">
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
                  <tr>
                    <td class="impText">Total</td>
                    <td style="padding-left:10px"></td>
                    <td class="impText"><?php print($badTotal); ?></td>
                  </tr>
        </tbody>
      </table>
      <br>
      <h4>Met And...</h4>
      <table class="nonDT" cellspacing="0px">
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
                    <td class="impText">Total</td>
                    <td style="padding-left:10px"></td>
                    <td class="impText"><?php print($goodTotal); ?></td>
                  </tr>
                <?php
              }
          ?>
        </tbody>
      </table>

      <table><tr>
        <td><h4>No Follow-up Required Total</h4></td>
        <td style="padding-left:10px"></td>
        <td><h4><?php echo $rTotals[3] ?: 0; ?></h4></td>
      </tr></table>
  </div>
    </div>

<!-- This contains the hidden content for inline calls -->
<div style='display:none'>
      <div id='priority_report' style='padding:10px; background:#fff;'>
    <p><strong>Priority Report:</strong></p>
 <p style="font-family:Arial, Helvetica, sans-serif;">The breakdown each school's contacts by follow-up priority. I.e. Hot, Medium, Mild, Not Interested and N/A.</p>
  </div>
    <div id='follow_up' style='padding:10px; background:#fff;'>
    <p><strong>Follow-up Progress by Person:</strong></p>
 <p style="font-family:Arial, Helvetica, sans-serif;">The breakdown of each schools "Uncontacted", "In Process" and "Completed" contacts.</p>
  </div>
    <div id='rejoiceables' style='padding:10px; background:#fff;'>
    <p><strong>Rejoiceables:</strong></p>
   <p style="font-family:Arial, Helvetica, sans-serif;">Summary of the total rejoiceables nationwide.</p>
    </div>
      <div id='results' style='padding:10px; background:#fff;'>
    <p><strong>Results:</strong></p>
   <p style="font-family:Arial, Helvetica, sans-serif;">Summary of the follow-up results nationwide.</p>
  </div>
      <div id='nat_report' style='padding:10px; background:#fff;'>
    <p><strong>National Numbers:</strong></p>
   <p style="font-family:Arial, Helvetica, sans-serif;">Interested Contacts: Total number of contacts with priority not set to "Not Interested". </p>
   <p style="font-family:Arial, Helvetica, sans-serif;">Contacts In Motion: Total number of interested contacts with status "In Progress" or "Completed".</p>
   <p style="font-family:Arial, Helvetica, sans-serif;">Volunteers: Total number of people assigned to follow-up a contact. </p>

  </div>
</div>
	
</body>
</html>
