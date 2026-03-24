<!-- Modal Chatbot -->
<div class="vf-content vf-chatbot-modal-container" role="dialog" aria-label="AI Assistant chatbot" data-vf-js-chatbot-modal-container">

  <div role="region" aria-label="Chatbot header" class="vf-chatbot-modal__header">
    <div role="region" aria-label="Chatbot selector" class="vf-chatbot-modal__header-left">
      <div class="vf-chatbot-selector" data-vf-js-chatbot-selector data-routes-path="../../assets/vf-chatbot/assets/vf-chatbot-selector-services.json" data-multiselect="true" data-max-multiselect="3" data-show-search="true" data-show-all-services="true" data-show-all-services-selected="true">
        <button class="vf-chatbot-selector__title" data-vf-js-selector-toggle>
          <img src="../../assets/vf-chatbot/assets/vf-chatbot--icon-24x24-dark-green.svg" alt="AI Assistant">
          <div class="vf-chatbot-selector__title-content vf-u-margin__left--200">
            <span class="vf-chatbot-selector__main-text">AI Assistant</span>
            <span class="vf-chatbot-selector__title-text">Services</span>
          </div>
          <span class="vf-chatbot-selector__chevron">
            <svg width="32" height="31" viewBox="0 0 32 31" fill="none" xmlns="http://www.w3.org/2000/svg">
              <g clip-path="url(#clip0_3647_8230)">
                <path d="M15.999 19.0975C15.7378 19.098 15.479 19.0468 15.2377 18.9468C14.9963 18.8469 14.7771 18.7001 14.5926 18.5151L8.32863 11.9279C8.21951 11.8137 8.13399 11.6791 8.07698 11.5318C8.01998 11.3845 7.99261 11.2274 7.99645 11.0695C8.00028 10.9116 8.03525 10.756 8.09934 10.6117C8.16342 10.4673 8.25537 10.337 8.36992 10.2283C8.48446 10.1195 8.61934 10.0344 8.76683 9.97791C8.91432 9.92139 9.07152 9.89454 9.2294 9.89889C9.38729 9.90325 9.54277 9.93872 9.68692 10.0033C9.83107 10.0678 9.96106 10.1602 10.0694 10.2751L15.7094 16.2143C15.7467 16.2537 15.7916 16.2851 15.8414 16.3066C15.8912 16.3281 15.9448 16.3391 15.999 16.3391C16.0533 16.3391 16.1069 16.3281 16.1567 16.3066C16.2065 16.2851 16.2514 16.2537 16.2886 16.2143L21.9286 10.2751C22.037 10.1602 22.167 10.0678 22.3112 10.0033C22.4553 9.93872 22.6108 9.90325 22.7687 9.89889C22.9266 9.89454 23.0838 9.92139 23.2312 9.97791C23.3787 10.0344 23.5136 10.1195 23.6282 10.2283C23.7427 10.337 23.8347 10.4673 23.8987 10.6117C23.9628 10.756 23.9978 10.9116 24.0016 11.0695C24.0055 11.2274 23.9781 11.3845 23.9211 11.5318C23.8641 11.6791 23.7786 11.8137 23.6694 11.9279L17.439 18.4991C17.2503 18.6888 17.0259 18.8394 16.7788 18.9421C16.5316 19.0448 16.2667 19.0976 15.999 19.0975Z" fill="#707372" />
              </g>
              <defs>
                <clipPath id="clip0_3647_8230">
                  <rect width="16" height="16" fill="white" transform="translate(8 6.5)" />
                </clipPath>
              </defs>
            </svg>
          </span>
        </button>

        <div class="vf-chatbot-selector__dropdown" data-vf-js-selector-dropdown>

          <div class="vf-chatbot-selector__search">
            <label class="vf-u-sr-only" id="vf-chatbot-selector-search-label" for="vf-chatbot-selector-search">Type to search</label>
            <input type="text" id="vf-chatbot-selector-search" aria-labelledby="vf-chatbot-selector-search-label" placeholder="Select services" data-vf-js-selector-search>
          </div>


          <div class="vf-chatbot-selector__header">
            <span data-max-select="3">Select up to 3 services</span>
            <a href="#" class="vf-chatbot-selector__clear" role="button" data-vf-js-selector-clear>Clear all</a>
          </div>
          <ul class="vf-chatbot-selector__list" data-vf-js-chatbot-selector-list>
            <!-- Routes will be populated dynamically via JavaScript -->
          </ul>
        </div>

      </div>
    </div>
    <div class="vf-chatbot-modal__header-right">
      <button class="vf-chatbot-modal__minimize" aria-label="Minimize chatbot" data-vf-js-chatbot-modal-minimize>
        <img src="../../assets/vf-chatbot/assets/vf-chatbot--icon-minimize.svg" alt="Minimize chatbot">
      </button>
      <button class="vf-chatbot-modal__close" aria-label="Close chatbot" data-vf-js-chatbot-modal-close>
        <img src="../../assets/vf-chatbot/assets/vf-chatbot--icon-close.svg" alt="Close chatbot">
      </button>
    </div>
  </div>

  <div class="vf-chatbot-modal | vf-u-background-color-ui--grey--light" data-vf-js-chatbot-modal>
    <div class="vf-chatbot-modal__content" data-vf-js-chatbot-modal-content>

      <!-- Welcome Screen -->
      <div role="region" aria-label="Chatbot welcome screen" class="vf-chatbot-welcome" data-vf-js-chatbot-welcome data-max-questions="4" data-enable-qa-data-loading="true" data-enable-predefined-qa="true" data-enable-fallback-responses="true" data-qa-data-url="../../assets/vf-chatbot/assets/vf-chatbot-qa.json">
        <div class="vf-chatbot-welcome__content">
          <div class="vf-chatbot-welcome__logo">
            <img src="../../assets/vf-chatbot/assets/vf-chatbot--icon-32x32-dark-green.svg" alt="AI Assistant">
          </div>
          <h1 class="vf-chatbot-welcome__title">AI Assistant</h1>
          <div class="vf-chatbot-welcome__message">
            Welcome! I'm here to help
          </div>
        </div>
        <div class="vf-chatbot-welcome__suggestions">
          <p class="vf-chatbot-welcome__suggestions-title vf-u-margin__bottom--200">Try asking me:</p>
          <div class="vf-chatbot-welcome__suggestions-grid" data-vf-js-chatbot-welcome-suggestions-grid>
            <!-- Suggestions will be populated dynamically from qa.json using the template below -->
          </div>
        </div>

        <!-- Template for welcome suggestions using vf-chatbot-action-prompt -->
        <template id="welcome-suggestion-template">
          <div class="vf-chatbot-action-prompt">
            <a href="#" class="vf-chatbot-action-prompt__link" role="button">

            </a>
          </div>
        </template>
      </div>

      <!-- Messages Container -->
      <div role="region" aria-label="Chat messages" class="vf-chatbot-modal__messages-no-scrollbar  vf-u-margin__bottom--400" data-vf-js-chatbot-modal-messages data-auto-scroll="true">
        <!-- Messages will be added here dynamically -->
      </div>

      <!-- Disclaimer Banner -->
      <div role="region" aria-label="Disclaimer banner" class="vf-chatbot-modal__disclaimer" data-vf-js-chatbot-modal-disclaimer>
        <div class="vf-banner vf-banner--alert vf-banner--info">
          <div class="vf-banner__content">
            <p class="vf-banner__text">Disclaimer: This chatbot is designed to assist you with general information and basic inquiries. See our <a class="vf-banner__link" target="_blank" rel="noopener noreferrer" aria-label="disclaimer notes (opens in new tab)" href="https://www.ebi.ac.uk/data-protection/privacy-notice/embl-ebi-public-website/">disclaimer notes</a>.</p>
            <button role="button" aria-label="close notification banner" class="vf-button vf-button--icon vf-button--dismiss | vf-banner__button">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <title>dismiss banner</title>
                <path d="M14.3,12.179a.25.25,0,0,1,0-.354l9.263-9.262A1.5,1.5,0,0,0,21.439.442L12.177,9.7a.25.25,0,0,1-.354,0L2.561.442A1.5,1.5,0,0,0,.439,2.563L9.7,11.825a.25.25,0,0,1,0,.354L.439,21.442a1.5,1.5,0,0,0,2.122,2.121L11.823,14.3a.25.25,0,0,1,.354,0l9.262,9.263a1.5,1.5,0,0,0,2.122-2.121Z" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Input Container -->
    <div role="region" aria-label="Chat message input" class="vf-chatbot-modal__input-container">
      <div class="vf-chatbot-modal__input-wrapper">
        <label class="vf-u-sr-only" id="vf-chatbot-modal-input-label" for="vf-chatbot-modal-input">Ask me</label>
        <textarea id="vf-chatbot-modal-input" aria-labelledby="vf-chatbot-modal-input-label" class="vf-chatbot-modal__input vf-form__textarea vf-u-padding__left--400" placeholder="Ask me ..." rows="1" data-vf-js-chatbot-modal-input></textarea>
        <button class="vf-chatbot-modal__send-button" aria-label="Send message" data-vf-js-chatbot-modal-send>
          <img src="../../assets/vf-chatbot/assets/vf-chatbot--icon-send.svg" alt="Send">
        </button>
      </div>

      <div role="region" aria-label="footnote" class="vf-chatbot-modal__footnote vf-u-margin__top--200 vf-u-margin__bottom--200" data-vf-js-chatbot-modal-footnote>
        Review AI generated content for accuracy. <a class="vf-link" target="_blank" rel="noopener noreferrer" aria-label="Leave feedback (opens in new tab)" href="https://embl.service-now.com/esc?id=sc_cat_item&sys_id=5eeb8eb91b92e650b376da88b04bcbc1">Leave feedback</a>.
      </div>
    </div>

    <div class="vf-chatbot-dialog" data-vf-js-chatbot-dialog>
      <div class="vf-chatbot-dialog__content">
        <div class="vf-chatbot-dialog__header vf-u-margin__bottom--400">
          <h2 class="vf-chatbot-dialog__title">Close chat and delete conversation?</h2>
          <button class="vf-chatbot-dialog__close" data-vf-js-dialog-close aria-label="Close dialog">
            <svg width="24" height="24" viewBox="0 0 24 24">
              <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
            </svg>
          </button>
        </div>

        <div class="vf-chatbot-dialog__body vf-u-margin__bottom--800">
          <p class="vf-text vf-text-body--3">Are you sure you want to close the chat? <br>Your current conversation history will be permanently deleted.</p>
        </div>

        <div class="vf-chatbot-dialog__actions">
          <button class="vf-chatbot-dialog__button vf-chatbot-dialog__button--outline" data-vf-js-dialog-cancel>
            Keep chat open
          </button>
          <button class="vf-chatbot-dialog__button vf-chatbot-dialog__button--primary" data-vf-js-dialog-confirm>
            Close and delete
          </button>
        </div>
      </div>
    </div>

    <!-- Templates -->
    <template id="feedback-positive-template">
      <div class="vf-chatbot-feedback__form vf-u-margin__top--400">
        <div class="vf-chatbot-feedback__form-content vf-u-padding--400">
          <div class="vf-chatbot-feedback__form-content-header">
            <div class="vf-chatbot-feedback__title">Tell us more (optional)</div>
            <button role="button" class="vf-chatbot-feedback__form-close vf-button vf-button--icon vf-button--dismiss | vf-banner__button" type="button" aria-label="Close feedback form" data-vf-js-feedback-form-close>
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <title>dismiss banner</title>
                <path d="M14.3,12.179a.25.25,0,0,1,0-.354l9.263-9.262A1.5,1.5,0,0,0,21.439.442L12.177,9.7a.25.25,0,0,1-.354,0L2.561.442A1.5,1.5,0,0,0,.439,2.563L9.7,11.825a.25.25,0,0,1,0,.354L.439,21.442a1.5,1.5,0,0,0,2.122,2.121L11.823,14.3a.25.25,0,0,1,.354,0l9.262,9.263a1.5,1.5,0,0,0,2.122-2.121Z" />
              </svg>
            </button>
          </div>
          <div class="vf-chatbot-feedback__options">
            <button class="vf-chatbot-feedback__option" data-feedback-option="accurate">
              Accurate answer
            </button>
            <button class="vf-chatbot-feedback__option" data-feedback-option="easy">
              Easy to understand
            </button>
            <button class="vf-chatbot-feedback__option" data-feedback-option="formatted">
              Well formatted
            </button>
            <button class="vf-chatbot-feedback__option" data-feedback-option="helpful">
              Helpful
            </button>
          </div>
          <label id="vf-chatbot-feedback-comment-title" for="vf-chatbot-feedback-comment" class="vf-chatbot-feedback__comment-title">Comments</label>
          <textarea id="vf-chatbot-feedback-comment" aria-labelledby="vf-chatbot-feedback-comment-title" class="vf-chatbot-feedback__comment" rows="4"></textarea>
          <button type="submit" class="vf-chatbot-feedback__submit vf-u-padding--200" data-vf-js-feedback-submit>
            Submit
          </button>
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
                <title>dismiss banner</title>
                <path d="M14.3,12.179a.25.25,0,0,1,0-.354l9.263-9.262A1.5,1.5,0,0,0,21.439.442L12.177,9.7a.25.25,0,0,1-.354,0L2.561.442A1.5,1.5,0,0,0,.439,2.563L9.7,11.825a.25.25,0,0,1,0,.354L.439,21.442a1.5,1.5,0,0,0,2.122,2.121L11.823,14.3a.25.25,0,0,1,.354,0l9.262,9.263a1.5,1.5,0,0,0,2.122-2.121Z" />
              </svg>
            </button>
          </div>
          <div class="vf-chatbot-feedback__options">
            <button class="vf-chatbot-feedback__option" data-feedback-option="inaccurate">
              Inaccurate answer
            </button>
            <button class="vf-chatbot-feedback__option" data-feedback-option="nocontext">
              Did not use context
            </button>
            <button class="vf-chatbot-feedback__option" data-feedback-option="poorformat">
              Poorly formatted
            </button>
            <button class="vf-chatbot-feedback__option" data-feedback-option="nothelpful">
              Not helpful
            </button>
          </div>
          <label id="vf-chatbot-feedback-comment-title" for="vf-chatbot-feedback-comment" class="vf-chatbot-feedback__comment-title">Comments</label>
          <textarea id="vf-chatbot-feedback-comment" aria-labelledby="vf-chatbot-feedback-comment-title" class="vf-chatbot-feedback__comment" rows="4"></textarea>
          <button type="submit" class="vf-chatbot-feedback__submit vf-u-padding--200" data-vf-js-feedback-submit>
            Submit
          </button>
        </div>
      </div>
    </template>

    <template id="user-message-template">
      <div class="vf-chatbot-message vf-chatbot-message--user  vf-u-margin__top--400">
        <div class="vf-chatbot-message__avatar vf-u-margin__bottom--200">
          <span class="vf-chatbot-message__avatar-name">You</span>
          <img src="../../assets/vf-chatbot/assets/vf-chatbot--avatar-user.svg" alt="Your avatar">
        </div>
        <div class="vf-chatbot-message__content vf-u-padding--200">
          <div class="vf-chatbot-message__content-prompt vf-u-padding__left--200  vf-u-padding__right--200">
            Hello!
          </div>
        </div>
      </div>
    </template>

    <template id="assistant-message-template">
      <div class="vf-chatbot-message vf-chatbot-message--assistant  vf-u-margin__top--400">
        <div class="vf-chatbot-message__avatar vf-u-margin__bottom--200">
          <img src="../../assets/vf-chatbot/assets/vf-chatbot--icon-16x16-dark-green.svg" alt="{{config.title}} avatar">
          <span class="vf-chatbot-message__avatar-name">AI Assistant</span>
        </div>
        <div class="vf-chatbot-message__content vf-u-padding--200">
          <div class="vf-chatbot-message__content-prompt vf-u-padding__left--200  vf-u-padding__right--200">
            How can I help you?
          </div>
        </div>
      </div>
      <div class="vf-chatbot-feedback vf-u-margin__top--200" data-vf-js-chatbot-feedback></div>
    </template>

    <template id="loading-indicator-template">
      <div class="vf-chatbot-message vf-chatbot-message--assistant vf-chatbot-message--loading  vf-u-margin__top--400">
        <div class="vf-chatbot-message__avatar vf-u-margin__bottom--200">
          <img src="../../assets/vf-chatbot/assets/vf-chatbot--icon-16x16-dark-green.svg" alt="AI Assistant">
          <span class="vf-chatbot-message__avatar-name">AI Assistant</span>
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
        <div class="vf-chatbot-action-prompts__list" data-vf-js-action-prompts-list>
          <!-- Individual prompts will be populated here -->
        </div>
      </div>
    </template>

    <template id="single-action-prompt-template">
      <div class="vf-chatbot-action-prompt">
        <a href="#" class="vf-chatbot-action-prompt__link" role="button">

        </a>
      </div>
    </template>
  </div>
</div>

<script>
    const config = {
  type: "modal",
  title: "AI Assistant",
  welcome_logo: true,
  welcome_message: "Welcome! I'm here to help",
  welcome_logo_alt: "AI Assistant",
  welcome_suggestions_title: "Try asking me:",
  input_placeholder: "Ask me ...",
  welcome_max_suggestions: 4,
  disclaimer: 'Disclaimer: This chatbot is designed to assist you with general information and basic inquiries. See our <a class="vf-banner__link" target="_blank" rel="noopener noreferrer" aria-label="disclaimer notes (opens in new tab)" href="https://www.ebi.ac.uk/data-protection/privacy-notice/embl-ebi-public-website/">disclaimer notes</a>.',
  footnote: 'Review AI generated content for accuracy. <a class="vf-link" target="_blank" rel="noopener noreferrer" aria-label="Leave feedback (opens in new tab)" href="https://embl.service-now.com/esc?id=sc_cat_item&sys_id=5eeb8eb91b92e650b376da88b04bcbc1">Leave feedback</a>.',
  icons: {
    assistant_avatar: "https://stable.visual-framework.dev/assets/vf-chatbot/assets/vf-chatbot--icon-16x16-dark-green.svg",
    user_avatar: "https://stable.visual-framework.dev/assets/vf-chatbot/assets/vf-chatbot--avatar-user.svg",
    send_button: "https://stable.visual-framework.dev/assets/vf-chatbot/assets/vf-chatbot--icon-send.svg",
    main_logo_url: "https://stable.visual-framework.dev/assets/vf-chatbot/assets/vf-chatbot--icon-32x32-dark-green.svg",
    minimize: "https://stable.visual-framework.dev/assets/vf-chatbot/assets/vf-chatbot--icon-minimize.svg",
    close: "https://stable.visual-framework.dev/assets/vf-chatbot/assets/vf-chatbot--icon-close.svg"
  },
  api: {
    chat_endpoint: "AWS_API_URL",
    qa_data_url: "https://wwwdev.ebi.ac.uk/training/chatbot-qa/chatbot-qa.json", // These are static responses
    headers:{
      "Content-Type": "application/json",
      "Authorization": "Bearer your-token"
    },
    timeout: 90000
  },
  features: {
    enable_welcome: true,
    enable_feedback: true,
    enable_sources: true,
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
  selectorContext: {
    chatbotRoutes: {
      multiSelect: false,
      showSearch: true,
      showSearchThreshold: 5,
      routes: "https://stable.visual-framework.dev/assets/vf-chatbot/assets/vf-chatbot-selector-llms.json",
      placeholder: "Search",
      title: "Services",
      selector_logo_url: "https://stable.visual-framework.dev/assets/vf-chatbot/assets/vf-chatbot--icon-24x24-dark-green.svg",
      selector_logo_title: "AI Assistant"
    }
  },
  handlers: {
    on_message_send: "handleMessageSend",
    on_response_receive: "handleResponseReceive",
    on_feedback_submit: "handleFeedbackSubmit",
    on_suggestion_click: "handleSuggestionClick",
    on_error: "handleError",
    on_conversation_start: "handleConversationStart",
    on_conversation_end: "handleConversationEnd",
    on_fab_click: "handleFabClick",
    on_dialog_confirm: "handleDialogConfirm",
    on_dialog_cancel: "handleDialogCancel",
    on_minimize: "handleMinimize"
  },
  feedback_options: {
    positive: [
      { id: "accurate", label: "Accurate" },
      { id: "easy", label: "Easy to understand" },
      { id: "formatted", label: "Well formatted" }
    ],
    negative: [
      { id: "inaccurate", label: "Inaccurate" },
      { id: "nocontext", label: "Did not use context" },
      { id: "poorformat", label: "Poorly formatted" }
    ]
  },
  enable_session_persistence: true, // If true, the chatbot will remember the conversation and state across page reloads or navigation (using sessionStorage)
  restore_minimized_state: true // If true, restore minimized state after navigation
};

    window.addEventListener("load", function(e) {
	e.preventDefault();
	e.stopPropagation();
	e.stopImmediatePropagation();
initVFChatbot(config);
});
</script>