/**
 * SC-04: Portal email validation — require domain + TLD (reject user@domain, a@b, localhost).
 */
(function (window, $) {
    "use strict";

    var EMAIL_TLD_RE =
        /^[a-z0-9.!#$%&'*+/=?^_`{|}~-]+@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z]{2,}$/i;

    window.isValidPortalEmail = function (value) {
        var email = String(value || "").trim().toLowerCase();
        if (!email) return false;
        if (email.indexOf("@") === -1) return false;

        var domain = email.split("@").pop();
        if (!domain || domain === "localhost" || /\.localhost$/i.test(domain)) {
            return false;
        }

        return EMAIL_TLD_RE.test(email);
    };

    if ($ && $.validator && $.validator.methods) {
        // Override default jQuery Validate email rule used on auth forms
        $.validator.methods.email = function (value, element) {
            return this.optional(element) || window.isValidPortalEmail(value);
        };

        $.validator.messages.email =
            $.validator.messages.email ||
            "Enter a valid email with a domain and extension (e.g. name@example.com).";
    }
})(window, window.jQuery);
