$.fn.bindFirst = function(name, selector, fn) {
    // bind as you normally would
    // don't want to miss out on any jQuery magic
    this.on(name, selector, fn);

    // Thanks to a comment by @Martin, adding support for
    // namespaced events too.
    this.each(function() {
        var handlers = $._data(this, 'events')[name.split('.')[0]];
        // take out the handler we just inserted from the end
        var handler = handlers.pop();
        // move it at the beginning
        handlers.splice(0, 0, handler);
    });
};

var clickHandler = function() {
    alert('Click Handler! ');
};

var submitHandler = function () {
    alert('Submit Handler! ');
};

jQuery(function () {
    var formSelector = '#form124567582';

    var $form = jQuery(formSelector);
    var form = $form.get(0);
    var $r = $form.closest('.r');
    var r = $r.get(0);

    $r.bindFirst('click', '.js-form-proccess [type=submit]', clickHandler);
    $r.bindFirst('submit', '.js-form-proccess', submitHandler);

//jQuery.data(r, "events");
});

