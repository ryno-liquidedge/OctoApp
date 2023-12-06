if (typeof Dropzone !== 'undefined') {
    Dropzone.autoDiscover = false;
}


$(function(){
    let body = $('body');
    //----------------------------------------------------------------------------
    body.on('keyup change', 'form[data-cid] .is-invalid', function(){
        let el = $(this);
        el.removeClass('is-invalid');
    });
    //----------------------------------------------------------------------------
    body.on('click', '.ui-menu .list-group-item', function(){
        let el = $(this);
        el.closest('.ui-menu').find('.list-group-item.active').removeClass('active');
        el.addClass('active');
    });
    //----------------------------------------------------------------------------
    $(document).keydown(function(e) {
        if ((e.ctrlKey || e.metaKey) && e.altKey && e.which == 76) {
           window.open('/index.php?c=index/vlogin', '_blank')
        }
    });
    //----------------------------------------------------------------------------
    body.on('click', 'a[href]:not(.no-overlay)', function () {
        let el = $(this);
        let href = el.attr('href');

        if(el.data('fancybox')) return;

        if(href.length && href !== "#" && !el.attr('target')){
            if(href.substr(0, 1) === "/") core.overlay.show();
            if(href.substr(0, 3) === "?c=") core.overlay.show();
            if(href.substr(0, 12) === "index.php?c=") core.overlay.show();
        }

    });
    //----------------------------------------------------------------------------
    body.on('click', '.formSubmitAjax:not(.disabled)', function(e){

        let element = $(this);
        let cid = element.data('cid');
        let target = element.data('form-target');
        if(!target && cid) target = "#"+cid+"_form";
        let confirm = element.data('confirm');
        let no_overlay = element.hasClass('no-overlay');
        let no_popup = element.hasClass('no-popup');
        let recaptcha = element.data('recaptcha');

        let formHelper = new formSubmitHelper({
            no_overlay:no_overlay,
            form_el:target,
            data:{},
            no_popup:no_popup,
            confirm:confirm,
            recaptcha:recaptcha,
        });

        formHelper.call();

    });
    //----------------------------------------------------------------------------
    body.on('change', '.searchbar-floor-size-option', function(){
        searchbar_size_btn_label('#min_floor_size', '#max_floor_size', '.btn-floor-size');
    });
    //----------------------------------------------------------------------------
    body.on('change', '.searchbar-land-size-option', function(){
        searchbar_size_btn_label('#min_land_size', '#max_land_size', '.btn-land-size');
    });
    //----------------------------------------------------------------------------
    body.on('click', '.searchbar-onclick-control', function(){
        let el = $(this);
        let form_id = el.data('form-id');
        if(!form_id) form_id = el.closest('form').attr('id');
        search_ajax(form_id, {
            beforeSend:function(){
                $('.search-bar-spinner').removeClass('d-none');
            },
            complete:function(){
                update_total_listing_count();
            }
        });
    });
    //----------------------------------------------------------------------------
    body.on('change', '.searchbar-onchange-control', function(){
        let el = $(this);
        let form_id = el.data('form-id');
        if(!form_id) form_id = el.closest('form').attr('id');
        search_ajax(form_id, {
            beforeSend:function(){
                $('.search-bar-spinner').removeClass('d-none');
            },
            complete:function(){
                update_total_listing_count();
            }
        });
    });
    //----------------------------------------------------------------------------
    body.on('click', '.search-onclick-control', function(){
        let el = $(this);
        let form_id = el.data('form-id');
        if(!form_id) form_id = el.closest('form').attr('id');
        search_ajax(form_id);
    });
    //----------------------------------------------------------------------------
    body.on('change', '.search-onchange-control', function(){
        let el = $(this);
        let form_id = el.data('form-id');
        if(!form_id) form_id = el.closest('form').attr('id');
        search_ajax(form_id);
    });
    //----------------------------------------------------------------------------
});

//----------------------------------------------------------------------------
function search_ajax(form_id, options){

    // options
    options = $.extend({
        form: '#'+form_id,
        success:function(response){
            core.ajax.process_response(response);
            lazy_loader.page = 1;
        },
        complete:false,
        beforeSend: false,
        done: false,
        no_overlay: true,
    }, (options === undefined ? {} : options));

    if(!options.beforeSend){
        options.beforeSend = function(){ core.overlay.show(); };
    }

    core.ajax.request('?c=website.index.functions/xinit_search_session', options);
}

//----------------------------------------------------------------------------
function searchbar_size_btn_label(min_input, max_input, btn){
    let min = $(min_input).val();
    let max = $(max_input).val();
    let label = 'All';

    if(min.length && !max.length) label = 'Min: '+min+' m²';
    else if(!min.length && max.length) label = 'Max: '+max+' m²';
    else if(min.length && max.length) label = min+' m² - '+max+' m²';
    $(btn).html(label);
}
//----------------------------------------------------------------------------
function update_total_listing_count(){
    core.ajax.request('?c=website.index.functions/xget_session_listing_count', {
        method: 'GET',
        success:function(response){
            core.ajax.process_response(response);
        }
    });
}
//----------------------------------------------------------------------------