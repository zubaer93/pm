var Watchlist = function () {
    this.bind();
};

Watchlist.prototype.bind = function () {
    var self = this;
    self.bindModal($('.js-watchlist'));
    self.createWatchlist($('.js-create-watchlist'));
};

Watchlist.prototype.bindModal = function (element) {
    var self = this;
    element.on('click', function(e) {
        e.preventDefault();
        $('.js-modal-content').html($('.js-modal-loader').html());
        $('#modal').modal('show');
        self.showModal($(this).data('url'));
        self.saveModal(this);
    });
};

Watchlist.prototype.listenCheckbox = function (element) {
    var self = this;
    $('body').on('change', '.js-new-watchlist', function (e) {
        if (this.checked) {
            $('.js-name').removeClass('hide');
        } else {
            $('.js-name').addClass('hide');
        }
    });
};

Watchlist.prototype.showModal = function (url) {
    var self = this;
    $.get(url, function(result) {
        $('.js-modal-content').html(result);
        self.listenCheckbox();
    });
};

Watchlist.prototype.saveModal = function () {
    var self = this;
    $('body').on('submit', '.js-add-watchlist-form', function (e) {
        if ($('.js-new-watchlist').is(':checked')) {
            e.preventDefault();
            self.saveGroup(this);
        }
    });
};

Watchlist.prototype.saveGroup = function(element) {
    var form = $(element);

    $.post(form.data('url-group'), form.serialize(), function(result) {
        if (result.status) {
            toastr.success(result.message);
            var template = $("#watchlist-option").html();
            var html = Mustache.render(template, {
                id: result.watchlist.id,
                name: result.watchlist.name,
            });
            $(".js-watchlist-select").append(html);
            $('.js-name').addClass('hide');
            $('#name').val('');
            $('.js-new-watchlist').prop('checked', false);
        } else {
            toastr.error(result.message);
        }
    }, "json");
};

Watchlist.prototype.createWatchlist = function(form) {
    form.on('submit', function(e) {
        var self = this;
        e.preventDefault();
        if (!$(this).find('input.js-item-watchlist-name').val()) {
            toastr.error('We could not save your watchlist group. Please try again');
        } else {
            $.post($(this).attr('action'), $(this).serialize(), function(result) {
                if (result.status) {
                    toastr.success(result.message);
                    var template = $("#item-watchlist-template-new").html();
                    var html = Mustache.render(template, {
                        id: result.watchlist.id,
                        name: result.watchlist.name,
                        item: result.item
                    });

                    $(self).parents('.tab-pane').find('.js-watchlist-items').append(html);
                } else {
                    toastr.error(result.message);
                }
            }, "json");
        }
    });
};