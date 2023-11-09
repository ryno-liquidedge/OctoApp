/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class popup {

    //--------------------------------------------------------------------------------
    /**
     * constructor
     * @param options
     */
    constructor(options) {
        // options
        this.options = $.extend({
            class: '',
            class_modal_content: '',
            width: 'auto',
            id: core.string.get_random_id({prepend: "modal"}),
            title: 'Alert',
            closable: true,
            hide_header: false,
            hide_footer: false,
            backdrop: true, // true | false | 'static'
            body_content: '',
            footer_content: '',
            on_show: function () {
            },
            on_shown: function () {
            },
            on_hide: function () {
            },
            on_hidden: function () {
            },
        }, (options === undefined ? {} : options));

        this.aria_labelledby = this.options.id + 'Label';
    }

    //--------------------------------------------------------------------------------
    set_title(title) {
        this.options.title = title;
    }

    //--------------------------------------------------------------------------------
    set_body_content(content) {
        this.options.body_content = content;
    }

    //--------------------------------------------------------------------------------
    set_footer_content(content) {
        this.options.footer_content = content;
    }

    //--------------------------------------------------------------------------------
    on_show(f) {
        this.options.on_show = f;
    }

    //--------------------------------------------------------------------------------
    on_shown(f) {
        this.options.on_shown = f;
    }

    //--------------------------------------------------------------------------------
    on_hide(f) {
        this.options.on_hide = f;
    }

    //--------------------------------------------------------------------------------
    on_hidden(f) {
        this.options.on_hidden = f;
    }

    //--------------------------------------------------------------------------------
    build_html() {
        return '' +
			'<div class="modal ui-modal-inject fade" id="'+this.options.id+'" aria-hidden="true" aria-labelledby="'+this.aria_labelledby+'" tabindex="-1" data-z-index="1050">' +
				'<div class="modal-dialog modal-dialog-centered '+this.options.width+' '+this.options.class+'">' +
					'<div class="modal-content '+this.options.class_modal_content+'">' +
						'<div class="modal-header '+(this.options.hide_header ? "d-none" : "")+'">'+
							'<h5 class="modal-title" id="'+this.aria_labelledby+'">'+this.options.title+'</h5>'+
							'<button type="button" class="btn-close '+(!this.options.closable ? 'd-none' : '')+'" data-bs-dismiss="modal" aria-label="Close"></button>'+
						'</div>'+
						'<div class="modal-body">'+
							this.options.body_content+
						'</div>'+
						'<div class="modal-footer'+((this.options.hide_footer || !this.options.footer_content) ? "d-none" : "")+'">'+
						  this.options.footer_content+
						'</div>'+
					'</div>'+
				'</div>'+
			'</div>';
    }

    //--------------------------------------------------------------------------------
    build(options) {

        options = $.extend(this.options, (options === undefined ? {} : options));

        let html = this.build_html(options);
        let instance = this;
        let existing_modal = $('.modal.ui-modal-inject').last();

        document.body.insertAdjacentHTML('beforeend', html);

        let element = document.getElementById(this.options.id);

        element.addEventListener('show.bs.modal', function (event) {

            let $this = $(this);
            instance.options.on_show.apply(this, [event, instance.options]);

            if (existing_modal.length) {
                let index = parseInt(existing_modal.attr('data-z-index'));
                let new_index = index + 100;

                $this.attr("data-z-index", new_index);
                $this.css('z-index', new_index);
                setTimeout(function (){
                    $this.next().css('z-index', new_index - 10);
                }, 10);
            }

            // clear focus from buttons
            $('button:focus').blur();
            $('body').focus().hover();
        });

        element.addEventListener('shown.bs.modal', function (event) {
            instance.options.on_shown.apply(this, [event, instance.options]);
            $(this).find('button[commodal-btn=ok]').focus();
            core.browser.popup_id_arr.push(instance.options.id);
            core.overlay.hide();
        });

        element.addEventListener('hide.bs.modal', function (event) {
            instance.options.on_hide.apply(this, [event, instance.options]);
        });

        element.addEventListener('hidden.bs.modal', function (event) {
            instance.options.on_hidden.apply(this, [event, instance.options]);
            element.remove();
            core.browser.popup_id_arr.pop();
            if (core.browser.popup_id_arr.length) {
                $('body').addClass('modal-open');
            }
        });

        let popup = new bootstrap.Modal(element, options);
        popup.show();

        return popup;

    }

    //------------------------------------------------------------------------------
}