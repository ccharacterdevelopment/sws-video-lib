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
      
        <div class="text article__body spacing" style='max-width: 95%'>
      <?php 
	  if ((isset($_GET['t'])) && ($_GET['t']=="ALL TOPICS")) { unset($_GET['t']);}
	  //print_r($_GET); ?>
      <form id='form1' method="get" action="" style='float:left;'><p style='padding-bottom: 8px !important;'><a href='/weekly-offertory-readings'><strong>Print Versions</strong></a> or <a href='/tithes-offerings/this-weeks-offering/'><strong>This Week's Reading</strong></a></p>
      <select id='t' name='t' onchange="this.form.submit()"><option <?php if (!(isset($_GET['t']))) { echo "selected"; } ?>><strong><?php if (isset($_GET['t'])) { echo "ALL TOPICS";} else { echo "FILTER BY TOPIC"; } ?></strong></option>
    <?php 
		$tag_list=sws_tags('','all','reading_topics');
		foreach ($tag_list as $arr) { 
				if ((isset($_GET['t'])) && ($_GET['t']==$arr['slug'])) { $sel="selected";} else {$sel="";}
				echo "<option value='".$arr['slug']."' $sel>".$arr['name']."</option>"; 
		}
	?></select></form>
         <form id='form2' action="<?php echo home_url( '/' ); ?>" role="search" method="get" class="search-form toggled-element" style='float:right; text-align:center'>
	<input type="hidden" name="post_type" value="video" />
  <fieldset>
    <legend class="is-vishidden">Search Readings</legend>
    <input type="search" name="s" placeholder="Search Readings" class="search-form__input font--secondary--s" required />
    <button class="search-form__submit is-vishidden">
      <span class="is-vishidden">Submit</span>
    </button> <!-- /.search-form__submit -->
    <span style='font-size:75% !important'>Date format: YYYY-MM-DD</span>
  </fieldset>
</form> <!-- /.search-form -->
<div style='clear:both;'></div>
        <?php 
		if (isset($_GET['t'])) { 	// modified search
			 $args=array(
			'tax_query' => array(
					array(
						'taxonomy' => 'reading_topics',
						'field' => 'slug',
						'terms' => array( $_GET['t'] )
					)
			),
			'post_type' => 'readings',
			'post_status' => 'publish',
			'posts_per_page' => '40',
			'orderby' => 'post_name',
			'order' => 'DESC');
			sws_list_readings($args);			
		} else {
			sws_list_readings(); // default			
		} ?>
    </div>
     <!-- /.shift-left--fluid -->
    <?php //get_sidebar('sws'); ?>
  </div> <!-- /.flex-container -->
</div> <!-- /.layout-container -->
