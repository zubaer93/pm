var options = {

    url: function (phrase) {
        return $('#companies_search').data("url") + '.json';
    },

    categories: [{
            listLocation: "companies",
            maxNumberOfElements: 5,
        }, {
            listLocation: "users",
            maxNumberOfElements: 5,
        }, {
            listLocation: "trader",
            maxNumberOfElements: 5,
        }
    ],

    getValue: function (element) {
        return element.name;
    },

    template: {
        type: "custom",
        method: function (value, item) {
            if (item.type == 'company') {
                var template = $('#search-company-template').html();
                var output = Mustache.render(template, item);
            } else if (item.type == 'user') {
                var template = $('#search-user-template').html();
                var output = Mustache.render(template, item);
            } else if (item.type == 'trader') {
                var template = $('#search-trader-template').html();
                var output = Mustache.render(template, item);
            }
            return output;
        }
    },

    list: {
        maxNumberOfElements: 12,
        onShowListEvent: function () {
            $('.search-navbar-loader').hide();
        }
    },

    ajaxSettings: {
        dataType: "json",
        method: "GET",
        data: {
            dataType: "json"
        }
    },

    preparePostData: function (data) {
        var search = $("#js-search-navbar").val();
        $('.search-navbar-loader').show();
        if (search.length) {

        } else {
            search = $("#js-search-navbar-mobail").val();
        }
        data.phrase = search;
        return data;
    },

    theme: "square"
};

var homeSeachoptions = {

    url: function (phrase) {
        return $('#companies_search').data("url");
    },

    categories: [{
            listLocation: "companies",
            maxNumberOfElements: 5,
        }, {
            listLocation: "users",
            maxNumberOfElements: 5,
        }, {
            listLocation: "trader",
            maxNumberOfElements: 5,
        }
    ],

    getValue: function (element) {
        return element.name;
    },

    template: {
        type: "custom",
        method: function (value, item) {
            console.log(item);
            if (item.type == 'company') {
                var template = $('#search-company-template').html();
                var output = Mustache.render(template, item);
            } else if (item.type == 'user') {
                var template = $('#search-user-template').html();
                var output = Mustache.render(template, item);
            } else if (item.type == 'trader') {
                var template = $('#search-trader-template').html();
                var output = Mustache.render(template, item);
            }
            return output;
        }
    },

    list: {
        maxNumberOfElements: 10,
        onShowListEvent: function () {
            $('.js-search-home-loader').hide();
        }
    },

    ajaxSettings: {
        dataType: "json",
        method: "GET",
        data: {
            dataType: "json"
        }
    },
    preparePostData: function (data) {
        var search = $("#js-search-home").val();
        $('.js-search-home-loader').show();
        if (search.length) {

        } else {
            search = $("#js-search-navbar-mobail").val();
        }
        data.phrase = search;
        return data;
    },

    theme: "square"
};

$("#js-search-navbar").easyAutocomplete(options);

$("#js-search-navbar-mobail").easyAutocomplete(options);

$("#js-search-home").easyAutocomplete(homeSeachoptions);

$('#js-search-home, #js-search-navbar,#js-search-navbar-mobail').on('keyup keypress', function (e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) {
        e.preventDefault();
        window.location.href = '/' + $('.search-navbar-loader').attr('data-lang') + '/search?q=' + $("#js-search-navbar").val();
        return false;
    }
});
$('#js-search-home').on('keyup keypress', function (e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) {
        e.preventDefault();
        window.location.href = '/' + $('.search-navbar-loader').attr('data-lang') + '/search?q=' + $("#js-search-home").val();
        return false;
    }
});