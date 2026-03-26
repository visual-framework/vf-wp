(function () {
  var DEFAULT_EVENT_HERO_IMAGE =
    "https://www.embl.org/about/info/course-and-conference-office/wp-content/uploads/EES26-07_WebHero_SexDifferencesAndDiseases-scaled.jpg";
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
    return document.querySelector("[data-vf-js-events-chatbot-selector]");
  }

  function getSelectorDropdown() {
    var selector = getSelectorElement();

    return selector ? selector.querySelector("[data-vf-js-selector-dropdown]") : null;
  }

  function getSelectorToggle() {
    var selector = getSelectorElement();

    return selector ? selector.querySelector("[data-vf-js-selector-toggle]") : null;
  }

  function getSelectorSearchInput() {
    var selector = getSelectorElement();

    return selector ? selector.querySelector("[data-vf-js-selector-search]") : null;
  }

  function getSelectorList() {
    var selector = getSelectorElement();

    return selector ? selector.querySelector("[data-vf-js-chatbot-selector-list]") : null;
  }

  function getSelectorTitleText() {
    var selector = getSelectorElement();

    return selector ? selector.querySelector(".vf-chatbot-selector__title-text") : null;
  }

  function getRoutesPath() {
    var selector = getSelectorElement();

    return selector ? selector.getAttribute("data-routes-path") : "";
  }

  function getSelectorEmptyLabel() {
    var selector = getSelectorElement();

    return selector ? selector.getAttribute("data-empty-label") || "Select other event" : "Select other event";
  }

  function getDefaultSelectorRouteId() {
    var selector = getSelectorElement();

    return selector ? selector.getAttribute("data-selected-route-id") || "" : "";
  }

  function sortRoutesAlphabetically(routes) {
    return routes.slice().sort(function (routeA, routeB) {
      return String(routeA.title || "").localeCompare(
        String(routeB.title || ""),
        undefined,
        { sensitivity: "base" }
      );
    });
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

  function applyEventInfoBackground(eventInfo, backgroundImage) {
    if (!eventInfo) {
      return;
    }

    eventInfo.style.backgroundImage =
      "url('" + (backgroundImage || DEFAULT_EVENT_HERO_IMAGE) + "')";
    eventInfo.style.backgroundRepeat = "no-repeat";
    eventInfo.style.backgroundPosition = "73% 46%";
    eventInfo.style.backgroundSize = "auto";
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
        var routes = sortRoutesAlphabetically(normalizeRoutesPayload(data));

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

  function updateSelectorTitle() {
    var titleText = getSelectorTitleText();

    if (titleText) {
      titleText.textContent = getSelectorEmptyLabel();
    }
  }

  function closeSelectorDropdown() {
    var selector = getSelectorElement();
    var dropdown = getSelectorDropdown();
    var titleEl = getSelectorToggle();

    if (!selector) {
      return;
    }

    if (dropdown) {
      dropdown.style.display = "none";
    }

    if (titleEl) {
      titleEl.classList.remove("vf-chatbot-selector__title--expanded");
    }

    updateSelectorTitle();
  }

  function openSelectorDropdown() {
    var dropdown = getSelectorDropdown();
    var titleEl = getSelectorToggle();

    if (dropdown) {
      dropdown.style.display = "block";
    }

    if (titleEl) {
      titleEl.classList.add("vf-chatbot-selector__title--expanded");
    }
  }

  function renderSelectorItems(searchQuery) {
    var list = getSelectorList();
    var selectedRouteId = getActiveEventId(getChatbotRoot()) || getDefaultSelectorRouteId();
    var normalizedQuery = String(searchQuery || "").toLowerCase().trim();
    var routes;

    if (!list) {
      return;
    }

    routes = Object.keys(eventRoutesById)
      .map(function (routeId) {
        return eventRoutesById[routeId];
      })
      .filter(Boolean);

    routes = sortRoutesAlphabetically(routes);

    if (selectedRouteId) {
      routes.sort(function (routeA, routeB) {
        if (routeA.id === selectedRouteId && routeB.id !== selectedRouteId) {
          return -1;
        }

        if (routeB.id === selectedRouteId && routeA.id !== selectedRouteId) {
          return 1;
        }

        return 0;
      });
    }

    if (normalizedQuery) {
      routes = routes.filter(function (route) {
        return String(route.title || "").toLowerCase().indexOf(normalizedQuery) !== -1;
      });
    }

    list.innerHTML = "";

    routes.forEach(function (route) {
      var item = document.createElement("li");

      item.className = "vf-chatbot-selector__item";
      item.setAttribute("data-vf-js-selector-item", "");
      item.setAttribute("data-route-id", route.id || "");
      item.setAttribute("data-title", route.title || "");
      item.setAttribute("role", "button");
      item.setAttribute("tabindex", "0");
      item.setAttribute("aria-label", "Select " + (route.title || "event"));

      if (route.id === selectedRouteId) {
        item.classList.add("vf-chatbot-selector__item--selected");
      }

      item.innerHTML =
        '<div class="vf-chatbot-selector__item-content">' +
        '<div class="vf-chatbot-selector__item-title">' +
        (route.title || "") +
        "</div>" +
        "</div>" +
        '<span class="vf-chatbot-selector__tick">' +
        '<svg width="24" height="20" viewBox="0 0 24 20" fill="none" xmlns="http://www.w3.org/2000/svg">' +
        '<path d="M6.8478 19.4278C6.33162 19.4208 5.82376 19.2967 5.36257 19.0647C4.90137 18.8328 4.49889 18.4991 4.18551 18.0889L0.426086 13.8152C0.149005 13.4712 0.0154068 13.0335 0.0531476 12.5934C0.0908883 12.1533 0.297055 11.7447 0.62866 11.4529C0.960265 11.1611 1.39171 11.0085 1.83304 11.0271C2.27438 11.0456 2.69152 11.2338 2.99751 11.5523L6.52037 15.562C6.55956 15.6066 6.60755 15.6425 6.66133 15.6675C6.71511 15.6925 6.77349 15.7061 6.83279 15.7074C6.89209 15.7087 6.95101 15.6976 7.00582 15.675C7.06063 15.6523 7.11015 15.6185 7.15123 15.5758L21.0369 1.10375C21.1921 0.940538 21.3778 0.809473 21.5836 0.71804C21.7893 0.626608 22.0111 0.576599 22.2362 0.570868C22.4613 0.565137 22.6853 0.603797 22.8954 0.684641C23.1056 0.765484 23.2977 0.886928 23.4609 1.04204C23.6242 1.19715 23.7552 1.38289 23.8467 1.58865C23.9381 1.79441 23.9881 2.01617 23.9938 2.24126C23.9996 2.46635 23.9609 2.69036 23.8801 2.90051C23.7992 3.11066 23.6778 3.30282 23.5227 3.46604L9.46209 18.2655C9.1402 18.6414 8.7385 18.9409 8.28624 19.1419C7.83398 19.343 7.34257 19.4406 6.8478 19.4278Z" fill="#54585A"/>' +
        "</svg>" +
        "</span>";

      list.appendChild(item);
    });

    updateSelectorTitle();
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

    renderSelectorItems("");
    closeSelectorDropdown();
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
      applyEventInfoBackground(eventInfo, activeRoute.hero_image);
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
      eventInfo.style.backgroundRepeat = "no-repeat";
      eventInfo.style.backgroundPosition = "73% 46%";
      eventInfo.style.backgroundSize = "auto";
      return;
    }

    applyEventInfoBackground(eventInfo);
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
      applyEventInfoBackground(eventInfo, route.hero_image);
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

  function handleEventRouteSelection(selectedRouteId) {
    var searchInput = getSelectorSearchInput();
    var currentRouteId = getActiveEventId(getChatbotRoot());

    if (!selectedRouteId) {
      return;
    }

    if (selectedRouteId === currentRouteId) {
      renderSelectorItems(searchInput ? searchInput.value || "" : "");
      closeSelectorDropdown();
      return;
    }

    loadEventRoutes().then(function () {
      var route = eventRoutesById[selectedRouteId];

      if (!route) {
        return;
      }

      console.log("Selected event code:", route.id || selectedRouteId);
      updateEventInfoCard(route);
      resetEventInfo();
      resetConversationForEventSwitch(getChatbotInstance());
      renderSelectorItems("");
      closeSelectorDropdown();

      if (searchInput) {
        searchInput.value = "";
      }
    });
  }

  function bindSelectorSelection() {
    var selector = getSelectorElement();
    var toggle = getSelectorToggle();
    var searchInput = getSelectorSearchInput();
    var list = getSelectorList();

    if (!selector || selector.__vfEventsSelectionBound) {
      return;
    }

    selector.__vfEventsSelectionBound = true;

    if (toggle) {
      toggle.addEventListener("click", function (event) {
        var dropdown = getSelectorDropdown();
        var isOpen = dropdown && dropdown.style.display === "block";

        event.preventDefault();
        event.stopPropagation();

        if (isOpen) {
          closeSelectorDropdown();
        } else {
          openSelectorDropdown();
        }
      });
    }

    if (searchInput) {
      searchInput.addEventListener("input", function (event) {
        renderSelectorItems(event.target.value || "");
      });
    }

    if (list) {
      list.addEventListener("click", function (event) {
        var item = event.target.closest("[data-vf-js-selector-item]");

        if (!item) {
          return;
        }

        event.preventDefault();
        event.stopPropagation();
        handleEventRouteSelection(item.getAttribute("data-route-id") || "");
      });

      list.addEventListener("keydown", function (event) {
        var item;

        if (event.key !== "Enter" && event.key !== " ") {
          return;
        }

        item = event.target.closest("[data-vf-js-selector-item]");

        if (!item) {
          return;
        }

        event.preventDefault();
        event.stopPropagation();
        handleEventRouteSelection(item.getAttribute("data-route-id") || "");
      });
    }

    if (!selector.__vfEventsDocumentClickHandler) {
      selector.__vfEventsDocumentClickHandler = function (event) {
        if (!selector.contains(event.target)) {
          closeSelectorDropdown();
        }
      };

      document.addEventListener("click", selector.__vfEventsDocumentClickHandler);
    }

    loadEventRoutes().then(function () {
      renderSelectorItems("");
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

      console.log("Event chatbot payload:", requestData);
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
    updateSelectorTitle();
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
