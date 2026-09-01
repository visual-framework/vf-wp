<?php
/*
Template Name: VF Chatbot
Template Post Type: page
*/

if (!function_exists('vfwp_intranet_chatbot_dismiss_icon')) {
  function vfwp_intranet_chatbot_dismiss_icon() {
    return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
      <title>dismiss banner</title>
      <path d="M14.3,12.179a.25.25,0,0,1,0-.354l9.263-9.262A1.5,1.5,0,0,0,21.439.442L12.177,9.7a.25.25,0,0,1-.354,0L2.561.442A1.5,1.5,0,0,0,.439,2.563L9.7,11.825a.25.25,0,0,1,0,.354L.439,21.442a1.5,1.5,0,0,0,2.122,2.121L11.823,14.3a.25.25,0,0,1,.354,0l9.262,9.263a1.5,1.5,0,0,0,2.122-2.121Z" />
    </svg>';
  }
}

if (!function_exists('vfwp_intranet_chatbot_feedback_template')) {
  function vfwp_intranet_chatbot_feedback_template($options) {
    ?>
    <div class="vf-chatbot-feedback__form vf-u-margin__top--400">
      <div class="vf-chatbot-feedback__form-content vf-u-padding--400">
        <div class="vf-chatbot-feedback__form-content-header">
          <div class="vf-chatbot-feedback__title"><?php esc_html_e('Tell us more (optional)', 'vfwp'); ?></div>
          <button role="button" class="vf-chatbot-feedback__form-close vf-button vf-button--icon vf-button--dismiss | vf-banner__button" type="button" aria-label="<?php esc_attr_e('Close feedback form', 'vfwp'); ?>" data-vf-js-feedback-form-close>
            <?php echo vfwp_intranet_chatbot_dismiss_icon(); ?>
          </button>
        </div>
        <div class="vf-chatbot-feedback__options">
          <?php foreach ($options as $option) : ?>
            <button class="vf-chatbot-feedback__option" data-feedback-option="<?php echo esc_attr($option['id']); ?>">
              <?php echo esc_html($option['label']); ?>
            </button>
          <?php endforeach; ?>
        </div>
        <label id="vf-chatbot-feedback-comment-title" for="vf-chatbot-feedback-comment" class="vf-chatbot-feedback__comment-title"><?php esc_html_e('Comments', 'vfwp'); ?></label>
        <textarea id="vf-chatbot-feedback-comment" aria-labelledby="vf-chatbot-feedback-comment-title" class="vf-chatbot-feedback__comment" rows="4"></textarea>
        <button type="submit" class="vf-chatbot-feedback__submit vf-u-padding--200" data-vf-js-feedback-submit>
          <?php esc_html_e('Submit', 'vfwp'); ?>
        </button>
      </div>
    </div>
    <?php
  }
}

$vf_chatbot_asset_base = trailingslashit(get_template_directory_uri()) . 'assets/assets/vf-chatbot/assets/';
$vf_chatbot_title = __('AI Assistant', 'vfwp');
$vf_chatbot_disclaimer_url = 'https://www.ebi.ac.uk/data-protection/privacy-notice/embl-ebi-public-website/';
$vf_chatbot_feedback_url = 'https://embl.service-now.com/esc?id=sc_cat_item&sys_id=5eeb8eb91b92e650b376da88b04bcbc1';
$vf_chatbot_disclaimer = sprintf(
  __('Disclaimer: This chatbot is designed to assist you with general information and basic inquiries. See our <a class="vf-banner__link" target="_blank" rel="noopener noreferrer" aria-label="disclaimer notes (opens in new tab)" href="%s">disclaimer notes</a>.', 'vfwp'),
  esc_url($vf_chatbot_disclaimer_url)
);
$vf_chatbot_footnote = sprintf(
  __('Review AI generated content for accuracy. <a class="vf-link" target="_blank" rel="noopener noreferrer" aria-label="Leave feedback (opens in new tab)" href="%s">Leave feedback</a>.', 'vfwp'),
  esc_url($vf_chatbot_feedback_url)
);

$vf_chatbot_config = array(
  'type'                      => 'standalone',
  'title'                     => $vf_chatbot_title,
  'welcome_logo'              => true,
  'welcome_message'           => __("Welcome! I'm here to help", 'vfwp'),
  'welcome_logo_alt'          => $vf_chatbot_title,
  'welcome_suggestions_title' => __('Try asking me:', 'vfwp'),
  'input_placeholder'         => __('Ask me ...', 'vfwp'),
  'welcome_max_suggestions'   => 4,
  'disclaimer'                => $vf_chatbot_disclaimer,
  'footnote'                  => $vf_chatbot_footnote,
  'icons'                     => array(
    'assistant_avatar' => $vf_chatbot_asset_base . 'vf-chatbot--icon-16x16-dark-green.svg',
    'user_avatar'      => $vf_chatbot_asset_base . 'vf-chatbot--avatar-user.svg',
    'send_button'      => $vf_chatbot_asset_base . 'vf-chatbot--icon-send.svg',
    'main_logo_url'    => $vf_chatbot_asset_base . 'vf-chatbot--icon-32x32-dark-green.svg',
  ),
  'api'                       => array(
    'chat_endpoint'     => esc_url_raw(rest_url('vfwp/v1/chat')),
    'feedback_endpoint' => false,
    'qa_data_url'       => $vf_chatbot_asset_base . 'vf-chatbot-qa.json',
    'headers'           => array(
      'Content-Type' => 'application/json',
      'X-WP-Nonce'   => wp_create_nonce('wp_rest'),
      'Authorization' => '',
    ),
    'timeout'           => 10000,
  ),
  'features'                  => array(
    'enable_welcome'               => true,
    'enable_feedback'              => true,
    'enable_sources'               => true,
    'enable_sources_custom_format' => false,
    'enable_welcome_suggestions'   => true,
    'enable_typing_indicator'      => true,
    'enable_disclaimer'            => true,
    'enable_predefined_qa'         => true,
    'enable_fallback_responses'    => true,
    'enable_qa_data_loading'       => true,
    'enable_instant_feedback'      => false,
    'enable_conversation_history'  => true,
  ),
  'behavior'                  => array(
    'auto_scroll'    => true,
    'typing_delay'   => 800,
    'show_scrollbar' => false,
  ),
  'selectorContext'           => array(
    'chatbotRoutes' => array(
      'multiSelect'              => true,
      'maxMultiSelect'           => 3,
      'showSearch'               => true,
      'showSearchThreshold'      => 5,
      'showAllServices'          => true,
      'showAllServicesSelected'  => true,
      'routes'                   => $vf_chatbot_asset_base . 'vf-chatbot-selector-services.json',
      'placeholder'              => __('Select services', 'vfwp'),
      'title'                    => __('Services', 'vfwp'),
      'selector_logo_url'        => $vf_chatbot_asset_base . 'vf-chatbot--icon-24x24-dark-green.svg',
      'selector_logo_title'      => $vf_chatbot_title,
    ),
  ),
);
$vf_chatbot_config_json = wp_json_encode($vf_chatbot_config);

$vf_chatbot_positive_feedback_options = array(
  array('id' => 'accurate', 'label' => __('Accurate', 'vfwp')),
  array('id' => 'easy', 'label' => __('Easy to understand', 'vfwp')),
  array('id' => 'formatted', 'label' => __('Well formatted', 'vfwp')),
);
$vf_chatbot_negative_feedback_options = array(
  array('id' => 'inaccurate', 'label' => __('Inaccurate answer', 'vfwp')),
  array('id' => 'nocontext', 'label' => __('Did not use context', 'vfwp')),
  array('id' => 'poorformat', 'label' => __('Poorly formatted', 'vfwp')),
);

get_header();

wp_add_inline_script(
  'vf-scripts',
  'window.config = ' . wp_json_encode(array('type' => 'standalone')) . ';',
  'before'
);
?>

<main id="content">
  <?php if (post_password_required(get_the_ID())) : ?>
    <section class="embl-grid embl-grid--has-centered-content | vf-u-padding__top--400 vf-u-padding__bottom--800">
      <div></div>
      <div class="vf-content">
        <?php echo get_the_password_form(get_the_ID()); ?>
      </div>
      <div></div>
    </section>
  <?php else : ?>
    <!-- Standalone Chatbot -->
    <div class="vf-content vf-chatbot-standalone-container"
      data-vf-js-chatbot-standalone-container
      data-vf-chatbot-config="<?php echo esc_attr($vf_chatbot_config_json); ?>">

      <div class="vf-chatbot-standalone__header">
        <div class="vf-chatbot-standalone__header-left">
          <div
            class="vf-chatbot-selector"
            data-vf-js-chatbot-selector
            data-routes-path="<?php echo esc_url($vf_chatbot_config['selectorContext']['chatbotRoutes']['routes']); ?>"
            data-multiselect="true"
            data-max-multiselect="3"
            data-show-search="true"
            data-show-all-services="true"
            data-show-all-services-selected="true">
            <button class="vf-chatbot-selector__title" data-vf-js-selector-toggle>
              <img src="<?php echo esc_url($vf_chatbot_config['selectorContext']['chatbotRoutes']['selector_logo_url']); ?>" alt="<?php echo esc_attr($vf_chatbot_title); ?>">
              <div class="vf-chatbot-selector__title-content vf-u-margin__left--200">
                <span class="vf-chatbot-selector__main-text"><?php echo esc_html($vf_chatbot_title); ?></span>
                <span class="vf-chatbot-selector__title-text"><?php echo esc_html($vf_chatbot_config['selectorContext']['chatbotRoutes']['title']); ?></span>
              </div>
              <span class="vf-chatbot-selector__chevron">
                <svg width="32" height="31" viewBox="0 0 32 31" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                  <g clip-path="url(#clip0_vfwp_chatbot_selector)">
                    <path d="M15.999 19.0975C15.7378 19.098 15.479 19.0468 15.2377 18.9468C14.9963 18.8469 14.7771 18.7001 14.5926 18.5151L8.32863 11.9279C8.21951 11.8137 8.13399 11.6791 8.07698 11.5318C8.01998 11.3845 7.99261 11.2274 7.99645 11.0695C8.00028 10.9116 8.03525 10.756 8.09934 10.6117C8.16342 10.4673 8.25537 10.337 8.36992 10.2283C8.48446 10.1195 8.61934 10.0344 8.76683 9.97791C8.91432 9.92139 9.07152 9.89454 9.2294 9.89889C9.38729 9.90325 9.54277 9.93872 9.68692 10.0033C9.83107 10.0678 9.96106 10.1602 10.0694 10.2751L15.7094 16.2143C15.7467 16.2537 15.7916 16.2851 15.8414 16.3066C15.8912 16.3281 15.9448 16.3391 15.999 16.3391C16.0533 16.3391 16.1069 16.3281 16.1567 16.3066C16.2065 16.2851 16.2514 16.2537 16.2886 16.2143L21.9286 10.2751C22.037 10.1602 22.167 10.0678 22.3112 10.0033C22.4553 9.93872 22.6108 9.90325 22.7687 9.89889C22.9266 9.89454 23.0838 9.92139 23.2312 9.97791C23.3787 10.0344 23.5136 10.1195 23.6282 10.2283C23.7427 10.337 23.8347 10.4673 23.8987 10.6117C23.9628 10.756 23.9978 10.9116 24.0016 11.0695C24.0055 11.2274 23.9781 11.3845 23.9211 11.5318C23.8641 11.6791 23.7786 11.8137 23.6694 11.9279L17.439 18.4991C17.2503 18.6888 17.0259 18.8394 16.7788 18.9421C16.5316 19.0448 16.2667 19.0976 15.999 19.0975Z" fill="#707372" />
                  </g>
                  <defs>
                    <clipPath id="clip0_vfwp_chatbot_selector">
                      <rect width="16" height="16" fill="white" transform="translate(8 6.5)" />
                    </clipPath>
                  </defs>
                </svg>
              </span>
            </button>

            <div class="vf-chatbot-selector__dropdown" data-vf-js-selector-dropdown>
              <div class="vf-chatbot-selector__search">
                <label class="vf-u-sr-only" id="vf-chatbot-selector-search-label" for="vf-chatbot-selector-search"><?php esc_html_e('Type to search', 'vfwp'); ?></label>
                <input type="text" id="vf-chatbot-selector-search" aria-labelledby="vf-chatbot-selector-search-label" placeholder="<?php echo esc_attr($vf_chatbot_config['selectorContext']['chatbotRoutes']['placeholder']); ?>" data-vf-js-selector-search>
              </div>

              <div class="vf-chatbot-selector__header">
                <span data-max-select="3"><?php esc_html_e('Select up to 3 services', 'vfwp'); ?></span>
                <a href="#" class="vf-chatbot-selector__clear" role="button" data-vf-js-selector-clear><?php esc_html_e('Clear all', 'vfwp'); ?></a>
              </div>
              <ul class="vf-chatbot-selector__list" data-vf-js-chatbot-selector-list>
                <!-- Routes will be populated dynamically via JavaScript -->
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div class="vf-chatbot-standalone | vf-u-background-color-ui--grey--light vf-u-margin__bottom--400" data-vf-js-chatbot-standalone>
        <div class="vf-chatbot-standalone__content" data-vf-js-chatbot-standalone-content>

          <!-- Welcome Screen -->
          <div role="region" aria-label="<?php esc_attr_e('Chatbot welcome screen', 'vfwp'); ?>"
            class="vf-chatbot-welcome"
            data-vf-js-chatbot-welcome
            data-max-questions="<?php echo esc_attr($vf_chatbot_config['welcome_max_suggestions']); ?>"
            data-enable-qa-data-loading="true"
            data-enable-predefined-qa="true"
            data-enable-fallback-responses="true"
            data-qa-data-url="<?php echo esc_url($vf_chatbot_config['api']['qa_data_url']); ?>">
            <div class="vf-chatbot-welcome__content">
              <div class="vf-chatbot-welcome__logo">
                <img src="<?php echo esc_url($vf_chatbot_config['icons']['main_logo_url']); ?>" alt="<?php echo esc_attr($vf_chatbot_title); ?>">
              </div>
              <h1 class="vf-chatbot-welcome__title"><?php echo esc_html($vf_chatbot_title); ?></h1>
              <div class="vf-chatbot-welcome__message">
                <?php echo esc_html($vf_chatbot_config['welcome_message']); ?>
              </div>
            </div>
            <div class="vf-chatbot-welcome__suggestions">
              <p class="vf-chatbot-welcome__suggestions-title vf-u-margin__bottom--200"><?php echo esc_html($vf_chatbot_config['welcome_suggestions_title']); ?></p>
              <div class="vf-chatbot-welcome__suggestions-grid" data-vf-js-chatbot-welcome-suggestions-grid>
                <!-- Suggestions will be populated dynamically from qa.json using the template below -->
              </div>
            </div>

            <template id="welcome-suggestion-template">
              <div class="vf-chatbot-action-prompt">
                <a href="#" class="vf-chatbot-action-prompt__link" role="button"></a>
              </div>
            </template>
          </div>

          <!-- Messages Container -->
          <div class="vf-chatbot-standalone__messages-no-scrollbar" data-vf-js-chatbot-standalone-messages data-auto-scroll="true">
            <!-- Messages will be added here dynamically -->
          </div>

          <!-- Disclaimer Banner -->
          <div class="vf-chatbot-standalone__disclaimer" data-vf-js-chatbot-standalone-disclaimer>
            <div class="vf-banner vf-banner--alert vf-banner--info">
              <div class="vf-banner__content">
                <p class="vf-banner__text"><?php echo wp_kses_post($vf_chatbot_disclaimer); ?></p>
                <button role="button" aria-label="<?php esc_attr_e('close notification banner', 'vfwp'); ?>" class="vf-button vf-button--icon vf-button--dismiss | vf-banner__button">
                  <?php echo vfwp_intranet_chatbot_dismiss_icon(); ?>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Input Container -->
        <div class="vf-chatbot-standalone__input-container">
          <div class="vf-chatbot-standalone__input-wrapper">
            <label class="vf-u-sr-only" id="vf-chatbot-standalone-input-label" for="vf-chatbot-standalone-input"><?php esc_html_e('Ask me', 'vfwp'); ?></label>
            <textarea
              id="vf-chatbot-standalone-input"
              aria-labelledby="vf-chatbot-standalone-input-label"
              class="vf-chatbot-standalone__input vf-form__textarea vf-u-padding__left--400"
              placeholder="<?php echo esc_attr($vf_chatbot_config['input_placeholder']); ?>"
              rows="1"
              data-vf-js-chatbot-standalone-input
            ></textarea>
            <button class="vf-chatbot-standalone__send-button" aria-label="<?php esc_attr_e('Send message', 'vfwp'); ?>" data-vf-js-chatbot-standalone-send>
              <img src="<?php echo esc_url($vf_chatbot_config['icons']['send_button']); ?>" alt="<?php esc_attr_e('Send', 'vfwp'); ?>">
            </button>
          </div>

          <div class="vf-chatbot-standalone__footnote vf-u-margin__top--200" data-vf-js-chatbot-standalone-footnote>
            <?php echo wp_kses_post($vf_chatbot_footnote); ?>
          </div>
        </div>

        <!-- Templates -->
        <template id="feedback-positive-template">
          <?php vfwp_intranet_chatbot_feedback_template($vf_chatbot_positive_feedback_options); ?>
        </template>

        <template id="feedback-negative-template">
          <?php vfwp_intranet_chatbot_feedback_template($vf_chatbot_negative_feedback_options); ?>
        </template>

        <template id="user-message-template">
          <div class="vf-chatbot-message vf-chatbot-message--user vf-u-margin__top--400">
            <div class="vf-chatbot-message__avatar vf-u-margin__bottom--200">
              <span class="vf-chatbot-message__avatar-name"><?php esc_html_e('You', 'vfwp'); ?></span>
              <img src="<?php echo esc_url($vf_chatbot_config['icons']['user_avatar']); ?>" alt="<?php esc_attr_e('You', 'vfwp'); ?>">
            </div>
            <div class="vf-chatbot-message__content vf-u-padding--200">
              <div class="vf-chatbot-message__content-prompt vf-u-padding__left--200 vf-u-padding__right--200">
                <?php esc_html_e('Hello!', 'vfwp'); ?>
              </div>
            </div>
          </div>
        </template>

        <template id="assistant-message-template">
          <div class="vf-chatbot-message vf-chatbot-message--assistant vf-u-margin__top--400">
            <div class="vf-chatbot-message__avatar vf-u-margin__bottom--200">
              <img src="<?php echo esc_url($vf_chatbot_config['icons']['assistant_avatar']); ?>" alt="<?php echo esc_attr($vf_chatbot_title); ?>">
              <span class="vf-chatbot-message__avatar-name"><?php echo esc_html($vf_chatbot_title); ?></span>
            </div>
            <div class="vf-chatbot-message__content vf-u-padding--200">
              <div class="vf-chatbot-message__content-prompt vf-u-padding__left--200 vf-u-padding__right--200">
                <?php esc_html_e('How can I help you?', 'vfwp'); ?>
              </div>
            </div>
          </div>
          <div class="vf-chatbot-feedback vf-u-margin__top--200" data-vf-js-chatbot-feedback></div>
        </template>

        <template id="loading-indicator-template">
          <div class="vf-chatbot-message vf-chatbot-message--assistant vf-chatbot-message--loading vf-u-margin__top--400">
            <div class="vf-chatbot-message__avatar vf-u-margin__bottom--200">
              <img src="<?php echo esc_url($vf_chatbot_config['icons']['assistant_avatar']); ?>" alt="<?php echo esc_attr($vf_chatbot_title); ?>">
              <span class="vf-chatbot-message__avatar-name"><?php echo esc_html($vf_chatbot_title); ?></span>
            </div>
            <div class="vf-chatbot-message__content vf-u-padding--200">
              <div class="vf-chatbot-message__content-loading-dots" aria-label="<?php esc_attr_e('Loading', 'vfwp'); ?>" role="status">
                <span class="vf-chatbot-message__dot"></span>
                <span class="vf-chatbot-message__dot"></span>
                <span class="vf-chatbot-message__dot"></span>
              </div>
            </div>
          </div>
        </template>

        <template id="action-prompts-template">
          <div class="vf-chatbot-action-prompts vf-u-margin__top--400">
            <div class="vf-chatbot-action-prompts__list" data-vf-js-action-prompts-list>
              <!-- Individual prompts will be populated here -->
            </div>
          </div>
        </template>

        <template id="single-action-prompt-template">
          <div class="vf-chatbot-action-prompt">
            <a href="#" class="vf-chatbot-action-prompt__link" role="button"></a>
          </div>
        </template>
      </div>
    </div>
  <?php endif; ?>
</main>

<?php
get_footer();
