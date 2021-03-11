<?php

session_start();

// error_log(print_r($_SESSION['sws'],true),0);

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
} else {
	foreach ($_SESSION['sws'] as $key=>$value) {
		${$key}=$value;
		//error_log($key."|".$value,0);
	}
}

sws_iframe_head();
sws_iframe_head();

?>
<style>
.ejj_dir_pic {
  display: grid;
  grid-template-columns: max-content;
  grid-auto-rows: 150px;
  grid-template-rows: 200px;
  max-width:70%;
  max-height: 300px;
  max-width: 200px;
}

/*.dir_entry {
  align-self: flex-end;
}*/

.dir_list_div {
  overflow: visible;
  max-height: fit-content;
  align-items:flex-start;
}

.ejj_nopic {
  width: 150px;
  height: 200px;
  border: 3px solid gray;
  text-align: center;
  padding-top: 25%;
  font-size: 200%;

}

.ejj_has_pic {
  width: 150px;
  height: 200px;
  background-size: cover;
  background-position: center center;
}

</style>
<div class='dirlist_holder'>
<a href='dir_unions_ejj.php'>BACK TO UNION LIST</a>
<?php

if ($group_by_conf=="Y") {
	ejj_list_dir_by_union($union,$group,$group2);
} else {
	
	// do something else
}
?>
<br>
<a href='dir_unions_ejj.php'>BACK TO UNION LIST</a>
</div><!--<script type="text/javascript" src="/custom/javascript/iframeResizer.contentWindow.min.js"></script> -->
  <script type="text/javascript" src="../js/iframeResizer.contentWindow.min.js"></script> 
</body></HTML>
