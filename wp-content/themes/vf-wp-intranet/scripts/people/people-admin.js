(() => {
    // Bail if no settings are available
    if (!window.vfwpPeopleSettings) {
        console.error('vfwpPeopleSettings is not defined.');
        return;
    }

    const { adminUrl, syncPath, deletePath, token, messages } = window.vfwpPeopleSettings;

    // Create modal for sync and delete actions
    const createModal = (title, message) => {
        const modal = document.createElement('dialog');
        modal.id = 'vfwp-people-modal';
        modal.innerHTML = `
            <h3><b>${title}</b></h3>
            <p>${message}</p>
            <progress></progress>
        `;
        document.body.appendChild(modal);
        return modal;
    };

    // Append styles for modal
    const $style = document.createElement('style');
    $style.id = 'modal-css';
    $style.innerHTML = `
        #vfwp-people-modal {
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
        #vfwp-people-modal::backdrop {
            background-color: rgb(0,0,0,0.2);
            backdrop-filter: blur(2px);
        }
        #vfwp-people-modal .spinner {
            float: none;
            vertical-align: top;
            margin: 0 10px 0 0;
        }
    `;
    document.head.appendChild($style);

    // Function to display a success notice
    const displaySuccessNotice = (message, lastSyncTime) => {
        const notice = document.createElement('div');
        notice.className = 'notice notice-success is-dismissible';
        notice.innerHTML = `
            <p>${message}</p>
        `;
        document.querySelector('.wrap').prepend(notice);
    };

    // Handle sync button click
    const syncButton = document.querySelector('#vfwp-sync-people');
    if (syncButton) {
        syncButton.addEventListener('click', async () => {
            const modal = createModal(messages.syncing, messages.reload);
            modal.showModal();

            const fetchWithTimeout = (url, options, timeout = 5000) => {
                const controller = new AbortController();
                const timer = setTimeout(() => controller.abort(), timeout);
                
                return fetch(url, { ...options, signal: controller.signal })
                    .finally(() => clearTimeout(timer));
            };
            
            try {
                const response = await fetchWithTimeout(syncPath, {
                    method: 'POST',
                    headers: { 'X-WP-Nonce': token },
                }, 3600000); // Set timeout to 1h
        
                if (!response.ok) {
                    throw new Error(`The server is currently busy and unable to process your request.\n\nHTTP error! Status: ${response.status}.\n\nPlease try again later`);
                }
            
                const json = await response.json();
                if (json.success) {
                    const lastSyncTime = new Date().toLocaleString();
                    localStorage.setItem('lastSyncTime', lastSyncTime);
                    displaySuccessNotice(json.message, lastSyncTime);
                    window.location.reload();
                } else {
                    throw new Error(json.message || messages.error);
                }
            } catch (error) {
                console.error('Sync error:', error);
                modal.innerHTML = `<p>${error.message}.</p>`;
            }
        });
    }

    // Handle delete button click
    const deleteButton = document.querySelector('#vfwp-delete-people');
    if (deleteButton) {
        deleteButton.addEventListener('click', () => {
            const confirmModal = document.createElement('dialog');
            confirmModal.id = 'vfwp-delete-confirm-modal';
            confirmModal.innerHTML = `
                <h3><b>Confirm Deletion</b></h3>
                <p>Are you sure you want to delete all people? This action cannot be undone.</p>
                <button id="confirm-delete" class="button button-danger">Delete</button>
                <button id="cancel-delete" class="button">Cancel</button>
            `;
            document.body.appendChild(confirmModal);
            confirmModal.showModal();

            // Handle delete confirmation
            confirmModal.querySelector('#confirm-delete').addEventListener('click', async () => {
                confirmModal.close();
                const modal = createModal(messages.deleting, messages.reload);
                modal.showModal();

                try {
                    const response = await fetch(deletePath, {
                        method: 'POST',
                        headers: {
                            'X-WP-Nonce': token,
                        },
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }

                    const json = await response.json();

                    if (json.success) {
                        displaySuccessNotice(json.message);
                        window.location.reload();
                    } else {
                        throw new Error(json.message || messages.error);
                    }
                } catch (error) {
                    console.error('Delete error:', error);
                    modal.innerHTML = `<p>${error.message}</p>`;
                }
            });

            // Handle delete cancellation
            confirmModal.querySelector('#cancel-delete').addEventListener('click', () => {
                confirmModal.close();
            });
        });
    }

    // Display last sync time on page load
    const lastSyncTime = localStorage.getItem('lastSyncTime');
    if (lastSyncTime) {
        displaySuccessNotice('People data synced successfully.');
    }
})();