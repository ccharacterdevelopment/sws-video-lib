<?php

//Eric's code
function ejj_dir_listing($row) {
	

	// error_log(print_r($row, true), 0);
	$ministry=$_SESSION['sws']['min_title'];
	
	echo "<div style='margin-left:1.5em; margin-top:0;'><strong>";
	echo sws_dir_names($row);
	echo "</strong><br />";
	echo sws_dir_titles($row)."<br />";

	if (strlen($row['address1'])>0) { echo $row['address1']."<br />"; }
	if (strlen($row['address2'])>0) { echo $row['address2']."<br />"; }
	echo $row['city']." ".$row['state']." ".$row['zip']."<br />";
	if (!($row['country']=="USA")) {
		if (strlen($row['country'])>0) { echo $row['country']."<br />";}
	}
	if (strlen($row['work_phone'])>0) { echo "<strong>Phone:</strong> ".$row['work_phone']."<br />"; }
	if ((strlen($row['email'])>0) && (strpos($row['email'],"BAD")==false)) { 
		echo "<span style='font-weight:bold'>E-mail:</span> ".sws_spamSpan($row['email'])."<br />"; 
	}
	

	echo "</div>";
}

function ejj_list_unions($title="Men's Ministries") {

	
	echo "<h3>$title Leadership in the North American Division</h3>
	<div style='margin-left:75px; width:100%'>
		<ul class='dirlist_unions'>";	

	$db = new Db();
	$union_array = $db -> select("select * from COMMON_temp_union order by full_text"); 
	
	foreach ($union_array as $key=>$value) {
		$union=$union_array[$key]['full_text']; $id=$union_array[$key]['id'];
		if (strpos($union,"Division")==false) { // skip NAD entry
			if ($union=="Canadian") {	$union="Seventh-day Adventist Church in Canada"; } 
			echo "<li><a href='dir_page_ejj.php?u=$id'>$union</a></li>";
		}
	}
	echo "<br /><li><a href='dir_page_ejj.php?u=ANNG'>Guam-Micronesia Mission</a></li>";
	echo "</ul></div>";	
}

function ejj_list_dir_by_union($unionCode) {

	$ministry=$_SESSION['sws']['min_title'];
	$group=$_SESSION['sws']['group'];
	$group_id=$_SESSION['sws']['group_id'];
	if (isset($_SESSION['sws']['group_id2'])) { // adapt for multiple groups
		$groupCond="(groups like '%:$group_id:%' OR groups like '%:".$_SESSION['sws']['group_id2'].":%')"; 
	} else { $groupCond="groups like '%:$group_id:%'"; }
	
	$db = new Db();
	if (!($unionCode=="ANNG")) {
		$union= $db->query("select full_text from COMMON_temp_union where id='$unionCode' ")->fetch_object()->full_text;
	} else {
		$union= $db->query("select full_text from COMMON_temp_conf where id='$unionCode' ")->fetch_object()->full_text;  
		$unionCode="Guam";
	}

	echo "<h3>$ministry Leadership in the $union</h3><div class='dirlist_div'>";
	
	$sql="select * from dbi_master where $groupCond and union_conf like '".$union."%' and (conference like '%".$union."%' or conference='' or conference like '%Union%' or conference is null)";  //echo $sql;

	
	$union_array = $db -> select($sql); 

	if (count($union_array)>0) {
		foreach ($union_array as $key=>$value) {
			$row=$union_array[$key];
			echo "<div class='dir_entry'>";

			if (file_exists("/var/www/html/custom/dbi/files/acs/".$row['id']."/P/presenter_pic.jpg")) {
				//echo "<img src= '/custom/dbi/files/acs/".$row['id']."/P/presenter_pic.jpg' alt=''class='ejj_dir_pic'/><br/>";
				echo "<div class='ejj_has_pic' style='background-image: url(\"/custom/dbi/files/acs/".$row['id']."/P/presenter_pic.jpg\")'></div>";
			
			}
	
			else {
				echo "<div class='ejj_nopic'> No Photo Available </div>";
	
			}
		
			if (strlen($row['conference'])>0) { $myconf= $row['conference']; } else { $myconf=$row['union_conf'];}
			if (!(strpos($myconf," in Canada")===false)) { $myconf="Seventh-day Adventist Church<br />in Canada";} 
		
			echo $myconf."</span><br />";
		
			ejj_dir_listing($row);
			echo "</div>";
		}
	}

	// cycle through conference personnel
	$sqlC="select * from dbi_master where union_conf like '".$union."%' and $groupCond and conference not like '%Union%' and conference is not null and conference!='' and conference not like '%Adventist%' order by conference"; 		

	if ($unionCode=="Guam") { // modify the query
		$sqlC=str_replace("union_conf like '".$union."%'","conference like '%Guam%' ",$sqlC);
		//echo $sqlC;
	}
	
	$conf_array = $db -> select($sqlC);

	if (!($unionCode=="ANN")) {
	foreach ($conf_array as $key=>$value) {
		$row=$conf_array[$key];

		echo "<div class='dir_entry'>";

		if (file_exists("/var/www/html/custom/dbi/files/acs/".$row['id']."/P/presenter_pic.jpg")) {
			//echo "<img src= '/custom/dbi/files/acs/".$row['id']."/P/presenter_pic.jpg' alt=''class='ejj_dir_pic'/><br/>";
			echo "<div class='ejj_has_pic' style='background-image: url(\"/custom/dbi/files/acs/".$row['id']."/P/presenter_pic.jpg\")'></div>";
		
		}

		else {
			echo "<div class='ejj_nopic'> No Photo Available </div>";

		}

		
		
		// echo "<img src='Error.src' onerror='this.style.display' display='none'/>";
		echo "<span class='h4'>".$row['conference']." Conference</span>";

		ejj_dir_listing($row, $ministry);
		echo "</div>";
	}
	echo "</div>";
	} // don't do for NAD entry
}

//End Eric's code

function fm_counselor_topics() {
	$db = new Db(); $retArr=array();
	$sql="SELECT field_options FROM dbi_custom_fields WHERE field_name='Counseling Specialties'";
	$query= $db ->select($sql); //print_r($query);

	foreach ($query as $row) {
		$retArr=explode("|",$row['field_options']);
	}
	sort($retArr);
	
	foreach ($retArr as $key=>$value) {
		echo "<div style='min-width:17em; display:inline-block;'><input type='checkbox' id='sp_$key' name='sp_$key' value=\"$value\" class='item'/><label for 'sp_$key'>&nbsp;$value</label></div>";	
	}
}

/*
function min_validate_page($roles="administrator|office") {

	///print_r($_SESSION['sew']);
	//echo $_SESSION['sew']['role']."<hr />";
	//echo $roles;
	
	if (
	((isset($_SESSION['sew']['role'])) && 
	(
		(!(strpos($_SESSION['sew']['role'],"dministrato"))===false) ||
		(!(strpos($_SESSION['sew']['role'],"office"))===false)	
		
	)) 
	|| ((isset($_SESSION['sew']['auth'])) && ($_SESSION['sew']['auth']==true)) )
	{
		//print_r($_SESSION['sew']);
		$_SESSION['sew']['auth']=true;
		$test="Cowabunga"; 
	} else {
		if ((!isset($_SESSION['sew']['role'])) || (strpos($roles,$_SESSION['sew']['role'])===false)) { // redirect to login page
	header("Location: https://min-db1.nadadventist.org/dbi/blank.html");
		}
	}
}

function fm_insert_record($vars, $tableName) {
	$db = new Db();
	$sql=""; $fields=""; $vals="";
	$k=0;
	$excludeArray=array("submit","submit2", "button", "button2", "filter", "lookup");
	
	foreach ($vars as $key=>$value) {
		if ((!(in_array($key,$excludeArray))) && (strlen($value)>0)) { // process only db vars
			if ($k>0) {$fields.=", "; $vals.=", ";}
			$fields.=" `".$key."`";
			$vals.=" \"$value\"";
			$k++;
		}
	}
	$sql="insert into $tableName (".$fields.") values (".$vals.")";
	$db -> query($sql);
	
	$new_index  = $db->query("SELECT max(`index`) as mytemp FROM `master`")->fetch_object()->mytemp;  
	
	$_SESSION['sew']['js_text']="Okay, I've created an entry for ".$_SESSION['sew']['working']['firstname']." ".$_SESSION['sew']['working']['lastname'].".";
	//unset($_SESSION['sew']['working']);
	unset($_SESSION['sew']['lookup']);
	header ("Location: redirect.php?target=1&lookup=".$new_index);
} 

function fm_remove_record($index, $tableName, $target=1){

	$db = new Db();
	// does the removed version exist?
	$rows = $db -> select("SELECT * FROM information_schema.tables WHERE table_schema = 'fm' AND table_name = '".$tableName."_REMOVED' LIMIT 1;");
	$numrows=count($rows);
	
	if ($numrows==0) { // create the table

		$db -> query("create table ".$tableName."_REMOVED as select * from $tableName where `index`=$index");

	} else { // insert into the table

		$db -> query("insert into ".$tableName."_REMOVED select * from $tableName where `index`=$index");
		$db -> query("delete from ".$tableName." where `index`=$index");

	}

	if ($target==1) {	
		$_SESSION['sew']['js_text']="Okay, ".$_SESSION['sew']['working']['firstname']." ".$_SESSION['sew']['working']['lastname']." has been removed from your database.";
		unset($_SESSION['sew']['lookup']);
		if (isset($_SESSION['sew']['nextval'])) {$goto=$_SESSION['sew']['nextval'];} else {$goto=$_SESSION['sew']['prevval'];}
		header ("Location: redirect.php?target=1&lookup=".$goto);	
	} else {
		header ("Location: redirect.php?target=$target");
	}
} 

function fm_update_record ($vars, $POST, $tableName, $lookup) {

	$db = new Db();
	$sql=""; $update=""; $vals="";
	$k=0; 
	$excludeArray=array("submit","submit2", "button", "delete", "button2", "filter", "index", "lookup");

	foreach ($_SESSION['sew']['working'] as $key=>$value) {
		if ((!(in_array($key,$excludeArray))) && (strpos($key,"CB_")===false) && (strpos($key,"_VAR")===false) ) { // process only db vars
			if ($k>0) {$update.=", ";}
			if (!(isset($vars[$key]))) {$vars[$key]=""; $value="";} else {if (strlen($vars[$key])==0) {$value="";}}
		$update.=" `".$key."`=\"".$vars[$key]."\"";
		$k++;
		}
	}
	
	$sql="update $tableName set ".$update." where `index`=$lookup"; echo $sql;
	$db -> query($sql);
	$db -> query("update $tableName set date_mod=now() where `index`=$lookup");

	// process checkboxes that may have been UN-checked
	
	$_SESSION['sew']['js_text']="Okay, your changes to ".$vars['firstname']." ".$vars['lastname']." have been saved.";
	 unset($_SESSION['sew']['working']);  unset($_SESSION['sew']['lookup']);
}*/

function fm_field_array() {
	$field_array=array(
		'First name' => 'firstname', 
		'MI' => 'mi', 
		'Last name' => 'lastname', 
		'Prefix' => 'prefix', 
		'Address' => 'address1', 
		'Address cont.' => 'address2', 	
		'City' => 'city', 
		'State' => 'state', 
		'ZIP code' => 'zip', 
		'Country' => 'country', 
		'AAFLP Category' => 'aaflp_category', 
		'Exp Date' => 'aaflp_exp_date',
		'Status' => 'aaflp_status', 
		'Processed' => 'aaflp_date_processed'
		);
	return $field_array;
}


function fm_get_filter_val($id){ 
	$db = new Db();
	$cond = $db->query("SELECT `value` FROM `misc_OLD` where `id`=$id ")->fetch_object()->value;  
	return $cond;
}

/*function fm_dumpquery2($sql, $dir="N", $min="Family") {
	echo $sql;
	
	$db = new Db();
	$count_dir=0; $min1=""; $min2=""; $min3="";

	if (!(strpos($sql,"dir_fam='-1'")===false)) { // query contains FM dir
		$min1="Family"; $count_dir++; }
	
	if (!(strpos($sql,"dir_men='-1'")===false)) { // query contains FM dir
		$min2="Men's"; $count_dir++;}
	
	if (!(strpos($sql,"dir_asam='-1'")===false)) { // query contains FM dir
		$min3="Adventist Single Adult"; $count_dir++; }
	
	$field_array=fm_field_array();
	$field_arrayFLIP=array_flip($field_array);

	$query= $db ->select($sql); //print_r($query);

	$numrows=count($query);
	$field_list=array();

	foreach ($query as $key=>$value) {	$row=$query[$key];	}
	foreach ($row as $key2=>$value2) { 	array_push($field_list,$key2); 	}

	$numfields=count($field_array);
	
    echo '<table border=1 style="border-collapse:collapse; border:1px solid gray;"><thead><tr>';

	foreach ($field_list as $col_name) {
	// display a "friendly" column name if available
		if (in_array($col_name, $field_array)) {
			if (strlen($field_arrayFLIP[$col_name])>0) { $col_name2=$field_arrayFLIP[$col_name];	}
		} 	else {$col_name2=$col_name;}	
		if ((strpos($col_name,"_codir")===false) && (strpos($col_name,"dir_")===false) && (!($col_name=="index"))) { // DO NOT DISPLAY CO-DIRECTOR COLUMNS
		echo '<th><a href="?sort='.$col_name.'">'. $col_name2 . '</a></th>'; }
    } // foreach
    echo '</tr></thead>';

	foreach ($query as $key=>$value) {
		$row=$query[$key];
       	echo '<tr>';

		foreach ($field_list as $col_name) {
            $field = $col_name;
			$temp_val= $row[$col_name]; 
			
			if (($col_name=="full_text") && (strpos($temp_val,"Union")===false) && (strpos($temp_val,"Church")===false) && (strpos($temp_val,"Mission")===false)) { $temp_val="&nbsp;&nbsp;&nbsp;&nbsp;".$temp_val;	}		
			
			if ($dir=="Y") { 
				if ($field=="name") { // get modified name
				$temp_val=sew_dir_names($row,$min,"Y", $row['index']);
				}
				if ($field=="title") { // get modified title
					if ($count_dir<2) { $temp_val=sew_dir_titles($row,$min, $row['index']); }
					else { // MULTIPLE DIRECTORS CALLED FOR
						$temp_val="";
						if (($min1=="Family") && ($row['dir_fam']=="-1")) { $temp_val=sew_dir_titles($row, $min1, $row['index']);}
						if (($min2=="Men's") && ($row['dir_men']=="-1")) { 
							if (strlen($temp_val)>0) { $temp_val.="<br />";}
							$temp_val.=sew_dir_titles($row, $min2, $row['index']);
						}
						if (($min3=="Adventist Single Adult") && ($row['dir_asam']=="-1")) {
							if (strlen($temp_val)>0) {$temp_val.="<br />";}
							$temp_val.=sew_dir_titles($row,$min3, $row['index']);
						}
					}
				}
			}
			
			
			if ((strpos($col_name,"_codir")===false)  && (strpos($col_name,"dir_")===false) && (!($col_name=="index"))) { // DO NOT DISPLAY CO-DIRECTOR COLUMNS			
			echo '<td valign="top" nowrap>' . $temp_val. '</td>'; }
        	}
        echo '</tr>';

    }
    echo '</table>';    	
} */


function sws_list_dir_by_union($unionCode) {

	$ministry=$_SESSION['sws']['min_title'];
	$group=$_SESSION['sws']['group'];
	$group2=$_SESSION['sws']['group2'];
	$all_conf=$_SESSION['sws']['all_conf'];
	$group_id=$_SESSION['sws']['group_id'];
	if (isset($_SESSION['sws']['group_id2'])) { // adapt for multiple groups
		$groupCond="(groups like '%:$group_id:%' OR groups like '%:".$_SESSION['sws']['group_id2'].":%')"; 
	} else { $groupCond="groups like '%:$group_id:%'"; }

	$db = new Db();
	if (!($unionCode=="ANNG")) {
		$union= $db->query("select full_text from COMMON_temp_union where id='$unionCode' ")->fetch_object()->full_text;
	} else {
		$union= $db->query("select full_text from COMMON_temp_conf where id='$unionCode' ")->fetch_object()->full_text;  
		$unionCode="Guam";
	}

	echo "<h3>$ministry Leadership in the $union</h3><div class='dirlist_div'>";
	
	$sql="select * from dbi_master where $groupCond and union_conf like '".$union."%' and (conference like '%".$union."%' or conference='' or conference like '%Union%' or conference is null)";  //echo $sql;

	
	$union_array = $db -> select($sql); 

	if (count($union_array)>0) {
		foreach ($union_array as $key=>$value) {
			$row=$union_array[$key];
			echo "<div class='dir_entry'><span class='h4'>";
		
			if (strlen($row['conference'])>0) { $myconf= $row['conference']; } else { $myconf=$row['union_conf'];}
			if (!(strpos($myconf," in Canada")===false)) { $myconf="Seventh-day Adventist Church<br />in Canada";} 
		
			echo $myconf."</span><br />";
		
			sws_dir_listing($row);
			echo "</div>";
		}
	} else { 
		if ($all_conf=="Y") {
			echo "<div class='dir_entry'><span class='h4'>$union</span><br />NONE LISTED</div>";
		
		}	
	}

	// cycle through conference personnel
	$sqlC="select * from dbi_master where union_conf like '".$union."%' and $groupCond and conference not like '%Union%' and conference is not null and conference!='' and conference not like '%Adventist%' order by conference"; 		

	if ($unionCode=="Guam") { // modify the query
		$sqlC=str_replace("union_conf like '".$union."%'","conference like '%Guam%' ",$sqlC);
		//echo $sqlC;
	}
	
	$conf_array = $db -> select($sqlC);

	if (!($unionCode=="ANN")) {
	foreach ($conf_array as $key=>$value) {
		$row=$conf_array[$key];

		echo "<div class='dir_entry'><span class='h4'>".$row['conference']." Conference</span>";
		sws_dir_listing($row, $ministry);
		echo "</div>";
	}
	echo "</div>";
	} // don't do for NAD entry
}

// function sws_dir_names($row) {
// 	$prefix=$_SESSION['sws']['show_prefixes'];
	
// 	//error_log(print_r($row,true),0);
	
// 	if (($prefix=="Y") && (strlen($row['prefix'])>0)) { $name=$row['prefix']." "; } else {$name="";}

// 	$name.=$row['firstname']." ".$row['mi']." ".$row['lastname'];
		
// 	return $name;
// }

function sws_dir_names($row) {
	$prefix=$_SESSION['sws']['show_prefixes'];
	
	//error_log(print_r($row,true),0);
	
	if (($prefix=="Y") && (strlen($row['prefix'])>0)) { $name=$row['prefix']." "; } else {$name="";}

	$name.=$row['firstname']." ".$row['mi']." ".$row['lastname'];
		
	return $name;
}

function sws_dir_titles($row) {
	$title="";
	$ministry=$_SESSION['sws']['min_title'];

	if (!(strpos($row['title'],$ministry)===false)) { 	
		$title= $row['title'];
	} else {			
		if (
		(!(strpos($row['firstname']," and ")===false))
		|| (!(strpos($row['firstname'], " & ")===false))
		|| (!(strpos($row['firstname'], " &amp; ")===false))
		) {
			$title="Directors of ".$ministry;
		} else {
			$title="Director of ".$ministry;
		}
	}
	return $title;	
}

function sws_dir_listing($row) {
	
	$ministry=$_SESSION['sws']['min_title'];
	
	echo "<div style='margin-left:1.5em; margin-top:0;'><strong>";
	echo sws_dir_names($row);
	echo "</strong><br />";
	echo sws_dir_titles($row)."<br />";

	if (strlen($row['address1'])>0) { echo $row['address1']."<br />"; }
	if (strlen($row['address2'])>0) { echo $row['address2']."<br />"; }
	echo $row['city']." ".$row['state']." ".$row['zip']."<br />";
	if (!($row['country']=="USA")) {
		if (strlen($row['country'])>0) { echo $row['country']."<br />";}
	}
	if (strlen($row['work_phone'])>0) { echo "<strong>Phone:</strong> ".$row['work_phone']."<br />"; }
	if ((strlen($row['email'])>0) && (strpos($row['email'],"BAD")==false)) { 
		echo "<span style='font-weight:bold'>E-mail:</span> ".sws_spamSpan($row['email'])."<br />"; 
	}
	echo "</div>";
}



function sew_get_custom($record_id,$field_id,$min="fam") {

		$db = new Db();
		
		if ($min=="fam") {$min="fm";}
		$myVal=false;
		
		$is_there=$db->query("select count(`field_value`) as mytemp from dbi_custom_field_data where `record_id`='$record_id' and `field_id`='$field_id'")->fetch_object()->mytemp; 
		if ($is_there==1) {
		$myVal=$db->query("select `field_value` as mytemp from dbi_custom_field_data where `record_id`='$record_id' and `field_id`='$field_id'")->fetch_object()->mytemp; 
		}
		if ($is_there>1) {$myVal='2';}
		return $myVal;
}


?>

