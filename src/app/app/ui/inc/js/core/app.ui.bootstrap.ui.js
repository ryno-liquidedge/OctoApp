/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

var ui = {
    is_mobile:core.util.is_mobile(),
    
    social: {
        url: window.location.href,
        protocol: ['http', 'https'].indexOf(window.location.href.split(':')[0]) === -1 ? 'https://' : '//',
        title: '',
        image: '',
        description: '',
        keywords: '',
        source: '',
        //------------------------------------------------
        getMetadata: function (id) {
            if ($("meta[property='og:" + id + "']").length) return $("meta[property='og:" + id + "']").attr('content');
            if ($("meta[name='twitter:" + id + "']").length) return $("meta[name='twitter:" + id + "']").attr('content');
            if ($("meta[name=" + id + "]").length) return $("meta[name=" + id + "]").attr('content');

            return '';
        },
        //------------------------------------------------
        getTitle: function () {

            if (this.title.length) return this.title;

            return ui.social.getMetadata('title')
        },
        //------------------------------------------------
        getImage: function () {

            if (this.image.length) return this.image;

            return ui.social.getMetadata('image')
        },
        //------------------------------------------------
        getDescription: function () {

            if (this.description.length) return this.description;

            return ui.social.getMetadata('description')
        },
        //------------------------------------------------
        popup: function (url) {
            // set left and top position
            var popupWidth = 500,
                popupHeight = 400,
                // fix dual screen mode
                dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left,
                dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top,
                width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width,
                height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height,
                // calculate top and left position
                left = ((width / 2) - (popupWidth / 2)) + dualScreenLeft,
                top = ((height / 2) - (popupHeight / 2)) + dualScreenTop,

                // show popup
                shareWindow = window.open(url, 'targetWindow', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=' + popupWidth + ', height=' + popupHeight + ', top=' + top + ', left=' + left);

            // Puts focus on the newWindow
            if (window.focus) {
                shareWindow.focus();
            }
        },
        //------------------------------------------------
        share: {
            'mailto': function () {
                var url = 'mailto:?subject=' + encodeURIComponent(ui.social.title) + '&body=Thought you might enjoy reading this: ' + encodeURIComponent(ui.social.url) + ' - ' + encodeURIComponent(ui.social.description);

                window.location.href = url;
            },
            'twitter': function () {
                var url = ui.social.protocol + 'twitter.com/share';
                url += '?text=' + encodeURIComponent(ui.social.title);
                url += '&url=' + encodeURIComponent(ui.social.url);
                url += '&hashtags=' + encodeURIComponent(ui.social.keywords);

                ui.social.popup(url);
            },
            'pinterest': function () {
                var url = ui.social.protocol + 'pinterest.com/pin/create/bookmarklet/?is_video=false';
                url += '&media=' + encodeURIComponent(ui.social.image);
                url += '&url=' + encodeURIComponent(ui.social.url);
                url += '&description=' + encodeURIComponent(ui.social.title);

                ui.social.popup(url);
            },
            'facebook': function () {
                var url = ui.social.protocol + 'www.facebook.com/share.php?';
                url += 'u=' + encodeURIComponent(ui.social.url);
                url += '&title=' + encodeURIComponent(ui.social.title);

                ui.social.popup(url);
            },
            'linkedin': function () {
                var url = ui.social.protocol + 'www.linkedin.com/sharing/share-offsite/';
                url += '?url=' + encodeURIComponent(ui.social.url);
                url += '&title=' + encodeURIComponent(ui.social.title);
                url += '&source=' + encodeURIComponent(ui.social.source);

                ui.social.popup(url);
            },
            'googleplus': function () {
                var url = ui.social.protocol + 'plus.google.com/share?';
                url += 'url=' + encodeURIComponent(ui.social.url);

                ui.social.popup(url);
            },
            'reddit': function () {
                var url = ui.social.protocol + 'www.reddit.com/submit?';
                url += 'url=' + encodeURIComponent(ui.social.url);
                url += '&title=' + encodeURIComponent(ui.social.title);

                ui.social.popup(url);
            },
            'delicious': function () {
                var url = ui.social.protocol + 'del.icio.us/post?';
                url += 'url=' + encodeURIComponent(ui.social.url);
                url += '&title=' + encodeURIComponent(ui.social.title);
                url += '&notes=' + encodeURIComponent(ui.social.description);

                ui.social.popup(url);
            },
            'tapiture': function () {
                var url = ui.social.protocol + 'tapiture.com/bookmarklet/image?';
                url += 'img_src=' + encodeURIComponent(ui.social.image);
                url += '&page_url=' + encodeURIComponent(ui.social.url);
                url += '&page_title=' + encodeURIComponent(ui.social.title);

                ui.social.popup(url);
            },
            'stumbleupon': function () {
                var url = ui.social.protocol + 'www.stumbleupon.com/submit?';
                url += 'url=' + encodeURIComponent(ui.social.url);
                url += '&title=' + encodeURIComponent(ui.social.title);

                ui.social.popup(url);
            },
            'slashdot': function () {
                var url = ui.social.protocol + 'slashdot.org/bookmark.pl?';
                url += 'url=' + encodeURIComponent(ui.social.url);
                url += '&title=' + encodeURIComponent(ui.social.title);

                ui.social.popup(url);
            },
            'technorati': function () {
                var url = ui.social.protocol + 'technorati.com/faves?';
                url += 'add=' + encodeURIComponent(ui.social.url);
                url += '&title=' + encodeURIComponent(ui.social.title);

                ui.social.popup(url);
            },
            'posterous': function () {
                var url = ui.social.protocol + 'posterous.com/share?';
                url += 'linkto=' + encodeURIComponent(ui.social.url);

                ui.social.popup(url);
            },
            'tumblr': function () {
                var url = ui.social.protocol + 'www.tumblr.com/share?v=3';
                url += '&u=' + encodeURIComponent(ui.social.url);
                url += '&t=' + encodeURIComponent(ui.social.title);

                ui.social.popup(url);
            },
            'googlebookmarks': function () {
                var url = ui.social.protocol + 'www.google.com/bookmarks/mark?op=edit';
                url += '&bkmk=' + encodeURIComponent(ui.social.url);
                url += '&title=' + encodeURIComponent(ui.social.title);
                url += '&annotation=' + encodeURIComponent(ui.social.description);

                ui.social.popup(url);
            },
            'newsvine': function () {
                var url = ui.social.protocol + 'www.newsvine.com/_tools/seed&save?';
                url += 'u=' + encodeURIComponent(ui.social.url);
                url += '&h=' + encodeURIComponent(ui.social.title);

                ui.social.popup(url);
            },
            'pingfm': function () {
                var url = ui.social.protocol + 'ping.fm/ref/?';
                url += 'link=' + encodeURIComponent(ui.social.url);
                url += '&title=' + encodeURIComponent(ui.social.title);
                url += '&body=' + encodeURIComponent(ui.social.description);

                ui.social.popup(url);
            },
            'evernote': function () {
                var url = ui.social.protocol + 'www.evernote.com/clip.action?';
                url += 'url=' + encodeURIComponent(ui.social.url);
                url += '&title=' + encodeURIComponent(ui.social.title);

                ui.social.popup(url);
            },
            'friendfeed': function () {
                var url = ui.social.protocol + 'www.friendfeed.com/share?';
                url += 'url=' + encodeURIComponent(ui.social.url);
                url += '&title=' + encodeURIComponent(ui.social.title);

                ui.social.popup(url);
            },
            'vkontakte': function () {
                var url = ui.social.protocol + 'vkontakte.ru/share.php?';
                url += 'url=' + encodeURIComponent(ui.social.url);
                url += '&title=' + encodeURIComponent(ui.social.title);
                url += '&description=' + encodeURIComponent(ui.social.description);
                url += '&image=' + encodeURIComponent(ui.social.image);
                url += '&noparse=true';

                ui.social.popup(url);
            },
            'odnoklassniki': function () {
                var url = ui.social.protocol + 'www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1';
                url += '&st.comments=' + encodeURIComponent(ui.social.description);
                url += '&st._surl=' + encodeURIComponent(ui.social.url);

                ui.social.popup(url);
            },
            'whatsapp': function () {
                var url = ui.social.protocol + 'api.whatsapp.com/send';
                url += '?text=' + encodeURIComponent(ui.social.description);;
                url += '%20' + encodeURIComponent(ui.social.url);

                window.open(url, '_blank');
            },
        },
    }
    
};

$(function(){
    let body = $('body');

    //----------------------------------------------------------------------------
    body.on('keypress', '.init-search', function(e){

        let el = $(this);
        let keycode = (e.keyCode ? e.keyCode : e.which);
        let cid = el.data('cid');
        let form_id = el.data('form-target');
        if(!form_id && cid) form_id = "#"+cid+"_form";
        if(!form_id) form_id = el.closest("form").attr("id");

        if (keycode == 13) {
            $('#'+form_id+" .btn-submit").click();
            $('#'+form_id+" .btn-clear").removeClass('d-none');
        }
    });
    //----------------------------------------------------------------------------
    body.on('click', '.toggle-field-mask', function(){
        let element = $(this);
        let target = element.data('target');
        if(!target) target = '#'+element.closest('.input-group').find('input').attr('id');

        let target_arr = target.split(',');


        $.each(target_arr, function( index, value ) {
            target = $(value);
            if(target.attr('type') === 'password'){
                target.attr('type', 'text');
                element.addClass('fa-eye-slash').removeClass('fa-eye');
            }else{
                target.attr('type', 'password');
                element.addClass('fa-eye').removeClass('fa-eye-slash');
            }
        });
    });
    //----------------------------------------------------------------------------
    body.on('click', '.toggle-icon', function () {
        let el = $(this);
        let icon_default = el.attr('data-icon-default');
        let icon_toggle = el.attr('data-icon-toggle');
        let fontawesome_class = el.attr('data-fontawesome-class');

        if(!fontawesome_class) fontawesome_class = ".fas"

        let icon_element = el.find(fontawesome_class);

        if(icon_element){
        	if(icon_element.hasClass(icon_default)){
        		icon_element.removeClass(icon_default).addClass(icon_toggle);
			}else{
        		icon_element.addClass(icon_default).removeClass(icon_toggle);
			}
		}
    });
    //----------------------------------------------------------------------------
    body.on('mouseenter mouseleave mousemove', '.mouse-follow-tooltip', function(e){
        if(!ui.is_mobile){
            e.preventDefault();
            e.stopPropagation();
            onHoverToggleTooltip($(this) , e);
        }
    });
    //----------------------------------------------------------------------------
    body.on('click', '.show-loader, .show-overlay', function () {
        core.overlay.show();
    });
    //----------------------------------------------------------------------------

});

// mouse-follow-tooltip
function onHoverToggleTooltip(el, e) {
    var $this = el,
        title = $this.attr('data-title'),
        type = e.type,
        offset = $this.offset(),
        xOffset = e.pageX - offset.left + ($this.data('offset-left') ? parseInt($this.data('offset-left')) : 10),
        yOffset = e.pageY - offset.top + ($this.data('offset-top') ? parseInt($this.data('offset-top')) : 30);

        let id = core.string.get_random_id();
        if(!$this.attr('data-target')){
            $this.attr('data-target', id);
        }else{
            id = $this.attr('data-target');
        }

    if (type == 'mouseenter') {
        $this.append('<span id="'+id+'" class=\"mouse-follow-tooltip-title\">' + title + '</span>').show();
        $this.find('.title')
            .css('top', (yOffset) + 'px')
            .css('left', (xOffset) + 'px');
    } else if (type == 'mouseleave') {
        $this.find('.mouse-follow-tooltip-title').remove();
    } else if (type == 'mousemove') {
        $this.find('.mouse-follow-tooltip-title')
            .css('top', (yOffset) + 'px')
            .css('left', (xOffset) + 'px');
    }

}

