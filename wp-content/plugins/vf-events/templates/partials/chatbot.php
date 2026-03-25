<?php
$chatbot_event_post_id = !empty($post->post_parent) ? $post->post_parent : get_the_ID();
$chatbot_title = get_the_title($chatbot_event_post_id);
$chatbot_event_type = get_field('vf_event_event_type', $chatbot_event_post_id);
$chatbot_embo_event_name = get_field('vf_event_embo_subtype', $chatbot_event_post_id);
$chatbot_displayed = get_field('vf_event_displayed', $chatbot_event_post_id);
$chatbot_start_date = get_field('vf_event_start_date', $chatbot_event_post_id);
$chatbot_end_date = get_field('vf_event_end_date', $chatbot_event_post_id);
$chatbot_location = get_field('vf_event_location', $chatbot_event_post_id);
$chatbot_other_location = get_field('vf_event_other_location', $chatbot_event_post_id);
$chatbot_hero_image = get_field('vf_event_hero', $chatbot_event_post_id);
$chatbot_event_type_value = '';
$chatbot_event_type_label = '';
$chatbot_event_date_label = '';
$chatbot_event_location_label = '';

if (is_array($chatbot_hero_image) && !empty($chatbot_hero_image['ID'])) {
  $chatbot_hero_image = wp_get_attachment_url($chatbot_hero_image['ID'], 'medium', false, array(
    'loading'  => 'lazy',
    'itemprop' => 'image',
  ));
}

if (is_array($chatbot_event_type)) {
  if (!empty($chatbot_event_type['value'])) {
    $chatbot_event_type_value = $chatbot_event_type['value'];
  } elseif (!empty($chatbot_event_type['label'])) {
    $chatbot_event_type_value = sanitize_title($chatbot_event_type['label']);
  }

  if (!empty($chatbot_event_type['label'])) {
    $chatbot_event_type_label = $chatbot_event_type['label'];
  }
} elseif (is_string($chatbot_event_type)) {
  $chatbot_event_type_value = $chatbot_event_type;
  $chatbot_event_type_label = $chatbot_event_type;
}

if (!empty($chatbot_displayed)) {
  $chatbot_event_type_label = $chatbot_displayed;
} elseif (is_array($chatbot_embo_event_name) && !empty($chatbot_embo_event_name['label'])) {
  $chatbot_event_type_label = $chatbot_embo_event_name['label'];
}

if (!empty($chatbot_start_date)) {
  $chatbot_start = DateTime::createFromFormat('j M Y', $chatbot_start_date);
  $chatbot_end = !empty($chatbot_end_date)
    ? DateTime::createFromFormat('j M Y', $chatbot_end_date)
    : false;

  if ($chatbot_start instanceof DateTime) {
    if ($chatbot_end instanceof DateTime) {
      if ($chatbot_start->format('M') === $chatbot_end->format('M')) {
        $chatbot_event_date_label = $chatbot_start->format('j') . ' – ' . $chatbot_end->format('j M Y');
      } else {
        $chatbot_event_date_label = $chatbot_start->format('j M') . ' – ' . $chatbot_end->format('j M Y');
      }
    } else {
      $chatbot_event_date_label = $chatbot_start->format('j M Y');
    }
  }
}

if (!empty($chatbot_other_location)) {
  $chatbot_event_location_label = $chatbot_other_location;
} elseif (!empty($chatbot_location)) {
  $chatbot_event_location_label = is_array($chatbot_location)
    ? implode(' and ', $chatbot_location)
    : $chatbot_location;
}
?>
<div class="vf-chatbot" data-vf-js-chatbot data-event-type="<?php echo esc_attr(strtolower($chatbot_event_type_value)); ?>">
  <button class="vf-chatbot-fab" aria-label="Open chat" data-vf-js-chatbot-fab type="button">
    <svg class="vf-chatbot-fab__icon vf-chatbot-fab__icon--chat" width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
      <g clip-path="url(#vf-chatbot-fab-clip)">
        <path d="M4.23995 24.0191L4.25995 17.5491H2.85995C1.28995 17.5491 0.00994589 16.2691 0.00994589 14.6991V3.75906C-5.41061e-05 2.18906 1.27995 0.909058 2.84995 0.909058H21.1399C22.7099 0.909058 23.9899 2.18906 23.9899 3.75906V14.6891C23.9899 16.2591 22.7099 17.5391 21.1399 17.5391H10.7399L4.22995 24.0091L4.23995 24.0191ZM2.84995 1.97906C1.86995 1.97906 1.06995 2.77906 1.06995 3.75906V14.6891C1.06995 15.6691 1.86995 16.4691 2.84995 16.4691H5.32995V21.4191L10.2999 16.4691H21.1499C22.1299 16.4691 22.9299 15.6691 22.9299 14.6891V3.75906C22.9299 2.77906 22.1299 1.97906 21.1499 1.97906H2.84995Z" fill="white"/>
        <path d="M18.27 7.06906C18.76 7.55906 21.93 8.41906 21.93 8.41906C21.93 8.41906 18.75 9.28906 18.27 9.76906C17.79 10.2491 16.92 13.4291 16.92 13.4291C16.92 13.4291 16.04 10.2391 15.57 9.76906C15.1 9.29906 11.91 8.41906 11.91 8.41906C11.91 8.41906 15.02 7.61906 15.57 7.06906C16.12 6.51906 16.92 3.40906 16.92 3.40906C16.92 3.40906 17.78 6.57906 18.27 7.06906Z" fill="white"/>
      </g>
      <defs>
        <clipPath id="vf-chatbot-fab-clip">
          <rect width="24" height="23.11" fill="white" transform="translate(0 0.909058)"/>
        </clipPath>
      </defs>
    </svg>
  </button>

  <div class="vf-content vf-chatbot-modal-container vf-chatbot-modal-container--inactive" role="dialog" aria-label="Event Assistant chatbot" data-vf-js-chatbot-modal-container>
    <div role="region" aria-label="Chatbot header" class="vf-chatbot-modal__header">
      <div role="region" aria-label="Chatbot title" class="vf-chatbot-modal__header-left">
        <div class="vf-chatbot-selector">
          <div class="vf-chatbot-selector__title">
            <img src="/wp-content/themes/vf-wp/assets/assets/vf-chatbot/assets/vf-chatbot--icon-24x24-dark-green.svg" alt="Event Assistant">
            <div class="vf-chatbot-selector__title-content vf-u-margin__left--200">
              <span class="vf-chatbot-selector__main-text">Event Assistant</span>
              <span class="vf-chatbot-selector__title-text">Custom text</span>
            </div>
          </div>
        </div>
      </div>
      <div class="vf-chatbot-modal__header-right">
        <button class="vf-chatbot-modal__minimize" aria-label="Minimize chatbot" data-vf-js-chatbot-modal-minimize type="button">
          <img src="/wp-content/themes/vf-wp/assets/assets/vf-chatbot/assets/vf-chatbot--icon-minimize.svg" alt="Minimize chatbot">
        </button>
        <button class="vf-chatbot-modal__close" aria-label="Close chatbot" data-vf-js-chatbot-modal-close type="button">
          <img src="/wp-content/themes/vf-wp/assets/assets/vf-chatbot/assets/vf-chatbot--icon-close.svg" alt="Close chatbot">
        </button>
      </div>
    </div>

    <div class="vf-chatbot-modal | vf-u-background-color-ui--grey--light" data-vf-js-chatbot-modal>
                  <div
            id="eventInfo"
            class="vf-events-chatbot-event-hero"
            <?php if (!empty($chatbot_hero_image)) { ?>
             style="background: url('<?php echo esc_url($chatbot_hero_image); ?>') no-repeat 73% 46%; background-size: auto; margin: 6px 6px 1rem 6px; border-radius: 2px;"
            <?php } ?>
          >
            <div class="vf-events-chatbot-event-card">
              <?php if (!empty($chatbot_event_date_label)) { ?>
                <p class="vf-badge vf-badge--primary customBadgePurple vf-events-chatbot-badge"><?php echo esc_html($chatbot_event_date_label); ?></p>
              <?php } ?>
              <?php if (!empty($chatbot_event_type_label)) { ?>
                <p class="vf-badge vf-badge--primary customBadgePurple vf-events-chatbot-badge"><?php echo esc_html($chatbot_event_type_label); ?></p>
              <?php } ?>
              <?php if (!empty($chatbot_title)) { ?>
                <h3 class="event-card-title"><?php echo esc_html($chatbot_title); ?></h3>
              <?php } ?>
              <p style="display: none;" class="event-card-location"><?php echo esc_html($chatbot_event_location_label); ?></p>
            </div>
          </div>
      <div class="vf-chatbot-modal__content" data-vf-js-chatbot-modal-content>
        <div
          role="region"
          aria-label="Chatbot welcome screen"
          class="vf-chatbot-welcome"
          data-vf-js-chatbot-welcome
          data-max-questions="4"
          data-enable-qa-data-loading="true"
          data-enable-predefined-qa="true"
          data-enable-fallback-responses="true"
          data-qa-data-url="/wp-content/themes/vf-wp/assets/assets/chatbot/qa.json"
        >

          <div class="vf-chatbot-welcome__content">
            <div class="vf-chatbot-welcome__logo">
              <img src="/wp-content/themes/vf-wp/assets/assets/vf-chatbot/assets/vf-chatbot--icon-32x32-dark-green.svg" alt="Event Assistant">
            </div>
            <h1 class="vf-chatbot-welcome__title">Event Assistant</h1>
            <div class="vf-chatbot-welcome__message">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam cursus quis risus a egestas.
            </div>
          </div>
          <div class="vf-chatbot-welcome__suggestions">
            <p class="vf-chatbot-welcome__suggestions-title vf-u-margin__bottom--200">Try asking me:</p>
            <div class="vf-chatbot-welcome__suggestions-grid" data-vf-js-chatbot-welcome-suggestions-grid></div>
          </div>
          <template id="welcome-suggestion-template">
            <div class="vf-chatbot-action-prompt">
              <a href="#" class="vf-chatbot-action-prompt__link" role="button"></a>
            </div>
          </template>
        </div>

        <div role="region" aria-label="Chat messages" class="vf-chatbot-modal__messages-no-scrollbar vf-u-margin__bottom--400" data-vf-js-chatbot-modal-messages data-auto-scroll="true"></div>

        <div role="region" aria-label="Disclaimer banner" class="vf-chatbot-modal__disclaimer" data-vf-js-chatbot-modal-disclaimer>
          <div class="vf-banner vf-banner--alert vf-banner--info">
            <div class="vf-banner__content">
              <p class="vf-banner__text">Disclaimer: This chatbot is designed to assist you with general information and basic inquiries. Review generated content for accuracy.</p>
              <button role="button" aria-label="Close notification banner" class="vf-button vf-button--icon vf-button--dismiss | vf-banner__button" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                  <title>Dismiss banner</title>
                  <path d="M14.3,12.179a.25.25,0,0,1,0-.354l9.263-9.262A1.5,1.5,0,0,0,21.439.442L12.177,9.7a.25.25,0,0,1-.354,0L2.561.442A1.5,1.5,0,0,0,.439,2.563L9.7,11.825a.25.25,0,0,1,0,.354L.439,21.442a1.5,1.5,0,0,0,2.122,2.121L11.823,14.3a.25.25,0,0,1,.354,0l9.262,9.263a1.5,1.5,0,0,0,2.122-2.121Z"/>
                </svg>
              </button>
            </div>
          </div>
          </div>
        </div>

      <div role="region" aria-label="Chat message input" class="vf-chatbot-modal__input-container">
        <div class="vf-chatbot-modal__input-wrapper">
          <label class="vf-u-sr-only" id="vf-chatbot-modal-input-label" for="vf-chatbot-modal-input">Ask me</label>
          <textarea id="vf-chatbot-modal-input" aria-labelledby="vf-chatbot-modal-input-label" class="vf-chatbot-modal__input vf-form__textarea vf-u-padding__left--400" placeholder="Ask about this event ..." rows="1" data-vf-js-chatbot-modal-input></textarea>
          <button class="vf-chatbot-modal__send-button" aria-label="Send message" data-vf-js-chatbot-modal-send type="button">
            <img src="/wp-content/themes/vf-wp/assets/assets/vf-chatbot/assets/vf-chatbot--icon-send.svg" alt="Send">
          </button>
        </div>

        <div role="region" aria-label="Chatbot footnote" class="vf-chatbot-modal__footnote vf-u-margin__top--200 vf-u-margin__bottom--200" data-vf-js-chatbot-modal-footnote>
          Review AI generated content for accuracy.
        </div>
      </div>

      <div class="vf-chatbot-dialog" data-vf-js-chatbot-dialog>
        <div class="vf-chatbot-dialog__content">
          <div class="vf-chatbot-dialog__header vf-u-margin__bottom--400">
            <h2 class="vf-chatbot-dialog__title">Close chat and delete conversation?</h2>
            <button class="vf-chatbot-dialog__close" data-vf-js-dialog-close aria-label="Close dialog" type="button">
              <svg width="24" height="24" viewBox="0 0 24 24">
                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
              </svg>
            </button>
          </div>

          <div class="vf-chatbot-dialog__body vf-u-margin__bottom--800">
            <p class="vf-text vf-text-body--3">Are you sure you want to close the chat? Your current conversation history will be permanently deleted.</p>
          </div>

          <div class="vf-chatbot-dialog__actions">
            <button class="vf-chatbot-dialog__button vf-chatbot-dialog__button--outline" data-vf-js-dialog-cancel type="button">
              Keep chat open
            </button>
            <button class="vf-chatbot-dialog__button vf-chatbot-dialog__button--primary" data-vf-js-dialog-confirm type="button">
              Close and delete
            </button>
          </div>
        </div>
      </div>

      <template id="feedback-positive-template">
        <div class="vf-chatbot-feedback__form vf-u-margin__top--400">
          <div class="vf-chatbot-feedback__form-content vf-u-padding--400">
            <div class="vf-chatbot-feedback__form-content-header">
              <div class="vf-chatbot-feedback__title">Tell us more (optional)</div>
              <button role="button" class="vf-chatbot-feedback__form-close vf-button vf-button--icon vf-button--dismiss | vf-banner__button" type="button" aria-label="Close feedback form" data-vf-js-feedback-form-close>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                  <title>Dismiss banner</title>
                  <path d="M14.3,12.179a.25.25,0,0,1,0-.354l9.263-9.262A1.5,1.5,0,0,0,21.439.442L12.177,9.7a.25.25,0,0,1-.354,0L2.561.442A1.5,1.5,0,0,0,.439,2.563L9.7,11.825a.25.25,0,0,1,0,.354L.439,21.442a1.5,1.5,0,0,0,2.122,2.121L11.823,14.3a.25.25,0,0,1,.354,0l9.262,9.263a1.5,1.5,0,0,0,2.122-2.121Z"/>
                </svg>
              </button>
            </div>
            <div class="vf-chatbot-feedback__options">
              <button class="vf-chatbot-feedback__option" data-feedback-option="accurate" type="button">Accurate answer</button>
              <button class="vf-chatbot-feedback__option" data-feedback-option="easy" type="button">Easy to understand</button>
              <button class="vf-chatbot-feedback__option" data-feedback-option="formatted" type="button">Well formatted</button>
              <button class="vf-chatbot-feedback__option" data-feedback-option="helpful" type="button">Helpful</button>
            </div>
            <label id="vf-chatbot-feedback-comment-title-positive" for="vf-chatbot-feedback-comment-positive" class="vf-chatbot-feedback__comment-title">Comments</label>
            <textarea id="vf-chatbot-feedback-comment-positive" aria-labelledby="vf-chatbot-feedback-comment-title-positive" class="vf-chatbot-feedback__comment" rows="4"></textarea>
            <button type="submit" class="vf-chatbot-feedback__submit vf-u-padding--200" data-vf-js-feedback-submit>Submit</button>
          </div>
        </div>
      </template>

      <template id="feedback-negative-template">
        <div class="vf-chatbot-feedback__form vf-u-margin__top--400">
          <div class="vf-chatbot-feedback__form-content vf-u-padding--400">
            <div class="vf-chatbot-feedback__form-content-header">
              <div class="vf-chatbot-feedback__title">Tell us more (optional)</div>
              <button role="button" class="vf-chatbot-feedback__form-close vf-button vf-button--icon vf-button--dismiss | vf-banner__button" type="button" aria-label="Close feedback form" data-vf-js-feedback-form-close>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                  <title>Dismiss banner</title>
                  <path d="M14.3,12.179a.25.25,0,0,1,0-.354l9.263-9.262A1.5,1.5,0,0,0,21.439.442L12.177,9.7a.25.25,0,0,1-.354,0L2.561.442A1.5,1.5,0,0,0,.439,2.563L9.7,11.825a.25.25,0,0,1,0,.354L.439,21.442a1.5,1.5,0,0,0,2.122,2.121L11.823,14.3a.25.25,0,0,1,.354,0l9.262,9.263a1.5,1.5,0,0,0,2.122-2.121Z"/>
                </svg>
              </button>
            </div>
            <div class="vf-chatbot-feedback__options">
              <button class="vf-chatbot-feedback__option" data-feedback-option="inaccurate" type="button">Inaccurate answer</button>
              <button class="vf-chatbot-feedback__option" data-feedback-option="nocontext" type="button">Did not use context</button>
              <button class="vf-chatbot-feedback__option" data-feedback-option="poorformat" type="button">Poorly formatted</button>
              <button class="vf-chatbot-feedback__option" data-feedback-option="nothelpful" type="button">Not helpful</button>
            </div>
            <label id="vf-chatbot-feedback-comment-title-negative" for="vf-chatbot-feedback-comment-negative" class="vf-chatbot-feedback__comment-title">Comments</label>
            <textarea id="vf-chatbot-feedback-comment-negative" aria-labelledby="vf-chatbot-feedback-comment-title-negative" class="vf-chatbot-feedback__comment" rows="4"></textarea>
            <button type="submit" class="vf-chatbot-feedback__submit vf-u-padding--200" data-vf-js-feedback-submit>Submit</button>
          </div>
        </div>
      </template>

      <template id="user-message-template">
        <div class="vf-chatbot-message vf-chatbot-message--user vf-u-margin__top--400">
          <div class="vf-chatbot-message__avatar vf-u-margin__bottom--200">
            <span class="vf-chatbot-message__avatar-name">You</span>
            <img src="/wp-content/themes/vf-wp/assets/assets/vf-chatbot/assets/vf-chatbot--avatar-user.svg" alt="Your avatar">
          </div>
          <div class="vf-chatbot-message__content vf-u-padding--200">
            <div class="vf-chatbot-message__content-prompt vf-u-padding__left--200 vf-u-padding__right--200">Hello!</div>
          </div>
        </div>
      </template>

      <template id="assistant-message-template">
        <div class="vf-chatbot-message vf-chatbot-message--assistant vf-u-margin__top--400">
          <div class="vf-chatbot-message__avatar vf-u-margin__bottom--200">
            <img src="/wp-content/themes/vf-wp/assets/assets/vf-chatbot/assets/vf-chatbot--icon-16x16-dark-green.svg" alt="Event Assistant">
            <span class="vf-chatbot-message__avatar-name">Event Assistant</span>
          </div>
          <div class="vf-chatbot-message__content vf-u-padding--200">
            <div class="vf-chatbot-message__content-prompt vf-u-padding__left--200 vf-u-padding__right--200">How can I help you?</div>
          </div>
        </div>
        <div class="vf-chatbot-feedback vf-u-margin__top--200" data-vf-js-chatbot-feedback></div>
      </template>

      <template id="loading-indicator-template">
        <div class="vf-chatbot-message vf-chatbot-message--assistant vf-chatbot-message--loading vf-u-margin__top--400">
          <div class="vf-chatbot-message__avatar vf-u-margin__bottom--200">
            <img src="/wp-content/themes/vf-wp/assets/assets/vf-chatbot/assets/vf-chatbot--icon-16x16-dark-green.svg" alt="Event Assistant">
            <span class="vf-chatbot-message__avatar-name">Event Assistant</span>
          </div>
          <div class="vf-chatbot-message__content vf-u-padding--200">
            <div class="vf-chatbot-message__content-loading-dots" aria-label="Loading" role="status">
              <span class="vf-chatbot-message__dot"></span>
              <span class="vf-chatbot-message__dot"></span>
              <span class="vf-chatbot-message__dot"></span>
            </div>
          </div>
        </div>
      </template>

      <template id="action-prompts-template">
        <div class="vf-chatbot-action-prompts vf-u-margin__top--400">
          <div class="vf-chatbot-action-prompts__list" data-vf-js-action-prompts-list></div>
        </div>
      </template>

      <template id="single-action-prompt-template">
        <div class="vf-chatbot-action-prompt">
          <a href="#" class="vf-chatbot-action-prompt__link" role="button"></a>
        </div>
      </template>
    </div>
  </div>
</div>

<script src="/wp-content/plugins/vf-events/assets/vf-events-chatbot.js"></script>
<script>
  (function () {
    var assetBase = "/wp-content/themes/vf-wp/assets/assets/vf-chatbot/assets";
    var assetBaseCustom = "/wp-content/themes/vf-wp/assets/assets/chatbot";

    window.vfEventChatbotConfig = {
      type: "modal",
      title: "Event Assistant",
      welcome_logo: true,
      welcome_message: "Ask about this event, event logistics, or related EMBL resources.",
      welcome_logo_alt: "Event Assistant",
      welcome_suggestions_title: "Try asking me:",
      input_placeholder: "Ask about this event",
      welcome_max_suggestions: 3,
      icons: {
        assistant_avatar: assetBase + "/vf-chatbot--icon-16x16-dark-green.svg",
        user_avatar: assetBase + "/vf-chatbot--avatar-user.svg",
        send_button: assetBase + "/vf-chatbot--icon-send.svg",
        main_logo_url: assetBase + "/vf-chatbot--icon-32x32-dark-green.svg",
        minimize: assetBase + "/vf-chatbot--icon-minimize.svg",
        close: assetBase + "/vf-chatbot--icon-close.svg"
      },
      api: {
        chat_endpoint: "https://8jtt848211.execute-api.eu-west-2.amazonaws.com/message",
        feedback_endpoint: false,
        qa_data_url: assetBaseCustom + "/qa.json",
        headers: {
          "Content-Type": "application/json"
        },
        timeout: 15000
      },
      features: {
        enable_welcome: true,
        enable_feedback: true,
        enable_sources: false,
        enable_sources_custom_format: false,
        enable_welcome_suggestions: true,
        enable_typing_indicator: true,
        enable_disclaimer: true,
        enable_predefined_qa: true,
        enable_fallback_responses: true,
        enable_qa_data_loading: true,
        enable_instant_feedback: false
      },
      behavior: {
        auto_scroll: true,
        typing_delay: 800,
        show_scrollbar: false
      },
      handlers: {
        on_message_send: "vfEventsHandleChatbotMessageSend",
        on_suggestion_click: "vfEventsHandleChatbotSuggestionClick",
        on_fab_click: "vfEventsHandleChatbotFabClick",
        on_dialog_confirm: "vfEventsHandleChatbotDialogConfirm"
      },
      feedback_options: {
        positive: [
          { id: "accurate", label: "Accurate" },
          { id: "easy", label: "Easy to understand" },
          { id: "formatted", label: "Well formatted" }
        ],
        negative: [
          { id: "inaccurate", label: "Inaccurate answer" },
          { id: "nocontext", label: "Did not use context" },
          { id: "poorformat", label: "Poorly formatted" }
        ]
      },
      disclaimer: "Disclaimer: This chatbot is designed to assist you with general information and basic inquiries. Review generated content for accuracy.",
      footnote: "Review AI generated content for accuracy.",
      enable_session_persistence: true,
      restore_minimized_state: true
    };

    window.config = window.vfEventChatbotConfig;
  })();
</script>
<style>
    .vf-chatbot-modal .vf-chatbot-welcome__content {
    min-height: 25dvh;
}

    .vf-chatbot-modal-container,
    .vf-chatbot-modal,
    .vf-chatbot-modal__content,
    .vf-chatbot-modal__messages-no-scrollbar,
    .vf-chatbot-welcome {
        overscroll-behavior: contain;
    }
    .vf-chatbot-modal {
        justify-content: space-between;
        overflow: hidden;
    }

    .vf-events-chatbot-event-hero {
        display: block;
        position: relative;
        transition: background-size 220ms ease, background-position 220ms ease, box-shadow 220ms ease;
    }
    .vf-chatbot-welcome__content {
        position: relative;
        padding-top: 0.5rem;
    }
    .vf-chatbot-modal__content {
        background: transparent;
    }
    .vf-events-chatbot-event-card {
        position: relative;
        z-index: 1;
        display: flex;
        flex-wrap: wrap;
        align-content: flex-end;
        padding: 0.5rem 1rem;
        background: linear-gradient(360deg, rgba(0, 0, 0, 0.6) 18%, rgba(0, 0, 0, 0) 75%);
        box-shadow: 0px 5px 15px 0px rgba(0, 0, 0, .55);
        transition: padding 220ms ease, background 220ms ease, box-shadow 220ms ease;
    }
    .vf-events-chatbot-badge {
        margin-right: 1rem;
        margin-bottom: 0.75rem;
        transform-origin: top left;
        transition: transform 220ms ease, margin 220ms ease, padding 220ms ease, font-size 220ms ease, line-height 220ms ease;
        max-height: 3rem;
        overflow: hidden;
    }
    .vf-chatbot-message__content-prompt p, li {
        font-size: 16px !important;
    }
    .vf-chatbot-message__content-prompt h1, h2, h3 {
        font-size: 18px !important;
    }

    .customBadgePurple {
    background-color: #007B53 !important;
    border: 0 !important;
    border-radius: 2px !important;
    color: #fff !important;
    font-weight: 500 !important;
    font-size: 11px !important;
    margin-bottom: 0;
}

.event-card-title {
    width: 100%;
    margin: 0.5rem 0 !important;
    font-size: 18px !important;
    font-weight: 600 !important;
    color: #fff !important;
    transition: font-size 220ms ease, margin 220ms ease, line-height 220ms ease;
}
.vf-events-chatbot-event-hero--compact .vf-events-chatbot-event-card {
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
    background: linear-gradient(360deg, rgba(0, 0, 0, 0.72) 18%, rgba(0, 0, 0, 0.15) 100%);
    box-shadow: 0px 2px 8px 0px rgba(0, 0, 0, .35);
}
.vf-events-chatbot-event-hero--compact .vf-events-chatbot-badge {
    transform: scale(0.92);
    margin-top: 0;
    margin-right: 0.5rem;
    margin-bottom: 0.35rem;
    font-size: 10px !important;
    line-height: 1.1;
}
.vf-events-chatbot-event-hero--compact .event-card-title {
    margin: 0 !important;
    font-size: 15px !important;
    line-height: 1.3;
}
</style>
