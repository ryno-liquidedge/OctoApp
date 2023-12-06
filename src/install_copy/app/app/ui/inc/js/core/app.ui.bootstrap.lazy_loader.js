//----------------------------------------------------------------------------
var lazy_loader = {

    all_items_loaded:false,
    page:1,
    //----------------------------------------------------------------------------
    init:function(wrapper, url, options){

        // options
        options = $.extend({
            url: url,
            wrapper: wrapper,
            offset: 500,
            data: {},
            success: false,
            beforeSend: false,
        }, (options === undefined ? {} : options));

        if(options.offset === null){
            options.offset = $(wrapper).offset().top + $(wrapper).outerHeight(true) - 150;
        }

        wrapper = $(wrapper);

        $(document).ajaxStop(function(){
            $(window).scroll(function() {
               if(lazy_loader.do_call(options)) {
                   if(!wrapper.hasClass('loading')){
                       lazy_loader.load_content(options);
                   }
               }
            });
        });
    },
    //----------------------------------------------------------------------------
    do_call:function(options){

        let wrapper = $(options.wrapper);

        // options
        if(lazy_loader.all_items_loaded) return false;
        return $(window).scrollTop() >= (wrapper.height() - options.offset);
    },
    //----------------------------------------------------------------------------
    load_content:function(options){

        // options
        let wrapper = $(options.wrapper);

        core.ajax.request(options.url+'&page='+lazy_loader.page+'&nocache='+core.string.get_random_id(), {
            no_overlay: true,
            data: options.data,
            method: 'GET',
            beforeSend: function(){
                wrapper.addClass('loading');
                wrapper.append('<div class="lazy-load-hint w-100 text-center p-4"><span class="fas fa-spinner fa-spin mr-2 ui-icon-element"></span> Loading ...</div>');

                if(options.beforeSend){
                    options.beforeSend.apply(this, [options]);
                }

            },
            success: function(response){
                wrapper.append(response);

                if(options.success){
                    options.success.apply(this, [response, options]);
                }

                wrapper.find('.lazy-load-hint').remove();
                if(response === '') lazy_loader.all_items_loaded = true;
                lazy_loader.page = lazy_loader.page + 1;
                wrapper.removeClass('loading');
            }
        });
    }
    //----------------------------------------------------------------------------
}