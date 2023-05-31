(function () {
	scheduler.locale.labels.section_text = 'Name';
	scheduler.locale.labels.section_room = 'Room';
	scheduler.locale.labels.section_status = 'Status';
	scheduler.locale.labels.section_is_paid = 'Paid';
	scheduler.locale.labels.section_time = 'Time';
	scheduler.xy.scale_height = 30;
	scheduler.config.details_on_create = true;
	scheduler.config.details_on_dblclick = true;
	scheduler.config.prevent_cache = true;
	scheduler.config.show_loading = true;
	scheduler.config.xml_date = "%Y-%m-%d %H:%i";

	var roomsArr = scheduler.serverList("room");
	var roomTypesArr = scheduler.serverList("roomType");
	var roomStatusesArr = scheduler.serverList("roomStatus");
	var bookingStatusesArr = scheduler.serverList("bookingStatus");

	scheduler.config.lightbox.sections = [
		{map_to: "text", name: "text", type: "textarea", height: 24},
		{map_to: "room", name: "room", type: "select", options: scheduler.serverList("currentRooms")},
		{map_to: "status", name: "status", type: "radio", options: scheduler.serverList("bookingStatus")},
		{map_to: "is_paid", name: "is_paid", type: "checkbox", checked_value: true, unchecked_value: false},
		{map_to: "time", name: "time", type: "calendar_time"}
	];

	scheduler.locale.labels.timeline_tab = 'Day';
	scheduler.locale.labels.timeline_week_tab = 'Week';
	scheduler.locale.labels.timeline_month_tab = 'Month';

	scheduler.createTimelineView({
		fit_events: true,
		name: "timeline",
		y_property: "room",
		render: 'bar',
		x_unit: "hour",
		x_date: "%H:%i",
		x_size: 24,
		x_step:1,
		dy: 52,
		event_dy: 48,
		section_autoheight: false,
		round_position: true,
		y_unit: scheduler.serverList("currentRooms"),
		
	});

	scheduler.createTimelineView({
		fit_events: true,
		name: "timeline_week",
		y_property: "room",
		render: 'bar',
		x_unit: "day",
		x_date: "%D, %d %F",
		x_size: 7,
		dy: 52,
		event_dy: 48,
		section_autoheight: false,
		round_position: true,

		y_unit: scheduler.serverList("currentRooms"),
		
	});

	scheduler.createTimelineView({
		fit_events: true,
		name: "timeline_month",
		y_property: "room",
		render: 'bar',
		x_unit: "day",
		x_date: "%d",
		// x_size: 7,
		dy: 52,
		event_dy: 48,
		section_autoheight: false,
		round_position: true,

		y_unit: scheduler.serverList("currentRooms"),
		second_scale: {
			x_unit: "month",
			x_date: "%F %Y"
		}
	});

	function findInArray(array, key) {
		for (var i = 0; i < array.length; i++) {
			if (key == array[i].key)
				return array[i];
		}
		return null;
	}

	function getRoomType(key) {
		return findInArray(roomTypesArr, key).label;
	}

	function getRoomStatus(key) {
		return findInArray(roomStatusesArr, key);
	}

	function getRoom(key) {
		return findInArray(roomsArr, key);
	}

	 // $('.dhx_cal_tab[name="timeline_week_tab"]').click( function () {
  //           var currentdate = scheduler.getState().date;
  //           scheduler.setCurrentView( currentdate, "timeline_week"); 
  //           scheduler.date.timeline_start = scheduler.date.week_start;
  //           console.log('week tab');
  //           scheduler.date.add_timeline = function (date, step) {
  //           return scheduler.date.add(date, step, "week");
		// } 
  //   });

	 // $('.dhx_cal_tab[name="timeline_tab"]').click( function () {
  //           var currentdate = scheduler.getState().date;
  //           scheduler.setCurrentView( currentdate, "timeline");
  //           scheduler.date.timeline_start = scheduler.date.day_start;
  //           console.log('day tab');
  //           scheduler.date.add_timeline = function (date, step) {
            	
		// 	return scheduler.date.add(date, step, "day");
		// } 
  //   });

	// $('.dhx_cal_tab[name="timeline_month_tab"]').click( function () {
	// 		var currentdate = scheduler.getState().date;
 //            scheduler.setCurrentView( currentdate, "timeline_month");
 //            scheduler.date.timeline_start = scheduler.date.month_start;
 //            console.log("helo1");
 //            scheduler.date.add_timeline = function (date, step) {
	// 		return scheduler.date.add(date, step, "month");
	// 	};

	// 	scheduler.attachEvent("onBeforeViewChange", function (old_mode, old_date, mode, date) {
	// 		var year = date.getFullYear();
	// 		var month = (date.getMonth() + 1);
	// 		var d = new Date(year, month, 0);
	// 		var daysInMonth = d.getDate();
	// 		scheduler.matrix["timeline_month"].x_size = daysInMonth;
	// 		console.log(daysInMonth);
	// 		return true;
	// 		}); 
	// });

	scheduler.templates.timeline_scale_label = function (key, label, section) {
		//var roomStatus = getRoomStatus(section.status);
		return [,
			"<div class='dhx_matrix_scell' style='border:none'>" + label + "</div>"
			//"<div class='timeline_item_separator'></div>",
			//"<div class='timeline_item_cell'>" + getRoomType(section.type) + "</div>",
			//"<div class='timeline_item_separator'></div>",
			//"<div class='timeline_item_cell room_status'>",
			//"<span class='room_status_indicator room_status_indicator_" + roomStatus.key + "'></span>",
			//"<span class='status-label'>" + roomStatus.label + "</span>",
			//"</div>"
			].join("");
	};

	scheduler.attachEvent("onBeforeViewChange", function (old_mode, old_date, mode, date) {
			
		var year = date.getFullYear();
		var month = (date.getMonth() + 1);
		var d = new Date(year, month, 0);
		var daysInMonth = d.getDate();
		scheduler.matrix["timeline_month"].x_size = daysInMonth;
		scheduler.date.timeline_month_start = scheduler.date.month_start;
		scheduler.date.timeline_week_start = scheduler.date.week_start;
		return true;
		}); 


	scheduler.date.timeline_start = scheduler.date.day_start;
	scheduler.date.add_timeline = function (date, step) {
		return scheduler.date.add(date, step, "day");
	};

	scheduler.templates.event_class = function (start, end, event) {
		return "event_" + (event.status || "");
	};

	function getBookingStatus(key) {
		var bookingStatus = findInArray(bookingStatusesArr, key);
		return !bookingStatus ? '' : bookingStatus.label;
	}

	function getPaidStatus(isPaid) {
		return isPaid ? "paid" : "not paid";
	}

	var eventDateFormat = scheduler.date.date_to_str("%d %M %Y %H:%i:%s");
	scheduler.templates.event_bar_text = function (start, end, event) {
		var paidStatus = getPaidStatus(event.is_paid);
		var startDate = eventDateFormat(event.start_date);
		var endDate = eventDateFormat(event.end_date);
		var room = getRoom(event.room); //added
		return ["Booking: "+event.text +" Room: "+room.label+ "<br />",
			startDate + " - " + endDate,
			"<div class='booking_status booking-option'>" + getBookingStatus(event.status) + "</div>"].join("");
	};

	scheduler.templates.tooltip_text = function (start, end, event) {
		var room = getRoom(event.room) || {label: ""};

		var html = [];
		html.push("Booking ID: <b>" + event.text + "</b>");
		html.push("Room: <b>" + room.label + "</b>");
		html.push("Check-in: <b>" + eventDateFormat(start) + "</b>");
		html.push("Check-out: <b>" + eventDateFormat(end) + "</b>");
		// html.push(getBookingStatus(event.status) + ", " + getPaidStatus(event.is_paid));
		return html.join("<br>")
	};

	scheduler.templates.lightbox_header = function (start, end, ev) {
		var formatFunc = scheduler.date.date_to_str('%d.%m.%Y');
		return formatFunc(start) + " - " + formatFunc(end);
	};

	scheduler.attachEvent("onEventCollision", function (ev, evs) {
		for (var i = 0; i < evs.length; i++) {
			if (ev.room != evs[i].room) continue;
			dhtmlx.message({
				type: "error",
				text: "This room is already booked for this date."
			});
		}
		return true;
	});

	scheduler.attachEvent('onEventCreated', function (event_id) {
		var ev = scheduler.getEvent(event_id);
		ev.status = 1;
		ev.is_paid = false;
		ev.text = 'new booking';
	});

	scheduler.addMarkedTimespan({days: [0, 6], zones: "fullday", css: "timeline_weekend"});

	window.updateSections = function updateSections(value) {
		var currentRoomsArr = [];
		if (value == 'all') {
			scheduler.updateCollection("currentRooms", roomsArr.slice());
			return
		}
		for (var i = 0; i < roomsArr.length; i++) {
			if (value == roomsArr[i].type) {
				currentRoomsArr.push(roomsArr[i]);
			}
		}
		scheduler.updateCollection("currentRooms", currentRoomsArr);
	};

	scheduler.attachEvent("onXLE", function () {
		updateSections("all");

		var select = document.getElementById("room_filter");
		var selectHTML = ["<option value='all'>All</option>"];
		for (var i = 1; i < roomTypesArr.length + 1; i++) {
			selectHTML.push("<option value='" + i + "'>" + getRoomType(i) + "</option>");
		}
		select.innerHTML = selectHTML.join("");
	});

	scheduler.attachEvent("onEventSave", function (id, ev, is_new) {
		if (!ev.booking_id) {
			dhtmlx.alert("Text must not be empty");
			return false;
		}
		return true;
	});

})();

function init() {
	scheduler.init('scheduler_here', new Date(), "timeline");
	scheduler.load("/data", "json");
	window.dp = new dataProcessor("/data");
	dp.init(scheduler);
	dp.setTransactionMode("REST");


	(function () {
		var element = document.getElementById("scheduler_here");
		var top = scheduler.xy.nav_height + 1 + 1;// first +1 -- blank space upper border, second +1 -- hardcoded border length
		var height = scheduler.xy.scale_height;
		var width = scheduler.matrix.timeline.dx;
		var header = document.createElement("div");
		header.className = "collection_label";
		header.style.position = "absolute";
		header.style.top = top + "px";
		header.style.width = width + "px";
		header.style.height = height + "px";

		var descriptionHTML = "<div class='timeline_item_separator'></div>" +
			"<div class='dhx_matrix_scell' style=\"padding-top: 5px;border-bottom:none;\">Number</div>";
			//"<div class='timeline_item_separator'></div>" +
			//"<div class='timeline_item_cell'>Type</div>" +
			//"<div class='timeline_item_separator'></div>" +
			//"<div class='timeline_item_cell room_status'>Status</div>";
		header.innerHTML = descriptionHTML;
		element.appendChild(header);
	})();
}
