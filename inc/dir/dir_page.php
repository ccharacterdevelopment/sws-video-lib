<?php

session_start();

error_log(print_r($_SESSION['sws'],true),0);

include "assets/Db.php";
include "assets/functions_sws.php";
include "assets/dir_functions.php";

if (isset($_GET['u'])) {$union=urldecode($_GET['u']);} else {$union="ANB";}

if (isset($_GET['vars'])) { // process url vars
	$tmp=json_decode(base64_decode(urldecode($_GET['vars'])),true);
	foreach ($tmp as $key=>$value) {
		$_SESSION['sws'][$key]=$value;
		${$key}=$value;
		//error_log($key."|".$value,0);
	}
	sws_get_group_id($group);
	if (!($group2=="X")) { sws_get_group_id($group2,"group_id2"); }
} else {
	foreach ($_SESSION['sws'] as $key=>$value) {
		${$key}=$value;
		//error_log($key."|".$value,0);
	}
}

sws_iframe_head();

?>
<div class='dirlist_holder'>
<a href='dir_unions.php'>BACK TO UNION LIST</a>
<?php
if ($group_by_conf=="Y") {
	sws_list_dir_by_union($union,$group,$group2);
} else {
	// do something else
}
?>
<br>
<a href='dir_unions.php'>BACK TO UNION LIST</a>
</div><!--<script type="text/javascript" src="../custom/javascript/iframeResizer.contentWindow.min.js"></script>-->
</body></HTML>