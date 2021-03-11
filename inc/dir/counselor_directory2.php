<?php
session_start();


include "assets/Db.php";
include "assets/functions_sws.php";
include "assets/dir_functions.php";

//$_SESSION['sew']['which']="fam";

$vars=sew_clean_post($_POST); //error_log(print_r($vars,true),0);

$specialties="";
foreach ($vars as $key=>$value) { // loop specialties
	if (substr($key,0,3)=="sp_") {
		if (strlen($specialties)>0) { $specialties.=" OR ";} 
		{$specialties.=" `specialties` like '%|".$value."|%' ";}
	}
}
if (strlen($specialties)>0) {$specialties=" AND (".$specialties.")";}

if ((isset($vars['new_sort']))) {$sort=$vars['new_sort'];} else {$sort="`lastname`, `firstname`";}


$db = new Db();

/*if (isset($_POST['min'])) {$min=$_POST['min'];} else {$min="fam";}

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
<div style='width:100%;'>
<h2>Search Results</h2>
<span style='color:#FF0000; font-weight:bold;'>*</span> 
        Indicates Seventh-day Adventist counselors. <br />
&nbsp;&nbsp;<a href='counselor_directory.php<?php if (isset($_POST['min'])) { echo "?m=".$_POST['min']; } ?>'>BACK to search</a><br />

<br />
<?php
$condition="";
$zip_search=substr($vars['zip_value'],0,3)."%";

if (strlen($vars['lastname'])>0) {$condition="and `lastname` like '%".$vars['lastname']."%' ";}
if (strlen($vars['firstname'])>0) {$condition.="and `firstname` like '%".$vars['firstname']."%' ";}
if ((strlen($vars['state_prov'])>0) && (!($vars['state_prov']=="CHOOSE STATE/PROVINCE"))) {$condition.="and `state`='".$vars['state_prov']."' ";}
if (strlen($vars['zip_value'])>0) {$condition.="and `zip` like '".$zip_search."' ";} 
if (strlen($specialties)>0) {$condition.=$specialties;}
if (isset($vars['sda']) && ($vars['sda']=="sda")) {$condition.=" and `sda`='Seventh-day Adventist' ";}

// get result query
$sql="select * from (
select *, id as myID, (select field_value from dbi_custom_field_data where record_id=myID and field_id=13) as specialties, (select field_value from dbi_custom_field_data where record_id=myID and field_id=10) as degree, (select field_value from dbi_custom_field_data where record_id=myID and field_id=9) as dept, (select field_value from dbi_custom_field_data where record_id=myID and field_id=15) as sp_other, (select field_value from dbi_custom_field_data where record_id=myID and field_id=19) as sda from dbi_master where groups like '%:17:%')
as T1 where `lastname`!='' ".$condition." order by ".$sort; //error_log( $sql,0);

$query = $db -> select($sql); 

if (!($query)) { echo "<div style='width:50%; font-weight:bold; padding:3em;'>Your query returned no results. Please try again.</div>";}	else {

$row_count=count($query);
	
?>&nbsp;&nbsp;Your search returned <?php echo $row_count; ?> result(s).<br /><hr/><div class='dirlist_div'><?php
	
foreach ($query as $key=>$value) {

	$row=$query[$key];

$specialty=''; $organization2=''; $dept2=''; $degree2='';
$address_line1=''; $address_line2='';

$firstname=$row["firstname"];
$lastname=$row["lastname"];
$mi=$row["mi"];
$degree=$row["degree"];
$prefix=$row["prefix"];
$work_phone=$row['work_phone'];
$fax=$row['fax'];
$title=$row["title"];
$website=$row["website"];
$organization=$row["organization"]."<br>";
$dept=$row["dept"]."<br>";
if (!(strpos($website,"@",0))) {$website="http://".$website;} else {$website="mailto:".$website;}

$address1=$row["address1"];
if (strlen($row['address2'])>0) { $address1.=", ".$row["address2"];}
$city=$row["city"];
$sp_other=$row["sp_other"];
//$wk_other=$row["wk_other"];
if ($row['country']=="USA") {$country="<br />";} else {
$country=$row["country"]."<br />";}

if (!(strpos($row["specialties"],"|")===false)) {$specialty=str_replace("|"," &#8226; ",$row['specialties']);}
	else { $specialty=$row['specialties'];}

if ($row["sda"]=="Seventh-day Adventist") {$color="<font color='#FF0000'><strong>* </strong></font>";} else {$color='';}


if (strlen($address1)>0) {$address_line1=$address1."<br>";}
if (strlen($city)>0) {$address_line2=$row["city"].", ".$row["state"]." ".$row["zip"]."  ".$country;}
if ((!($prefix=='')) || (!($prefix = null))) {$prefix=$prefix." ";}
if ((!($degree=='')) && (!($degree = null))) {$degree2=", ".$row["degree"];}
if ((!($mi=='')) || (!($mi = null))) {$mi=$mi." ";}
$name=$prefix.$firstname." ".$mi.$lastname.$degree2;
if ((!($row["website"]=='')) and (!($row["website"] = null))) { $name="<a href='".$website."' target='_blank'>".$name."</a>";}
if ((!($organization=='')) || (!($organization = null))) {$organization2=$organization;}
if ((!($dept=='')) || (!($dept = null))) {$dept2=$dept;}

echo "<div class='dirlist_row'>
<div class='half_col'><strong>$name</strong> $color
<div style='padding-left:2em;'>";

	  if (!($organization2=="<br>")) {echo $organization2;}
	  if (!($dept2=="<br>")) {echo $dept2;}	  
	  if (!($address_line1==", ")) {echo $address_line1;}
  	  if (!($address_line2=="<br>")) {echo $address_line2;}
	  if (strlen($work_phone)>0) {  echo 'WORK&nbsp;PHONE:&nbsp;'.$work_phone.'<br />'; }
	  if (strlen($fax)>0) { echo 'FAX: '.$fax."<br>";}

echo "</div>
</div>
<div class='half_col' style='text-align:center; vertical-align: top;'>$specialty</div>
</div>
";

 } ?></div><?php } ?>  
<div style='width:100%'><em><br>
  Please 
        note that listing in this directory does not consitute a recommendation 
        or endorsement from the North American Division <?php echo $ministry; ?> Department.</em><br /><br /><a href='counselor_directory.php<?php if (isset($_POST['min'])) { echo "?m=".$_POST['min']; } ?>'>BACK 
        to search</a></div>
</div>
</body></html>
