<?php get_header(); 

if (class_exists('VF_Global_Header')) {
  VF_Plugin::render(VF_Global_Header::get_plugin('vf_global_header'));
}
if (class_exists('VF_Intranet_Breadcrumbs')) {
  VF_Plugin::render(VF_Intranet_Breadcrumbs::get_plugin('vf_wp_breadcrumbs_intranet'));
}

// Pages search query
$page_args = array(
  'post_type' => array('page', 'teams'),
  'posts_per_page' => -1,
   's' => get_search_query(), 
   'relevanssi' => true,
);
$page_query = new WP_Query( $page_args );

// People search query
// $people_args = array(
//   'post_type' => 'people',
//   'posts_per_page' => -1,
//    's' => get_search_query(), 
//    'relevanssi' => true,
// );
// $people_query = new WP_Query( $people_args );

// documents search query
$documents_args = array(
  'post_type' => 'documents',
  'posts_per_page' => -1,
   's' => get_search_query(), 
   'relevanssi' => true,
);
$documents_query = new WP_Query( $documents_args );

// People search query
$insites_args = array(
  'post_type' => 'insites',
  'posts_per_page' => -1,
   's' => get_search_query(), 
   'relevanssi' => true,
);
$insites_query = new WP_Query( $insites_args );

// People search query
$events_args = array(
  'post_type' => 'events',
  'posts_per_page' => -1,
   's' => get_search_query(), 
   'relevanssi' => true,
);
$events_query = new WP_Query( $events_args );

?>

<section class="vf-hero | vf-u-fullbleed | vf-hero--800 | vf-u-margin__bottom--0">
  <style>
    .vf-hero {
      --vf-hero--bg-image: url('https://www.embl.org/internal-information/wp-content/uploads/20220325_Intranet-hero-scaled.jpg');
    }

  </style>
  <div class="vf-hero__content | vf-box | vf-stack vf-stack--200">
    <h2 class="vf-hero__heading">
      <a class="vf-hero__heading_link" href="https://www.embl.org/internal-information">
        EMBL Intranet </a>
    </h2>
  </div>
</section>

<?php
if (class_exists('VF_Navigation')) {
  VF_Plugin::render(VF_Navigation::get_plugin('vf_navigation'));
}
?>

<section class="vf-intro | vf-u-margin__bottom--0">
  <div>
    <!-- empty -->
  </div>
  <div class="vf-stack">
    <h1 class="vf-intro__heading">
      Intranet search
    </h1>
    <div class="vf-banner vf-banner--alert vf-banner--info">
      <div class="vf-banner__content">
        <p class="vf-banner__text">Can't find what you need on the intranet? It may be on the public website <a
            class="vf-banner__link" href="https://www.embl.org/search">embl.org/search</a></p>
      </div>
    </div>
</section>
<div
  class="embl-grid embl-grid--has-centered-content | vf-u-padding__top--500 vf-u-padding__bottom--500 | vf-u-margin__bottom--800">
  <div></div>
  <form role="search" method="get"
    class="vf-form vf-form--search vf-form--search--<?php echo esc_html($type); ?> | vf-sidebar vf-sidebar--end"
    action="<?php echo esc_url(home_url('/')); ?>">
    <div class="vf-sidebar__inner">
      <div class="vf-form__item | vf-search__item">
      <input id="search" class="vf-form__input vf-form__input--filter" data-jplist-control="textbox-filter"
            data-group="data-group-1" data-name="my-filter-1" data-path=".search" type="text"
            placeholder="Enter your search term" data-clear-btn-id="name-clear-btn"
            value="<?php echo esc_attr(get_search_query()); ?>" name="s">      </div>
      <button type="submit" class="vf-search__button | vf-button vf-button--primary"
        value="<?php esc_attr_e('Search', 'vfwp'); ?>">
        <span class="vf-button__text">Search</span>
      </button>
    </div>
  </form>
</div>


<section
  class="embl-grid embl-grid--has-centered-content vf-content | vf-u-padding__top--500 vf-u-padding__bottom--500 | vf-u-margin__bottom--0">
  <div></div>
  <div data-jplist-group="data-group-1">
<?php
    $the_query = new WP_Query( array( 'posts_per_page' => -1, 'post_type' => array('page','post', 'people'), 'orderby' => 'title',
    'order'   => 'asc',) );
    if( $the_query->have_posts() ) :
        echo '<ul>';
        while( $the_query->have_posts() ): $the_query->the_post(); ?>

            <li data-jplist-item class="search"><a href="<?php echo esc_url( post_permalink() ); ?>"><?php the_title();?></a></li>

        <?php endwhile;
       echo '</ul>';
        wp_reset_postdata();  
    endif;
    ?>
  </div>
</section>
 


<?php
if (class_exists('VF_Global_Footer')) {
  VF_Plugin::render(VF_Global_Footer::get_plugin('vf_global_footer'));
} ?>

<?php get_footer(); ?>

<script type="text/javascript">
  jplist.init({
    deepLinking: true
  });

</script>