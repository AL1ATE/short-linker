$(function () {
    const $button = $('#create-short-link-btn');
    const $input = $('#url-input');
    const $messageBox = $('#message-box');
    const $resultBox = $('#result-box');
    const $shortUrlLink = $('#short-url-link');
    const $qrCodeImage = $('#qr-code-image');

    function showMessage(type, text) {
        $messageBox
            .removeClass('d-none alert-success alert-danger')
            .addClass(type === 'success' ? 'alert-success' : 'alert-danger')
            .text(text);
    }

    function hideMessage() {
        $messageBox.addClass('d-none').text('');
    }

    function hideResult() {
        $resultBox.addClass('d-none');
        $shortUrlLink.attr('href', '').text('');
        $qrCodeImage.attr('src', '');
    }

    $button.on('click', function () {
        const url = $input.val().trim();
        const endpoint = $button.data('url');

        hideMessage();
        hideResult();

        $.ajax({
            url: endpoint,
            type: 'POST',
            dataType: 'json',
            data: {
                url: url
            },
            success: function (response) {
                if (!response.success) {
                    showMessage('error', response.message || 'Произошла ошибка.');
                    return;
                }

                const data = response.data;

                showMessage('success', response.message || 'Успешно.');
                $shortUrlLink.attr('href', data.short_url).text(data.short_url);
                $qrCodeImage.attr('src', data.qr_code);
                $resultBox.removeClass('d-none');
            },
            error: function () {
                showMessage('error', 'Ошибка запроса. Попробуйте еще раз.');
            }
        });
    });
});