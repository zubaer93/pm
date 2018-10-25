index = 0;
var KeyPeople = function () {
    this.bind();
};

KeyPeople.prototype.bind = function () {
    var self = this;
    self.add();
    self.remove();
};

KeyPeople.prototype.add = function () {
    $('body').on('click', '.js-add-element', function(e) {
        var template = $("#people").html();
        index++;
        var html = Mustache.render(template, {'index': index});
        $('.js-fields').append(html);
    });
};

KeyPeople.prototype.remove = function () {
    $('body').on('click', '.js-remove-element', function(e) {
        $('.js-people-' + $(this).data('index')).remove();
    });
};
