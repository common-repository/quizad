(function (win, $, reCaptchaToken, RequestFactory) {
    var loginData = {
        token: null,
        form : document.getElementById('form'),
        host : location.protocol + '//' + location.hostname,
    };

    function requestWithCaptcha(loginData, notices) {
        grecaptcha.ready(function () {
            grecaptcha.execute(reCaptchaToken, {action: 'login'}).then(function (token) {
                loginData.token = token;
                notices.hideAll();

                var request      = RequestFactory();
                var errorMessage = 'Pojawił się nieoczekiwany błąd rejestracji';
                request.onSuccess(function (status, text) {
                    var responseObject = JSON.parse(text);
                    if (responseObject.success) {
                        notices.hideError();
                        notices.showSuccess(responseObject);
                    } else {
                        if (responseObject.errors !== undefined) {
                            errorMessage = responseObject.errors[0];
                        } else if (responseObject.message !== undefined) {
                            errorMessage = responseObject.message;
                        }
                        notices.showError(errorMessage);
                        notices.hideSuccess();
                    }
                });

                request.onError(function () {
                    notices.showError(errorMessage);
                    notices.hideSuccess();
                });

                request.post(ajaxurl, {}, loginData);
            });
        });
    }

    function pushCategoriesFromApi(categories) {
        var categoryPane = $('.categories .leftPane');
        categoryPane.find('div.option').remove();

        categories.forEach(function (category) {
            categoryPane.append('<div data-option="' + category.id + '" ' +
                'value="' + category.name + '" ' + 'class="option">' + category.name +
                '</div>'
            );
        });

        var options = $('#sources').find('.option:not(.disabled)');
        options.on('click', clickOption);

    }

    /**
     * @return {string}
     */
    function getCategoriesForApi() {
        var targetsEl          = $('#targets');
        var optionsToBeSent    = targetsEl.find('.option');
        var categoriesToBeSent = optionsToBeSent.toArray().map(function (item) {
            return $(item).data('option');
        });
        return categoriesToBeSent.join();

    }

    // Notices library - show
    var notices = (function () {
        return {
            showSuccess: function (data) {
                $('.message .notice-success').show();
                $('#login').addClass('input-disabled').attr('disabled', true);
            },
            hideSuccess: function () {
                $('.message .notice-success').hide();
            },
            showError  : function (message) {
                var box = $('.message .notice-error').show();
                box.find('p').html(message);
            },
            hideError  : function () {
                $('.message .notice-error').hide();
            },
            hideAll    : function () {
                $('.message .quizAd-hiddenByDefault').hide();
            }
        }
    })();

    var noticesToken = (function () {
        return {
            showSuccess: function (data) {
                $('.loader').addClass('check-mark');
                $('.categories, #login').removeClass('disabled');
                $('#token, #confirm').addClass('input-disabled').attr('disabled', true);
                if (data.categories.length > 0) {
                    pushCategoriesFromApi(data.categories);
                }
            },
            hideSuccess: function () {
                $('.loader').removeClass('check-mark');
            },
            showError  : function () {
                $('.loader').addClass('error-mark');
            },
            hideError  : function () {
                $('.loader').removeClass('error-mark');
            },
            hideAll    : function () {
                $('.loader').removeClass('error-mark check-mark');
            }
        }
    })();

    notices.hideAll();

    loginData.form.addEventListener('submit', function (e) {
        if (!$(e.target).hasClass('form-login')) {
            e.preventDefault();
            loginData.categories = getCategoriesForApi();
            loginData.email      = document.getElementById('email').value;
            // required by wordpress
            loginData.action     = 'quizAd_register';
            requestWithCaptcha(loginData, notices);
        } else {
            e.preventDefault();
            loginData.categories  = getCategoriesForApi();
            loginData.accessToken = document.getElementById('token').value;
            // required by wordpress
            loginData.action      = 'quizAd_login';
            requestWithCaptcha(loginData, notices);
        }
    });


    var options = $('#sources').find('.option:not(.disabled)');

    function createNewOption(label, value) {
        var optionEl            = document.createElement('div');
        optionEl.dataset.option = value;
        optionEl.className      = 'option';
        optionEl.appendChild(document.createTextNode(label));
        return optionEl;
    }

    function clickOption(e) {
        var value = $(e.target).data('option');
        var label = $(e.target).text();

        var targetsEl = $('#targets');

        if (targetsEl.find('.option[data-option=' + value + ']').length > 0) {
            return; // break, it has value already
        }

        var option = createNewOption(label, value);
        $(option).on('click', function () {
            $(option).remove();
        });
        targetsEl.append(option);
    }

    options.on('click', clickOption);

    $('#confirm').on("click", function (e) {
        var tokenJq = $(e.target);
        if (tokenJq.val().length !== 32) {
            noticesToken.showError();
        }
        $('.loader').show();

        e.preventDefault();
        loginData.categories  = getCategoriesForApi();
        loginData.accessToken = document.getElementById('token').value;
        // required by wordpress
        loginData.action      = 'quizAd_access_token';
        requestWithCaptcha(loginData, noticesToken);
    });

})(window, jQuery, reCaptchaToken, QuizAdRequestFactory);


