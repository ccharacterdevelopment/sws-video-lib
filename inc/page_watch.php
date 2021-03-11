<?php
 //$tmp=get_allowed_mime_types();
  
  //print_r($tmp);
?><?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header-carousel'); ?>
<div class="layout-container full--until-large">
  <div class="flex-container cf">
    <div class="shift-left--fluid column__primary bg--white can-be--dark-light no-pad--btm">
      <div class="pad--primary spacing">
        <div class="text article__body spacing">
          <header class="article__header article__flow spacing--quarter">
              <h1 class="font--secondary--xl theme--secondary-text-color"><?php 
		  if ((!isset($_GET['v'])) || (sws_show_single(array('link'=>'https://www.vimeo.com/'.$_GET['v']))==false)) { ?>
          Oops, missing something! <br />The linked video is not found.
		  <?php } else {
			  if ((isset($_GET['a'])) && ($_GET['a']==1)) { $autoplay=true;} else { $autoplay=false;}
			  $vidArr=sws_show_single(array('link'=>'https://www.vimeo.com/'.$_GET['v']),"videos",$autoplay);
			  if (strlen($vidArr['title'])>0) { ?>
         <?php echo $vidArr['title']; ?>          
              <?php  } ?>
              </h1></header><?php
			  //print_r($vidArr);
			  echo $vidArr['player'];
				echo "<p>".$vidArr['description']."</p>";
				//if (strlen($vidArr['title'])>0) { echo "<p><a href='".$vidArr['url']."' target='_blank'>DOWNLOAD</a></p>"; }
		
		  }
		   ?>
       </div>
      </div>
      <?php include(locate_template('templates/block-layout.php')); ?>
    </div> <!-- /.shift-left--fluid -->
    <?php get_sidebar('sws'); ?>
  </div> <!-- /.flex-container -->
</div> <!-- /.layout-container -->
<?php endwhile; ?>
