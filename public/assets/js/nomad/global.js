$(function() {

    // select2 template
    altair_form_adv.adv_selects();

    altair_form_adv.align_widget_example();

});

$(function (){
    $('#check-all').iCheck({
        checkboxClass: 'icheckbox_md'
    });

    $('#check-all').on('ifChecked', function(event) {
        $('.ts_checkbox').iCheck('check');
    });
    $('#check-all').on('ifUnchecked', function(event) {
        $('.ts_checkbox').iCheck('uncheck');
    });

    $('.ts_checkbox').on('ifUnchecked', function (event) {
        $('#check-all').iCheck('uncheck');
    });

    $('.ts_checkbox').on('ifChecked', function (event) {
        if ($('.ts_checkbox').filter(':checked').length == $('.ts_checkbox').length) {
            $('#check-all').iCheck('check');
        }
    });
});

$(function (){
    $("#inputSearch").attr("style","visibility: visible");
});

altair_form_adv = {

    adv_selects: function() {
        $('#select_adv_single').selectize({
            plugins: {
                'remove_button': {
                    label: ''
                }
            },
            onDropdownOpen: function($dropdown) {
                $dropdown
                    .hide()
                    .velocity('slideDown', {
                        begin: function() {
                            $dropdown.css({'margin-top':'0'})
                        },
                        duration: 200,
                        easing: easing_swiftOut
                    })
            },
            onDropdownClose: function($dropdown) {
                $dropdown
                    .show()
                    .velocity('slideUp', {
                        complete: function() {
                            $dropdown.css({'margin-top':''})
                        },
                        duration: 200,
                        easing: easing_swiftOut
                    })
            }
        });

        $('#select_adv_1').selectize({
            plugins: {
                'remove_button': {
                    label: ''
                },
                'drag_drop': {}
            },
            options: [
                {class: 'planet', id: 1, title: 'Mercury', url: 'http://en.wikipedia.org/wiki/Mercury_(planet)'},
                {class: 'planet', id: 2, title: 'Venus', url: 'http://en.wikipedia.org/wiki/Venus'},
                {class: 'planet', id: 3, title: 'Earth', url: 'http://en.wikipedia.org/wiki/Earth'},
                {class: 'planet', id: 4, title: 'Mars', url: 'http://en.wikipedia.org/wiki/Mars'},
                {class: 'planet', id: 5, title: 'Jupiter', url: 'http://en.wikipedia.org/wiki/Jupiter'},
                {class: 'planet', id: 6, title: 'Saturn', url: 'http://en.wikipedia.org/wiki/Saturn'},
                {class: 'planet', id: 7, title: 'Uranus', url: 'http://en.wikipedia.org/wiki/Uranus'},
                {class: 'planet', id: 8, title: 'Neptune', url: 'http://en.wikipedia.org/wiki/Neptune'},
                {class: 'star', id: 9, title: 'UY Scuti', url: 'https://en.wikipedia.org/wiki/UY_Scuti'},
                {class: 'star', id: 10, title: 'WOH G64', url: 'https://en.wikipedia.org/wiki/WOH_G64'},
                {class: 'star', id: 11, title: 'RW Cephei', url: 'https://en.wikipedia.org/wiki/RW_Cephei'},
                {class: 'star', id: 12, title: 'Westerlund 1-26', url: 'https://en.wikipedia.org/wiki/Westerlund_1-26'}
            ],
            optgroups: [
                {value: 'planet', label: 'Planets'},
                {value: 'star', label: 'Stars'}
            ],
            optgroupField: 'class',
            maxItems: null,
            valueField: 'id',
            labelField: 'title',
            searchField: 'title',
            create: false,
            render: {
                option: function(data, escape) {
                    return  '<div class="option">' +
                        '<span class="title">' + escape(data.title) + '</span>' +
                        '</div>';
                },
                item: function(data, escape) {
                    return '<div class="item"><a href="' + escape(data.url) + '" target="_blank">' + escape(data.title) + '</a></div>';
                },
                optgroup_header: function(data, escape) {
                    return '<div class="optgroup-header">' + escape(data.label) + '</div>';
                }
            },
            onDropdownOpen: function($dropdown) {
                $dropdown
                    .hide()
                    .velocity('slideDown', {
                        begin: function() {
                            $dropdown.css({'margin-top':'0'})
                        },
                        duration: 200,
                        easing: easing_swiftOut
                    })
            },
            onDropdownClose: function($dropdown) {
                $dropdown
                    .show()
                    .velocity('slideUp', {
                        complete: function() {
                            $dropdown.css({'margin-top':''})
                        },
                        duration: 200,
                        easing: easing_swiftOut
                    })
            }
        });

        var REGEX_EMAIL = '([a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*@' +
            '(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?)';
        $('#select_adv_2').selectize({
            plugins: {
                'remove_button': {
                    label: ''
                },
                'drag_drop': {}
            },
            persist: false,
            maxItems: null,
            valueField: 'email',
            labelField: 'name',
            searchField: ['name', 'email'],
            options: [
                {email: 'brian@thirdroute.com', name: 'Brian Reavis'},
                {email: 'nikola@tesla.com', name: 'Nikola Tesla'},
                {email: 'someone@gmail.com'}
            ],
            render: {
                item: function(item, escape) {
                    return '<div>' +
                        (item.name ? '<span class="name">' + escape(item.name) + '</span>' : '') +
                        (item.email ? '<span class="email">' + escape(item.email) + '</span>' : '') +
                        '</div>';
                },
                option: function(item, escape) {
                    var label = item.name || item.email;
                    var caption = item.name ? item.email : null;
                    return '<div>' +
                        '<span class="label">' + escape(label) + '</span>' +
                        (caption ? '<span class="caption">' + escape(caption) + '</span>' : '') +
                        '</div>';
                }
            },
            createFilter: function(input) {
                var match, regex;

                // email@address.com
                regex = new RegExp('^' + REGEX_EMAIL + '$', 'i');
                match = input.match(regex);
                if (match) return !this.options.hasOwnProperty(match[0]);

                // name <email@address.com>
                regex = new RegExp('^([^<]*)\<' + REGEX_EMAIL + '\>$', 'i');
                match = input.match(regex);
                if (match) return !this.options.hasOwnProperty(match[2]);

                return false;
            },
            create: function(input) {
                if ((new RegExp('^' + REGEX_EMAIL + '$', 'i')).test(input)) {
                    return {email: input};
                }
                var match = input.match(new RegExp('^([^<]*)\<' + REGEX_EMAIL + '\>$', 'i'));
                if (match) {
                    return {
                        email : match[2],
                        name  : $.trim(match[1])
                    };
                }
                alert('Invalid email address.');
                return false;
            },
            onDropdownOpen: function($dropdown) {
                $dropdown
                    .hide()
                    .velocity('slideDown', {
                        begin: function() {
                            $dropdown.css({'margin-top':'0'})
                        },
                        duration: 200,
                        easing: easing_swiftOut
                    })
            },
            onDropdownClose: function($dropdown) {
                $dropdown
                    .show()
                    .velocity('slideUp', {
                        complete: function() {
                            $dropdown.css({'margin-top':''})
                        },
                        duration: 200,
                        easing: easing_swiftOut
                    })
            }
        });

    },

    align_widget_example: function() {

        // select/unselect table rows

        $('#ts_align')
            .tablesorter({
                headers: {
                    0: {sorter: false}
                },
                theme: 'altair',
                widgets: ['zebra', 'alignChar'],
                widgetOptions : {
                    alignChar_wrap         : '<i/>',
                    alignChar_charAttrib   : 'data-align-char',
                    alignChar_indexAttrib  : 'data-align-index',
                    alignChar_adjustAttrib : 'data-align-adjust' // percentage width adjustments
                }
            });

        $('.ts_align')
            .tablesorter({
                headers: {
                    0: {sorter: false}
                },
                theme: 'altair',
                widgets: ['zebra', 'alignChar'],
                widgetOptions : {
                    alignChar_wrap         : '<i/>',
                    alignChar_charAttrib   : 'data-align-char',
                    alignChar_indexAttrib  : 'data-align-index',
                    alignChar_adjustAttrib : 'data-align-adjust' // percentage width adjustments
                }
            });
    }

};
