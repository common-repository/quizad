(function (Requests, window, document, reCaptchaToken, $) {

    // Notices library - show
    var notices = (function () {
        return {
            showSuccess: function (message) {
                var success = $('.message .notice-success').show();
                success.find('p').html(message);
                $('#login').addClass('input-disabled').attr('disabled', true);
            },
            hideSuccess: function () {
                $('.message .notice-success').hide();
            },
            showError  : function (message) {
                var error = $('.message .notice-error').show();
                error.find('p').html(message);
            },
            hideError  : function () {
                $('.message .notice-error').hide();
            },
            hideAll    : function () {
                $('.message .quizAd-hiddenByDefault').hide();
            }
        }
    })();

    notices.hideAll();

    const EmailForms = {
        emailResent: {
            'id'  : $('#resent-email'),
            'data': function (token) {
                return {
                    action: 'quizAd_email_resent',
                    host  : location.protocol + '//' + location.hostname,
                    token : token,
                }
            }
        },
        submitForm : function (actionType) {
            actionType.id.on('submit', function (e) {
                e.preventDefault();
                grecaptcha.ready(function () {
                    grecaptcha.execute(reCaptchaToken, {action: 'login'}).then(function (token) {
                        var request = Requests();

                        request.onSuccess(function (code, responseText) {
                            var responseObject = JSON.parse(responseText);
                            if (responseObject.success) {
                                notices.hideError();
                                notices.showSuccess('Email wysłany pomyślnie, proszę sprawdź swoją skrzynkę pocztową.');
                                $('#resent-email input[type=submit]').addClass('disabled');
                            } else {
                                notices.showError(responseObject.message)
                                notices.hideSuccess();
                            }
                        });

                        request.onError(function () {
                            notices.showError('Pojawił się nieoczekiwany błąd wysyłania email.')
                            notices.hideSuccess();
                        })

                        request.post(ajaxurl, {}, actionType.data(token));
                    });
                });
            });
        },
    };

    EmailForms.submitForm(EmailForms.emailResent);

})(QuizAdRequestFactory, window, document, reCaptchaToken, jQuery);