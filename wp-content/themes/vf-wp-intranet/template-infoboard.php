<?php
/**
* Template Name: Infoboard
*/

$counter = 1;

// Internal events
$args = array(
    'post_type' => 'events',
    'posts_per_page' => 2,
    'tax_query' => array(
        array(
            'taxonomy' => 'event-location',
            'field' => 'slug',
            'terms'    => array('heidelberg', 'virtual')
        ) ));
    $internal_events_query = new WP_Query( $args );
    
    // internal_events Loop
    if ( $internal_events_query->have_posts() ) {
        while ( $internal_events_query->have_posts() ) {
        $internal_events_query->the_post();
        $post_id = get_the_ID();
        $time = get_field('vf_event_internal_start_time', $post_id);
        $start_date = get_field('vf_event_internal_start_date',$post_id);
        $start = DateTime::createFromFormat('j M Y', $start_date);
        $end_date = get_field('vf_event_internal_end_date',$post_id);
        $end = DateTime::createFromFormat('j M Y', $end_date);

        if (!empty($time)) {
            $event_time = ', ' . $time;
        }
        else {
            $event_time = '';
        }   
        echo '<div id="internal_events-' . $counter . '">';?>

        <span class="location_date"> 
        <?php
         if ($end_date) { 
            if ($start->format('M') == $end->format('M')) {
                echo $start->format('j'); ?> - <?php echo $end->format('j F Y') . $event_time; }
            else {
                echo $start->format('j M'); ?> - <?php echo $end->format('j F Y') . $event_time; }
               } 
        else {
            echo $start->format('j F Y') . $event_time; 
         } 
         ?> 
        </span>
        <?php
        echo '<span class="title">' . get_the_title() . '</span>';
        echo '</div>';
        $counter++;
    }
    
} else {
    // no posts found
}
wp_reset_postdata();


// Internal news
$args = array(
    'post_type' => 'insites',
    'posts_per_page'      => 1,
    'tax_query' => array(
        array(
            'taxonomy' => 'embl-location',
            'field' => 'slug',
            'terms'    => array('heidelberg', 'all')
        ) ));
$internal_news_query = new WP_Query( $args );
 
// internal_news Loop
if ( $internal_news_query->have_posts() ) {
    echo '<div id="internal_news">';
    while ( $internal_news_query->have_posts() ) {
        $internal_news_query->the_post();
        echo '<div style="display: inline-block; margin-right: 24px;"><table class="newsContent"><tr><td> <img src="' . get_the_post_thumbnail_url( get_the_ID(), 'medium_large' ) . '"</td></tr></table></div>';
        echo '<div style="display: inline-block; position: absolute;"><div class="newsDate">' . get_the_date( 'j F Y' ) . '</div>';
        echo '<div class="newsTitle">' . get_the_title() . '</div>';
        echo '<div class="newsSubtitle"><p>' . get_the_excerpt() . '</p></div>';
    }
    echo '</div></div>';
} else {
    // no posts found
}

wp_reset_postdata();

echo do_shortcode('[get_posts_via_rest]'); 
?>