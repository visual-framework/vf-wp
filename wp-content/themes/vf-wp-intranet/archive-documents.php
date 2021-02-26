<?php

get_header();

?>


<section class="vf-intro | vf-u-margin__bottom--0">
  <div>
    <!-- empty -->
  </div>
  <div class="vf-stack">
    <h1 class="vf-intro__heading">
      <?php wp_title(''); ?>
    </h1>
    <p class="vf-lede">The repository holds the digital copies of internal EMBL documents, reports, brochures
      and various other publications.</p>
    <p class="vf-intro__text"><?php
      printf(
        esc_html__('There are currently %1$d documents in the repository', 'vfwp'),
        get_all_documents_posts()
      ); ?></p>
    <br>
  </div>
</section>

<style>
  .vf-search--inline .vf-search__input {
    min-width: 300px;
  }

  .vf-search--inline .vf-form__select {
    padding: 8px 12px;
  }

  .vf-search--inline .vf-search__item:not(:first-child) {
    padding-left: 10px;
  }

  .vf-search--inline .vf-search__button {
    top: -3px;
  }

</style>
<div
  class="embl-grid embl-grid--has-centered-content | vf-u-background-color--grey--lightest vf-u-fullbleed vf-u-background-color-ui--off-white vf-u-padding__top--500 vf-u-padding__bottom--500 | vf-u-margin__bottom--800">
  <div></div>
  <form role="search" method="get" class="vf-form  | vf-search vf-search--inline"
    action="<?php echo esc_url(home_url('/')); ?>">
    <div class="vf-form__item | vf-search__item">
      <input type="search" class="vf-form__input | vf-search__input" placeholder="Search for a document"
        value="<?php echo esc_attr(get_search_query()); ?>" name="s">
    </div>
    <div class="vf-form__item | vf-search__item" style="display: none;">
      <select class="vf-form__select" id="vf-form__select" name="post_type" value="post_type">
        <option value="documents" name="post_type[]">Document</option>
      </select>
    </div>
    <input type="submit" class="vf-search__button | vf-button vf-button--primary vf-button--sm"
      value="<?php esc_attr_e('Search', 'vfwp'); ?>">
  </form>
</div>

<section class="embl-grid embl-grid--has-centered-content">
  <div>
    <?php include(locate_template('partials/document-filter.php', false, false)); ?>
  </div>
  <div>
    <h4 class="vf-text vf-text-heading--4 vf-u-margin__top--0">Recently added:</h4>
    <?php
        if ( have_posts() ) {
          while ( have_posts() ) {
            the_post();
            include(locate_template('partials/vf-summary--document.php', false, false)); 
            if ( ! $vf_theme->is_last_post()) {
                echo '<hr class="vf-divider">';
              }
          }
        } else {
          echo '<p>', __('No posts found', 'vfwp'), '</p>';
        } ?>
    <div class="vf-grid"> <?php vf_pagination();?></div>
  </div>
</section>

<?php

get_footer();

?>
