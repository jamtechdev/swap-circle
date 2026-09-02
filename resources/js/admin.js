document.addEventListener('DOMContentLoaded', function () {
    window.adminStatusBadge = function (status, label) {
        var normalized = String(status || '').trim();
        var config = {
            Active: ['active', 'Active'],
            Inactive: ['inactive', 'Inactive'],
            Pending: ['pending', 'Pending'],
            Deleted: ['deleted', 'Deleted'],
            Approved: ['approved', 'Approved'],
            Funded: ['funded', 'Funded'],
            Rejected: ['rejected', 'Rejected'],
            Accepted: ['accepted', 'Accepted'],
        };
        var entry = config[normalized] || ['unknown', normalized || 'Unknown'];
        var text = label || entry[1];

        return '<span class="admin-status-badge admin-status-badge--' + entry[0] + '">' + text + '</span>';
    };

    window.adminActionButton = function (type, options) {
        options = options || {};
        var classMap = {
            view: 'btn btn-secondary',
            edit: 'btn btn-info',
            activate: 'btn btn-success',
            deactivate: 'btn btn-warning',
            delete: 'btn btn-danger',
        };
        var iconMap = {
            view: 'fa-eye',
            edit: 'fa-edit',
            activate: 'fa-check',
            deactivate: 'fa-times',
            delete: 'fa-trash',
        };
        var btnClass = classMap[type] || classMap.view;
        if (options.extraClass) {
            btnClass += ' ' + options.extraClass;
        }
        var icon = options.icon || iconMap[type] || 'fa-circle';
        var title = options.title || '';
        var attrs = options.attrs || {};
        var tag = options.tag || (options.href ? 'a' : 'button');
        var html = '<' + tag + ' class="' + btnClass + '"';

        if (title) {
            html += ' title="' + title.replace(/"/g, '&quot;') + '"';
        }

        Object.keys(attrs).forEach(function (key) {
            html += ' ' + key + '="' + String(attrs[key]).replace(/"/g, '&quot;') + '"';
        });

        if (tag === 'a' && options.href) {
            html += ' href="' + String(options.href).replace(/"/g, '&quot;') + '"';
        } else if (tag === 'button') {
            html += ' type="' + (options.buttonType || 'button') + '"';
        }

        html += '><i class="fa ' + icon + '" aria-hidden="true"></i></' + tag + '>';
        return html;
    };

    window.adminActionGroup = function (buttons) {
        return '<div class="admin-action-group">' + buttons.join('') + '</div>';
    };

    var path = window.location.pathname.replace(/\/+$/, '') || '/';

    document.querySelectorAll('.deznav .metismenu a[href]').forEach(function (link) {
        var href = link.getAttribute('href');
        if (!href || href === 'javascript:void()' || href.indexOf('javascript:') === 0) {
            return;
        }

        try {
            var linkPath = new URL(link.href, window.location.origin).pathname.replace(/\/+$/, '') || '/';
            if (linkPath === path || (linkPath !== '/admin' && path.indexOf(linkPath) === 0)) {
                link.classList.add('admin-nav-active');
                var li = link.closest('li');
                if (li) {
                    li.classList.add('mm-active');
                }
                var parentUl = link.closest('ul:not(.metismenu)');
                if (parentUl) {
                    parentUl.classList.add('mm-show');
                    parentUl.setAttribute('aria-expanded', 'true');
                    var parentLi = parentUl.closest('li');
                    if (parentLi) {
                        parentLi.classList.add('mm-active');
                    }
                }
            }
        } catch (e) {
            // Ignore malformed URLs.
        }
    });

    var sidebarScroll = document.querySelector('.deznav-scroll');
    if (sidebarScroll) {
        sidebarScroll.classList.remove('ps', 'ps--active-y');
        sidebarScroll.style.overflowY = 'auto';
    }
});
