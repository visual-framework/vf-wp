// custom-autocomplete.js
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('vf-search-input');
    const suggestionsContainer = document.createElement('div');
    suggestionsContainer.classList.add('autocomplete-suggestions');
    searchInput.parentNode.appendChild(suggestionsContainer);
  
    let debounceTimeout;
  
    searchInput.addEventListener('input', function () {
      const searchTerm = searchInput.value.trim();
  
      // Use a debounce function to limit the frequency of requests
      clearTimeout(debounceTimeout);
      debounceTimeout = setTimeout(() => {
        if (searchTerm.length >= 3) {
          fetchSuggestions(searchTerm);
        } else {
          suggestionsContainer.innerHTML = '';
        }
      }, 300); // Adjust the delay (in milliseconds) as needed
    });
  
    async function fetchSuggestions(searchTerm) {
      try {
        const response = await fetch(
          `${ajax_object.ajax_url}?action=custom_autocomplete&term=${searchTerm}`
        );
  
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
  
        const data = await response.json();
        displaySuggestions(data);
      } catch (error) {
        console.error('Error fetching data:', error);
      }
    }
  
    function displaySuggestions(suggestions) {
      suggestionsContainer.innerHTML = '';
  
      if (suggestions.length === 0) {
        return;
      }
  
      const fragment = document.createDocumentFragment();
  
      suggestions.forEach((suggestion) => {
        const suggestionItem = document.createElement('div');
        suggestionItem.textContent = suggestion;
        suggestionItem.classList.add('autocomplete-suggestion');
  
        suggestionItem.addEventListener('click', function () {
          searchInput.value = suggestion;
          suggestionsContainer.innerHTML = '';
          // You can also trigger the search here.
        });
  
        fragment.appendChild(suggestionItem);
      });
  
      suggestionsContainer.appendChild(fragment);
    }
  
    // Close suggestions when clicking outside the input and suggestions container
    document.addEventListener('click', function (e) {
      if (e.target !== searchInput && e.target !== suggestionsContainer) {
        suggestionsContainer.innerHTML = '';
      }
    });
  });
  