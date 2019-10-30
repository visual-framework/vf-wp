<?php

if( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Walker_Comment') ) :

// Extend default Walker
class VF_Walker_Comment extends Walker_Comment {

  /**
   * Empty string to pass by reference to parent methods and ignore
   */
  private $tmp;

  /**
   * Starts the list before the elements are added.
   */
  public function start_lvl( &$output, $depth = 0, $args = array() ) {
    parent::start_lvl($this->tmp, $depth, $args);
    $output .= '<ol class="vf-discussion__list | vf-list">' . "\n";
  }

  /**
   * Ends the list of items after the elements are added.
   */
  public function end_lvl( &$output, $depth = 0, $args = array() ) {
    parent::start_lvl($this->tmp, $depth, $args);
    $output .= "</ol><!-- .children -->\n";
  }

  protected function html5_comment( $comment, $depth, $args ) {
    $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
    $class = 'vf-discussion__item';
    if ($this->has_children) {
      $class .= ' parent';
    }
?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $class, $comment ); ?>>
      <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">

        <div class="comment-author vcard | vf-discussion__meta">
          <?php
          if ( 0 != $args['avatar_size'] ) {
            $avatar = get_avatar( $comment, '120' );
            $avatar = preg_replace(
              '#class=[\'"]([^"]+?)[\'"]#',
              'class="$1 | vf-discussion__author-avatar"',
              $avatar
            );
            $avatar = preg_replace(
              '#(width|height)=[\'"][^"]*?[\'"]#',
              '',
              $avatar
            );
            echo $avatar;
          }
          ?>
          <?php
            /* translators: %s: comment author link */
            printf( __( '<cite class="fn | vf-discussion__author">%s</cite> <span class="says">says:</span>' ),
              get_comment_author( $comment )
            );
          ?>
          <p class="vf-discussion__date">
            <time datetime="<?php comment_time( 'c' ); ?>"><?php
              comment_date( '', $comment );
            ?></time>
          </p>
        </div>

        <div class="comment-content | vf-discussion__comment | vf-content">
          <?php if ( '0' == $comment->comment_approved ) : ?>
          <p class="comment-awaiting-moderation">
            <strong><?php _e( 'Your comment is awaiting moderation.' ); ?></strong>
          </p>
          <?php endif; ?>
          <?php comment_text(); ?>
        </div>

        <?php
        $reply = get_comment_reply_link( array_merge( $args, array(
          'add_below' => 'div-comment',
          'depth'     => $depth,
          'max_depth' => $args['max_depth'],
          'before'    => '',
          'after'     => ''
        ) ) );
        if ( ! vf_html_empty($reply)) {
          $reply = preg_replace(
            '#class=[\'"]([^"]+?)[\'"]#',
            'class="$1 | vf-button vf-button--outline--primary | vf-discussion__reply"',
            $reply
          );
          echo "<div class=\"reply\">{$reply}</div>";
        }
        ?>
      </article>
<?php
  }

} // VF_Walker_Comment

endif;

?>
