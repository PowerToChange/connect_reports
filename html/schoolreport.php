<?php
  include '../header.php';
  $schoolID = 0; $schoolLabel = $_POST["hiddenSchool"] ?: -1;
  $surveyID = 0; $surveyLabel = $_POST["hiddenSurvey"] ?: -1;
  $onlyInt = $_POST["intStudents"] ? true : false;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<title>School Reports</title>
	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>
    	<link rel="stylesheet" href="css/jquery.dataTables_themeroller.css" />
	<link href="../html/css/custom-theme/jquery-ui-1.10.3.custom.css" rel="stylesheet">
	<link rel="stylesheet" href="css/colorbox.css" />
             <link rel="stylesheet" type="text/css" href="css/dropdown_style.css" />

	<script src="../html/js/jquery-1.9.1.js"></script>
	<script src="../html/js/jquery-ui-1.10.3.custom.js"></script>
	<script src="../html/js/jquery.colorbox-min.js"></script>
	<script src="../html/js/jquery.colorbox.js"></script>
  <script src="../html/js/jquery.dataTables.js"></script>      
	<script type="text/javascript" src="js/modernizr.custom.79639.js"></script> 
	<noscript><link rel="stylesheet" type="text/css" href="css/noJS.css" /></noscript>
	<script>
$(document).ready(function(){
	$(".inline").colorbox({inline:true, width:"50%"});
	
	    $('#dataTable').dataTable( {
          "aaSorting": [],
          "bJQueryUI": true,
          "sPaginationType": "full_numbers"
        } );
		$('#dataTable2').dataTable( {
          "aaSorting": [],
          "bJQueryUI": true,
          "sPaginationType": "full_numbers"
        } );
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
    
    <!-- jQuery if needed -->
    <script type="text/javascript" src="js/modernizr.custom.79639.js"></script>
		<script type="text/javascript">
			
			function DropDown(el) {
				this.dd = el;
				this.placeholder = this.dd.children('span');
				this.opts = this.dd.find('ul.dropdown > li');
				this.val = '';
				this.index = -1;
				this.initEvents();
			}
			DropDown.prototype = {
				initEvents : function() {
					var obj = this;

					obj.dd.on('click', function(event){
						$(this).toggleClass('active');
						return false;
					});

					obj.opts.on('click',function(){
						var opt = $(this);
						obj.val = opt.text();
						obj.index = opt.index();
						obj.placeholder.text(obj.val);
            document.getElementById('hiddenSchool').value= obj.val;
					});
				},
				getValue : function() {
					return this.val;
				},
				getIndex : function() {
					return this.index;
				}
			}

			$(function() {

				var dd = new DropDown( $('#dd') );

				$(document).click(function() {
					// all dropdowns
					$('.wrapper-dropdown-1').removeClass('active');
					
					
				});
				

			});
			
				function DropDown2(el) {
				this.dd2 = el;
				this.placeholder = this.dd2.children('span');
				this.opts = this.dd2.find('ul.dropdown > li');
				this.val = '';
				this.index = -1;
				this.initEvents();
			}
			DropDown2.prototype = {
				initEvents : function() {
					var obj = this;

					obj.dd2.on('click', function(event){
						$(this).toggleClass('active');
						return false;
					});

					obj.opts.on('click',function(){
						var opt = $(this);
						obj.val = opt.text();
						obj.index = opt.index();
						obj.placeholder.text(obj.val);
            document.getElementById('hiddenSurvey').value= obj.val;
					});
				},
				getValue : function() {
					return this.val;
				},
				getIndex : function() {
					return this.index;
				}
			}

			$(function() {

				var dd2 = new DropDown2( $('#dd2') );

				$(document).click(function() {
					// all dropdowns
					$('.wrapper-dropdown-1').removeClass('active');
					
					
				});
				

			});
			
		</script>
    
	</head>

	<body>
<div id="header">
      <div class="title">Connect Reports: <?php echo ($schoolLabel != -1 ? $schoolLabel : "Choose a University"); ?></div>
      <div class="right_link"><a href="../index.php">Home</a> <font color="#3399FF">|</font> <a href="nationalreport.php">National Report </a><font color="#3399FF">|</font><a href="?logout="> Logout</a> </div>
    </div>
    <div class="clear"></div>
    <?php checkUser($validUser); ?>

<div id="choose"><div class="container">
<!-- Codrops top bar --><!--/ Codrops top bar -->
    <form name="reportForm" id="reportForm" action="schoolreport.php" method="post">
			<section class="main">
				<div class="wrapper-demo">
					<div id="dd" class="wrapper-dropdown-1" tabindex="1">
						<span><?php echo ($schoolLabel != -1 ? $schoolLabel : "Search University or College"); ?></span>
            <input type="hidden" value="<?php echo ($schoolLabel != -1 ? $schoolLabel : -1); ?>" id="hiddenSchool" name="hiddenSchool">
					    <ul class="dropdown" tabindex="1">
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
                      if($row["SCHOOL"] == $schoolLabel){
                        $schoolID = $row["ID"];
                      }
                      if($isStaff || in_array($row["PULSEID"], $testIds)){
                        $schoolExists = true;
                        echo "<li><a href=\"\">" . $row["SCHOOL"] . "</a></li>\n";
                      }
                    }
                  }
                  if(!$schoolExists){
                    echo "<li><a href=\"#\">No Schools with Surveys</a></li>\n";
                  }
                ?>
					    </ul>
					</div>
        </div>
      </section>
	
      <section class="main">
				<div class="wrapper-demo">
					<div id="dd2" class="wrapper-dropdown-1" tabindex="1">
						<span><?php echo ($surveyLabel != -1 ? $surveyLabel : "Search Survey"); ?></span>
            <input type="hidden" value="<?php echo ($surveyLabel != -1 ? $surveyLabel : -1); ?>" id="hiddenSurvey" name="hiddenSurvey">
              <ul class="dropdown" tabindex="1">
                <?php
                  $surveyQuery = "select civicrm_survey.title as 'SURVEY', civicrm_survey.id as 'ID' from civicrm_activity
                    inner join civicrm_survey on civicrm_activity.source_record_id = civicrm_survey.id
                    where activity_date_time > '2013-08-01' and civicrm_activity.activity_type_id = 32
                    group by `civicrm_survey`.id;";
                  if ($surveys = $mysqli->query($surveyQuery)) {
                    while ($row = mysqli_fetch_assoc($surveys)) {
                      if($row["SURVEY"] == $surveyLabel){
                        $surveyID = $row["ID"];
                      }
                      echo "<li><a href=\"\">" . $row["SURVEY"] . "</a></li>\n";
                    }
                  }
                ?>					      
					    </ul>
            </div>
            <br>
          </div>
			</section>

      <div class="int_box">
        <input type="checkbox" id="intStudents" name="intStudents" value="true" <?php if($onlyInt){echo "checked";}?>>
        <label for="intStudents">  Only International Students</label>
      </div>
      <div class="update_link" onClick="document.forms['reportForm'].submit();">View</div>
    </form>
	</div>
</div>

<div class="accordion-expand-holder"> <a class="open">Expand All</a> <font color="#3399FF"> |</font><a class="close">Collapse All</a> </div>
<div id="accordion">
      <h3>1. Priority Report<a class='inline' href="#priority_report" onmouseover="this.style.opacity=0.8;this.filters.alpha.opacity=80" onmouseout="this.style.opacity=1;this.filters.alpha.opacity=100" style="opacity: 1;"><img src="images/questionmark.png" / style="margin:0 0 0 10px; border:none;" ></a></h3>
      <div class="text">
    
    <table id="dataTable" cellpadding="0" cellspacing="0">
  <thead>
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
      $priQuery = "select civicrm_activity.`priority_id` as PRIORITY, 
        count(CASE civicrm_activity.status_id WHEN 4 then 1 ELSE NULL END) as 'UNCONTACTED', 
        count(CASE civicrm_activity.status_id WHEN 3 then 1 ELSE NULL END) as 'IN PROGRESS',
        count(CASE civicrm_activity.status_id WHEN 2 then 1 ELSE NULL END) as 'COMPLETED',
        count(*) as 'TOTAL' from civicrm_activity
        inner join civicrm_activity_target on civicrm_activity.id = civicrm_activity_target.activity_id
        inner join civicrm_contact a on civicrm_activity_target.target_contact_id = a.id 
        inner join civicrm_relationship on a.id = civicrm_relationship.contact_id_a
        inner join civicrm_contact school on civicrm_relationship.`contact_id_b` = school.id
        where activity_date_time > '2013-08-01' and activity_type_id = 32 and civicrm_relationship.`relationship_type_id` = 10
        and school.id = ? and civicrm_activity.`source_record_id` = ? group by civicrm_activity.`priority_id`";
      if($onlyInt){
        $priQuery = "select civicrm_activity.`priority_id` as PRIORITY, 
          count(CASE civicrm_activity.status_id WHEN 4 then 1 ELSE NULL END) as 'UNCONTACTED', 
          count(CASE civicrm_activity.status_id WHEN 3 then 1 ELSE NULL END) as 'IN PROGRESS',
          count(CASE civicrm_activity.status_id WHEN 2 then 1 ELSE NULL END) as 'COMPLETED',
          count(*) as 'TOTAL' from civicrm_activity
          inner join civicrm_activity_target on civicrm_activity.id = civicrm_activity_target.activity_id
          inner join civicrm_contact a on civicrm_activity_target.target_contact_id = a.id 
          inner join civicrm_value_student_demographics_7 on a.id = civicrm_value_student_demographics_7.entity_id 
          inner join civicrm_relationship on a.id = civicrm_relationship.contact_id_a
          inner join civicrm_contact school on civicrm_relationship.`contact_id_b` = school.id
          where activity_date_time > '2013-08-01' and activity_type_id = 32 and civicrm_relationship.`relationship_type_id` = 10
          and civicrm_value_student_demographics_7.i_am_an_international_student_61 = \"yes\" 
          and school.id = ? and civicrm_activity.`source_record_id` = ? group by civicrm_activity.`priority_id`";
      }
      if ($priStmt = $mysqli->prepare($priQuery)){
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

      <h3>2. Follow-up Progress by Person<a class='inline' href="#follow_up" onmouseover="this.style.opacity=0.8;this.filters.alpha.opacity=80" onmouseout="this.style.opacity=1;this.filters.alpha.opacity=100" style="opacity: 1;"><img src="images/questionmark.png" / style="margin:0 0 0 10px; border:none;" ></a></h3>
          <div class="text">
          
    <table id="dataTable2" cellpadding="0" cellspacing="0">
  <thead>
    <tr>
      <th>Volunteer</th>
      <th>Uncontacted</th>
      <th>In Progress</th>
      <th>Completed</th>
      <th>Total</th>
    </tr>
  </thead>
  <tbody> 
    <?php 
      $sumUn = 0; $sumIn = 0; $sumCom = 0; $sumSTotal = 0;
      $stuQuery = "select b.`sort_name` as 'NAME', 
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
        and school.id = ? and civicrm_activity.`source_record_id` = ? group by b.`sort_name`;";
      if($onlyInt){
        $stuQuery = "select b.`sort_name` as 'NAME', 
          count(CASE civicrm_activity.status_id WHEN 4 then 1 ELSE NULL END) as 'UNCONTACTED', 
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
          and civicrm_value_student_demographics_7.i_am_an_international_student_61 = \"yes\" 
          and school.id = ? and civicrm_activity.`source_record_id` = ? group by b.`sort_name`;";
      }
      if ($stuStmt = $mysqli->prepare($stuQuery)){
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
      <h3>3. Rejoiceables<a class='inline' href="#rejoiceables" onmouseover="this.style.opacity=0.8;this.filters.alpha.opacity=80" onmouseout="this.style.opacity=1;this.filters.alpha.opacity=100" style="opacity: 1;"><img src="images/questionmark.png"  style="margin:0 0 0 10px; border:none;" ></a></h3>
       <div class="text">
    
<table class="nonDT" cellspacing="0px">
  <?php
    $totals = array(); $rejTotal = 0;
    $titles = array("","Interaction", "Spiritual Conversation", "Gospel Presentation", "Indicated Decision", "Shared Spirit-Filled Life");
    $rejQuery = "select civicrm_value_rejoiceable_16.rejoiceable_143 as 'TYPE', count(*) as 'COUNT' from civicrm_activity
      inner join civicrm_value_rejoiceable_16 on civicrm_activity.id = civicrm_value_rejoiceable_16.entity_id
      inner join civicrm_activity_target on civicrm_activity.id = civicrm_activity_target.activity_id
      inner join civicrm_contact a on civicrm_activity_target.target_contact_id = a.id 
      inner join civicrm_relationship on a.id = civicrm_relationship.contact_id_a
      inner join civicrm_contact school on civicrm_relationship.`contact_id_b` = school.id
      where activity_date_time > '2013-08-01' and activity_type_id = 47 and civicrm_value_rejoiceable_16.related_survey_152 is not null
      and civicrm_value_rejoiceable_16.rejoiceable_143 is not null and school.id = ? and civicrm_value_rejoiceable_16.related_survey_152 = ?
      group by civicrm_value_rejoiceable_16.rejoiceable_143";
    if($onlyInt){
      $rejQuery = "select civicrm_value_rejoiceable_16.rejoiceable_143 as 'TYPE', count(*) as 'COUNT' from civicrm_activity
      inner join civicrm_value_rejoiceable_16 on civicrm_activity.id = civicrm_value_rejoiceable_16.entity_id
      inner join civicrm_activity_target on civicrm_activity.id = civicrm_activity_target.activity_id
      inner join civicrm_contact a on civicrm_activity_target.target_contact_id = a.id 
      inner join civicrm_relationship on a.id = civicrm_relationship.contact_id_a
      inner join civicrm_contact school on civicrm_relationship.`contact_id_b` = school.id
      inner join civicrm_value_student_demographics_7 on a.id = civicrm_value_student_demographics_7.entity_id
      where activity_date_time > '2013-08-01' and activity_type_id = 47 and civicrm_value_rejoiceable_16.related_survey_152 is not null
      and civicrm_value_student_demographics_7.i_am_an_international_student_61 = \"yes\"
      and civicrm_value_rejoiceable_16.rejoiceable_143 is not null and school.id = ? and civicrm_value_rejoiceable_16.related_survey_152 = ?
      group by civicrm_value_rejoiceable_16.rejoiceable_143";
    }
    if ($rejStmt = $mysqli->prepare($rejQuery)){
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
</table>
<p class="impText"><?php print($rejTotal); ?> Rejoiceables</p>

  </div>
      <h3>4. Results<a class='inline' href="#results" onmouseover="this.style.opacity=0.8;this.filters.alpha.opacity=80" onmouseout="this.style.opacity=1;this.filters.alpha.opacity=100" style="opacity: 1;"><img src="images/questionmark.png" style="margin:0 0 0 10px; border:none;" ></a></h3>
        <div class="text">
    
  <?php
    $rTotals = array(); $badTotal = 0; $goodTotal = 0;
    $titles = array(0 => "Bad Info", 1 => "No Response", 2=> "No Longer Interested", 5 => "Request Fulfilled Digital", 
      7 => "Request Fulfilled Face-to-Face", 8 => "Digital Interaction and Wants to Continue", 10 => "Face-to-face Interaction and Wants to Continue");
    $resQuery = "select civicrm_activity.engagement_level as TYPE, count(*) as COUNT from civicrm_activity
      inner join civicrm_activity_target on civicrm_activity.id = civicrm_activity_target.activity_id
      inner join civicrm_contact a on civicrm_activity_target.target_contact_id = a.id
      inner join civicrm_relationship on a.id = civicrm_relationship.contact_id_a
      inner join civicrm_contact school on civicrm_relationship.`contact_id_b` = school.id 
      where activity_date_time > '2013-08-01' and activity_type_id = 32
      and civicrm_activity.engagement_level is not null and school.id = ? and civicrm_activity.`source_record_id` = ?
      group by civicrm_activity.engagement_level";
    if($onlyInt){
      $resQuery = "select civicrm_activity.engagement_level as TYPE, count(*) as COUNT from civicrm_activity
        inner join civicrm_activity_target on civicrm_activity.id = civicrm_activity_target.activity_id
        inner join civicrm_contact a on civicrm_activity_target.target_contact_id = a.id 
        inner join civicrm_value_student_demographics_7 on a.id = civicrm_value_student_demographics_7.entity_id
        inner join civicrm_relationship on a.id = civicrm_relationship.contact_id_a 
        inner join civicrm_contact school on civicrm_relationship.`contact_id_b` = school.id 
        where activity_date_time > '2013-08-01' and activity_type_id = 32 
        and civicrm_activity.engagement_level is not null and school.id = ? and civicrm_activity.`source_record_id` = ? 
        and civicrm_value_student_demographics_7.i_am_an_international_student_61 = \"yes\"
        group by civicrm_activity.engagement_level";
    }
    if ($resStmt = $mysqli->prepare($resQuery)){
      $resStmt->bind_param("ii", $schoolID, $surveyID);
      $resStmt->execute();
      $resStmt->bind_result($type_bind, $count_bind);
      while ($resStmt->fetch()) {
        if(isset($type_bind)){
          $rTotals[$type_bind] = $count_bind;
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
            $resStmt->close();
          }
      ?>
    </table>
    <br>
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
  <p style="padding:10px 0;"><strong>Priority Report</strong></p>
    <p style="font-family:Arial, Helvetica, sans-serif; padding:10px 0;">The breakdown of your school's contacts by follow-up priority. I.e. Hot, Medium, Mild, Uninterested and N/A.</p>
  </div>
    <div id='follow_up' style='padding:10px; background:#fff;'>
   <p style="padding:10px 0;"><strong>Follow-up Progress By Person</strong></p>
    <p style="font-family:Arial, Helvetica, sans-serif; padding:10px 0;">The breakdown of each volunteer's "Uncontacted", "In Process" and "Completed" contacts.</p>
  </div>
    <div id='rejoiceables' style='padding:10px; background:#fff;'>
   <p style="padding:10px 0;"><strong>Rejoiceables:</strong></p>
    <p style="font-family:Arial, Helvetica, sans-serif; padding:10px 0;">Summary of the total rejoiceables for your campus.</p>
    </div>
      <div id='results' style='padding:10px; background:#fff;'>
    <p style="padding:10px 0;"><strong>Results:</strong></p>
    <p style="font-family:Arial, Helvetica, sans-serif; padding:10px 0;">Summary of the follow-up results for you campus.</p>
  </div>
</div>
	
</body>
</html>
