(function () {
  var eventRoutesPromise = null;
  var eventRoutesById = {};
  var activeEventState = {
    id: "",
    type: ""
  };
  var defaultEventState = null;
  var welcomeContentCollapseTimeout = null;

  function getChatbotRoot(instance) {
    if (instance && instance.container && instance.container.closest) {
      return instance.container.closest("[data-vf-js-chatbot]");
    }

    return document.querySelector("[data-vf-js-chatbot]");
  }

  function getSelectorElement() {
    return document.querySelector("[data-vf-js-chatbot-selector]");
  }

  function getRoutesPath() {
    var selector = getSelectorElement();

    return selector ? selector.getAttribute("data-routes-path") : "";
  }

  function sanitizeEventType(type) {
    return String(type || "")
      .toLowerCase()
      .replace(/[^a-z0-9]+/g, "-")
      .replace(/^-+|-+$/g, "");
  }

  function getEventType(chatbotRoot) {
    var type = chatbotRoot ? chatbotRoot.getAttribute("data-event-type") : "";
    var heroKicker;
    var kickerText;

    if (type) {
      return type;
    }

    heroKicker = document.querySelector(".vf-hero__kicker");
    kickerText = heroKicker ? heroKicker.textContent.toLowerCase() : "";

    if (kickerText.indexOf("course") !== -1) {
      return "course";
    }

    if (kickerText.indexOf("conference") !== -1) {
      return "conference";
    }

    if (kickerText.indexOf("webinar") !== -1) {
      return "webinar";
    }

    return "event";
  }

  function getEventIdFromUrl() {
    var pathSegments = window.location.pathname.split("/").filter(Boolean);

    return pathSegments.length ? pathSegments[pathSegments.length - 1] : "";
  }

  function setInstanceEventState(instance, eventId, eventType) {
    if (!instance) {
      return;
    }

    instance.__vfActiveEventId = eventId || "";
    instance.__vfActiveEventType = eventType || "";
  }

  function getActiveEventId(chatbotRoot, instance) {
    var root = chatbotRoot || getChatbotRoot();
    var activeEventId = root ? root.getAttribute("data-event-id") : "";
    var instanceEventId = instance ? instance.__vfActiveEventId || "" : "";

    return instanceEventId || activeEventState.id || activeEventId || getEventIdFromUrl();
  }

  function getActiveEventType(chatbotRoot, instance) {
    var root = chatbotRoot || getChatbotRoot();
    var activeEventType = root ? root.getAttribute("data-event-type") : "";
    var instanceEventType = instance ? instance.__vfActiveEventType || "" : "";

    return instanceEventType || activeEventState.type || activeEventType || getEventType(root);
  }

  function buildRequestBody(instance, message) {
    var chatbotRoot = getChatbotRoot(instance);

    return {
      question: message,
      eventId: getActiveEventId(chatbotRoot, instance),
      type: getActiveEventType(chatbotRoot, instance)
    };
  }

  function normalizeRoutesPayload(data) {
    if (Array.isArray(data)) {
      return data;
    }

    if (data && Array.isArray(data.routes)) {
      return data.routes;
    }

    return [];
  }

  function loadEventRoutes() {
    var routesPath = getRoutesPath();

    if (!routesPath) {
      return Promise.resolve([]);
    }

    if (eventRoutesPromise) {
      return eventRoutesPromise;
    }

    eventRoutesPromise = fetch(routesPath)
      .then(function (response) {
        if (!response.ok) {
          throw new Error("Failed to load event routes: " + response.status);
        }

        return response.json();
      })
      .then(function (data) {
        var routes = normalizeRoutesPayload(data);

        eventRoutesById = {};
        routes.forEach(function (route) {
          if (route && route.id) {
            eventRoutesById[route.id] = route;
          }
        });

        return routes;
      })
      .catch(function (error) {
        console.error("Unable to load event routes:", error);
        eventRoutesById = {};
        eventRoutesPromise = null;
        return [];
      });

    return eventRoutesPromise;
  }

  function getEventInfo() {
    return document.getElementById("eventInfo");
  }

  function getEventCard() {
    return document.querySelector(".vf-events-chatbot-event-card");
  }

  function getEventCardElement(selector) {
    var card = getEventCard();

    return card ? card.querySelector(selector) : null;
  }

  function ensureEventCardElement(selector, tagName, className) {
    var card = getEventCard();
    var element = card ? card.querySelector(selector) : null;
    var title;

    if (element || !card) {
      return element;
    }

    element = document.createElement(tagName);
    element.className = className;

    if (selector === "[data-vf-js-chatbot-event-title]") {
      element.setAttribute("data-vf-js-chatbot-event-title", "");
      card.appendChild(element);
      return element;
    }

    title = card.querySelector("[data-vf-js-chatbot-event-title]");
    if (selector === "[data-vf-js-chatbot-event-date]") {
      element.setAttribute("data-vf-js-chatbot-event-date", "");
    } else if (selector === "[data-vf-js-chatbot-event-type]") {
      element.setAttribute("data-vf-js-chatbot-event-type", "");
    }

    if (title) {
      card.insertBefore(element, title);
    } else {
      card.appendChild(element);
    }

    return element;
  }

  function createEventDateLabel(startDate, endDate) {
    if (!startDate) {
      return "";
    }

    if (!endDate) {
      return startDate;
    }

    return startDate + " – " + endDate;
  }

  function getWelcomeContent() {
    return document.querySelector(".vf-chatbot-welcome__content");
  }

  function setWelcomeContentCollapsed(collapsed) {
    var welcomeContent = getWelcomeContent();

    if (!welcomeContent) {
      return;
    }

    if (welcomeContentCollapseTimeout) {
      window.clearTimeout(welcomeContentCollapseTimeout);
      welcomeContentCollapseTimeout = null;
    }

    if (collapsed) {
      welcomeContent.classList.add("vf-chatbot-welcome__content--faded");
      welcomeContentCollapseTimeout = window.setTimeout(function () {
        welcomeContent.classList.add("vf-chatbot-welcome__content--collapsed");
        welcomeContentCollapseTimeout = null;
      }, 220);
      return;
    }

    welcomeContent.classList.remove("vf-chatbot-welcome__content--collapsed");

    window.requestAnimationFrame(function () {
      window.requestAnimationFrame(function () {
        welcomeContent.classList.remove("vf-chatbot-welcome__content--faded");
      });
    });
  }

  function compactEventInfo() {
    var eventInfo = getEventInfo();

    if (eventInfo) {
      eventInfo.classList.add("vf-events-chatbot-event-hero--compact");
    }

    setWelcomeContentCollapsed(true);
  }

  function resetEventInfo() {
    var eventInfo = getEventInfo();

    if (eventInfo) {
      eventInfo.classList.remove("vf-events-chatbot-event-hero--compact");
      eventInfo.style.display = "";
    }

    setWelcomeContentCollapsed(false);
  }

  function captureDefaultEventState() {
    var chatbotRoot = getChatbotRoot();
    var eventInfo = getEventInfo();
    var dateBadge = getEventCardElement("[data-vf-js-chatbot-event-date]");
    var typeBadge = getEventCardElement("[data-vf-js-chatbot-event-type]");
    var title = getEventCardElement("[data-vf-js-chatbot-event-title]");

    if (defaultEventState || !chatbotRoot) {
      return;
    }

    defaultEventState = {
      id: chatbotRoot.getAttribute("data-event-id") || "",
      type: chatbotRoot.getAttribute("data-event-type") || "",
      dateLabel: dateBadge ? dateBadge.textContent : "",
      typeLabel: typeBadge ? typeBadge.textContent : "",
      title: title ? title.textContent : "",
      heroBackgroundImage: eventInfo ? eventInfo.style.backgroundImage || "" : "",
      heroBackgroundRepeat: eventInfo ? eventInfo.style.backgroundRepeat || "" : "",
      heroBackgroundPosition: eventInfo ? eventInfo.style.backgroundPosition || "" : "",
      heroBackgroundSize: eventInfo ? eventInfo.style.backgroundSize || "" : ""
    };
  }

  function clearSelectorSelection() {
    var selector = getSelectorElement();
    var selectorInstance;
    var dropdown;
    var titleEl;
    var titleText;
    var searchEl;
    var clearEl;

    if (!selector) {
      return;
    }

    selectorInstance = selector.__vfChatbotSelectorInstance;

    if (selectorInstance && defaultEventState && defaultEventState.id) {
      selectorInstance.setSelection([defaultEventState.id]);
    }

    selector
      .querySelectorAll("[data-vf-js-selector-item]")
      .forEach(function (item) {
        item.style.display = "";
      });

    dropdown = selector.querySelector("[data-vf-js-selector-dropdown]");
    titleEl = selector.querySelector("[data-vf-js-selector-toggle]");
    titleText = selector.querySelector(".vf-chatbot-selector__title-text");
    searchEl = selector.querySelector("[data-vf-js-selector-search]");
    clearEl = selector.querySelector("[data-vf-js-selector-clear]");

    if (dropdown) {
      dropdown.style.display = "none";
    }

    if (titleEl) {
      titleEl.classList.remove("vf-chatbot-selector__title--expanded");
    }

    if (titleText) {
      titleText.textContent =
        selector.getAttribute("data-empty-label") || "Select other event";
    }

    if (searchEl) {
      searchEl.value = "";
    }

    if (clearEl) {
      clearEl.classList.remove("vf-chatbot-selector__clear--active");
    }
  }

  function restoreDefaultEventState() {
    var chatbotRoot = getChatbotRoot();
    var eventInfo = getEventInfo();
    var dateBadge = ensureEventCardElement(
      "[data-vf-js-chatbot-event-date]",
      "p",
      "vf-badge vf-badge--primary customBadgePurple vf-events-chatbot-badge"
    );
    var typeBadge = ensureEventCardElement(
      "[data-vf-js-chatbot-event-type]",
      "p",
      "vf-badge vf-badge--primary customBadgePurple vf-events-chatbot-badge"
    );
    var title = ensureEventCardElement(
      "[data-vf-js-chatbot-event-title]",
      "h3",
      "event-card-title"
    );

    if (!defaultEventState || !chatbotRoot) {
      return;
    }

    chatbotRoot.setAttribute("data-event-id", defaultEventState.id || "");
    chatbotRoot.setAttribute("data-event-type", defaultEventState.type || "");
    activeEventState.id = defaultEventState.id || "";
    activeEventState.type = defaultEventState.type || "";
    setInstanceEventState(
      getChatbotInstance(),
      activeEventState.id,
      activeEventState.type
    );

    if (dateBadge) {
      dateBadge.textContent = defaultEventState.dateLabel || "";
      dateBadge.style.display = defaultEventState.dateLabel ? "" : "none";
    }

    if (typeBadge) {
      typeBadge.textContent = defaultEventState.typeLabel || "";
      typeBadge.style.display = defaultEventState.typeLabel ? "" : "none";
    }

    if (title) {
      title.textContent = defaultEventState.title || "";
    }

    if (eventInfo) {
      eventInfo.style.backgroundImage = defaultEventState.heroBackgroundImage || "";
      eventInfo.style.backgroundRepeat = defaultEventState.heroBackgroundRepeat || "";
      eventInfo.style.backgroundPosition =
        defaultEventState.heroBackgroundPosition || "";
      eventInfo.style.backgroundSize = defaultEventState.heroBackgroundSize || "";
    }

    clearSelectorSelection();
    resetEventInfo();

    if (!defaultEventState.heroBackgroundImage) {
      syncEventInfoHero();
    }
  }

  function syncEventInfoHero() {
    var eventInfo = document.getElementById("eventInfo");
    var hero = document.querySelector(".vf-hero");
    var activeRoute = eventRoutesById[getActiveEventId(getChatbotRoot())];
    var heroBackground;

    if (!eventInfo) {
      return;
    }

    eventInfo.style.display = "";

    if (activeRoute && activeRoute.hero_image) {
      eventInfo.style.backgroundImage = "url('" + activeRoute.hero_image + "')";
      eventInfo.style.backgroundRepeat = "no-repeat";
      eventInfo.style.backgroundPosition = "73% 46%";
      eventInfo.style.backgroundSize = "auto";
      return;
    }

    if (!hero) {
      return;
    }

    heroBackground =
      hero.style.getPropertyValue("--vf-hero--bg-image") ||
      window.getComputedStyle(hero).getPropertyValue("--vf-hero--bg-image");

    heroBackground = heroBackground ? heroBackground.trim() : "";

    if (heroBackground && heroBackground !== "none") {
      eventInfo.style.backgroundImage = heroBackground;
    }
  }

  function updateEventInfoCard(route) {
    var chatbotRoot = getChatbotRoot();
    var eventInfo = getEventInfo();
    var dateBadge = ensureEventCardElement(
      "[data-vf-js-chatbot-event-date]",
      "p",
      "vf-badge vf-badge--primary customBadgePurple vf-events-chatbot-badge"
    );
    var typeBadge = ensureEventCardElement(
      "[data-vf-js-chatbot-event-type]",
      "p",
      "vf-badge vf-badge--primary customBadgePurple vf-events-chatbot-badge"
    );
    var title = ensureEventCardElement(
      "[data-vf-js-chatbot-event-title]",
      "h3",
      "event-card-title"
    );
    var dateLabel;

    if (!route || !chatbotRoot) {
      return;
    }

    chatbotRoot.setAttribute("data-event-id", route.id || "");
    chatbotRoot.setAttribute(
      "data-event-type",
      sanitizeEventType(route.event_type_value || route.event_type)
    );
    activeEventState.id = route.id || "";
    activeEventState.type = sanitizeEventType(
      route.event_type_value || route.event_type
    );
    setInstanceEventState(
      getChatbotInstance(),
      activeEventState.id,
      activeEventState.type
    );

    dateLabel = route.date_label || createEventDateLabel(route.start_date, route.end_date);

    if (dateBadge) {
      dateBadge.textContent = dateLabel;
      dateBadge.style.display = dateLabel ? "" : "none";
    }

    if (typeBadge) {
      typeBadge.textContent = route.event_type || "";
      typeBadge.style.display = route.event_type ? "" : "none";
    }

    if (title) {
      title.textContent = route.title || "";
    }

    if (eventInfo) {
      if (route.hero_image) {
        eventInfo.style.backgroundImage = "url('" + route.hero_image + "')";
        eventInfo.style.backgroundRepeat = "no-repeat";
        eventInfo.style.backgroundPosition = "73% 46%";
        eventInfo.style.backgroundSize = "auto";
      } else {
        eventInfo.style.backgroundImage = "";
      }
    }
  }

  function registerChatbotInstance(instance) {
    var chatbotRoot = getChatbotRoot(instance);

    if (chatbotRoot) {
      chatbotRoot.__vfEventsChatbotInstance = instance;
      setInstanceEventState(
        instance,
        chatbotRoot.getAttribute("data-event-id") || "",
        chatbotRoot.getAttribute("data-event-type") || ""
      );
    }
  }

  function getChatbotInstance() {
    var chatbotRoot = getChatbotRoot();

    return chatbotRoot ? chatbotRoot.__vfEventsChatbotInstance : null;
  }

  function resetConversationForEventSwitch(instance) {
    var loadingParent;

    if (!instance) {
      return;
    }

    if (instance.loadingIndicator) {
      instance.loadingIndicator.style.display = "none";
      loadingParent = instance.loadingIndicator.parentNode;

      if (loadingParent) {
        loadingParent.removeChild(instance.loadingIndicator);
      }
    }

    instance.messageHistory = [];

    if (typeof instance.generateConversationId === "function") {
      instance.conversationId = instance.generateConversationId();
    }

    instance.currentAssistant = "";

    if (instance.messagesContainer) {
      instance.messagesContainer.innerHTML = "";
    }

    if (instance.config && instance.config.enable_session_persistence) {
      sessionStorage.removeItem("chatbotModalConversationHTML");
      sessionStorage.removeItem("chatbotModalFeedbackState");
    }

    if (instance.welcomeScreen && instance.config.features.enable_welcome_suggestions) {
      instance.welcomeScreen.style.display = "flex";
    }

    if (instance.input) {
      instance.input.value = "";
      instance.input.style.height = "auto";
      instance.input.classList.remove("vf-chatbot-modal__input--scrollable");
    }

    if (instance.sendBtn) {
      instance.sendBtn.disabled = false;
    }

    if (typeof instance.scrollToBottom === "function") {
      instance.scrollToBottom();
    }
  }

  function handleEventRouteSelection(detail) {
    var selectedItems = detail && detail.selectedItems ? detail.selectedItems : [];
    var selectedRouteId = selectedItems.length > 0 ? selectedItems[0] : "";

    if (!selectedRouteId || selectedRouteId === "all") {
      return;
    }

    loadEventRoutes().then(function () {
      var route = eventRoutesById[selectedRouteId];

      if (!route) {
        return;
      }

      updateEventInfoCard(route);
      resetEventInfo();
      resetConversationForEventSwitch(getChatbotInstance());
    });
  }

  function bindSelectorSelection() {
    var selector = getSelectorElement();

    if (!selector || selector.__vfEventsSelectionBound) {
      return;
    }

    selector.__vfEventsSelectionBound = true;
    selector.addEventListener("routeselection", function (event) {
      handleEventRouteSelection(event.detail);
    });
  }

  function patchChatbotClass(className) {
    var ChatbotClass = window[className];
    var originalAddAssistantResponse;
    var originalInit;
    var originalSetLoadingState;

    if (!ChatbotClass || !ChatbotClass.prototype || ChatbotClass.prototype.__vfEventsChatbotPatched) {
      return !!ChatbotClass;
    }

    originalAddAssistantResponse = ChatbotClass.prototype.addAssistantResponse;
    originalInit = ChatbotClass.prototype.init;
    originalSetLoadingState = ChatbotClass.prototype.setLoadingState;

    ChatbotClass.prototype.init = function () {
      var result = originalInit.apply(this, arguments);
      var instance = this;

      if (result && typeof result.then === "function") {
        return result.then(function (value) {
          registerChatbotInstance(instance);
          return value;
        });
      }

      registerChatbotInstance(instance);
      return result;
    };

    ChatbotClass.prototype.callChatAPI = async function (message) {
      var requestData = buildRequestBody(this, message);
      var requestBody = JSON.stringify(requestData);
      var response;

      console.log("Event chatbot POST body:", requestBody);

      response = await fetch(this.config.api.chat_endpoint, {
        method: "POST",
        headers: this.config.api.headers,
        body: requestBody,
        signal: AbortSignal.timeout(this.config.api.timeout)
      });

      if (!response.ok) {
        throw new Error(
          "API call failed: " + response.status + " " + response.statusText
        );
      }

      return await response.json();
    };

    ChatbotClass.prototype.setLoadingState = function (isLoading) {
      var loadingParent;

      if (
        !this.config ||
        !this.config.features ||
        !this.config.features.enable_typing_indicator
      ) {
        return;
      }

      if (isLoading) {
        if (!this.loadingIndicator && this.loadingTemplate) {
          var loadingContent = this.loadingTemplate.content.cloneNode(true);
          this.loadingIndicator = loadingContent.firstElementChild;

          if (this.loadingIndicator) {
            var avatar = this.loadingIndicator.querySelector("img");

            if (avatar) {
              avatar.src = this.config.icons.assistant_avatar;
            }

            if (this.messagesContainer) {
              this.messagesContainer.appendChild(this.loadingIndicator);
            }
          }
        } else if (
          this.loadingIndicator &&
          !this.loadingIndicator.parentNode &&
          this.messagesContainer
        ) {
          this.messagesContainer.appendChild(this.loadingIndicator);
        }

        if (this.loadingIndicator) {
          this.loadingIndicator.style.display = "block";
        }
      } else if (this.loadingIndicator) {
        this.loadingIndicator.style.display = "none";
        loadingParent = this.loadingIndicator.parentNode;

        if (loadingParent) {
          loadingParent.removeChild(this.loadingIndicator);
        }
      }

      if (this.sendBtn) {
        this.sendBtn.disabled = isLoading;
      }

      if (typeof this.scrollToBottom === "function") {
        this.scrollToBottom();
      } else if (typeof originalSetLoadingState === "function") {
        originalSetLoadingState.call(this, isLoading);
      }
    };

    ChatbotClass.prototype.addAssistantResponse = function (text, sources, prompts) {
      return originalAddAssistantResponse.call(
        this,
        formatResponseHtml(text),
        sources,
        prompts
      );
    };

    ChatbotClass.prototype.__vfEventsChatbotPatched = true;

    return true;
  }

  function formatResponseHtml(rawHtml) {
    var normalizedHtml = (rawHtml || "").trim();
    var blocks;

    if (
      normalizedHtml.indexOf("**") === -1 &&
      normalizedHtml.indexOf("\n- ") === -1 &&
      normalizedHtml.indexOf("](") === -1
    ) {
      return normalizedHtml;
    }

    normalizedHtml = normalizedHtml
      .replace(/<\/?p>/gi, "")
      .replace(/\r\n/g, "\n")
      .trim();

    normalizedHtml = normalizedHtml.replace(
      /\[([^\]]+)\]\((https?:\/\/[^\s)]+)\)/g,
      '<a href="$2" target="_blank" rel="noopener noreferrer">$1</a>'
    );
    normalizedHtml = normalizedHtml.replace(/\*\*(.*?)\*\*/g, "<strong>$1</strong>");

    blocks = normalizedHtml.split(/\n\s*\n/).filter(Boolean);

    return blocks
      .map(function (block) {
        var lines = block
          .split("\n")
          .map(function (line) {
            return line.trim();
          })
          .filter(Boolean);
        var bulletLines = [];
        var introLines = [];
        var bulletStartIndex = -1;

        lines.forEach(function (line, index) {
          if (bulletStartIndex === -1 && line.indexOf("- ") === 0) {
            bulletStartIndex = index;
          }
        });

        if (bulletStartIndex === 0) {
          bulletLines = lines;
        } else if (bulletStartIndex > 0) {
          introLines = lines.slice(0, bulletStartIndex);
          bulletLines = lines.slice(bulletStartIndex);
        }

        if (bulletLines.length > 0) {
          var introHtml = introLines.length
            ? "<p>" + introLines.join("<br>") + "</p>"
            : "";
          var listHtml =
            "<ul>" +
            bulletLines
              .map(function (line) {
                return "<li>" + line.replace(/^- /, "") + "</li>";
              })
              .join("") +
            "</ul>";

          return introHtml + listHtml;
        }

        return "<p>" + lines.join("<br>") + "</p>";
      })
      .join("");
  }

  function applyPatches() {
    var patchedModal = patchChatbotClass("VFChatbotModal");
    var patchedStandalone = patchChatbotClass("VFChatbotStandalone");

    return patchedModal || patchedStandalone;
  }

  function initializeEventChatbot() {
    var chatbotRoot = getChatbotRoot();

    if (chatbotRoot) {
      activeEventState.id = chatbotRoot.getAttribute("data-event-id") || "";
      activeEventState.type = chatbotRoot.getAttribute("data-event-type") || "";
    }

    applyPatches();
    bindSelectorSelection();
    loadEventRoutes();
    syncEventInfoHero();
    captureDefaultEventState();
  }

  window.vfEventsHandleChatbotMessageSend = function () {
    compactEventInfo();
  };

  window.vfEventsHandleChatbotSuggestionClick = function () {
    compactEventInfo();
  };

  window.vfEventsHandleChatbotFabClick = function () {
    resetEventInfo();
    syncEventInfoHero();
  };

  window.vfEventsHandleChatbotDialogConfirm = function () {
    restoreDefaultEventState();
  };

  window.vfEventsChatbot = {
    applyPatches: applyPatches,
    buildRequestBody: buildRequestBody,
    formatResponseHtml: formatResponseHtml,
    getActiveEventId: getActiveEventId,
    getActiveEventType: getActiveEventType,
    getEventIdFromUrl: getEventIdFromUrl,
    getEventType: getEventType,
    syncEventInfoHero: syncEventInfoHero,
    compactEventInfo: compactEventInfo,
    resetEventInfo: resetEventInfo,
    updateEventInfoCard: updateEventInfoCard
  };

  initializeEventChatbot();
  document.addEventListener("DOMContentLoaded", initializeEventChatbot);
  window.addEventListener("load", initializeEventChatbot);
})();
