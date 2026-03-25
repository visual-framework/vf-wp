(function () {
  function getChatbotRoot(instance) {
    if (instance && instance.container && instance.container.closest) {
      return instance.container.closest("[data-vf-js-chatbot]");
    }

    return document.querySelector("[data-vf-js-chatbot]");
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

  function buildRequestBody(instance, message) {
    return {
      question: message,
      eventId: getEventIdFromUrl(),
      type: getEventType(getChatbotRoot(instance))
    };
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

  function getEventInfo() {
    return document.getElementById("eventInfo");
  }

  function compactEventInfo() {
    var eventInfo = getEventInfo();

    if (eventInfo) {
      eventInfo.classList.add("vf-events-chatbot-event-hero--compact");
    }
  }

  function resetEventInfo() {
    var eventInfo = getEventInfo();

    if (eventInfo) {
      eventInfo.classList.remove("vf-events-chatbot-event-hero--compact");
      eventInfo.style.display = "";
    }
  }

  function hideEventInfo() {
    var eventInfo = document.getElementById("eventInfo");

    if (eventInfo) {
      eventInfo.style.display = "none";
    }
  }

  function showEventInfo() {
    var eventInfo = document.getElementById("eventInfo");

    if (eventInfo) {
      eventInfo.style.display = "";
    }
  }

  function syncEventInfoHero() {
    var eventInfo = document.getElementById("eventInfo");
    var hero = document.querySelector(".vf-hero");
    var heroBackground;

    if (!eventInfo) {
      return;
    }

    eventInfo.style.display = "";

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

  function patchChatbotClass(className) {
    var ChatbotClass = window[className];
    var originalAddAssistantResponse;

    if (!ChatbotClass || !ChatbotClass.prototype || ChatbotClass.prototype.__vfEventsChatbotPatched) {
      return !!ChatbotClass;
    }

    originalAddAssistantResponse = ChatbotClass.prototype.addAssistantResponse;

    ChatbotClass.prototype.callChatAPI = async function (message) {
      var requestData = buildRequestBody(this, message);
      var response;

      console.log("Event chatbot request body:", requestData);

      response = await fetch(this.config.api.chat_endpoint, {
        method: "POST",
        headers: this.config.api.headers,
        body: JSON.stringify(requestData),
        signal: AbortSignal.timeout(this.config.api.timeout)
      });

      if (!response.ok) {
        throw new Error(
          "API call failed: " + response.status + " " + response.statusText
        );
      }

      return await response.json();
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

  function applyPatches() {
    var patchedModal = patchChatbotClass("VFChatbotModal");
    var patchedStandalone = patchChatbotClass("VFChatbotStandalone");

    return patchedModal || patchedStandalone;
  }

  window.vfEventsHandleChatbotMessageSend = function () {
    compactEventInfo();
  };

  window.vfEventsHandleChatbotSuggestionClick = function () {
    compactEventInfo();
  };

  window.vfEventsHandleChatbotFabClick = function () {
    syncEventInfoHero();
    resetEventInfo();
  };

  window.vfEventsHandleChatbotDialogConfirm = function () {
    resetEventInfo();
  };

  window.vfEventsChatbot = {
    applyPatches: applyPatches,
    buildRequestBody: buildRequestBody,
    formatResponseHtml: formatResponseHtml,
    getEventIdFromUrl: getEventIdFromUrl,
    getEventType: getEventType,
    syncEventInfoHero: syncEventInfoHero,
    compactEventInfo: compactEventInfo,
    resetEventInfo: resetEventInfo
  };

  applyPatches();
  syncEventInfoHero();
  document.addEventListener("DOMContentLoaded", function () {
    applyPatches();
    syncEventInfoHero();
  });
  window.addEventListener("load", function () {
    applyPatches();
    syncEventInfoHero();
  });
})();
