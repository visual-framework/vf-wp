(() => {
    if (!window.vfwpTeamsSettings) return;

    const { apiRoot, token, messages } = window.vfwpTeamsSettings;

    const escapeHtml = (value) => String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');

    const createNotice = (type, html) => {
        const notice = document.createElement('div');
        notice.className = `notice ${type} is-dismissible`;
        notice.innerHTML = html;

        const button = document.createElement('button');
        button.className = 'notice-dismiss';
        button.addEventListener('click', () => notice.remove());
        notice.appendChild(button);

        const noticeInfo = document.querySelector('.vfwp-teams-sync-notice');
        if (noticeInfo) {
            noticeInfo.insertAdjacentElement('afterend', notice);
        } else {
            document.querySelector('.wrap').prepend(notice);
        }

        return notice;
    };

    const displayStats = (stats) => {
        const {
            created,
            updated,
            deleted,
            skipped,
            created_titles,
            updated_titles,
            deleted_titles,
            error_messages,
        } = stats;

        let html = `<p><strong>Teams data synced successfully.</strong> Created: <strong>${created}</strong>, Updated: <strong>${updated}</strong>, Deleted: <strong>${deleted}</strong>, Unchanged: <strong>${skipped}</strong></p>`;
        html += '<table style="width:100%;border-collapse:collapse;margin-top:5px;margin-bottom:10px;">';

        const renderRow = (label, values) => {
            if (!values || values.length === 0) return '';

            return `<tr>
                <td style="border:1px solid #ddd;padding:5px;vertical-align:top;"><strong>${escapeHtml(label)}</strong></td>
                <td style="border:1px solid #ddd;padding:5px;">${values.map(escapeHtml).join('<br>')}</td>
            </tr>`;
        };

        html += renderRow('Created', created_titles);
        html += renderRow('Updated', updated_titles);
        html += renderRow('Deleted', deleted_titles);
        html += renderRow('Warnings', error_messages);
        html += '</table>';

        createNotice(error_messages && error_messages.length ? 'notice-warning' : 'notice-success', html);
    };

    const syncButton = document.querySelector('#vfwp-sync-teams');
    if (!syncButton) return;

    syncButton.addEventListener('click', async () => {
        syncButton.disabled = true;
        const syncingNotice = createNotice('notice-warning', `<p>${escapeHtml(messages.syncing)}</p>`);

        try {
            const response = await fetch(`${apiRoot}sync-teams`, {
                method: 'POST',
                headers: { 'X-WP-Nonce': token },
            });

            const json = await response.json();
            if (!response.ok || !json.success) {
                throw new Error(json.message || messages.error);
            }

            syncingNotice.remove();
            displayStats(json.stats);
        } catch (err) {
            syncingNotice.remove();
            createNotice('notice-error', `<p>${escapeHtml(err.message || messages.error)}</p>`);
        } finally {
            syncButton.disabled = false;
        }
    });
})();
