
var Backend  = Backend || {};

Backend.poiEditPositionWizard = function(el){
    el = $(el);

    var input = $('ctrl_position');
    var marker = $$('#tl_point_of_interest .poi span.marker')[0];

    var originalWidth = parseInt(el.get('data-original-width'));
    var originalHeight = parseInt(el.get('data-original-height'));

    var getScale = function() {
        return el.getComputedSize().width / originalWidth;
    };

    var updatePosition = function(clickPos) {
        var scale = getScale();
        var values = {
            x: Math.max(0, clickPos.x/scale).round(),
            y: Math.max(0, clickPos.y/scale).round(),
        };

        if (values.x > originalWidth) {
            values.x = originalWidth;
        }

        if (values.y > originalHeight) {
            values.y = originalHeight;
        }

        input.value = JSON.encode(values);

        marker.setStyles({
                'top': clickPos.y - marker.getComputedSize().height/2,
                'left': clickPos.x - marker.getComputedSize().width/2
        });

        marker.removeClass('not-set');
    };

    var click = function(event) {
        event.preventDefault();

        var clickPos = {
            x: event.page.x - el.getPosition().x - el.getComputedSize().computedLeft,
            y: event.page.y - el.getPosition().y - el.getComputedSize().computedTop
        };

        updatePosition(clickPos);
    };

    var init = function() {
        el.addEvents({
            click: click
        });

        marker.addEvents({
            click: deletePoi
        });

        if (input.value !== '' && typeof JSON.decode(input.value) === 'object') {
            var values = JSON.decode(input.value);

            marker.setStyles({
                'top': (values.y * getScale()).round() - marker.getComputedSize().height/2,
                'left': (values.x * getScale()).round() - marker.getComputedSize().width/2
            });

            marker.removeClass('not-set');
        }
    };

    var deletePoi = function(event) {
        event.preventDefault();

        marker.addClass('not-set');
        input.value = '';

        return false;
    };

    window.addEvent('domready', init);
};
