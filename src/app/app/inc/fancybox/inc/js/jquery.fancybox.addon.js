/**
 * Class jquery.fancybox.addon
 * @package
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

$(document).on('beforeShow.fb', function( e, instance, slide ) {

    let el = $('.fancybox-image[src="'+slide.src+'"]');
    instance.rotateImage(el, true);

});

$(document).on('onInit.fb', function (e, instance) {

    //add rotation method
    instance.rotateImage = function(el, skip_increment){

        let rotation = core.storage.session.get('fancybox', el.attr('src'), 'rotation');

        if(!skip_increment){
            if(!rotation) rotation = 1;
            else rotation = parseInt(rotation) + 1;
        }else{ rotation = parseInt(rotation); }

        if(rotation >= 4) rotation = 0;
        let n = 90 * rotation;

        el.css('webkitTransform', 'rotate(-' + n + 'deg)');
        el.css('mozTransform', 'rotate(-' + n + 'deg)');

        if(!skip_increment)
            core.storage.session.set('fancybox', el.attr('src'), 'rotation', rotation++);

    };

    //add toolbar item
    var btn_id = core.data.generate_unique_id("fancybox-button");

    if ($('.fancybox-toolbar').find('#rotate_button').length === 0) {
        $('.fancybox-toolbar').prepend('<button id=\"'+btn_id+'\" class=\"fancybox-button\" title=\"Rotate Image\"><i class=\"fas fa-sync-alt\"></i></button>');
    }

    //add event
    $('body').on('click', '#'+btn_id, function () {
        let el = $('.fancybox-slide--current .fancybox-image');
        instance.rotateImage(el);
    });

    //rotate clicked image
    setTimeout(function(){
        let el = $('.fancybox-slide--current .fancybox-image[src="'+instance.current.src+'"]');
        instance.rotateImage(el, true);
    }, 0)

});