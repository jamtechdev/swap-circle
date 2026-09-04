/**
 * SC-01: International phone for signup (intl-tel-input).
 * Validates against selected country; submits E.164; never silently truncates.
 */
(function (window, $) {
    "use strict";

    var UTILS_URL =
        (document.currentScript && document.currentScript.getAttribute("data-iti-utils")) ||
        "/users/assets/plugin/select-flag/js/utils.js";

    function countryDisplayName(data) {
        return (data && data.name) || "";
    }

    function syncCountryField(iti, $country) {
        if (!$country.length || !iti) return;
        $country.val(countryDisplayName(iti.getSelectedCountryData()));
    }

    function matchCountryIso($country) {
        var typed = String($country.val() || "")
            .trim()
            .toLowerCase();
        if (!typed || !window.intlTelInputGlobals) return null;
        var countries = window.intlTelInputGlobals.getCountryData();
        var exact = countries.find(function (c) {
            return c.name.toLowerCase() === typed;
        });
        if (exact) return exact.iso2;
        var partial = countries.find(function (c) {
            return c.name.toLowerCase().indexOf(typed) === 0;
        });
        return partial ? partial.iso2 : null;
    }

    function refreshDialCodePadding(iti) {
        if (!iti) return;
        // Re-apply left padding for flag + separate dial code after layout/utils settle
        if (typeof iti._updateInputPadding === "function") {
            iti._updateInputPadding();
            return;
        }
        try {
            iti.setCountry(iti.getSelectedCountryData().iso2);
        } catch (e) {
            /* ignore */
        }
    }

    /**
     * @param {object} options
     * @param {string} options.inputSelector
     * @param {string} [options.countrySelector]
     * @param {string} [options.initialCountry]
     * @returns {object|null} intl-tel-input instance
     */
    window.initSignupIntlPhone = function (options) {
        var input = document.querySelector(options.inputSelector);
        if (!input || typeof window.intlTelInput !== "function") {
            return null;
        }

        var $country = options.countrySelector
            ? $(options.countrySelector)
            : $();
        var initialCountry = options.initialCountry || "ng";

        var iti = window.intlTelInput(input, {
            initialCountry: initialCountry,
            preferredCountries: ["ng", "gb", "us", "gh", "ke", "za"],
            separateDialCode: true,
            autoPlaceholder: "aggressive",
            formatOnDisplay: true,
            nationalMode: true,
            utilsScript: UTILS_URL,
        });

        syncCountryField(iti, $country);
        refreshDialCodePadding(iti);

        if (iti.promise && typeof iti.promise.then === "function") {
            iti.promise.then(function () {
                refreshDialCodePadding(iti);
                syncCountryField(iti, $country);
            });
        } else {
            window.setTimeout(function () {
                refreshDialCodePadding(iti);
            }, 300);
        }

        input.addEventListener("countrychange", function () {
            syncCountryField(iti, $country);
            refreshDialCodePadding(iti);
            if ($(input).data("validator")) {
                $(input).valid();
            }
        });

        // Do not strip + / spaces; only re-validate. Silent truncation was the bug.
        $(input).on("blur input", function () {
            if ($(input).data("validator")) {
                $(input).valid();
            }
        });

        $country.on("change blur", function () {
            var iso = matchCountryIso($country);
            if (iso) {
                iti.setCountry(iso);
                refreshDialCodePadding(iti);
            }
        });

        if (!$.validator.methods.intlPhone) {
            $.validator.addMethod(
                "intlPhone",
                function (value, element) {
                    if (this.optional(element)) return true;
                    var instance = $(element).data("iti");
                    if (!instance) return false;
                    if (typeof instance.isValidNumber !== "function") {
                        return String(value || "").replace(/\D/g, "").length >= 7;
                    }
                    return instance.isValidNumber();
                },
                "Enter a valid phone number for the selected country."
            );
        }

        $(input).data("iti", iti);

        return iti;
    };

    /** E.164 for API payload; falls back to raw value only if plugin missing. */
    window.getSignupIntlPhoneE164 = function (inputSelector) {
        var $input = $(inputSelector);
        var iti = $input.data("iti");
        if (iti && typeof iti.getNumber === "function") {
            var e164 = iti.getNumber();
            if (e164) return e164;
        }
        return String($input.val() || "").trim();
    };
})(window, jQuery);
