<?php
$cities = ['Aarhus',
'Athens',
'Bergen',
'Bilbao',
'Bremerhaven',
'Galway',
'Klaipeda',
'Kotor',
'Kristineberg / Gothenburg',
'Liepaja',
'Lorient',
'Mallorca',
'Naples',
'Oostende',
'Porto',
'Roscoff',
'Sopot',
'Southampton',
'Split',
'Tallinn',
'Texel',
'Turku']; ?>

<form class="vf-stack vf-stack-400 | vf-u-margin__bottom--400">
      <fieldset class="vf-form__fieldset vf-stack vf-stack--400">
        <legend class="vf-form__legend">Location</legend>
        <div class="vf-form__item vf-stack">
          <select class="vf-form__select" id="vf-form__select" data-jplist-control="select-filter"
            data-group="news">
            <option value="0" data-path="default" data-name="default" data-group="news">All</option>
            <?php foreach ($cities as $city): ?>
               
<option value="city-<?php echo $city; ?>" data-path=".city-<?php echo $city; ?>" data-name="default" data-group="news"><?php echo $city; ?>
</option>

        <?php endforeach; ?>

          </select>
        </div>
      </fieldset>
    </form>

