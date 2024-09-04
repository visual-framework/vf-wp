<h3 class="vf-links__heading">Top posts</h3>
<div class="top-posts">
	<?php $popular = new WP_Query(array('posts_per_page'=>3, 'meta_key'=>'popular_posts', 'orderby'=>'meta_value_num', 'order'=>'DESC'));
	while ($popular->have_posts()) : $popular->the_post(); 
	include(locate_template('partials/vf-summary--article-no-thumbnail.php', false, false));
	?>
	<?php endwhile; wp_reset_postdata(); ?>
</div>
<h3 class="vf-links__heading">Picture of the week</h3>
<div class="pow widget">
<?php $my_query = new WP_Query( 'category_name=picture-week&posts_per_page=1' );
while ( $my_query->have_posts() ) : $my_query->the_post();
$do_not_duplicate = $post->ID; 
		        include(locate_template('partials/vf-summary--pow.php', false, false)); ?>
<?php endwhile; ?>
	<hr class="vf-divider">
	</div>
<div class="in-print widget">
	<h3 class="vf-links__heading">In-print</h3>
	<img src="wp-content/uploads/2019/10/inprint.png">
	<p>Looking for past print editions of EMBLetc? Browse our archive, going back 20 years.</p>
	<a class="vf-link" href="#">PDF Archive</a>
</div>
	<hr class="vf-divider">
<div class="vf-links">
    <h3 class="vf-links__heading">Archive</h3>
    <ul class="vf-links__list | vf-list">
        <li class="vf-list__item">
            <a class="vf-list__link" href="JavaScript:Void(0);">
        <?php wp_get_archives(array( 'type' => 'monthly', 'limit' => 6 )) ?>
      </a>
        </li>
		    </ul>
</div>
<div class="twitter widget">
      <a class="twitter-timeline" data-width="370" data-height="400"
        href="https://twitter.com/embl?ref_src=twsrc%5Etfw" data-tweet-limit="1">Tweets by embl</a>
      <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
    </div>	
	<hr class="vf-divider">


