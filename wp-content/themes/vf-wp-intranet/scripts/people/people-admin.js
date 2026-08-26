(() => {
    if (!window.vfwpPeopleSettings) return;

    const { apiRoot, token, messages } = window.vfwpPeopleSettings;

    // Insert a container below the notice-info div
      // Create dismissible notice below .notice-info
    const createNotice = (type, html) => {
        const notice = document.createElement('div');
        notice.className = `notice ${type} is-dismissible`;
        notice.innerHTML = html;

        // Add dismiss button
        const button = document.createElement('button');
        button.className = 'notice-dismiss';
        button.addEventListener('click', () => notice.remove());
        notice.appendChild(button);

        // Insert below notice-info
        const noticeInfo = document.querySelector('.notice.notice-info');
        if (noticeInfo) {
            noticeInfo.insertAdjacentElement('afterend', notice);
        } else {
            document.querySelector('.wrap').prepend(notice);
        }
        return notice;
    };

    const displaySyncing = () => {
        return createNotice('notice-warning', `<p>${messages.syncing}</p>`);
    };

    const displayStats = (stats) => {
        const { created, updated, deleted, created_titles, updated_titles, deleted_titles } = stats;
        let html = `<p><strong>People data synced successfully.</strong> Created: <strong>${created}</strong>, Updated: <strong>${updated}</strong>, Deleted: <strong>${deleted}</strong></p>`;
        html += `<table style="width:100%;border-collapse:collapse;margin-top:5px;margin-bottom:10px;">`;
        const renderRow = (label, titles) => {
            if (!titles || titles.length === 0) return '';
            return `<tr>
                <td style="border:1px solid #ddd;padding:5px;vertical-align:top;"><strong>${label}</strong></td>
                <td style="border:1px solid #ddd;padding:5px;">${titles.join('<br>')}</td>
            </tr>`;
        };
        html += renderRow('Created', created_titles);
        html += renderRow('Updated', updated_titles);
        html += renderRow('Deleted', deleted_titles);
        html += `</table>`;

        createNotice('notice-success', html);
    };

    // --- SYNC BUTTON ---
    const syncButton = document.querySelector('#vfwp-sync-people');
    if (syncButton) {
        syncButton.addEventListener('click', async () => {
            const syncingNotice = displaySyncing();

            try {
                const response = await fetch(`${apiRoot}sync-people`, {
                    method: 'POST',
                    headers: { 'X-WP-Nonce': token },
                });

                if (!response.ok) throw new Error(`HTTP ${response.status}`);

                const json = await response.json();
                if (!json.success) throw new Error(json.message || messages.error);

                // Poll until completed
                const poll = async () => {
                    try {
                        const res = await fetch(`${apiRoot}get-sync-stats`, {
                            headers: { 'X-WP-Nonce': token },
                        });
                        if (!res.ok) throw new Error(`HTTP ${res.status}`);
                        const statsJson = await res.json();

                        if (statsJson.completed) {
                            syncingNotice.remove();
                            displayStats(statsJson.stats);
                        } else {
                            setTimeout(poll, 2000);
                        }
                    } catch (err) {
                        console.error('Poll error:', err);
                        syncingNotice.remove();
                        createNotice('notice-error', `<p>${err.message}</p>`);
                    }
                };

                setTimeout(poll, 2000);
            } catch (err) {
                console.error('Sync error:', err);
                syncingNotice.remove();
                createNotice('notice-error', `<p>${err.message}</p>`);
            }
        });
    }

    // --- DELETE BUTTON ---
    const deleteButton = document.querySelector('#vfwp-delete-people');
    if (deleteButton) {
        deleteButton.addEventListener('click', () => {
            if (!confirm('Are you sure you want to delete all people? This cannot be undone.')) return;

            const deletingNotice = createNotice('notice-warning', `<p>${messages.deleting}</p>`);

            fetch(`${apiRoot}delete-people`, {
                method: 'POST',
                headers: { 'X-WP-Nonce': token },
            })
            .then(res => res.json())
            .then(json => {
                deletingNotice.remove();
                if (json.success) {
                    createNotice('notice-success', `<p>${json.message}</p>`);
                } else {
                    createNotice('notice-error', `<p>${json.message || messages.error}</p>`);
                }
            })
            .catch(err => {
                deletingNotice.remove();
                console.error('Delete error:', err);
                createNotice('notice-error', `<p>${err.message}</p>`);
            });
        });
    }
})();
