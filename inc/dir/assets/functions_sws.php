<?php

// GENERAL FUNCTIONS USED BY MULTIPLE SITES/FILES

function sws_get_group_id($group_name="staff",$which="group_id") {

	$db = new Db();
	$myVal=$db->query("select `id` as mytemp from dbi_group_names where `descr`='$group_name'")->fetch_object()->mytemp; 
	if (!$myVal) { $myVal=5; }
	$_SESSION['sws'][$which]=$myVal;
}

function sws_iframe_head($themedir="X",$themedir2="X") { 

if ($themedir=="X") { $themedir=$_SESSION['sws']['themedir']; }
if ($themedir2=="X") { $themedir2=$_SESSION['sws']['themedir2']; }

$swsStyleHead= <<<EOT
<html lang="en-US">
  <head>
  <link rel='stylesheet' id='slick-css'  href='//cdn.jsdelivr.net/jquery.slick/1.5.9/slick.css?ver=5.4' type='text/css' media='all' />
<link rel='stylesheet' id='alps/main_css-css'  href='//cdn.adventist.org/alps/2/latest/css/main.css?ver=5.4' type='text/css' media='all' />
<link href="$themedir/style.css" rel="stylesheet" type="text/css">
<link href="$themedir2/style.css" rel="stylesheet" type="text/css">
<link href="assets/dir_styles.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="assets/sew_spamspan.js"></script>
</head>
<body>
EOT;
	echo $swsStyleHead;

}



function sew_print_info() {
	echo "<hr>";
	print_r($_SESSION['sew']['working']);
	echo "<hr>";
	print_r($_POST);	
}

function sew_checkLength($string, $min, $max=999999) {
  $length = strlen ($string);
  if (($length < $min) || ($length > $max)) { return FALSE;  } else {    return TRUE;  }
}

function sew_new_lines($array) {
	$temp=""; //print_r($array);
	foreach ($array as $value) {
		if ((strlen($temp)>0) && (strlen($value)>0)) {$temp.="<br />";}
		$temp.=$value;
	}
}

function sew_validate_phone($array) {
	$k=0;
	foreach ($array as $value) {
		$temp=preg_replace("/[^0-9]+/","",$_SESSION['sew']['working'][$value]);
		if (strlen($temp)>9)  { $k++;} 
	}
	if ($k>0) {return true;} else {return false;}
}


function sew_display_formatted_phone($input, $tag="") {
	$output="";
	if (strlen($input)>0) {
		if (strlen($tag)>0) { $tag=" (".$tag.") ";}
		$temp=preg_replace("/[^0-9]+/","",substr($input,0,10));
		if (strlen($temp)>6) {
			$temp2=substr($input,10,strlen($input));
			$output="(".substr($temp,0,3).") ".substr($temp,3,3)."-".substr($temp,6,4).$temp2.$tag;
		} else {$output=$input.$tag;}
	}
	return $output;	
}	

function sew_has_values($array) { 
	$k=0;
	foreach ($array as $value) {
		if (strlen($value)>0) {$k++;}
	}
	if ($k>0) { return true;} else { return false;}
}

function sew_mailto_encode($text) {
	$text=str_replace(array("<br />","<br>","<BR>","<BR />"),"%0A",$text);	
	$text=str_replace("?","%3F",$text);
	$text=str_replace("/","%2F",$text);
	$text=str_replace(":","%3A",$text);	
	$text=str_replace(" ","%20",$text);
	$text=str_replace("&nbsp;","%20",$text);	
	$text=str_replace("&","%26",$text);
	$test=str_replace(array("\n", "\r"), "",$text);
	$text=trim(preg_replace('/\s+/', '', $text));
	$text=strip_tags($text);
	
	return $text;	
}

function sew_su_tags($min) {
	
	switch ($min) {
		case "men": $url="//www.emale.org"; 
			echo '		<link type="text/css" rel="stylesheet" href="assets/su_styles_emale.css"/>';
			break;
		case "stew": $url="//www.igivesda.org"; break;
		case "asam": 
			echo '		<link type="text/css" rel="stylesheet" href="assets/su_styles_asam.css"/>';
			break;
		case "fam": $style="../assets/su_styles.css";
			echo '<link href="https://fonts.googleapis.com/css?family=Belleza" rel="stylesheet">
			<link type="text/css" rel="stylesheet" href="assets/su_styles.css"/>';
			break;
		default: $url="//www.nadfamily.org";
	}
	/*
	libxml_use_internal_errors( TRUE );
	$html = file_get_contents( "http:".$url."/article/2/" );
	$document = new DOMDocument();
	$results = $document->loadHTML( $html );
	$head_element = $document->getElementsByTagName( "head" )->item(0);
	$head_tags = $head_element->ownerDocument->saveXML( $head_element );

	$head_tags=str_replace(">",">|",$head_tags);
	
	$my_array=explode("|",$head_tags);
	$mytext="<!DOCTYPE HTML>
	<html lang=\"en-US\" class=\"su_bootstrap_safe\">";
	foreach ($my_array as $key=>$value) {
		if ((strlen($value)>0) && (strpos($value,"<base href")===false) && (strpos($value,"CDATA")===false)) { 
		// NOT BLANK, NOT THE BASE TAG, not a CDATA
	
			if ((!(strpos($value,"src=")===false)) && 
			(strpos($value,"src=\"http:")===false) && (strpos($value,"src=\"//")===false) ) { // IS A LINK TAG
				// add base to links
				$value=str_replace('src="','src="'.$url.'/',$value);
			}
			
			if ((!(strpos($value,"href=")===false)) && 
			(strpos($value,"href=\"http:")===false) && (strpos($value,"href=\"//")===false) ) { // IS A LINK TAG
				// add base to links
				$value=str_replace('href="','href="'.$url.'/',$value);
			}
	
			if (!(strpos($value,'"text/javascript" src="')===false)) { // contains a script link
				$mytext=$mytext."
				".$value."</script>";}	else {$mytext=$mytext."
				".$value;
			}
			$mytext=str_replace("http://","https://",$mytext);
			
			$mytext=str_replace('<!--[if !IE 8]>
				<link rel="search" href="https://www.nadfamily.org/search/description/open" type="application/opensearchdescription+xml" title="NAD Family Ministries" />
				<![endif]-->',"",$mytext);
		}
	} // foreach
	return $mytext;
	*/
}


function sws_spamSpan($email) {

	if (!(strpos($email,";")===false)) { 
		$ret="";
		$tmp=explode(";",$email);
		foreach($tmp as $item) { 
			error_log($item,0); 
			if (strlen($ret)>0) { $ret.=", "; }
			$ret.=sws_spamSpan(trim($item)); 
		}
		return $ret;
	} else {
	
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {	
			$at_pos=strpos($email,"@");
			$last_dot=strrpos($email,"."); 

			$user=substr($email,0,$at_pos);
			$domain=substr($email,$at_pos+1,$last_dot-1-$at_pos);
			$ext=substr($email,$last_dot+1,strlen($email));	

			$mytext="<span class=\"sew_spamspan\">
			<span class=\"sew_u\">$user</span>
			[at]
			<span class=\"sew_d\">$domain [dot] $ext</span>
			</span>";
			return $mytext;

		} else { return $email; }
	}
}

function sws_list_unions($title="Men's Ministries") {

	
	echo "<h3>$title Leadership in the North American Division</h3>
	<div style='margin-left:75px; width:100%'>
		<ul class='dirlist_unions'>";	

	$db = new Db();
	$union_array = $db -> select("select * from COMMON_temp_union order by full_text"); 
	
	foreach ($union_array as $key=>$value) {
		$union=$union_array[$key]['full_text']; $id=$union_array[$key]['id'];
		if (strpos($union,"Division")==false) { // skip NAD entry
			if ($union=="Canadian") {	$union="Seventh-day Adventist Church in Canada"; } 
			echo "<li><a href='dir_page.php?u=$id'>$union</a></li>";
		}
	}
	echo "<br /><li><a href='dir_page.php?u=ANNG'>Guam-Micronesia Mission</a></li>";
	echo "</ul></div>";	
}

function sew_list_dir_by_union($union,$min='fam') {

	$db = new Db();
	$union= $db->query("select full_text from COMMON_temp_union where id='$union' ")->fetch_object()->full_text;  

	switch ($min) {
		case "asam": 
			$ministry="Adventist Single Adult"; 			
			$minCase=" and groups like '%:23:%'";
			break;
		case "men": 
			$ministry="Men's"; 
			$minCase=" and groups like '%:2:%'";
			break;
		case "ypac": $ministry=""; break;
		default: $ministry="Family";
			$minCase=" and groups like '%:7:%'";
			break;
	}


	
	if ((strpos($union,"Union Conference")===false) && (strpos($union,"Canada")===false)) {$union.=" Union Conference";}

	switch ($min) {
		case "asam":
			echo "<h3>ASAM Leadership in the $union</h3><div style='width:100%; height:48rem; overflow:auto'>";
			break;
		case "ypac":
			echo "<h3>Ministry Leadership in the $union</h2><div style='width:90%; height:48rem; border: 1px solid green; overflow:auto'>";
			break;	
		default:
			echo "<h3>$ministry Ministries Leadership in the $union</h2><div style='width:100%; height:48rem; overflow:auto'>";
	}

	// first get any Union personnel
	$sql="select * from fm_master where groups like '%:4:%' and union_conf like '".$union."%' and (conference like '%".$union."%' or conference='' or conference like '%Union%' or conference is null)"; // echo $sql;
	$union_array = $db -> select($sql); 

	if (count($union_array)>0) {
		foreach ($union_array as $key=>$value) {
			$row=$union_array[$key];
			echo "<div style='float:left; min-width:25rem; min-height:14rem;'><span class='h4'>";
		
			if (strlen($row['conference'])>0) { $myconf= $row['conference']; } else { $myconf=$row['union_conf'];}
			if (!(strpos($myconf," in Canada")===false)) { $myconf="Seventh-day Adventist Church<br />in Canada";} 
		
			echo $myconf."</span><br />";
		
			sew_dir_listing($row, $ministry);
			echo "</div>";
		}
	}

	// cycle through conference personnel
	if (strpos($union,"Guam")===false) {
		$conf_array = $db -> select("select * from fm_master where union_conf like '".$union."%' $minCase and conference not like '%Union%' and conference is not null and conference!='' and conference not like '%Adventist%' order by conference"); 
	} else { // GUAM SPECIAL CASE
		$conf_array = $db -> select("select * from fm_master where conference like '%Guam%' $minCase order by conference"); 
	}
	
	foreach ($conf_array as $key=>$value) {
		$row=$conf_array[$key];
		if (!(strpos($row['conference'],"Guam")===false)) {
		echo "<div style='float:left; min-width:25rem; min-height:14rem;'><span class='h4'>".$row['conference']." Conference</span><br />";
		} else {
		echo "<div style='float:left; min-width:25rem; min-height:14rem;'><span class='h4'>".$row['conference']." </span><br />";	
		}
		sew_dir_listing($row, $ministry);
		echo "</div>";
	}
	echo "</div>";
}

function sew_dir_names($row, $ministry="Family", $prefix="Y", $index="X") {
	$name="";
	if (is_null($index)) { return $name; } else {
		if (!($index=="X")) { 
			$db = new Db(); 
			$query = $db->select("select * from master where `index`='$index'");
			foreach ($query as $key=>$value) { 	$row=$query[$key];	}
		}

		switch ($ministry) {
			case "Men's": $co_row="men_codirectors"; break;
			case "Adventist Single Adult": $co_row="asam_codirectors"; break;	
			default: $co_row="fm_codirectors"; 
		}

		if (($prefix=="Y") && (strlen($row['prefix'])>0)) { $name=$row['prefix']." "; }

		if ($row[$co_row]=="-1") { 
			if ((strpos($row['firstname']," and ")===false) || (!(strpos($row['firstname']," &amp; ")===false)) || (!(strpos($row['firstname']," & ")===false))) {
				if (($row['spouse_lastname']==$row['lastname']) || ($row['spouse_lastname']=="")) {
					 $name.=$row['firstname']." &amp; ".$row['spouse_prefix']." ".$row['spouse_name']." ".$row['lastname'];
				} else {
					 $name.=$row['firstname']." ".$row['lastname']." &amp; ".$row['spouse_name']." ".$row['spouse_lastname'];
				}
			}
			else { $name.= $row['firstname']." ".$row['mi']." ".$row['lastname'];}
				
		} else { $name.=$row['firstname']." ".$row['mi']." ".$row['lastname'];}
		
	return $name;
	}
}

function sew_dir_titles($row, $ministry="Family", $index="X") {
	$title="";

	if (is_null($index)) { return $title;} else {
		if (!($index=="X")) { 
			$db = new Db(); 
			$query = $db->select("select * from master where `index`='$index'");
			foreach ($query as $key=>$value) { 	$row=$query[$key];	}
		}

		switch ($ministry) {
			case "Men's": $co_row="men_codirectors"; break;
			case "Adventist Single Adult": $co_row="asam_codirectors"; break;	
			default: $co_row="fm_codirectors"; 
		}

		if (($row['title']=="Conference Coordinator") || (!(strpos($row['title'],"Assistant")===false)) || (!(strpos($row['title'],"Associate")===false)) ) { $title= $row['title'];} else {
				
			if ($row[$co_row]=="-1") { $title=$ministry." Ministries Co-Directors"; } 
			else { $title="Director of ".$ministry." Ministries";}
		}
	return $title;	
	}
}

function sew_dir_listing($row, $ministry="Family") {
	
	echo "<div style='margin-left:1.5rem; margin-top:0;'><strong>";
	echo sew_dir_names($row, $ministry);
	echo "</strong><br />";
	echo sew_dir_titles($row, $ministry)."<br />";

	if (strlen($row['address1'])>0) { echo $row['address1']."<br />"; }
	if (strlen($row['address2'])>0) { echo $row['address2']."<br />"; }
	echo $row['city']." ".$row['state']." ".$row['zip']."<br />";
	if (!($row['country']=="USA")) {
		if (strlen($row['country'])>0) { echo $row['country']."<br />";}
	}
	if (strlen($row['work_phone'])>0) { echo "<strong>Phone:</strong> ".$row['work_phone']."<br />"; }
	if ((strlen($row['email'])>0) && (strpos($row['email'],"BAD")==false)) { 
		echo "<span style='font-weight:bold'>E-mail:</span> ".sew_spamSpan($row['email'])."<br />"; 
	}
	echo "</div>";
}

function sew_state_provs($name="state_prov", $type="select",$us="Y", $can="Y",$terr="Y",$opt_val="abbr", $display_val="name", $style="", $show_choose="Y") {

	$cond=""; $mytext="";
	if (strlen($style)>0) { $style=" style='".$style."'";}

	if (!($us=="Y")) {$cond.=" and not `country`='USA' ";}
	if (!($can=="Y")) {$cond.=" and not `country`='CAN' ";}
	if (!($terr=="Y")) {$cond.=" and not `terr`='Y' ";}
	if (strlen($cond)>0) {$cond=" where 1=1 ".$cond;}

	$db=new Db();
	// get array
	$query = $db -> select("select * from COMMON_states_provinces ".$cond." order by `name`"); 

	if ($type=="select") { 
		$mytext="<select name='$name' id='$name' $style>";
		if ($show_choose=="Y") {$mytext.="<option selected>CHOOSE STATE/PROVINCE</option>";}
		
		foreach ($query as $key=>$value) {
			$row=$query[$key];
			$mytext.="
			<option value=\"".$row[$opt_val]."\">".$row[$display_val]."</option>";
		}
		$mytext.="</select>";
	  }
	return $mytext;
}

function sew_clean_post ($arr, $set="N",$tags="N", $date_transform="N") {
	$ret=array();
	$ret['date_mod']=date("Y-m-d G:i");
    foreach($arr as $key=>$value) {
		$value=trim($value);
      	$value = str_replace('"', "'", $value);
	  	if ($tags=="N") { 
	  		$value=strip_tags($value);
		}

		if (($date_transform=="Y") && (strlen($value)>0) && (!(strpos($key,"date")===false))) { 		
			// transform date formats
			$value=date("Y-m-d",strtotime($value));
		}

		// process NON-combo-boxes
		if (strpos($key,"CB_")===false) {
			${$key} = stripslashes($value); 
			if ($set=="Y") {$_SESSION['sew']['working'][$key]=$value;}
			$ret[$key]=$value;
	  
		} else { // process combo-boxes
			// ignore the CB_ version, only process the _VAL
			if (!(strpos($key,"_VAL")===false)) {
				$newKey=substr($key,3,strlen($key)-7);
			${$newKey} = stripslashes($value); 
			if ($set=="Y") {$_SESSION['sew']['working'][$newKey]=$value;}
			$ret[$newKey]=$value;
			}
		}
	}
	return $ret;
}


function sew_rowsource_array($sql) {
	$db = new Db();
	$query = $db -> select($sql); 

	$fields=(count($query,1)/count($query,0))-1; //echo $fields;
	$numrows=count($query); //echo $numrows;
	
	if ($numrows==1) { // single row result, must be exploded into array
	
	foreach ($query as $key) { 	
		$result=explode("|",$key); 	
	}
	} else { // multi-row result
	
//		if (sew_is_assoc($query)) { // two-dimensional array
		
		foreach ($query as $key=>$value) {
			$m=0;
			foreach ($value as $key2=>$value2) { $temp_array[$m]=$value2; $m++;	}
			$result[$temp_array[0]]=$temp_array[1];
			}
		
	//	} else { // one-dimensional array
	//	echo "TEST THIS";
	//	foreach ($query as $key=>$value) { $row=$query[$key]; $result[]=$row; }
	//	}
	}
	return $result;
}

function sew_retrieve_itemname ($fieldname, $tablename, $id, $idcol='id', $st_id=0) {
	$db = new Db(); $mytext="";
	$sql="Select `$fieldname` as mytemp from $tablename where `$idcol`='$id'";
	$result=$db->query($sql);
	foreach ($result as $row) {	
		$mytext=$row['mytemp'];
	}
	return $mytext;	
}

function sew_is_assoc($array) {
  foreach (array_keys($array) as $k => $v) {
    if ($k !== $v)
      return true;
  }
  return false;
}

function sew_generate_scrolling_div($mypost, $array, $itemName, $itemType="radio", $div_height=8, $div_width=35, $border_color="gray", $border_width=2, $left=0, $right=0, $top=0, $bottom=0, $selected=1,$id="X",$class="X") {
	
	if ($div_height=="X") { $div_height="auto"; } else { $div_height.=" rem"; }
	if ($div_width=="X") { $div_width="auto"; } else { $div_width.=" rem"; }
	if (!($class=="X")) { $myClass=" class='$class'";} else {$myClass=""; }
	if (!($id=="X")) { $myID=" id='$id'"; } else { $myID=" id='$itemName'"; }

	$mytext="<div $myClass $myID style='text-align: left; border: ".$border_width."px solid $border_color; height: ".$div_height."; width: ".$div_width."; overflow:auto; margin-left:".$left."rem; margin-right:".$right."rem; margin-top:".$top."rem; margin-bottom:".$bottom."rem'>";	$k=1;

	if (sew_is_assoc($array)==true) { // associative array
		foreach ($array as $key => $value) {
			$this_var=$itemName."_".$k;
			$temp="";
			if ((!(isset($_SESSION['sew']['working'][$itemName]))) && ($selected==$k)) {$temp=" checked";} 
			if (isset($_SESSION['sew']['working'][$itemName]) && ($_SESSION['sew']['working'][$itemName]==$key)) {$temp=" checked";}
			if ($itemType=="radio") { $id=$itemName."_".$k; $name=$itemName; } 
			else {$id=$itemName."_ck_".$k; $name=$itemName."_ck_".$k; 
		if (array_key_exists($name,$mypost)) {$temp= "checked";}
		if ($selected==999) {$temp="checked";} // use 999 for "SELECT ALL" default
		}				
		$mytext.="<div class='myrow'><input type='$itemType' name='$name' id='$id' value=\"$key\" $temp class='item'/><label for='$id'>$value</label></div>";
			$k++;
			}
		} else { // vector array

/*		foreach ($array as $value) {
		$this_var=$itemName."_".$k;
		$temp="";
		if ((!(isset($_SESSION['sew']['working'][$itemName]))) && ($selected==$k)) {$temp=" checked";} 
		if (isset($_SESSION['sew']['working'][$itemName]) && ($_SESSION['sew']['working'][$itemName]==$value)) {$temp=" checked";}		
		if ($itemType=="radio") { $id=$itemName."_".$k; $name=$itemName; } else {$id=$itemName."_ck_".$k; $name=$itemName."_ck_".$k;
		if (array_key_exists($name,$mypost)) {$temp= "checked";}
		}				
		$mytext.="<div class='myrow'><input type='$itemType' name='$name' id='$id' value=\"$value\" $temp class='item'/><label for='$id'>$value</label></div>";
			$k++;

*/

		foreach ($array as $key => $value) {
			$this_var=$itemName."_".$k;
			$temp="";
			if ((!(isset($_SESSION['sew']['working'][$itemName]))) && ($selected==$k)) {$temp=" checked";} 
			if (isset($_SESSION['sew']['working'][$itemName]) && ($_SESSION['sew']['working'][$itemName]==$key)) {$temp=" checked";}
			if ($itemType=="radio") { $id=$itemName."_".$k; $name=$itemName; } 
			else {$id=$itemName."_ck_".$k; $name=$itemName."_ck_".$k; 
		if (array_key_exists($name,$mypost)) {$temp= "checked";}
		if ($selected==999) {$temp="checked";} // use 999 for "SELECT ALL" default
		}				
		$mytext.="<div class='myrow'><input type='$itemType' name='$name' id='$id' value=\"$key\" $temp class='item'/><label for='$id'>$value</label></div>";
			$k++;
			}
		}

			
	$mytext.="</div>";
	return $mytext;	
} // END SCROLLING DIV



function sew_generate_jumpMenu($array, $itemName, $target, $left=0, $right=0, $top=0, $bottom=0, $path="", $tab_order="", $placeholder="") {

	if (!($tab_order=="")) { $tab_txt=" tabindex=".$tab_order;} else {$tab_txt="";}
	$array_assoc=sew_is_assoc($array); // echo $array_assoc;
	$nextval=""; $prevval="";
	if ((isset($_SESSION['sew'][$itemName])) && (strlen($_SESSION['sew'][$itemName])>0)) { 
		$firstItem="";
		if ((!(array_key_exists($_SESSION['sew'][$itemName], $array))) && ($array_assoc==true) ) { 
		$firstItem="<option selected>".$_SESSION['sew'][$itemName]."</option>";}
		
	} else {$firstItem="<option value='' selected>CHOOSE</option>";}
	$mytext="<div style='margin-left:".$left."px; margin-right:".$right."px; margin-top:".$top."px; margin-bottom:".$bottom."px'>";
		$mytext.="<select name='$itemName' id='$itemName'  onChange=\"MM_jumpMenu('window',this,0)\" $tab_txt >$firstItem";	

		$r=0; $s=count($array)-1; $t=0; $temp="";
		foreach ($array as $key => $value) {
			if (($itemName=="lookup") && ($r==1)) { $_SESSION['sew']['nextval']=$key; $r=0; }
			if (isset($_SESSION['sew'][$itemName])) {
				if ($_SESSION['sew'][$itemName]==$key) {$temp="selected"; $r++; 
					if ($itemName=="lookup") {
						if ($r>0) { $_SESSION['sew']['prevval']=$prevval;} 
						if (($itemName=="lookup") && ($t==$s)) { unset($_SESSION['sew']['nextval']);}
					}
				} else {$temp="";}
			}
		$mytext.="<option value=\"".$path."redirect.php?target=$target&$itemName=".urlencode($key)."\" $temp>$value</option>";
		    $prevval=$key;
			$t++;
		}
	
	$mytext.="</select></div>";
	return $mytext;
}

function sew_generate_combobox($var_title = NULL, $sql, $width=200, $default="", $marLeft=0, $marTop=0, $tab_order="") {

	// echo "<!-- $sql -->"; 


	$db = new Db();	
	if (!($tab_order=="")) { $tab_txt=" tabindex=".$tab_order;} else {$tab_txt="";}
echo "<div  style='margin-left: ".$marLeft."px; margin-top: ".$marTop."px;' $tab_txt>
  <select id=\"CB_$var_title\" name=\"CB_$var_title\" class='combobox' style='width: ".$width."px' $tab_txt >
    <option value=\"\">CHOOSE</option>";

	$query = $db -> select($sql); 
	

	
	foreach ($query as $key=>$value) {
		$row=$query[$key]; 
		foreach ($row as $key2=>$value2) {

		if ( (isset($_SESSION['sew']['working'][$var_title])) && ($value2==$_SESSION['sew']['working'][$var_title])) { 
			echo "<option selected>".$value2."</option>"; $valText="value=\"".$value2."\""; }
			else { 
			 	if ((!(isset($_SESSION['sew']['working'][$var_title]))) && ($default==$value2)) { echo "<option selected>".$value2."</option>"; $valText="value=\"".$value2."\""; }
				else {  echo "<option>".$value2."</option>"; $valText=""; 	}
			}
		}
	}
if (isset($_SESSION['sew']['working'][$var_title])) { $this_txt=$_SESSION['sew']['working'][$var_title];} else {$this_txt="";}
echo "</select><input type='hidden' name='CB_".$var_title."_VAL' id='CB_".$var_title."_VAL' value=\"".$this_txt."\"  /></div>	";
	
}

function sew_generate_textbox($var, $var_title, $prompt_text= NULL, $width=125, $left=0, $right=0, $top=0, $bottom=0, $pad_left=0, $pad_right=0, $tab_order="",$date="N") {
	$var_title=str_replace(" ","&nbsp;",$var_title); 
	if (!($tab_order=="")) { $tab_txt=" tabindex=".$tab_order;} else {$tab_txt="";}
	if ( (isset($_SESSION['sew']['working'][$var])) && (strlen($_SESSION['sew']['working'][$var])>0)) {$this_val=$_SESSION['sew']['working'][$var];	$js_text="";} else {$this_val=$prompt_text; 
	if (strlen($prompt_text)>0) {$js_text="onfocus=\"this.value==this.defaultValue?this.value='':null\"";} else {$js_text="";}
	}
	
	if ($date=="Y") { $mytext="<script>  $( function() {    $( \"#$var\" ).datepicker();   } );   </script>"; } 
	else { $mytext="";}
	
	$mytext.="<div style='margin-left:".$left."px; margin-right:".$right."px; margin-top:".$top."px; margin-bottom:".$bottom."px'><span style=' padding-left: ".$pad_left."px; padding-right:".$pad_right."px'><input type='text' name='$var' id='$var' value=\"$this_val\" $js_text style='width: ".$width."px;' $tab_txt /></span>&nbsp;$var_title</div>";
	return $mytext;
}

function sew_generate_textboxX($var, $prompt_text= NULL, $width=125, $left=0, $right=0, $top=0, $bottom=0, $pad_left=0, $pad_right=0, $align="left", $tab_order="") {
	if (!($tab_order=="")) { $tab_txt=" tabindex=".$tab_order;} else {$tab_txt="";}
	if (is_null($align) || strlen($align)==0) {$align="left";}
	$js_text=""; $this_val="";
	if (isset($_SESSION['sew']['working'][$var])) {
	if (strlen($_SESSION['sew']['working'][$var])>0) {$this_val=$_SESSION['sew']['working'][$var];	$js_text="";} 
	} else {$this_val=$prompt_text; 
	if (strlen($prompt_text)>0) {$js_text="onfocus=\"this.value==this.defaultValue?this.value='':null\"";} else {$js_text="";} }
	$mytext="<input type='text' name='$var' id='$var' value=\"$this_val\" $js_text style='width: ".$width."px; text-align: ".$align."' $tab_txt />";
	return $mytext;
}

function sew_generate_textarea($var, $var_title, $width=225, $height=85, $left=0, $right=0, $top=0, $bottom=0, $pad_left=0, $pad_right=0, $tab_order="",$units="px") {

	if (!($tab_order=="")) { $tab_txt=" tabindex=".$tab_order;} else {$tab_txt="";}
	if (isset($_SESSION['sew']['working'][$var])) { $this_txt=$_SESSION['sew']['working'][$var];} else {$this_txt="";}

	$mytext="<div style='margin-left:".$left.$units."; margin-right:".$right.$units."; margin-top:".$top.$units."; margin-bottom:".$bottom.$units."; padding-left: ".$pad_left.$units."; padding-right:".$pad_right.$units."'>";
	if (strlen($var_title)>0) { $mytext.="<strong>$var_title</strong><br />"; } 
	$mytext.= "<textarea name=\"$var\" id=\"$var\" style='width: ".$width.$units."; height: ".$height.$units."' $tab_txt />".$this_txt."</textarea></div>";
	return $mytext;
}


function sew_generate_dropdown($array, $itemName, $allowEditable="N", $left=0, $right=0, $top=0, $bottom=0, $tab_order="") {
	
	if (!($tab_order=="")) { $tab_txt=" tabindex=".$tab_order;} else {$tab_txt="";}
	$array_assoc=sew_is_assoc($array);
	if ((isset($_SESSION['sew']['working'][$itemName])) && (strlen($_SESSION['sew']['working'][$itemName])>0)) { 
		$firstItem="";
		if ((!(array_key_exists($_SESSION['sew']['working'][$itemName], $array))) && ($array_assoc==true) ) { $firstItem="<option selected>".$_SESSION['sew']['working'][$itemName]."</option>";}
		if (($array_assoc==false) && (!(in_array($_SESSION['sew']['working'][$itemName], $array))) ) { $firstItem="<option selected>".$_SESSION['sew']['working'][$itemName]."</option>";}
	
	} else {$firstItem="<option value='' selected>CHOOSE</option>";}
	$mytext="<div style='margin-left:".$left."px; margin-right:".$right."px; margin-top:".$top."px; margin-bottom:".$bottom."px'>";
	if ($allowEditable=="Y") {
	$mytext.="<select name='$itemName' onKeyDown=\"fnKeyDownHandler_A(this, event);\" onKeyUp=\"fnKeyUpHandler_A(this, event); return false;\" onKeyPress = \"return fnKeyPressHandler_A(this, event);\"  onChange=\"fnChangeHandler_A(this);\" onFocus=\"fnFocusHandler_A(this, '$itemName_A');\" $tab_txt >
        $firstItem
        <option value=\"\">*** CLICK HERE &amp; TYPE NEW VALUE ***</option>";
	} else { 
	$mytext.="<select name='$itemName' id='$itemName'>$firstItem";	}
	if ($array_assoc==true) { // associative array
		foreach ($array as $key => $value) {
			if ((isset($_SESSION['sew']['working'][$itemName])) && ($_SESSION['sew']['working'][$itemName]==$key)) {$temp="selected";} else {$temp="";}
		$mytext.="<option value=\"$key\" $temp>$value</option>";
		}
	} else { // vector array
		foreach ($array as $value) {
			if ((isset($_SESSION['sew']['working'][$itemName])) && ($_SESSION['sew']['working'][$itemName]==$value)) {$temp="selected";} else {$temp="";}
		$mytext.="<option $temp>$value</option>";	
		}
	}
	$mytext.="</select></div>";
	if ($allowEditable=="Y") { $mytext.=" <!--use textbox for devices such as android and ipad that don't have a physical keyboard (textbox allows use of virtual soft keyboard)-->
        <input type=\"text\" id=\"".$itemName."_A\" style=\"visibility:hidden;display:none;width:150pt\" value=\"select option or type here\" onFocus=\"this.value = document.getElementById('$itemName').options[vEditableOptionIndex_A].text\" onKeyUp=\"document.getElementById('$itemName').options[vEditableOptionIndex_A].text=this.value; document.getElementById('$itemName').options[vEditableOptionIndex_A].value=this.value;\" onBlur=\"document.getElementById('$itemName').options[vEditableOptionIndex_A].text=this.value; document.getElementById('$itemName').options[vEditableOptionIndex_A].value=this.value; document.getElementById('$itemName').focus();\">
      </input>";}
	return $mytext;
}



function sew_generate_checkboxGRP($mypost, $array, $div_height=8, $div_width=35, $border_color="gray", $border_width=2, $left=0, $right=0, $top=0, $bottom=0, $tab_order="",$units="px") {

// print_r($array);

	 $mytext="<div style='text-align: left; border: ".$border_width.$units." solid $border_color; height: ".$div_height.$units."; width: ".$div_width.$units."; overflow:auto; margin-left:".$left.$units."; margin-right:".$right.$units."; margin-top:".$top.$units."; margin-bottom:".$bottom.$units."'>";	
	$count=count($array,0); $k=0;
	while ($k<$count) {			
		if (!($tab_order=="")) { $tab_txt=" tabindex=".$tab_order;} else {$tab_txt="";}
		$itemName=$array[$k]['varName'];
		$itemTitle=$array[$k]['varTitle'];
		$itemVal=$array[$k]['varVal'];
		$temp="";
		if ((isset($_SESSION['sew']['working'][$itemName])) && ($_SESSION['sew']['working'][$itemName]==$itemVal)) {$temp=" checked";}
		if (array_key_exists($itemName,$mypost)) {$temp= "checked";}

		$ckbx_part=substr($itemName,0,strpos($itemName,"-"));
		if ((isset($_SESSION['sew']['working'][$ckbx_part])) && (!(strpos($_SESSION['sew']['working'][$ckbx_part],$itemVal)===false))) { $temp = " checked";}
		
		$mytext.="<label for='$itemName'><input type='checkbox' name=\"$itemName\" id=\"$itemName\" value=\"$itemVal\" $temp $tab_txt />&nbsp;$itemTitle</label><br>";
			$k++; if (!($tab_order=="")) {$tab_order++;}
		}
	
	
	$mytext.="</div>";
	return $mytext;	
	
}


function sew_generate_radio($var, $var_title = NULL, $var_array, $selected=1, $newLine="Y", $left=0, $right=0, $top=0, $bottom=0, $tab_order="",$units="px") {

	if (!($tab_order=="")) { $tab_txt=" tabindex=".$tab_order;} else {$tab_txt="";}

$mytext="<div style='margin-left:".$left.$units."; margin-right:".$right.$units."; margin-top:".$top.$units."; margin-bottom:".$bottom.$units."'>";

if (strlen($var_title)>0) { $mytext.="<strong>$var_title</strong><br />"; }

if ($newLine=="Y") {$endText="<br />";} else {$endText="&nbsp;&nbsp;&nbsp;";}
$k=1;
foreach ($var_array as $key=> $val) {
	$temp="";
	if ((!(isset($_SESSION['sew']['working'][$var]))) && ($selected==$k)) {$temp=" checked";} 
	if ((isset($_SESSION['sew']['working'][$var])) && ($_SESSION['sew']['working'][$var]==$key)) {$temp=" checked";}
$mytext.="<label for=\"".$var."_$k\">
	<input type='radio' id=\"".$var."_$k\" name=\"$var\" value=\"$key\" $temp $tab_txt />&nbsp;$val</label>$endText";
$k++;
	}
	$mytext.="</div>";
	return $mytext;
	
}

function sew_sort_array($array, $sort_by) {
	$temp=array();
	foreach ($array as $key=>$row) 
		{ $temp[$key]=$row[$sort_by];}
	array_multisort($temp, SORT_ASC , $array);
	return ($array);
}

function sew_return_unique($array, $field) {
	$temp=array();
	foreach ($array as $h) {
		$temp[] = $h[$field];
	}
	$uniqueArray=array_unique($temp);
	return $uniqueArray;
}


function sew_mandrill_mail($subject, $message_html, $message_text, $email_array, $bcc = NULL, $sender_email="donotreply@ccharacter.com", $sender_name="The Friendly Script", $key="CZ2K3yHGZIRaur6LUTDCvw") {
	//echo $_SERVER['DOCUMENT_ROOT'];
	
require_once  $_SERVER['DOCUMENT_ROOT']."/functions/m/mandrill/mandrill/src/Mandrill.php";
$mandrill = new Mandrill($key);	

foreach ($email_array as $name=>$address) {
	$now=date("H:i:s d M, Y");
try {
    $message = array(
        'html' => $message_html,
        'text' => $message_text,
        'subject' => $subject,
        'from_email' => $sender_email,
        'from_name' => $sender_name,
        'to' => array(
            array(
                'email' => $address,
                'name' => $name,
                'type' => 'to'
            )
        ),
        'headers' => array('Reply-To' => $sender_email),
        'important' => false,
        'track_opens' => null,
        'track_clicks' => null,
        'auto_text' => null,
        'auto_html' => null,
        'inline_css' => null,
        'url_strip_qs' => null,
        'preserve_recipients' => null,
        'view_content_link' => null,
        'tracking_domain' => null,
        'signing_domain' => null,
        'return_path_domain' => null,
    );

if (!(is_null($bcc))) { $message['bcc_address']=$bcc; }
	//print_r($message);

    $async = false;
    $ip_pool = 'Main Pool';
    $send_at = null;
    $result = $mandrill->messages->send($message, $async, $ip_pool, $send_at);
    // print_r($result);
} catch(Mandrill_Error $e) {
	echo "ERROR!";
    // Mandrill errors are thrown as exceptions
    echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
    // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
    throw $e;
} 
} // FOREACH

} // END FUNCTION



?>