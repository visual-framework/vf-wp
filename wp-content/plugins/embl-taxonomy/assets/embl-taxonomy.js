(() => {
  // Bail if no sync button
  $button = document.querySelector('#embl-taxonomy-sync');
  if (!$button) return;

  // Append styles for modal
  const $style = document.createElement('style');
  $style.id = 'embl-taxonomy-css';
  $style.innerHTML = `
#embl-taxonomy-modal {
  border: 0px;
  border-radius: 5px;
  box-shadow:
    0px 0.3px 1.4px rgba(0, 0, 0, 0.056),
    0px 0.7px 3.3px rgba(0, 0, 0, 0.081),
    0px 1.3px 6.3px rgba(0, 0, 0, 0.1),
    0px 2.2px 11.2px rgba(0, 0, 0, 0.119),
    0px 4.2px 20.9px rgba(0, 0, 0, 0.144),
    0px 10px 50px rgba(0, 0, 0, 0.2)
  ;
  padding: 10px 20px;
  text-align: center;
}
#embl-taxonomy-modal::backdrop {
  background-color: rgb(0,0,0,0.2);
  backdrop-filter: blur(2px);
}
#embl-taxonomy-modal .spinner {
  float: none;
  vertical-align: top;
  margin: 0 10px 0 0;
}
`;
  document.head.appendChild($style);

  // Get localized data from PHP
  const {data, path, token, redirect} = window.emblTaxonomySettings;

  // Create modal for output
  const $modal = document.createElement('dialog');
  $modal.id = 'embl-taxonomy-modal';
  $modal.style.display = 'none';
  $modal.innerHTML = `
  <h3><b>${data.syncing}</b></h3>
  <p><span class="spinner is-active"></span> ${data.reload}</p>
  <progress></progress>
`;
  document.body.appendChild($modal);
  const $progress = $modal.querySelector('progress');

  // Sync button handler
  const startSync = async (url) => {
    try {
      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'X-WP-Nonce': token
        }
      });
      const json = await response.json();
      if (json.error) {
        $modal.innerHTML = `<p>${json.error}</p>`;
        return;
      }
      if (Object.hasOwn(json, 'total')) {
        $progress.max = json.total;
      }
      if (Object.hasOwn(json, 'offset')) {
        $progress.value = json.offset;
      }
      if (json.success === true) {
        $progress.value = $progress.max;
        window.location.href = redirect;
        return;
      }
      if (json.next) {
        startSync(new URL(json.next));
        return;
      }
      throw new Error();
    } catch {
      $modal.innerHTML = `<p>${data.error}</p>`;
    }
  };

  $button.addEventListener('click', (ev) => {
    ev.preventDefault();
    $button.disabled = true;
    $button.innerText = 'Syncing...';
    $progress.removeAttribute('max');
    $progress.removeAttribute('value');
    $modal.style.removeProperty('display');
    $modal.showModal();
    startSync(new URL(path));
  });
})();
