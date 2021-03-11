<?php
session_start();

date_default_timezone_set("America/New_York");

include "assets/Db.php";
include "assets/functions_sws.php";
include "assets/dir_functions.php";

/*$_SESSION['sew']['which']="fam";

if ((isset($_GET['m'])) && ($_GET['m'])) {$min=$_GET['m'];} else {$min='fam';}


switch ($min) {
	case "men": $ministry="Men's Ministries"; break;
	case "asam": $ministry="Adventist Single Adult Ministries";	break;
	default: $ministry="Family Ministries";
	
}*/

if (isset($_GET['vars'])) { // process url vars
	$tmp=json_decode(base64_decode(urldecode($_GET['vars'])),true);
	foreach ($tmp as $key=>$value) {
		$_SESSION['sws'][$key]=$value;
		${$key}=$value;
		//error_log($key."|".$value,0);
	}
	sws_get_group_id($group);
} else {
	foreach ($_SESSION['sws'] as $key=>$value) {
		${$key}=$value;
		//error_log($key."|".$value,0);
	}
}

sws_iframe_head($themedir,$themedir2);

?>
<h2>Counselor Search</h2>

Fill out the form below and click START SEARCH to query our database 
  of  counselors.
<form name="form1" method="post" action="counselor_directory2.php"><input type='hidden' name='min_title' value='<?php echo $min_title; ?>' />
<div style='margin-left:25%; padding-left:1.5em;'><br>
    Search by. . . </div>
<!--<div>
<div class="left_col">First Name</div>
<div class="right_col">      <input name="firstname" type="text" id="firstname"></div>
</div><div style="clear:both;"></div>

<div>
<div class="left_col">Last Name</div>
<div class="right_col"> 
<input name="lastname" type="text" id="lastname">
</div></div><div style="clear:both;"></div>
-->
<div>
<div class="left_col">State/Province</div>
<div class='right_col'> 
<?php echo sew_state_provs(); ?>
</div></div><div style="clear:both;"></div>

<div>
<div class="left_col">ZIP Code</div>
<div class="right_col"> 
 <input name="zip_value" type="text" id="zip_value">
</div></div><div style="clear:both;"></div>

<div>
<div class="left_col" style='padding-bottom: .5rem;'>Specialty&nbsp;Area(s)</div><hr style='clear:both;'/>
<div style="width:100%;">
<fieldset style='border:0;'>
<?php
fm_counselor_topics();


?></fieldset>
</div><hr /></div>

 <div style="clear:both;margin-left:25%; padding-left:1.5em;">
<input name="sda" type="checkbox" id="sda" value="sda">
                Include only Seventh-day Adventist counselors<br>
<br><div style='margin-left:25%; padding-left:1.5em;'>
<input type="submit" name="Submit" value="START SEARCH"></div>
    </div>  </form>
      <div style="clear:both;"><br>
To search for other Christian Counselors near you <a href="https://connect.aacc.net/?search_type=distance" target="_blank">click here</a>.  You will be directed to the Christian Care Network for Christian Counselors.<br>
<br>
<em>Please 
              note that listing in this directory does not consitute a recommendation 
              or endorsement from the North American Division <?php echo $min_title; ?> Department.</em>
</div>
<p>&nbsp;</p>
</body></html>