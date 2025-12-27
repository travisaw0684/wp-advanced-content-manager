jQuery(document).ready(function ($) {

    function fetchResources() {

        $.post(ACM.ajaxUrl, {
            action: 'acm_filter_resources',
            nonce: ACM.nonce,
            category: $('#acm-category').val(),
            content_type: $('#acm-type').val()
        }, function (response) {

            let html = '';

            if (response.success && response.data.length) {
                response.data.forEach(item => {
                    html += `
                        <div class="acm-item">
                            <h3><a href="${item.link}">${item.title}</a></h3>
                            <span>${item.type}</span>
                        </div>
                    `;
                });
            } else {
                html = '<p>No resources found.</p>';
            }

            $('#acm-results').html(html);
        });
    }

    $('#acm-category, #acm-type').on('change', fetchResources);

    fetchResources();
});
