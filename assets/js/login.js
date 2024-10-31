(function (win, $, reCaptchaToken, RequestFactory) {
    var loginData = {};
    var formLogin = document.getElementById('form-login-splash');

    formLogin.addEventListener('submit', function (e) {
        e.preventDefault();
        // grecaptcha.ready(function () {
            const login = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            if (!login || !password) {
                notices.showError('Podaj login i hasło');
                return;
            }
            // grecaptcha.execute(reCaptchaToken, { action: 'login' }).then(function (token) {
                loginData.token = 'grecaptha token';
                loginData.host = location.protocol + '//' + location.hostname;
                loginData.username = login;
                loginData.password = password;
                loginData.action = 'quizAd_login';
                var request = RequestFactory();

                request.onSuccess(function (status, text) {
                    var responseObject = JSON.parse(text);
                    if (responseObject.success) {
                        notices.hideError();
                        notices.showSuccess();
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    } else {
                        console.error(responseObject.message);
                        notices.showError(`Niepoprawne dane logowania. <br/>${ responseObject.message }`);
                        notices.hideSuccess();
                    }
                });

                request.onError(function () {
                    notices.showError('Pojawił się nieoczekiwany błąd logowania');
                    notices.hideSuccess();
                });

                request.post(ajaxurl, {}, loginData);
            // });
        // });
    });


    // Notices library - show
    var notices = (function () {
        return {
            showSuccess   : function (data) {
                $('.message .notice-success').show();
                $('#username').addClass('input-disabled').attr('disabled', true);
            }, hideSuccess: function () {
                $('.message .notice-success').hide();
            }, showError  : function (message) {
                var box = $('.message .notice-error').show();
                box.find('p').html(message);
            }, hideError  : function () {
                $('.message .notice-error').hide();
            }, hideAll    : function () {
                $('.message .quizAd-hiddenByDefault').hide();
            }
        }
    })();

})(window, jQuery, reCaptchaToken, QuizAdRequestFactory);