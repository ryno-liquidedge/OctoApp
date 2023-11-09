
if (typeof Dropzone !== 'undefined') {
    Dropzone.autoDiscover = false;
}

core.workspace.is_system = true;

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
    body.on('click', '.save-form-target', function(){
        let el = $(this);
        let form_target = el.data('form-target');
        let type = el.data('action-type');
        if(!type) type = "add";
        save_wizard_form(form_target, {type:type});

    });
    //----------------------------------------------------------------------------
});
//----------------------------------------------------------------------------
function save_wizard_form(form_target, options){

    let form = $(form_target);
    let form_action = form.attr('action');

    // options
    options = $.extend({
        type:"add",
        form:form_target,
        update_content:true,
        process_ajax_response:false,
        no_overlay:true,
    }, options);

    core.overlay.show();

    core.ajax.request_function(form_action, function(response){

        update_panels(response, options);

    }, options);
}
//----------------------------------------------------------------------------
function update_panels(response, options){

    // options
    options = $.extend({
        type:"add",
        update_content:true,
        process_ajax_response:false,
        no_overlay:true,
    }, options);

    if(response.alert && response.alert.length){
        core.overlay.hide();
        return core.browser.alert(response.alert);
    }

    if(options.process_ajax_response)
        core.ajax.process_response(response);

    if(options.update_content){
        setTimeout(function(){
            core.ajax.request("?c=system.listing."+options.type+".functions/xupdate_progress_badges", {
                no_overlay:true,
                method:'GET',
                success:function(response){ core.ajax.process_response(response); }
            })
            manage_options.refresh(null, {no_overlay:true, done:function(){
                core.overlay.hide();
            }});
        }, 100);
    }
}
//----------------------------------------------------------------------------
function select_wizard_menu(id, control, options){

    // options
    options = $.extend({
        type:"add",
    }, options);

    let menu_obj;
    if(options.type === "add") menu_obj = eval('add_listing_manage_menu');
    if(options.type === "edit") menu_obj = eval('listing_manage_menu');
    let menu_index = $('a[data-control="'+control+'"]').data('index');
    menu_obj.select_active_menu(menu_index);

    core.browser.scrollToTop();

    setTimeout(function(){
        $(id).focus();
    }, 500);
}
//----------------------------------------------------------------------------