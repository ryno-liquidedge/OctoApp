
//load externals
// document.write('<script src="https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js", type="text/javascript"></script>');
// document.write('<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap5@6.1.8/index.global.min.js", type="text/javascript"></script>');


class calendar {
    //--------------------------------------------------------------------------------
    constructor(options) {

        //init
        let instance = this;
        this.event_arr = {};
        this.repeating_events_arr = [];

        // options
        this.options = $.extend({
            id:false,
            themeSystem:'bootstrap5',
            initialView:'dayGridMonth',
            // selectOverlap:false,
            selectable:true,
            dragScroll:false,
            unselectAuto:false,
            selectDayColor:"bg-teal",
            disabledDayColor:"bg-primary",
            events:{},
            disabled_dates_arr:[],
            repeating_events_arr:[],
            buttonIcons:{
                prev: "chevron-left",
                next: "chevron-right",
                prevYear: "chevrons-left",
                nextYear: "chevrons-right",
            },
        }, (options === undefined ? {} : options));

    }
    //--------------------------------------------------------------------------------
    initRepeatingEvents(){
        let instance = this;

        $.each(instance.options.repeating_events_arr, function(index, event){
            instance.addRepeatingEvent(event);
        });

        $.each(this.repeating_events_arr, function(index, repeatingEvent){
            instance.calendar.addEvent(repeatingEvent);
        });
    }
    //--------------------------------------------------------------------------------
    init(){

        let instance = this;

        instance.initRepeatingEvents();

        $.each(instance.options.disabled_dates_arr, function(index, date){
            date = new Date(date);
            instance.addEvent(date, {
                disabled:true,
                eventSource:"init",
            });
        });
    }
    //--------------------------------------------------------------------------------
    addRepeatingEvent(options){

        let eventData = $.extend({
            groupId:'repeatingEvents',
            eventSource:'init',
            allDay:true,
            display:'background',
            rendering:'background',
            daysOfWeek:[],
            disabled:false,
            forceRender:true,
        }, (options === undefined ? {} : options));

        if(eventData.disabled && !eventData.classNames){
            eventData.classNames = ["bg-danger", "disabled"];
        }

        this.repeating_events_arr.push(eventData);
    }
    //--------------------------------------------------------------------------------
    getEventId(date){
        return core.form.parse_js_date(new Date(date)).split('-').join('_')
    }
    //--------------------------------------------------------------------------------
    getEvent(date){
        if(this.calendar){
            return this.calendar.getEventById(this.getEventId(date));
        }
    }
    //--------------------------------------------------------------------------------
    removeEvent(date){

        //remove from cache arr
        delete this.event_arr[this.getEventId(date)];

        //remove from calendar
        this.calendar.getEventById(this.getEventId(date)).remove();

        //remove input
         $('#'+this.options.id+'\\\['+core.form.parse_js_date(date)+'\\\]').remove();
    }
    //--------------------------------------------------------------------------------
    getEventData(date){
        return this.event_arr[this.getEventId(date)];
    }
    //--------------------------------------------------------------------------------
    hasEvent(date){
        let eventData = this.getEventData(date)
        return !!eventData;
    }
    //--------------------------------------------------------------------------------
    isDisabledDate(date){
        let eventData = this.event_arr[this.getEventId(date)];
        return (eventData && eventData.disabled);
    }
    //--------------------------------------------------------------------------------
    ajax(url, options){

        if(!url) return;

        options = $.extend({
            url: url,
            method: 'POST',
            data:{},
            beforeSend:function(){
                $('.calendar-loader').show();
            },
        }, (options === undefined ? {} : options));

        this.event_arr = {};
        let instance = this;

        instance.options.events = function( fetchInfo, successCallback, failureCallback ) {
            options.data['start'] = core.form.parse_js_date(fetchInfo.start);
            options.data['end'] = core.form.parse_js_date(fetchInfo.end);
            core.ajax.request_function(url, function(response){

                instance.calendar.removeAllEvents();

                $.each(response, function(index, event){
                    event = $.extend({
                        eventSource: 'ajax',
                        forceRender: false,
                    }, event);
                    instance.addEvent(event.date, event);
                });

                instance.initRepeatingEvents();

                successCallback(Object.keys(instance.event_arr).map((key) => instance.event_arr[key]));

                $('.calendar-loader').fadeOut();

            }, options);
        }
    }
    //--------------------------------------------------------------------------------
    addEvent(date, options){

        date = new Date(date);

        if(this.hasEvent(date)) return;

        let id = this.getEventId(date);
        let eventData = $.extend({
            id:id,
            start:date,
            end:date,
            eventSource:'new',
            allDay:true,
            display:'background',
            disabled:false,
            forceRender:true,
        }, (options === undefined ? {} : options));

        if(eventData.disabled && !eventData.classNames){
            eventData.classNames = [this.options.disabledDayColor, "disabled"];
        }

        this.event_arr[id] = eventData;
        if(this.calendar && eventData.forceRender) this.calendar.addEvent(eventData);

        if(eventData.eventSource === "new"){
            let eventDate = core.form.parse_js_date(date);
            $('<input>').attr({
                type: 'hidden',
                id: this.options.id+'['+eventDate+']',
                name: this.options.id+'['+eventDate+']',
                value: eventDate,
            }).appendTo('.calendar-input-wrapper[data-id='+this.options.id+']');
        }

        return eventData;
    }
    //--------------------------------------------------------------------------------
    load(){

        let instance = this;

        instance.options.select = function(selectionInfo) {
            let selectedDate = selectionInfo.start;
            instance.addEvent(selectedDate, {
                eventSource:'new',
                classNames:[instance.options.selectDayColor],
            });
        };

        instance.options.selectAllow = function (info) {
            let data = instance.isDisabledDate(info.start);
            if(!data) return true;
        };

        instance.options.selectOverlap = function(event) {
            let selectedDate = event.start;
            let eventData = instance.getEventData(selectedDate);

            instance.calendar.unselect();

            if(eventData && eventData.eventSource !== 'new') return;
            if(eventData && eventData.disabled) return;
            if(eventData && eventData.eventSource === 'new'){
                instance.removeEvent(selectedDate);
                return false;
            }
        };

        instance.options.validRange = function (nowDate) {
            return {
                start: nowDate
            };
        };

        $.getScript('https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js', function() {
            $.getScript('https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap5@6.1.8/index.global.min.js', function() {

                $('.calendar-loader').fadeOut();

                instance.calendar = new FullCalendar.Calendar($('#'+instance.options.id)[0], instance.options);
                instance.calendar.render();
                instance.init();

                core.workspace.calender_assets_loaded = true;
            });
        });
    }
    //--------------------------------------------------------------------------------
}

