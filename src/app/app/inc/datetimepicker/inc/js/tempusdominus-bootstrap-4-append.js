$.fn.datetimepicker.Constructor.Default = $.extend({}, $.fn.datetimepicker.Constructor.Default, {
  icons: {
      time: 'far fa-clock',
      date: 'fas fa-calendar',
      up: 'fas fa-arrow-up',
      down: 'fas fa-arrow-down',
      previous: 'fas fa-chevron-left',
      next: 'fas fa-chevron-right',
      today: 'fas fa-calendar-day',
      clear: 'fas fa-trash',
      close: 'fas fa-times'
  } });


moment.updateLocale('en', {
    week : {
        dow : 1 // Monday is the first day of the week
    }
});