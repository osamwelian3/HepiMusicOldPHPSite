$(document).ready(function() {

    newDate = new Date()
    monthArray = [ '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12' ]

    function getDynamicMonth( monthOrder ) {
        var getNumericMonth = parseInt(monthArray[newDate.getMonth()]);
        var getNumericMonthInc = parseInt(monthArray[newDate.getMonth()]) + 1;
        var getNumericMonthDec = parseInt(monthArray[newDate.getMonth()]) - 1;

        if (monthOrder === 'default') {

            if (getNumericMonth < 10 ) {
                return '0' + getNumericMonth;
            } else if (getNumericMonth >= 10) {
                return getNumericMonth;
            }

        } else if (monthOrder === 'inc') {

            if (getNumericMonthInc < 10 ) {
                return '0' + getNumericMonthInc;
            } else if (getNumericMonthInc >= 10) {
                return getNumericMonthInc;
            }

        } else if (monthOrder === 'dec') {

            if (getNumericMonthDec < 10 ) {
                return '0' + getNumericMonthDec;
            } else if (getNumericMonthDec >= 10) {
                return getNumericMonthDec;
            }
        }
    }

    /* initialize the calendar
    -----------------------------------------------------------------*/

    var calendar = $('#calendar').fullCalendar({
        height: 450,
        contentHeight: 600,
        defaultView: 'month',
        timeFormat: 'H(:mm)',
        timeZone: timeZone,
        header: {
            left: 'prev,next',
            center: 'title',
            right: 'today'  //'month,agendaWeek,agendaDay'
        },
        events: function( start, end, timezone, callback ) { //include the parameters fullCalendar supplies to you!
            var eventsDisplay = [];
            jQuery.ajax({
                url: getCustomerListUrl,
                type: 'GET',
                dataType: 'json',
                success: function (result) {
                  if(result.status){                        
                        $.each(result.allData, function (key, row) {
                            eventsDisplay.push({
                                id: row.appointment_id,
                                title: row.title_small,                                
                                start: row.date_time,
                                end: row.date_time,
                                className: row.bg_type,
                                editable: false
                            });
                        });                     
                    }
                    callback(eventsDisplay); //you have to pass the list of events to fullCalendar!
                }
            });
        },
        eventClick: function(info) {            
            if(info.id){
                $('#view-modal .task_id').val(info.id);                
                jQuery.ajax({
                    url: getCustomerListUrl+'?appointment_id='+info.id,
                    type: 'GET',
                    dataType: 'json',
                    success: function (result) {
                        if(result.status){
                            $('#view-modal .appointment_id').val(result.allData.appointment_id);
                            $('#view-modal .appo_date').html(result.allData.appo_date);
                            $('#view-modal .appo_time').html(result.allData.appo_time);
                            $('#view-modal .title').html(result.allData.title);
                            $('#view-modal .note').html(result.allData.note);
                            $('#view-modal .status_name').html(result.allData.status_name);

                            $('#view-modal .product_name').html(result.allData.product_name);
                            $('#view-modal .brand_name').html(result.allData.brand_name);

                            $('#view-modal .customer_name').html(result.allData.customer_name);
                            $('#view-modal .customer_email').html(result.allData.customer_email);
                            $('#view-modal .customer_phone').html(result.allData.customer_phone);
                            $('#view-modal .customer_shop_address').html(result.allData.customer_shop_address);
                            
                            if(result.allData.status==1 || result.allData.status==2){
                                $('.status_cancel').show();
                            }else{
                                $('.status_cancel').hide();
                            }

                            if(result.allData.status==1){
                                $('.status_confirmed').show();
                            }else{
                                $('.status_confirmed').hide();
                            }
                            if(result.allData.status==2){
                                $('.status_complete').show();
                            }else{
                                $('.status_complete').hide();
                            }
                            $('#stars li.selected').removeClass('selected');
                            if(result.allData.status==3){
                                if(result.allData.rating){
                                    var onStar = parseInt(result.allData.rating);
                                    var stars = $('#stars li').parent().children('li.star');
                                    for (i = 0; i < onStar; i++) {
                                        $(stars[i]).addClass('selected');
                                    }
                                }
                                $('.rating_stars_div').show();
                            }else{
                                $('.rating_stars_div').hide();
                            }
                            $('#view-modal').modal('show');
                        }                 
                    }
                });
            }
        },        
        editable: true,
        eventLimit: true,       
    })

    function enableDatePicker() {

        var startDate = flatpickr(document.getElementById('start-date'), {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            minDate: new Date()
        });

        var abv = startDate.config.onChange.push(function(selectedDates, dateStr, instance) {

            var endtDate = flatpickr(document.getElementById('end-date'), {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                minDate: dateStr
            });
        })

        var endtDate = flatpickr(document.getElementById('end-date'), {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            minDate: new Date()
        });
    }


    function randomString(length, chars) {
        var result = '';
        for (var i = length; i > 0; --i) result += chars[Math.round(Math.random() * (chars.length - 1))];
        return result;
    }

    // Setting dynamic style ( padding ) of the highlited ( current ) date
    function setCurrentDateHighlightStyle() {
        getCurrentDate = $('.fc-content-skeleton .fc-today').attr('data-date');
        if (getCurrentDate === undefined) {
            return;
        }
        splitDate = getCurrentDate.split('-');
        if (splitDate[2] < 10) {
            $('.fc-content-skeleton .fc-today .fc-day-number').css('padding', '3px 8px');
        } else if (splitDate[2] >= 10) {
            $('.fc-content-skeleton .fc-today .fc-day-number').css('padding', '3px 4px');
        }
    }
    setCurrentDateHighlightStyle();

    const mailScroll = new PerfectScrollbar('.fc-scroller', {
        suppressScrollX : true
    });

    var fcButtons = document.getElementsByClassName('fc-button');
    for(var i = 0; i < fcButtons.length; i++) {
        fcButtons[i].addEventListener('click', function() {
            const mailScroll = new PerfectScrollbar('.fc-scroller', {
                suppressScrollX : true
            });
            $('.fc-scroller').animate({ scrollTop: 0 }, 100);
            setCurrentDateHighlightStyle();
        })
    }
});
