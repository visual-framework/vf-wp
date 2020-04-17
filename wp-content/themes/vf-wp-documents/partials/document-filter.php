<?php

// Get all "Topic" terms
$topic_terms = get_terms(
  array(
    'taxonomy'   => 'document_topics',
    'hide_empty' => false,
  )
);

// Get all "Type" terms
$type_terms = get_terms(
  array(
    'taxonomy'   => 'document_types',
    'hide_empty' => false,
  )
);

// Get active taxonomy filters
$topic_selected = explode(',', get_query_var('document_topics'));
$type_selected = explode(',', get_query_var('document_types'));

// Get taxonomy labels
$topic_labels = vf_wp_document_topic_labels();
$type_labels = vf_wp_document_type_labels();

// Default empty date filter
$date_options = array();

// Get HTML for monthy archives
$monthly_archives = wp_get_archives(array(
  'type'      => 'monthly',
  'format'    => 'html',
  'echo'      => 0,
  'post_type' => 'document'
));

// Attempt to get year/month filter
$year = (string) get_query_var('year');
$month = str_pad(get_query_var('monthnum'), 2, '0', STR_PAD_LEFT);

// Use the `m` query filter (in case `s` search query is set)
$m = get_query_var('m');
if (strlen($m) === 6) {
  $year = substr($m, 0, 4);
  $month = substr($m, 4, 2);
}

// Parse all year/month values from URLs to create date filter
if (preg_match_all(
  '#/([0-9]{4})/([0-9]{1,2})#',
  $monthly_archives, $matches, PREG_SET_ORDER
)) {
  foreach ($matches as $match) {
    $value = "{$match[1]}{$match[2]}";
    $label = date('F Y', strtotime("{$match[1]}-{$match[2]}-01"));
    $date_options[] = array(
      'value'    => $value,
      'label'    => $label,
      'selected' => $value == "{$year}{$month}"
    );
  }
}

// $archive = get_post_type_archive_link('document');
$archive = home_url('/?post_type=document');

?>
<form action="<?php echo esc_url($archive); ?>" method="get">
  <div>

    <input type="hidden" name="post_type" value="document">

    <?php
    $search = trim(get_search_query());
    if ( ! empty($search)) {
    ?>
    <input type="hidden" name="s" value="<?php echo esc_attr($search); ?>">
    <?php } ?>

    <select class='vf-form__select' id='vf-form__select' name="document_topics">
      <option value="">
        <?php echo esc_html($topic_labels['all_items']); ?>
      </option>
      <?php
      foreach ($topic_terms as $term) {
        $selected = in_array($term->slug, $topic_selected) ? 'selected="selected"' : '';
      ?>
      <option value="<?php echo esc_attr($term->slug); ?>" <?php echo $selected ?>>
        <?php echo esc_html($term->name); ?>
      </option>
      <?php } ?>
    </select>

    <select class='vf-form__select' id='vf-form__select' name="document_types">
      <option value="">
        <?php echo esc_html($type_labels['all_items']); ?>
      </option>
      <?php
      foreach ($type_terms as $term) {
        $selected = in_array($term->slug, $type_selected) ? 'selected="selected"' : '';
      ?>
      <option value="<?php echo esc_attr($term->slug); ?>" <?php echo $selected ?>>
        <?php echo esc_html($term->name); ?>
      </option>
      <?php } ?>
    </select>

    <?php if ( ! empty($date_options)) { ?>
    <select class='vf-form__select' id='vf-form__select' name="m">
      <option value=""><?php echo esc_attr( __( 'Date' ) ); ?></option>
      <?php
      foreach ($date_options as $date) {
        $selected = $date['selected'] ? 'selected="selected"' : '';
      ?>
      <option value="<?php echo esc_attr($date['value']); ?>" <?php echo $selected ?>>
        <?php echo esc_html($date['label']); ?>
      </option>
      <?php } ?>
    </select>
    <?php } ?>

    <button class="vf-button vf-button--tertiary" type="submit">
      <?php esc_html_e('Apply filter', 'theme'); ?>
    </button>

  </div>
</form>
