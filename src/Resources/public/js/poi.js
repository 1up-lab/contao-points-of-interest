;(function($){
    $(document).ready(function(){
        $('.single-point a.poi').on('click', function(event) {
            if (undefined === $(this).data('reveal-id')) {
                return;
            }

            event.preventDefault();

            var selectedPoint = $(this).parent('li');

            if( selectedPoint.hasClass('is-open') ) {
                selectedPoint.removeClass('is-open').addClass('visited');
            } else {
                selectedPoint.addClass('is-open').siblings('.single-point.is-open').removeClass('is-open').addClass('visited');
            }

            $.colorbox({
                inline: true,
                href: $(this).next()
            });

            return false;
        });

        $(document).bind('cbox_closed', function() {
            $('.single-point').each(function(){
                if( $(this).hasClass('is-open') ) {
                    $(this).removeClass('is-open').addClass('visited');
                }
            });
        });
    });
})(jQuery);
