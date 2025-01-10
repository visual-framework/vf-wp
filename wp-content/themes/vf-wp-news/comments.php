<?php

$count = get_comments_number();
$title = get_the_title();

?>
<section class="vf-discussion">
  <h3 class="vf-discussion__title"><?php
    printf(
      _nx(
        '%1$s comment on %2$s',
        '%1$s comments on %2$s',
        'comment count',
        'comments title',
        'vfwp'
      ),
      "<span>{$count}</span>",
      "‘{$title}’"
    );
  ?></h3>
  <ol class="vf-discussion__list | vf-list"><?php
    wp_list_comments(array(
      'walker' => new VFWP_Walker_Comment(),
      'style' => 'ol'
    ));
  ?></ol>
</section>
<?php
if (comments_open()) {
?>
<hr class="vf-divider">
<h2 class="vf-text vf-text--heading-l"><?php _e('Leave a Reply', 'vfwp'); ?></h2>
<?php
}
vf_comment_form();
?>
