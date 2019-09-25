/* CONFIG PARAMETERS */
// var baseHost = 'http://sf.likemusic.loc';

// formUrl = 'https://forms.tildacdn.com/procces/';
var formUrl = baseHost + '/add?XDEBUG_SESSION_START=PHP_STORM';
var formSelector = '#form127249742';

var carColorSelectSelector = 'select[name=car_color]';
var carColorJsonUrl = baseHost + '/js/data/car/colors.json';

var carBrandSelectSelector = 'select[name=car_brand]';
var carBrandsJsonUrl = baseHost + '/js/data/car/brands.json';

var carModelsSelectSelector = 'select[name=car_model]';
var carModelsJsonUrlPattern = baseHost + '/js/data/car/models/';

var carIssueYearSelectSelector = 'select[name=car_issue_year]';
var carIssueYearMin = 1984;

var driverLicenseIssueCountrySelector = 'select[name=licence_issue_country]';
var driverLicenseIssueCountryJsonUrl = baseHost + '/js/data/driver/license/countries.json';

var carVinInputSelector = 'input[name=car_vin]';
var carNumberInputSelector = 'input[name=car_number]';

var errorCodeNumber = 0;

// Tilda form overrides
window.tildaFormNew = {};
window.tildaFormNew.showErrors = function ($jform, errors) {
    var validateErrors = window.tildaForm.arValidateErrors;
    var knownErrorCodesRu = validateErrors.RU;
    var knownErrorCodesEn = validateErrors.EN;

    $.each(errors, function (index, error) {
        var errorCodes = error.type;

        $.each(errorCodes, function (errorCodeIndex, errorCode) {
            if (errorCode in knownErrorCodesRu) {
                return;
            }

            errorCodeNumber++;
            var customErrorCode = 'error_' + errorCodeNumber;
            errorCodes[errorCodeIndex] = customErrorCode;
            knownErrorCodesRu[customErrorCode] = errorCode;
            knownErrorCodesEn[customErrorCode] = errorCode;
        })
    });

    window.tildaForm.showErrors($jform, errors);
};
window.tildaFormNew.send = function ($jform, btnformsubmit, formtype, formskey) {
    window.tildaForm.tildapayment = !1;
    if ($jform.data('formcart') == 'y') {
        window.tildaForm.addPaymentInfoToForm($jform)
    }
    if (formtype == 2 || (!formtype && formskey > '')) {
        var $inputElem;
        $inputElem = $jform.find('input[name=tildaspec-cookie]');
        if (!$inputElem || $inputElem.length == 0) {
            $jform.append('<input type="hidden" name="tildaspec-cookie" value="">');
            $inputElem = $jform.find('input[name=tildaspec-cookie]')
        }
        if ($inputElem.length > 0) {
            $inputElem.val(document.cookie)
        }
        $inputElem = $jform.find('input[name=tildaspec-referer]');
        if (!$inputElem || $inputElem.length == 0) {
            $jform.append('<input type="hidden" name="tildaspec-referer" value="">');
            $inputElem = $jform.find('input[name=tildaspec-referer]')
        }
        if ($inputElem.length > 0) {
            $inputElem.val(window.location.href)
        }
        $inputElem = $jform.find('input[name=tildaspec-formid]');
        if (!$inputElem || $inputElem.length == 0) {
            $jform.append('<input type="hidden" name="tildaspec-formid" value="">');
            $inputElem = $jform.find('input[name=tildaspec-formid]')
        }
        if ($inputElem.length > 0) {
            $inputElem.val($jform.attr('id'))
        }
        if (formskey > '') {
            $inputElem = $jform.find('input[name=tildaspec-formskey]');
            if (!$inputElem || $inputElem.length == 0) {
                $jform.append('<input type="hidden" name="tildaspec-formskey" value="">');
                $inputElem = $jform.find('input[name=tildaspec-formskey]')
            }
            if ($inputElem.length > 0) {
                $inputElem.val(formskey)
            }
        }
        $inputElem = $jform.find('input[name=tildaspec-version-lib]');
        if (!$inputElem || $inputElem.length == 0) {
            $jform.append('<input type="hidden" name="tildaspec-version-lib" value="">');
            $inputElem = $jform.find('input[name=tildaspec-version-lib]')
        }
        if ($inputElem.length > 0) {
            $inputElem.val(window.tildaForm.versionLib)
        }
        $inputElem = $jform.find('input[name=tildaspec-pageid]');
        if (!$inputElem || $inputElem.length == 0) {
            $jform.append('<input type="hidden" name="tildaspec-pageid" value="">');
            $inputElem = $jform.find('input[name=tildaspec-pageid]')
        }
        if ($inputElem.length > 0) {
            $inputElem.val($('#allrecords').data('tilda-page-id'))
        }
        $inputElem = $jform.find('input[name=tildaspec-projectid]');
        if (!$inputElem || $inputElem.length == 0) {
            $jform.append('<input type="hidden" name="tildaspec-projectid" value="">');
            $inputElem = $jform.find('input[name=tildaspec-projectid]')
        }
        if ($inputElem.length > 0) {
            $inputElem.val($('#allrecords').data('tilda-project-id'))
        }
        $jform.find('.js-form-spec-comments').val('');

        var d = {};
        d = $jform.serializeArray();
        if (window.tildaForm.tildapayment && window.tildaForm.tildapayment.products) {
            d.push({
                name: 'tildapayment',
                value: JSON.stringify(window.tildaForm.tildapayment)
            })
        }
        $.ajax({
            type: "POST",
            url: formUrl,
            data: d,
            dataType: "json",
            xhrFields: {
                withCredentials: !1
            },
            success: function (json) {
                var successurl = $jform.data('success-url');
                var successcallback = $jform.data('success-callback');
                var formsendedcallback = $jform.data('formsended-callback');
                btnformsubmit.removeClass('t-btn_sending');
                btnformsubmit.data('form-sending-status', '0');
                btnformsubmit.data('submitform', '');
                if (json && json.error) {
                    successurl = '';
                    successcallback = '';
                    var $errBox = $jform.find('.js-errorbox-all');
                    if (!$errBox || $errBox.length == 0) {
                        $jform.prepend('<div class="js-errorbox-all"></div>');
                        $errBox = $jform.find('.js-errorbox-all')
                    }
                    var $allError = $errBox.find('.js-rule-error-all');
                    if (!$allError || $allError.length == 0) {
                        $errBox.append('<p class="js-rule-error-all">' + json.error + '</p>');
                        $allError = $errBox.find('.js-rule-error-all')
                    }
                    $allError.html(json.error).show();
                    $errBox.show();
                    $jform.addClass('js-send-form-error');
                    $jform.trigger('tildaform:aftererror')
                } else {
                    if (json && json.needcaptcha) {
                        if (formskey) {
                            tildaForm.addTildaCaptcha($jform, formskey);
                            return
                        } else {
                            alert('Server busy. Please try again later.');
                            return
                        }
                    }
                    var formres = {};
                    if (json && json.results && json.results[0]) {
                        var str = json.results[0];
                        str = str.split(':');
                        formres.tranid = '' + str[0] + ':' + str[1];
                        formres.orderid = (str[2] ? str[2] : '0');
                        if (formres.orderid > '' && formres.orderid != '0') {
                            window.tildaForm.orderIdForStat = formres.orderid
                        }
                    } else {
                        formres.tranid = '0';
                        formres.orderid = '0'
                    }
                    $jform.data('tildaformresult', formres);
                    var virtPage = $jform.data('tilda-event-name') || '';
                    if (!virtPage || virtPage == '') {
                        if ($jform.data('formcart') == 'y' && json && ((json.next && json.next.type && json.next.type != 'function') || !json.next)) {
                            virtPage = '/tilda/' + $jform.attr('id') + '/payment/'
                        } else {
                            virtPage = '/tilda/' + $jform.attr('id') + '/submitted/'
                        }
                    }
                    var virtTitle = 'Send data from form ' + $jform.attr('id');
                    var virtPrice = 0;
                    var virtProduct = '';
                    if (window.Tilda && typeof Tilda.sendEventToStatistics == 'function') {
                        if (window.tildaForm.tildapayment && window.tildaForm.tildapayment.amount) {
                            virtPrice = window.tildaForm.tildapayment.amount;
                            if (parseFloat(window.tildaForm.tildapayment.amount) > 0) {
                                virtTitle = 'Order ' + formres.orderid
                            }
                        } else {
                            if ($jform.find('.js-tilda-price').length > 0) {
                                virtPrice = $jform.find('.js-tilda-price').val();
                                if (parseFloat(virtPrice) > 0) {
                                    virtTitle = 'Order ' + formres.orderid
                                }
                            }
                        }
                        Tilda.sendEventToStatistics(virtPage, virtTitle, virtProduct, virtPrice);
                        if (window.dataLayer) {
                            window.dataLayer.push({
                                'event': 'submit_' + $jform.attr('id')
                            })
                        }
                    } else {
                        if (typeof ga != 'undefined' && ga) {
                            if (window.mainTracker != 'tilda') {
                                ga('send', {
                                    'hitType': 'pageview',
                                    'page': virtPage,
                                    'title': virtTitle
                                })
                            }
                        }
                        if (window.mainMetrika > '' && window[window.mainMetrika]) {
                            window[window.mainMetrika].hit(virtPage, {
                                title: virtTitle,
                                referer: window.location.href
                            })
                        }
                        if (window.dataLayer) {
                            window.dataLayer.push({
                                'event': 'submit_' + $jform.attr('id')
                            })
                        }
                    }
                    $jform.trigger('tildaform:aftersuccess');
                    if (formsendedcallback && formsendedcallback.length > 0) {
                        eval(formsendedcallback + '($jform);')
                    }
                    if (json && json.next && json.next.type > '') {
                        var res = window.tildaForm.payment($jform, json.next);
                        successurl = '';
                        return !1
                    }
                    window.tildaForm.successEnd($jform, successurl, successcallback)
                }
            },
            error: function (error) {
                btnformsubmit.removeClass('t-btn_sending');
                btnformsubmit.data('form-sending-status', '0');
                btnformsubmit.data('submitform', '');
                var $errBox = $jform.find('.js-errorbox-all');
                if (!$errBox || $errBox.length == 0) {
                    $jform.prepend('<div class="js-errorbox-all"></div>');
                    $errBox = $jform.find('.js-errorbox-all')
                }
                var $allError = $errBox.find('.js-rule-error-all');
                if (!$allError || $allError.length == 0) {
                    $errBox.append('<p class="js-rule-error-all"></p>');
                    $allError = $errBox.find('.js-rule-error-all')
                }
                if (error && error.responseText > '') {
                    if (isJSON(error.responseText)) {
                        var responseJson = error.responseText;
                        var responseData = JSON.parse(responseJson);

                        var responseErrorCodes = responseData.errors;

                        if (responseErrorCodes) {
                            var arLang = window.tildaForm.arValidateErrors[window.tildaBrowserLang] || {};

                            if ('common' in responseErrorCodes) {
                                var commonErrorCodes = responseErrorCodes.common;
                                var commonErrorMessages = [];

                                for (var commonErrorCodeIndex in commonErrorCodes) {
                                    var commonErrorCode = commonErrorCodes[commonErrorCodeIndex];
                                    var commonErrorMessage = arLang[commonErrorCode] ? arLang[commonErrorCode] : commonErrorCode;
                                    commonErrorMessages.push(commonErrorMessage);
                                }

                                var errorMessage = commonErrorMessages.join('<br/>');
                                $allError.html(errorMessage)
                            }

                            var errors = [];

                            for (var key in responseErrorCodes) {
                                if (!Object.prototype.hasOwnProperty.call(responseErrorCodes, key)) {
                                    continue;
                                }

                                if (key == 'common') {
                                    continue;
                                }

                                var keyErrors = responseErrorCodes[key];

                                var vError = {};
                                vError.obj = $jform.find('[name=' + key + ']');
                                vError.type = keyErrors;

                                errors.push(vError);
                            }

                            if (errors.length > 0) {
                                window.tildaFormNew.showErrors($jform, errors);
                            }
                        } else if(responseData.message) {
                            $allError.html(responseData.message)
                        } else {
                            $allError.html(error.responseText)
                        }
                    } else {
                        $allError.html(error.responseText + '. Please, try again later.')
                    }
                } else {
                    if (error && error.statusText) {
                        $allError.html('Error [' + error.statusText + ']. Please, try again later.')
                    } else {
                        $allError.html('Unknown error. Please, try again later.')
                    }
                }
                $allError.show();
                $errBox.show();
                $jform.addClass('js-send-form-error');
                $jform.trigger('tildaform:aftererror')
            },
            timeout: 15000
        });
        return !1
    } else {
        if ($jform.data('is-formajax') == 'y') {
            var d = {};
            d = $jform.serializeArray();
            if (window.tildaForm.tildapayment && window.tildaForm.tildapayment.amount) {
                d.push({
                    name: 'tildapayment',
                    value: JSON.stringify(window.tildaForm.tildapayment)
                })
            }
            $.ajax({
                type: "POST",
                url: $jform.attr('action'),
                data: d,
                dataType: "text",
                xhrFields: {
                    withCredentials: !1
                },
                success: function (html) {
                    var json;
                    var successurl = $jform.data('success-url');
                    var successcallback = $jform.data('success-callback');
                    btnformsubmit.removeClass('t-btn_sending');
                    btnformsubmit.data('form-sending-status', '0');
                    btnformsubmit.data('submitform', '');
                    if (html && html.length > 0) {
                        if (html.substring(0, 1) == '{') {
                            if (window.JSON && window.JSON.parse) {
                                json = window.JSON.parse(html)
                            } else {
                                json = $.parseJSON(html)
                            }
                            if (json && json.message) {
                                if (json.message != 'OK') {
                                    $jform.find('.js-successbox').html(json.message)
                                }
                            } else {
                                if (json && json.error) {
                                    var $errBox = $jform.find('.js-errorbox-all');
                                    if (!$errBox || $errBox.length == 0) {
                                        $jform.prepend('<div class="js-errorbox-all"></div>');
                                        $errBox = $jform.find('.js-errorbox-all')
                                    }
                                    var $allError = $errBox.find('.js-rule-error-all');
                                    if (!$allError || $allError.length == 0) {
                                        $errBox.append('<p class="js-rule-error-all">Unknown error. Please, try again later.</p>');
                                        $allError = $errBox.find('.js-rule-error-all')
                                    }
                                    $allError.html(json.error);
                                    $allError.show();
                                    $errBox.show();
                                    $jform.addClass('js-send-form-error');
                                    $jform.trigger('tildaform:aftererror');
                                    return !1
                                }
                            }
                        } else {
                            $jform.find('.js-successbox').html(html)
                        }
                    }
                    var virtPage = '/tilda/' + $jform.attr('id') + '/submitted/';
                    var virtTitle = 'Send data from form ' + $jform.attr('id');
                    if (window.Tilda && typeof Tilda.sendEventToStatistics == 'function') {
                        window.Tilda.sendEventToStatistics(virtPage, virtTitle, '', 0)
                    } else {
                        if (typeof ga != 'undefined') {
                            if (window.mainTracker != 'tilda') {
                                ga('send', {
                                    'hitType': 'pageview',
                                    'page': virtPage,
                                    'title': virtTitle
                                })
                            }
                        }
                        if (window.mainMetrika > '' && window[window.mainMetrika]) {
                            window[window.mainMetrika].hit(virtPage, {
                                title: virtTitle,
                                referer: window.location.href
                            })
                        }
                    }
                    $jform.trigger('tildaform:aftersuccess');
                    window.tildaForm.successEnd($jform, successurl, successcallback)
                },
                error: function (error) {
                    btnformsubmit.removeClass('t-btn_sending');
                    btnformsubmit.data('form-sending-status', '0');
                    btnformsubmit.data('submitform', '');
                    var $errBox = $jform.find('.js-errorbox-all');
                    if (!$errBox || $errBox.length == 0) {
                        $jform.prepend('<div class="js-errorbox-all"></div>');
                        $errBox = $jform.find('.js-errorbox-all')
                    }
                    var $allError = $errBox.find('.js-rule-error-all');
                    if (!$allError || $allError.length == 0) {
                        $errBox.append('<p class="js-rule-error-all"></p>');
                        $allError = $errBox.find('.js-rule-error-all')
                    }
                    if (error && error.responseText > '') {
                        $allError.html(error.responseText + '. Please, try again later.')
                    } else {
                        if (error && error.statusText) {
                            $allError.html('Error [' + error.statusText + ']. Please, try again later.')
                        } else {
                            $allError.html('Unknown error. Please, try again later.')
                        }
                    }
                    $allError.show();
                    $errBox.show();
                    $jform.addClass('js-send-form-error');
                    $jform.trigger('tildaform:aftererror')
                },
                timeout: 15000
            });
            return !1
        } else {
            var attraction = $jform.attr('action');
            if (attraction.indexOf('forms.tildacdn.com') == -1) {
                btnformsubmit.data('form-sending-status', '3');
                $jform.submit();
                return !0
            } else {
                return !1
            }
        }
    }
};
window.tildaFormNew.validate = function($jform) {
    var errors =  window.tildaForm.validate($jform);
    var error = null;
    var $carVinField = $jform.find(carVinInputSelector);
    if (error = validateCarVinField($carVinField)) {
        errors.push(error);
    }

    var $carNumberField = $jform.find(carNumberInputSelector);
    if (error = validateCarNumberField($carNumberField)) {
        errors.push(error);
    }

    return errors;

    function validateCarVinField($carVinField) {
        return validateFieldByPatternAndLength($carVinField, 'A-Z0-9', 17, 'car_vin');
    }

    function validateCarNumberField($carNumberField) {
        return validateFieldByPatternAndLength($carNumberField, 'А-ЯЁ0-9', 8, 'car_number');
    }

    function validateFieldByPatternAndLength($field, pattern, length, errorCode) {
        var value = $field.val();

        var valueLength = value.length;

        if (valueLength < length) {
            return createError($field, 'minlength')
        } else if (valueLength > length) {
            return createError($field, 'maxlength')
        }

        var regExpStr = '^[' + pattern + ']{' + length + '}$';
        var regExp = new RegExp(regExpStr);

        if (regExp.test(value)) {
            return null;
        }

        return createError($field, errorCode);

        function createError($obj, errorType) {
            return {
                obj: $obj,
                type: [errorType]
            };
        }
    }
};

addNewErrorMessages();

// Generate selects and set own submit handler.
jQuery(function () {
    generateReferenceSelects();
    bindInputsMasks();

    var $form = jQuery(formSelector);
    var form = $form.get(0);
    var $r = $form.closest('.r');
    var r = $r.get(0);

    $('.r').off('click', '.js-form-proccess [type=submit]');
    $('.r').on('click', '.js-form-proccess [type=submit]', function (event) {
        event.preventDefault();
        var btnformsubmit = $(this);
        var btnstatus = btnformsubmit.data('form-sending-status');

        if (btnstatus >= '1') {
            return !1
        }

        var $activeForm = $(this).closest('form')
            , arErrors = !1;

        if ($activeForm.length == 0) {
            return !1
        }

        btnformsubmit.addClass('t-btn_sending');
        btnformsubmit.data('form-sending-status', '1');
        btnformsubmit.data('submitform', $activeForm);
        window.tildaForm.hideErrors($activeForm);
        arErrors = window.tildaFormNew.validate($activeForm);

        if (window.tildaForm.showErrors($activeForm, arErrors)) {
            btnformsubmit.removeClass('t-btn_sending');
            btnformsubmit.data('form-sending-status', '0');
            btnformsubmit.data('submitform', '');
            return !1
        } else {
            var formtype = $activeForm.data('formactiontype');
            var formskey = $('#allrecords').data('tilda-formskey');
            if ($activeForm.find('.js-formaction-services').length == 0 && formtype != 1 && formskey == '') {
                var $errBox = $activeForm.find('.js-errorbox-all');
                if (!$errBox || $errBox.length == 0) {
                    $activeForm.prepend('<div class="js-errorbox-all"></div>');
                    $errBox = $activeForm.find('.js-errorbox-all')
                }
                var $allError = $errBox.find('.js-rule-error-all');
                if (!$allError || $allError.length == 0) {
                    $errBox.append('<p class="js-rule-error-all">' + json.error + '</p>');
                    $allError = $errBox.find('.js-rule-error-all')
                }
                $allError.html('Please set receiver in block with forms').show();
                $errBox.show();
                $activeForm.addClass('js-send-form-error');
                btnformsubmit.removeClass('t-btn_sending');
                btnformsubmit.data('form-sending-status', '0');
                btnformsubmit.data('submitform', '');
                $activeForm.trigger('tildaform:aftererror');
                return !1
            }

            if ($activeForm.find('.g-recaptcha').length > 0 && grecaptcha) {
                window.tildaForm.currentFormProccessing = {
                    form: $activeForm,
                    btn: btnformsubmit,
                    formtype: formtype,
                    formskey: formskey
                };

                var captchaid = $activeForm.data('tilda-captcha-clientid');

                if (captchaid === undefined || captchaid === '') {
                    var opts = {
                        size: 'invisible',
                        sitekey: $activeForm.data('tilda-captchakey'),
                        callback: window.tildaForm.captchaCallback
                    };
                    captchaid = grecaptcha.render($activeForm.attr('id') + 'recaptcha', opts);
                    $activeForm.data('tilda-captcha-clientid', captchaid)
                } else {
                    grecaptcha.reset(captchaid)
                }

                grecaptcha.execute(captchaid);

                return !1
            }

            window.tildaFormNew.send($activeForm, btnformsubmit, formtype, formskey)
        }

        return !1
    });
    // $r.bindFirst('submit', '.js-form-proccess', submitHandler);
});

function addNewErrorMessages() {
    // RU
    var ruMessages = {
        'duplicate_driver_license': 'Водитель с указанным ВУ уже зарегистрирован.',
        'invalid_driver_license': 'Неверные данные ВУ.',
        'duplicate_phone': 'Водитель с указанным номером рабочего телефона уже зарегистрирован.',
        'unknown': 'Во время обработки запроса произошла неизветная ошибка. Попробуте повторить отправку формы немного позже.',
        'http_response_error': 'Во время обработки данных сервером Яндекса произошла ошибка. Попробуте повторить отправку формы немного позже.',
        'car_vin': 'Неверный VIN',
        'car_number': 'Неверный госномер. Если номер вашей машины состоит не из 8 символов, для регистрации обратитесь к оператору.'
    };

    Object.assign(window.tildaForm.arValidateErrors.RU, ruMessages);

    // EN
    var enMessages = {
        'duplicate_driver_license': 'Driver with that license already exists.',
        'invalid_driver_license': 'Invalid driver license.',
        'duplicate_phone': 'Driver with that working phone number already exists.',
        'unknown': 'There is an error occurred while processing form data. Please try again later.',
        'http_response_error': 'There is an error occurred on Yandex server, while processing form data. Please try again later.',
        'car_vin': 'Invalid VIN.',
        'car_number': 'Invalid car number.'
    };

    Object.assign(window.tildaForm.arValidateErrors.EN, enMessages);
}

function generateReferenceSelects() {
    generateCarIssueYearSelect();
    generateDriverLicenseIssueCountrySelect();
    generateCarBrandsSelect();
    generateCarColorsSelect();
    generateCarModelsSelect();

    return true;

    // Domain functions
    function generateCarIssueYearSelect() {
        var years = generateCarIssueYear();
        generateSelectByItems(carIssueYearSelectSelector, years, 'Выберите год ...');

        function generateCarIssueYear() {
            var years = [];
            var currentYear = getCurrentYear();

            for (var i = currentYear; i >= carIssueYearMin; i--) {
                years.push(i);
            }

            return years;

            function getCurrentYear() {
                var date = new Date();

                return date.getFullYear();
            }
        }
    }

    function generateDriverLicenseIssueCountrySelect() {
        generateSelectByJsonUrl(driverLicenseIssueCountrySelector, driverLicenseIssueCountryJsonUrl, 'Выберите страну ...', 'Россия');
    }

    function generateCarBrandsSelect() {
        generateSelectByJsonUrl(carBrandSelectSelector, carBrandsJsonUrl, 'Выберите марку ...');
    }

    function generateCarColorsSelect() {
        generateSelectByJsonUrl(carColorSelectSelector, carColorJsonUrl, 'Выберите цвет ...');
    }

    function generateCarModelsSelect() {
        var $modelsSelect = $(carModelsSelectSelector);
        $modelsSelect.empty();
        var option = $('<option>').prop('selected', true).text('Сперва выберите марку автомобиля в поле выше.');
        $modelsSelect.append(option);
        $modelsSelect.prop('disabled', 'disabled');

        $(carBrandSelectSelector).change(function () {
            var brand = $(this).val();
            $modelsSelect.empty();

            if (!brand) {
                option = $('<option>').prop('selected', true).text('Сперва выберите марку автомобиля в поле выше.');
                $modelsSelect.append(option);
                $modelsSelect.prop('disabled', 'disabled');

                return;
            }

            $modelsSelect.prop('disabled', 'disabled');
            var firstOption = $('<option>').val('').text('Загрузка моделей ...');
            $modelsSelect.append(firstOption);

            var carModelsJsonUrl = carModelsJsonUrlPattern + brand + '.json';
            $.getJSON(carModelsJsonUrl, function (models) {
                $.each(models, function (key, model) {
                    option = $('<option>').attr('value', model).text(model);
                    $modelsSelect.append(option);
                });

                firstOption.text('Выберите модель ...');
                $modelsSelect.prop('disabled', false);
                $modelsSelect.select2();
            });
        });
    }

    // Select generation functions
    function generateSelectByJsonUrl(selectSelector, jsonUrl, firstEmptyTitle, selectedValue = null) {
        var $select = $(selectSelector);

        $select.prop('disabled', true);
        $select.empty();
        var $firstOption = $('<option>').val('').text('Загружается ...');
        $select.append($firstOption);

        $.getJSON(jsonUrl, function (items) {
            fillSelectByItems($select, items, selectedValue);

            $firstOption.text(firstEmptyTitle);
            $select.prop('disabled', false);
            $select.select2();
        });
    }

    function generateSelectByItems(selectSelector, items, firstEmptyTitle, selectedValue = null) {
        var $select = $(selectSelector);

        $select.prop('disabled', true);
        $select.empty();
        var $firstOption = $('<option>').val('').text('Генерируется ...');
        $select.append($firstOption);

        fillSelectByItems($select, items, selectedValue);

        $firstOption.text(firstEmptyTitle);
        $select.prop('disabled', false);
        $select.select2();
    }

    function fillSelectByItems($select, items, selectedValue = null) {
        $.each(items, function (key, item) {
            var option = $('<option>').attr('value', item).text(item);
            if (selectedValue === item) {
                option.prop('selected', true);
            }

            $select.append(option);
        });
    }
}

function bindInputsMasks() {
    bindCarVinMask();
    bindCarNumberMask();

    function bindCarVinMask() {
        bindInputLimits(carVinInputSelector, 'A-Z0-9', 17);
    }

    function bindCarNumberMask() {
        bindInputLimits(carNumberInputSelector, 'А-ЯЁ0-9', 8);
    }

    function bindInputLimits(inputSelector, pattern, length) {
        var $input = $(inputSelector);

        $input.on('input', function(event){
            var srcValue = this.value;
            // var srcValueWithoutUnderline = srcValue.replace(/_/g, '');
            var regexpStr = '^[' + pattern + ']{' + length + '}$';
            var regexp = new RegExp(regexpStr);

            if (regexp.test(srcValue)) {
                return;
            }

            var resValue = srcValue.toUpperCase();

            var replaceRegexpStr = '[^' + pattern + '_' + ']';
            var replaceRegexp = new RegExp(replaceRegexpStr, 'g');
            resValue = resValue.replace(replaceRegexp, '');
            var valueLengthDiff = srcValue.length - resValue.length;

            resValue = resValue.substr(0, length);
            var selectionStart = this.selectionStart;
            var selectionEnd = this.selectionEnd;
            resValue = resValue.padEnd(length, '_');
            this.value = resValue;

            this.setSelectionRange(selectionStart - valueLengthDiff, selectionEnd - valueLengthDiff);
        });

    }
}

/**
 * @param str
 * @returns boolean
 */
function isJSON(str) {
    try {
        JSON.parse(str);
        return true;
    } catch (e) {
        return false;
    }
}
