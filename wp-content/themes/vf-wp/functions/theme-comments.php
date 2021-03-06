<?php
/**
 * Theme Comments
 * Sub-class initiated by `VF_Theme`
 * Provides the global `vf_comment_form` template function
 * for comments form with Visual Framework markup.
 */
if( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Theme_Comments') ) :

class VF_Theme_Comments {

  public function __construct() {

    add_filter(
      'comment_form_defaults',
      array($this, 'comment_form_defaults')
    );
    add_filter(
      'comment_form_fields',
      array($this, 'comment_form_fields')
    );
  }

  /**
   * Add classes to default messages
   */
  public function comment_form_defaults($defaults) {
    if (array_key_exists('must_log_in', $defaults)) {
      $defaults['must_log_in'] = str_replace(
        'class="must-log-in"',
        'class="must-log-in vf-form__helper"',
        $defaults['must_log_in']
      );
    }
    if (array_key_exists('logged_in_as', $defaults)) {
      $defaults['logged_in_as'] = str_replace(
        'class="logged-in-as"',
        'class="logged-in-as vf-form__helper"',
        $defaults['logged_in_as']
      );
    }
    if (array_key_exists('comment_notes_before', $defaults)) {
      $defaults['comment_notes_before'] = str_replace(
        'class="comment-notes"',
        'class="comment-notes vf-form__helper"',
        $defaults['comment_notes_before']
      );
    }
    return $defaults;
  }

  /**
   * Filter comment form fields
   */
  public function comment_form_fields($fields) {
    foreach ($fields as $k => $html) {
      $fields[$k] = $this->comment_form_classes($html, $k);
    }
    return $fields;
  }

  /**
   * Add `vf-form` classes to comment form field
   * Example:

      <div class="vf-form__item">
        <label for="text" class="vf-form__label">Form Label</label>
        <input type="text" id="text" class="vf-form__input">
        <p class="vf-form__helper">Form helper text</p>
      </div>

   */
  public function comment_form_classes($html, $k) {
    if (in_array($k, array('author', 'email', 'url'))) {
      if (preg_match(
        '#<input\s([^>/]*)/?>#',
        $html, $matches
      )) {
        $input = '<input class="vf-form__input" ' . trim($matches[1]) . '>';
        $html = str_replace($matches[0], $input, $html);
      }
    }
    if ($k === 'comment') {
      if (preg_match(
        '#<textarea\s([^>]*)>(.*?)</textarea>#',
        $html, $matches
      )) {
        $textarea = '<textarea class="vf-form__textarea" ' . trim($matches[1]) . '>' . $matches[2] . '</textarea>';
        $html = str_replace($matches[0], $textarea, $html);
      }
    }
    if (preg_match(
      '#<p\s([^>]*)>(.*?)</p>#',
      $html, $matches
    )) {
      $html = '<div class="comment-form-' . $k . ' vf-form__item">' . $matches[2] . '</div>';
    }
    if (preg_match(
      '#<label\s([^>]*)>(.*?)</label>#',
      $html, $matches
    )) {
      $label = '<label class="vf-form__label" for="' . $k . '">' . $matches[2] . '</label>';
      $html = str_replace($matches[0], $label, $html);
    }
    return $html;
  }

  static function comment_form() {
    comment_form(
      array(
        'logged_in_as' => null,
        'title_reply'  => null,
        'class_form'   => 'comment-form vf-form',
        'class_submit' => 'vf-button vf-button--primary'
        // 'submit_button'        => '<input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" />',
        // 'submit_field'         => '<p class="form-submit">%1$s %2$s</p>',
      )
    );
  }

} // VF_Theme_Comments

endif;

// /**
//  * Remove "Website" field from comments form
//  */
// add_filter('comment_form_default_fields', 'vf__comment_form_default_fields');

// function vf__comment_form_default_fields($fields) {
//   unset($fields['url']);
//   return $fields;
// }

/**
 * Wrap template function for `comment_form()`
 */
if ( ! function_exists('vf_comment_form')) {
  function vf_comment_form() {
    VF_Theme_Comments::comment_form();
  }
}

?>
