<?php

include "../custom/functions/Db.php";
include "../custom/functions/functions_sew.php";


$db = new Db();

include "assets/style_links.php";
?>
<style>
body { font-family: "Noto Sans",sans-serif !important; }
.myRow { width: 100%; height: auto; text-align: center; clear:both; margin-top:0 !important;  font-variant:small-caps;  }
.myLeft { float: left; text-align: left;  font-variant: normal; font-weight: bold;}
.myCenter { clear:both; width:100% text-align: center;  font-variant:small-caps; }
.myRight { float: right; text-align: right;  font-variant: normal;}
</style>
<body>
<div class='myRow' style="border-bottom:2px solid gray;">
<div class='myLeft'>&nbsp;&nbsp;Name</div>
Organization/Conference
<div class='myRight'>E-mail</div>
</div>
<?php
$k=0; $rowStyle=" style='background-color: #f4f3f6'";
$query = $db -> select("select * from dbi_master where groups like '%:12:%' order by lastname, firstname"); 

foreach ($query as $key=>$value) {
	
$row=$query[$key];
if ($k%2==0) { $tmp=$rowStyle;} else { $tmp="";}
?>
<div class='myRow' <?php echo $tmp; ?>>
	<div class='myLeft'><?php echo $row['firstname']." ".$row['lastname']; ?>
    </div>
	
	<div class='myRight'><?php echo sew_spamSpan($row['email']); ?>
    </div>
    <div class='myCenter'><?php if (strlen($row['conference'])>0) { echo $row['conference'];} else { echo $row['organization']; } ?></div>
</div>
<?php	$k++;
} 
?>
<?php
/*
$query = $db -> select("select * from dbi_master where asam_committee='-1' and  (asam_committee_pos IS NULL or asam_committee_pos='') order by lastname"); 

foreach ($query as $key=>$value) {
	
$row=$query[$key];

if ($k%2==0) { $tmp=$rowStyle;} else { $tmp="";}
?>
<div class='myRow'>
	<div class='myLeft'><strong><?php echo $row['firstname']." ".$row['lastname']; ?></strong>, <?php echo $row['asam_committee_pos']; ?>
    </div>
	
	<div class='myRight'><?php echo sew_spamSpan($row['email']); ?>
    </div>
    <div class='myCenter'><?php if (strlen($row['conference'])>0) { echo $row['conference'];} else { echo $row['organization']; } ?></div>
</div>
<?php	$k++;
} */
?></body>