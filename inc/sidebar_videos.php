<div class="shift-right--fluid bg--beige can-be--dark-dark">
  <?php if (is_active_sidebar('sidebar_breakout_block')): ?>
    <div class="text <?php if (!is_page_template('template-news.php')): echo 'block--breakout '; endif; ?>media-block block spacing bg--tan can-be--dark-dark pad--secondary--for-breakouts">
    <h2 class="font--tertiary--m theme--primary-text-color pad--btm">Key Words / Tags</h2>
    <p>Click a tag below to see related videos.</p><ul style='list-style:disc inside none; color: #438390; font-variant:small-caps;'>
    <?php 
	$tag_list=sws_tags(array("HOME"),get_the_id());
	foreach ($tag_list as $arr) { echo "<li><a href='/videos?t=".$arr['slug']."'>".$arr['name']."</a></li>"; }
	$tag_list=sws_tags(array(),get_the_id(),'video_ppl');
	foreach ($tag_list as $arr) { echo "<li><a href='/videos?p=".$arr['slug']."'>".$arr['name']."</a></li>"; }		
	?>
    </ul><p>Back to <a href='/videos'>VIDEO LIBRARY</a></p></div>
  <?php endif; ?>

  <div class="column__secondary can-be--dark-dark">
    <aside class="aside spacing">
      <div class="text pad--secondary spacing">
      <p>Visit us on <a href="https://www.youtube.com/channel/UCmA4AnSsxZpGrjvuaPdrg7Q/featured" target="_blank">YouTube</a></p>
     
       <p>Downloading YouTube videos may require the use of third-party software.</p>
      </div>
    </aside>
  </div>
</div> <!-- /.shift-right--fluid -->
