<?php

$vfwp_latest_posts_helpers = locate_template( 'blocks/vfwp-latest-posts/partials/helpers.php', false, false );

if ( $vfwp_latest_posts_helpers ) {
  require_once $vfwp_latest_posts_helpers;
}

$is_container = get_field( 'is_container' );
if ( null === $is_container ) {
  $is_container = true;
}
$is_container = (bool) $is_container;

$limit = get_field( 'limit' );
$post_type = get_field( 'post_type' );
$show_topics = get_field( 'show_topics' );
$show_excerpt = get_field( 'show_excerpt' );
$show_heading = get_field( 'show_heading' );

$heading_singular = get_field( 'heading_singular' );
$heading_singular = ! empty( $heading_singular ) ? trim( $heading_singular ) : __( 'Latest posts', 'vfwp' );

$heading_text = get_field( 'heading_text' );
$heading_url = get_field( 'heading_url' );

$heading_link = '';
$heading_target = '_self';

if ( ! empty( $heading_url ) && is_array( $heading_url ) ) {
  $heading_link = $heading_url['url'] ?? '';
  $heading_target = $heading_url['target'] ?? '_self';
} elseif ( ! empty( $heading_url ) && is_string( $heading_url ) ) {
  $heading_link = $heading_url;
} else {
  $heading_link = vfwp_latest_posts_get_default_heading_link( $post_type );
}

if ( empty( $post_type ) ) {
  $post_type = 'post';
}

$layout = get_field( 'layout' );
$grid = get_field( 'grid' );

$category = get_field( 'category' );
$tag = get_field( 'tag' );

$show_image = get_field( 'show_image' );
$show_location = get_field( 'show_location' );
$show_categories = get_field( 'show_categories' );

$vf_grid = '';
$embl_grid = '';
$has_outer_container = $is_container && ( ( 'list' === $layout && $show_heading ) || 'embl-grid' === $grid );

if ( 'columns' === $layout ) {
  $embl_grid = null;
  $vf_grid = 'class="vf-grid vf-grid__col-4 | vf-content"';
} else {
  $embl_grid = 'embl-grid--has-centered-content';
}
?>

<?php if ( $has_outer_container ) : ?>
<div class="embl-grid <?php echo esc_attr( $embl_grid ); ?> | vf-content">
    <?php if ( ! empty( $heading_singular ) ) : ?>
    <div class="vf-section-header">
        <h2 class="vf-section-header__heading">
            <a class="vf-section-header__heading vf-section-header__heading--is-link"
               target="<?php echo esc_attr( $heading_target ); ?>"
               href="<?php echo esc_url( $heading_link ); ?>">
                <?php echo esc_html( $heading_singular ); ?>
                <svg aria-hidden="true" class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="" fill-rule="nonzero"></path>
                </svg>
            </a>
        </h2>
        <?php if ( ! empty( $heading_text ) ) : ?>
        <p class="vf-section-header__text"><?php echo esc_html( $heading_text ); ?></p>
        <?php endif; ?>
    </div>
    <?php endif; ?>
<?php endif; ?>

<?php if ( $is_container && 'vf-grid' === $grid ) : ?>
    <?php if ( ! empty( $heading_singular ) ) : ?>
    <div class="vf-section-header">
        <h2 class="vf-section-header__heading">
            <a class="vf-section-header__heading vf-section-header__heading--is-link"
               target="<?php echo esc_attr( $heading_target ); ?>"
               href="<?php echo esc_url( $heading_link ); ?>">
                <?php echo esc_html( $heading_singular ); ?>
                <svg aria-hidden="true" class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="" fill-rule="nonzero"></path>
                </svg>
            </a>
        </h2>
        <?php if ( ! empty( $heading_text ) ) : ?>
        <p class="vf-section-header__text"><?php echo esc_html( $heading_text ); ?></p>
        <?php endif; ?>
    </div>
    <?php endif; ?>
<?php endif; ?>

<div <?php echo $vf_grid; ?>>

    <?php if ( 'list' === $layout ) : ?>
        <?php include( locate_template( 'blocks/vfwp-latest-posts/partials/embl-grid.php', false, false ) ); ?>
    <?php endif; ?>

    <?php if ( 'columns' === $layout ) : ?>
        <?php include( locate_template( 'blocks/vfwp-latest-posts/partials/columns-grid.php', false, false ) ); ?>
    <?php endif; ?>

</div>

<?php if ( $has_outer_container ) : ?>
</div>
<?php endif; ?>
