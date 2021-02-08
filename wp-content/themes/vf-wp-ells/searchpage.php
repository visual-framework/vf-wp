
<?php 
/**
 * Template Name: Search Page
 */
?> 
<?php get_header(); 

$total_results = $wp_query->found_posts;

?>

<section class="vf-hero vf-hero--primary vf-hero--block vf-hero--800 | vf-u-fullbleed | vf-u-margin__bottom--0" style="--vf-hero--bg-image: url('https://acxngcvroo.cloudimg.io/v7/https://www.embl.org/files/wp-content/uploads/Ells_Masthead_1000x600.png');  ">
  <div class="vf-hero__content | vf-stack vf-stack--400 ">
    <h2 class="vf-hero__heading">
      ELLS
    </h2>
    <p class="vf-hero__subheading">European Learning Laboratory for the Life Sciences</p>
  </div>
</section>
<?php

if (class_exists('VF_Navigation')) {
  VF_Plugin::render(VF_Navigation::get_plugin('vf_navigation'));
}

?>

<section class="embl-grid embl-grid--has-centered-content">
  <div></div>
  <div class="vf-content">

    <h1 class="vf-text-heading--1">Search</h1>

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
    </style>
    <div>
    <form role="search" method="get" class="vf-form  | vf-search vf-search--inline" action="<?php echo esc_url(home_url('/')); ?>">
  <div class="vf-form__item | vf-search__item">
    <input type="search" class="vf-form__input | vf-search__input" value="<?php echo esc_attr(get_search_query()); ?>" name="s">
  </div>
  <div class="vf-form__item | vf-search__item">
          <label class="vf-form__label vf-u-sr-only | vf-search__label" for="vf-form__select">Category</label>
          <select class="vf-form__select" id="vf-form__select" name="post_type" value="post_type">
            <option value="all" selected="" >Everything</option>
            <option value="teachingbase" name="post_type[]">TeachingBASE</option>
            <option value="insight-lecture" name="post_type[]">Insight Lecture</option>
            <option value="learninglab" name="post_type[]">LearningLabs</option>
          </select>
        </div>
  <input type="submit" class="vf-search__button | vf-button vf-button--primary vf-button--sm" value="<?php esc_attr_e('Search', 'vfwp'); ?>">
</form>

    </div>

  </div>
</section>







<?php get_footer(); ?>
