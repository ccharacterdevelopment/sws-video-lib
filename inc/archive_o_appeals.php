<?php get_template_part('templates/page', 'header'); ?>
<?php if (isset($_GET['t'])) {$search=urldecode($_GET['t']);} else { $search="X";} ?>
<style>.layout-container { max-width: 75vw;} </style>
<div class="layout-container full--until-large">
    <div class="flex-container cf">
      <div class="column__primary w--100p bg--white can-be--dark-light sws-archive">
       <?php if (!have_posts()) : ?>
        <div class="pad--primary no-pad--top spacing--half text">
          <div class="alert alert-warning pad-double--top pad-half--btm">
            <?php _e('Sorry, no results were found.', 'sage'); ?>
          </div>
          <?php get_template_part('patterns/components/search-form'); ?>
        </div>
      <?php endif; ?>
      <?php 
	  if ((isset($_GET['t'])) && ($_GET['t']=="ALL TOPICS")) { unset($_GET['t']);}
	  //print_r($_GET); ?>
      <form id='form1' method="get" action="" style='float:left; margin-left:1.1rem;'>
      <select id='t' name='t' onchange="this.form.submit()"><option <?php if (!(isset($_GET['t']))) { echo "selected"; } ?>><strong><?php if (isset($_GET['t'])) { echo "ALL TOPICS";} else { echo "FILTER BY TOPIC"; } ?></strong></option>
    <?php 
		$tag_list=sws_tags(array("HOME"));
		foreach ($tag_list as $arr) { 
			if (strpos($arr['name']," - ")===false) {
				if ((isset($_GET['t'])) && ($_GET['t']==$arr['slug'])) { $sel="selected";} else {$sel="";}
				echo "<option value='".$arr['slug']."' $sel>".$arr['name']."</option>"; 
			}
		}
	?></select></form>
         <form id='form2' action="<?php echo home_url( '/' ); ?>" role="search" method="get" class="search-form toggled-element" style='float:right; padding-right: 1.5rem;'>
	<input type="hidden" name="post_type" value="video" />
  <fieldset>
    <legend class="is-vishidden">Search Videos</legend>
    <input type="search" name="s" placeholder="Search Videos" class="search-form__input font--secondary--s" required />
    <button class="search-form__submit is-vishidden">
      <span class="is-vishidden">Submit</span>
    </button> <!-- /.search-form__submit -->
  </fieldset>
</form> <!-- /.search-form -->
<div style='clear:both;'></div>
        <?php 
		if (isset($_GET['t'])) { 	// modified search
			 $args=array(
			'tax_query' => array(
					array(
						'taxonomy' => 'post_tag',
						'field' => 'slug',
						'terms' => array( $_GET['t'] )
					)
			),
			'post_type' => 'o-appeals',
			'post_status' => 'publish',
			'posts_per_page' => '40',
			'orderby' => 'post_name',
			'order' => 'ASC');
			//nads_vid_grid($args);			
		} else {
			//nads_vid_grid(); // default			
		} ?>
    </div>
     <!-- /.shift-left--fluid -->
    <?php //get_sidebar('sws'); ?>
  </div> <!-- /.flex-container -->
</div> <!-- /.layout-container -->
