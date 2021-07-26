<?php



// Default empty date filter
$date_options = array();

// Get HTML for monthy archives
$yearly_archives = wp_get_archives(array(
  'type'      => 'yearly', # monthly for month and year archives
  'format'    => 'html',
  'echo'      => 0,
  'post_type' => 'industry_event'
));

// Attempt to get year/month filter
$year = (string) get_query_var('year');
# $month = str_pad(get_query_var('monthnum'), 2, '0', STR_PAD_LEFT);

// Use the `m` query filter (in case `s` search query is set)
$m = get_query_var('m');
if (strlen($m) === 6) {
  $year = substr($m, 0, 4);
#  $month = substr($m, 4, 2);
}

// to check permalinks structure '#m=([0-9]{4})([0-9]{1,2})#',

// Parse all year/month values from URLs to create date filter
if (preg_match_all(
  '#/([0-9]{4})#', # '#/([0-9]{4})/([0-9]{1,2})#' for month and year archive
  $yearly_archives, $matches, PREG_SET_ORDER
)) {

  foreach ($matches as $match) {
    $value = "{$match[1]}";
    $label = "{$match[1]}";
    $date_options[] = array(
      'value'    => $value,
      'label'    => $label,
      'selected' => $value == "{$year}"
    );
  }
}

// $archive = get_post_type_archive_link('document');
$archive = home_url('/?post_type=industry_event');

?>
<form class="vf-stack vf-stack-400" action="<?php echo esc_url($archive); ?>" method="get">

    <input type="hidden" name="post_type" value="industry_event">

    <?php
    $search = trim(get_search_query());
    if ( ! empty($search)) {
    ?>
    <input type="hidden" name="s" value="<?php echo esc_attr($search); ?>">
    <?php } ?>




    <fieldset class="vf-form__fieldset vf-stack vf-stack--400">
      <label class="vf-form__label">Year</label>

      <?php if ( ! empty($date_options)) { ?>
      <select class='vf-form__select' id='vf-form__select' name="m" style="padding: 3px 4px; width: 243px;">
        <option value=""><?php echo esc_attr( __( 'Select year' ) ); ?></option>
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
    </fieldset>

    <button class="vf-button vf-button--primary vf-button--sm" type="submit">
      <?php esc_html_e('Apply filters', 'theme'); ?>
    </button>

    <a class="vf-button vf-button--sm vf-button--tertiary" href="<?php echo get_home_url() . '/insight-lecture'; ?>">
      <?php esc_html_e('Reset filters', 'theme'); ?>
    </a>
</form>
