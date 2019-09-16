<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! interface_exists('VF_Gutenberg_Block') ) :

interface VF_Gutenberg_Block {

  public function key();

  public function title();

  public function render(array $block);

  public function fields();

} // VF_Gutenberg_Block

endif;

?>
