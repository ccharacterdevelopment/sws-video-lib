<?php
/**
 * Template Name: Simple Full-Width Template
 */
?>
<style>.layout-container { max-width: 75vw;} </style>
<?php while (have_posts()) : the_post(); ?>
  <?php
    // Featured image
    $thumb_id = get_post_thumbnail_id();
    // Image alt
    $alt = get_post_meta($thumb_id, '_wp_attachment_image_alt', true);

    $display_title = get_post_meta($post->ID, 'display_title', true);
    $kicker = get_post_meta($post->ID, 'kicker', true);
    $subtitle = get_post_meta($post->ID, 'subtitle', true);
    $intro = get_post_meta($post->ID, 'intro', true);
    $video_url = get_post_meta($post->ID, 'video_url', true);
    $hide_featured_image = get_post_meta($post->ID,'hide_featured_image', true);
    $caption = get_the_post_thumbnail_caption();
  ?>  <?php get_template_part('templates/page', 'header-carousel'); ?>
  
            <div class="layout-container full--until-large">
    <div class="flex-container cf">
      <div class="column__primary w--100p bg--white can-be--dark-light">
        <div class="spacing--double flex h--100p">
          <?php if (is_active_sidebar('widget_area_primary_top')): ?>
          <div class="pad--primary spacing text">
              <?php dynamic_sidebar('widget_area_primary_top'); ?>
            </div>
          <?php endif; ?>
          <div class="pad--primary">
          <?php // if (function_exists('wordpress_breadcrumbs')) wordpress_breadcrumbs(); ?>
          <?php
if ( function_exists('yoast_breadcrumb') ) {
  yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
}
?>
          <div class="text spacing">
            <header class="spacing--quarter">
              <a href='#' class='theme--secondary-text-color'><h1 class="font--secondary--xl theme--secondary-text-color">
                <?php if ($display_title) echo $display_title; ?>
              </h1>
              <?php if ($subtitle): ?>
                <h2 class="font--secondary--m"><?php echo $subtitle; ?></h2>
              <?php endif; ?>
              <?php if (in_category('news')): ?>
                <?php include(locate_template('patterns/components/share-tools.php')); ?>
              <?php endif; ?></a>
              <div class="article__meta">
                <span class="pub_date font--secondary--s gray can-be--white"><?php //the_date(); ?></span>
                <?php
                  $theme_options = get_option('alps_theme_settings');
                  $hide_author_global = $theme_options['hide_author_global'];
                  $hide_author_post = get_post_meta($post->ID, 'hide_author_post', true);
                ?>
                <?php if ($hide_author_global == true || $hide_author_post == true): ?>
                <?php else: ?>
                  <span class="divider">|</span>
                  <span class="byline font--secondary--s gray can-be--white"><?php the_author(); ?></span>
                <?php endif; ?>
              </div>
            </header>
            <?php if ($video_url): ?>
              <?php include(locate_template('patterns/components/featured-video.php')); ?>
            <?php else: ?>
              <?php if ($thumb_id && $hide_featured_image != 'true'): ?>
                <figure class="figure">
                  <div class="article__hero img-wrap">
                    <img src="<?php //echo wp_get_attachment_image_src($thumb_id, "featured__hero--m")[0]; ?>" alt="<?php echo $alt; ?>" class="article__hero-img">
                  </div>
                  <?php if ($caption): ?>
                    <figcaption class="figcaption">
                      <p class="font--secondary--xs"><?php echo $caption; ?></p>
                    </figcaption>
                  <?php endif; ?>
                </figure>
              <?php endif; ?>
            <?php endif; ?>
            <!--<?php if ($intro): ?>
              <h3><?php //echo $intro; ?></h3>
            <?php endif; ?>-->
            <?php the_content(); ?>
                  <?php include(locate_template('templates/block-layout.php')); ?>
            <?php // VIDEO CODE
			if (get_post_type()=='videos') { // VIDEO TEXT
				//print_r(get_fields());
				//echo "<hr />";
				//print_r($vidArr);
				echo $vidArr['player'];
				echo "<p>".$vidArr['description']."</p><p><a href='".$vidArr['url']."' target='_blank'>DOWNLOAD</a></p>";
			} // END VIDEO CODE
			?>
          </div>
          <?php comments_template('/templates/comments.php'); ?>
        </div>
          <?php if (is_active_sidebar('widget_area_primary')): ?>
            <div class="with-divider grid--uniform">
              <?php dynamic_sidebar('widget_area_primary'); ?>
          </div>
          <?php endif; ?>
          <?php include(locate_template('patterns/blocks/block-story.php')); ?>
          <?php if (is_active_sidebar('widget_area_primary_bottom')): ?>
            <div class="pad--primary spacing text">
              <?php dynamic_sidebar('widget_area_primary_bottom'); ?>
            </div>
          <?php endif; ?>
        </div>
        

      </div> <!-- /.column__primary -->
    </div> <!-- /.flex-container -->
  </div> <!-- /.layout-container -->
<?php endwhile; ?>
