
<?php
$counter = 2;
?>
<fieldset class="vf-form__fieldset vf-stack vf-stack--400">
  <legend class="vf-form__legend">Year</legend>

  <div class="vf-form__item vf-stack">
  <select class="vf-form__select" id="vf-form__select" data-jplist-control="select-filter" data-group="news" >
  <option value="0" data-path="default" >All</option>
    <?php echo wp_get_archives(array('type' => 'yearly', 'format' => 'custom', 'echo'=>'0', )) ?>
    </select>
    </div>
  </fieldset>



