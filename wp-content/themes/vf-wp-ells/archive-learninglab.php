<?php
get_header();

?>
<div class="vf-u-display-none | used-for-search-index" data-swiftype-name="page-description" data-swiftype-type="text">
  <?php echo swiftype_metadata_description(); ?>
</div>
<div class="vf-grid vf-grid__col-3 | vf-content">
  <div class="vf-grid__col--span-2">
    <h1 class="vf-text vf-text-heading--1">Teacher training
    </h1>

    <h3>EMBL is committed to equipping science educators with the tools, knowledge, and confidence to inspire the next generation.</h3>
    <p>Open to science teachers across Europe and beyond, our professional development courses are completely free of charge and designed to bridge the gap between world-class research and your daily lessons.</p>

    <details class="vf-details">
      <summary class="vf-details--summary">Why choose EMBL teacher training?</summary>

      <div class="vf-content">
        <p>We turn complex, world-class scientific research into accessible classroom experiences. Get the expert knowledge, modern tools, and practical inspiration you need to bring biology to life for your students.</p>

        <p><strong>First-hand science, grounded in the basics:</strong> Stay at the forefront of discovery by learning directly from world-class EMBL scientists. We know there is no meaningful enhancement without a strong foundation, so our experts always start from the basics before diving into today's most exciting, cutting-edge advancements.</p>
        <p><strong>Ready-to-use, modular resources:</strong> Save hours of lesson prep. You will walk away with high-quality teaching resources designed to be taken straight into your classroom. Because every class is unique, our materials are highly flexible, modular, and aligned with international curricula so you can adapt them perfectly to your students' needs.</p>
        <p><strong>A vibrant global network:</strong> Teaching does not have to happen in a silo. When you join our courses, you plug into a worldwide community of passionate educators. Swap ideas, tackle shared challenges, and build lasting professional relationships with peers from across the globe.</p>
        <p><strong>Accessibility and recognition:</strong> Science is for everyone. To break down language barriers, we provide translations for our expert talks and teaching resources whenever possible. To top it all off, you will receive official certificates of attendance to validate your hard work and boost your professional development portfolio.</p>
      </div>
    </details>

    <details class="vf-details">
      <summary class="vf-details--summary">Flexible formats for busy schedules</summary>

      <div class="vf-content">
        <p>We know educators have packed and constantly shifting schedules. That is why we offer a variety of learning styles, allowing you to choose the format that best fits your life.</p>

        <p><strong>Train-the-Trainer Courses:</strong> Intensive, in-person workshops (2-3 days) designed to build deep practical skills in laboratory or bioinformatics techniques, empowering you to bring these insights back and train your local teaching communities.</p>
        <p><strong>Virtual LearningLABs:</strong> Interactive, virtual training courses spanning several weeks. These balance deep scientific dives with a schedule designed around working teachers.</p>
        <p><strong>On-demand Courses:</strong> Completely flexible, on-demand online learning that you can complete entirely at your own pace, whenever it suits you.</p>
        <p><strong>EMBLconnect Courses:</strong> Localised training sessions led by trained EMBL Ambassadors across our Member States, bringing world-class life science education and tailored resources directly to your regional teaching community in your local language.</p>
        <p><strong>Demand-based Workshops:</strong> Responsive, community-driven sessions organised by request. Built directly around the specific needs of educators, these tailored workshops provide targeted training on a particular scientific topic or teaching resource exactly when you need it.</p>
        <p><strong>Specialised Workshops:</strong> Occasional, exclusive explorations into niche and emerging fields of life sciences, such as our BIOcean5D teacher professional development workshops on aquatic biodiversity.</p>
      </div>
    </details>
    <hr class="vf-divider vf-u-margin__top--600">
    <p class="vf-text-body vf-text-body--2 vf-u-margin__top--600 vf-u-margin__bottom--600"><strong>Contact us:</strong> <a href="mailto:teacher-training@embl.org">teacher-training@embl.org</a></p>
  </div>
  <aside class="ells-training-sidebar" aria-label="<?php esc_attr_e('Teacher training additional content', 'vf-wp-ells'); ?>">
    <?php dynamic_sidebar('ells-teacher-training-sidebar'); ?>
  </aside>
</div>
<?php /*
    <section id="embl-course-matcher" class="vf-content vf-u-padding--600 vf-u-background-color--green vf-u-text-color--ui--white | vf-stack vf-stack--400" aria-labelledby="embl-course-matcher-heading">
      <h2 id="embl-course-matcher-heading" class="vf-text vf-text-heading--3 | vf-u-text-color--ui--white">Find your ideal course format</h2>
      <p class="vf-text-body vf-text-body--3 | vf-u-text-color--ui--white">Use the dropdowns to tell us about your schedule and goals, and we will recommend the best fit.</p>

      <div class="vf-form vf-stack vf-stack--400">
        <div class="vf-form__item">
          <label class="vf-form__label vf-u-text-color--ui--white vf-u-margin__bottom--100" for="course-matcher-availability">What does your availability look like?</label>
          <select id="course-matcher-availability" class="vf-form__select course-matcher-select">
            <option value="none">Select availability</option>
            <option value="flexible">I need total flexibility (learn on my own time)</option>
            <option value="weeks">I can commit to a few weeks online</option>
            <option value="intensive">2-3 days for an intensive workshop</option>
            <option value="local">I want something local or by request</option>
          </select>
        </div>

        <div class="vf-form__item">
          <label class="vf-form__label vf-u-text-color--ui--white" for="course-matcher-goal">What is your primary goal?</label>
          <select id="course-matcher-goal" class="vf-form__select course-matcher-select">
            <option value="none">Select primary goal</option>
            <option value="pace">Learn entirely at my own pace</option>
            <option value="interactive">Interactive learning with peers</option>
            <option value="practical">Build practical lab/bioinformatics skills</option>
            <option value="community">Bring training to my local community</option>
            <option value="niche">Explore a niche scientific field</option>
          </select>
        </div>

        <div id="course-matcher-result" class="vf-u-padding--400 vf-u-background-color--green--dark | vf-stack vf-stack--200 vf-u-text-color--ui--white vf-u-display-none" role="status" aria-live="polite">
          <h3 id="course-matcher-result-title" class="vf-text vf-text-heading--4 | vf-u-text-color--ui--white"></h3>
          <p id="course-matcher-result-description" class="vf-text-body vf-text-body--3 | vf-u-text-color--ui--white vf-u-margin__bottom--0"></p>
        </div>
      </div>
    </section>
  </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const availSelect = document.getElementById('course-matcher-availability');
        const goalSelect = document.getElementById('course-matcher-goal');
        const resultBox = document.getElementById('course-matcher-result');
        const resultTitle = document.getElementById('course-matcher-result-title');
        const resultDesc = document.getElementById('course-matcher-result-description');

        if (!availSelect || !goalSelect || !resultBox || !resultTitle || !resultDesc) {
            return;
        }

        function updateRecommendation() {
            const avail = availSelect.value;
            const goal = goalSelect.value;

            if (avail === 'none' && goal === 'none') {
                resultBox.classList.add('vf-u-display-none');
                return;
            }

            resultBox.classList.remove('vf-u-display-none');

            if (goal === 'niche') {
                resultTitle.textContent = 'Specialised Workshops';
                resultDesc.textContent = 'Occasional, exclusive explorations into niche and emerging fields of life sciences, such as our BIOcean5D teacher professional development workshops on aquatic biodiversity.';
            } else if (avail === 'flexible' || goal === 'pace') {
                resultTitle.textContent = 'On-demand Courses';
                resultDesc.textContent = 'Completely flexible, on-demand online learning that you can complete entirely at your own pace, whenever it suits you.';
            } else if (avail === 'weeks' || goal === 'interactive') {
                resultTitle.textContent = 'Virtual LearningLABs';
                resultDesc.textContent = 'Interactive, virtual training courses spanning several weeks. These balance deep scientific dives with a schedule designed around working teachers.';
            } else if (avail === 'intensive' || goal === 'practical') {
                resultTitle.textContent = 'Train-the-Trainer Courses';
                resultDesc.textContent = 'Intensive, in-person workshops (2-3 days) designed to build deep practical skills in laboratory or bioinformatics techniques, empowering you to bring these insights back and train your local teaching communities.';
            } else if (avail === 'local' || goal === 'community') {
                resultTitle.textContent = 'EMBLconnect & Demand-based Workshops';
                resultDesc.textContent = 'Localised training sessions led by trained EMBL Ambassadors, or responsive, community-driven sessions organised by request directly for your regional teaching community.';
            } else {
                resultTitle.textContent = 'Explore All Formats';
                resultDesc.textContent = 'Based on your selections, several of our formats might work for you! Check out our full catalogue to find the perfect fit.';
            }
        }

        availSelect.addEventListener('change', updateRecommendation);
        goalSelect.addEventListener('change', updateRecommendation);
    });
</script>
*/
?>

<section class="vf-content">
  <h3>Browse or filter all courses</h3>
  <?php
  $archive_browser = array(
    'id' => 'teacher-training-browser',
    'post_type' => 'learninglab',
    'summary_template' => 'partials/vf-summary-llab.php',
    'items_per_page' => 10,
    'result_label' => 'courses',
    'filters' => array(
      array(
        'key' => 'type',
        'aliases' => array('llabs-type', 'learninglab_llabs_type'),
        'label' => 'Type',
        'all_label' => 'All types',
        'taxonomy' => 'llabs-type',
        'acf_field' => 'labs_type',
        'options' => vf_wp_ells_term_options('llabs-type'),
      ),
      array(
        'key' => 'format',
        'aliases' => array('llabs-format'),
        'label' => 'Format',
        'all_label' => 'All formats',
        'taxonomy' => 'llabs-format',
        'acf_field' => 'labs_format',
        'options' => vf_wp_ells_term_options('llabs-format'),
      ),
      array(
        'key' => 'location',
        'aliases' => array('llabs-location'),
        'label' => 'Location',
        'all_label' => 'All locations',
        'taxonomy' => 'llabs-location',
        'acf_field' => 'labs_location',
        'options' => vf_wp_ells_term_options('llabs-location'),
      ),
    ),
  );
  include(locate_template('partials/archive-browser.php', false, false));
  ?>
</section>

<?php include(locate_template('partials/ells-footer.php', false, false)); ?>
