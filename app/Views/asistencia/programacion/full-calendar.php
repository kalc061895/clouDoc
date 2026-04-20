<div class="d-md-flex align-items-center justify-content-between mb-7">
    <div class="mb-4 mb-md-0">
        <h4 class="fs-6 mb-0">Calendar</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a class="text-muted text-decoration-none" href="index.html">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Calendar</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex align-items-center justify-content-between gap-6">
        <select class="form-select border fs-3" aria-label="Default select example">
            <option selected>November 2024</option>
            <option value="1">February 2024</option>
            <option value="2">March 2024</option>
            <option value="3">April 2024</option>
        </select>
        <button class="btn btn-success d-flex align-items-center gap-1 fs-3 py-2 px-9">
            <i class="ti ti-plus fs-4"></i>
            Create
        </button>
    </div>
</div>
<div class="card">
    <div class="card-body calender-sidebar app-calendar">
        <div id="calendar"></div>
    </div>
</div>
<!-- BEGIN MODAL -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">
                    Add / Edit Event
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div>
                            <label class="form-label">Event Title</label>
                            <input id="event-title" type="text" class="form-control" />
                        </div>
                    </div>
                    <div class="col-md-12 mt-6">
                        <div>
                            <label class="form-label">Event Color</label>
                        </div>
                        <div class="d-flex">
                            <div class="n-chk">
                                <div class="form-check form-check-primary form-check-inline">
                                    <input class="form-check-input" type="radio" name="event-level" value="Danger" id="modalDanger" />
                                    <label class="form-check-label" for="modalDanger">Danger</label>
                                </div>
                            </div>
                            <div class="n-chk">
                                <div class="form-check form-check-warning form-check-inline">
                                    <input class="form-check-input" type="radio" name="event-level" value="Success" id="modalSuccess" />
                                    <label class="form-check-label" for="modalSuccess">Success</label>
                                </div>
                            </div>
                            <div class="n-chk">
                                <div class="form-check form-check-success form-check-inline">
                                    <input class="form-check-input" type="radio" name="event-level" value="Primary" id="modalPrimary" />
                                    <label class="form-check-label" for="modalPrimary">Primary</label>
                                </div>
                            </div>
                            <div class="n-chk">
                                <div class="form-check form-check-danger form-check-inline">
                                    <input class="form-check-input" type="radio" name="event-level" value="Warning" id="modalWarning" />
                                    <label class="form-check-label" for="modalWarning">Warning</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-6">
                        <div>
                            <label class="form-label">Enter Start Date</label>
                            <input id="event-start-date" type="date" class="form-control" />
                        </div>
                    </div>

                    <div class="col-md-12 mt-6">
                        <div>
                            <label class="form-label">Enter End Date</label>
                            <input id="event-end-date" type="date" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-danger-subtle text-danger" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn btn-success btn-update-event" data-fc-event-public-id="">
                    Update changes
                </button>
                <button type="button" class="btn btn-primary btn-add-event">
                    Add Event
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    /*========Calender Js=========*/
    /*==========================*/
    loadCalendar();
    function loadCalendar() {
        /*=================*/
        //  Calender Date variable
        /*=================*/
        var newDate = new Date();

        function getDynamicMonth() {
            getMonthValue = newDate.getMonth();
            _getUpdatedMonthValue = getMonthValue + 1;
            if (_getUpdatedMonthValue < 10) {
                return `0${_getUpdatedMonthValue}`;
            } else {
                return `${_getUpdatedMonthValue}`;
            }
        }
        /*=================*/
        // Calender Modal Elements
        /*=================*/
        var getModalTitleEl = document.querySelector("#event-title");
        var getModalStartDateEl = document.querySelector("#event-start-date");
        var getModalEndDateEl = document.querySelector("#event-end-date");
        var getModalAddBtnEl = document.querySelector(".btn-add-event");
        var getModalUpdateBtnEl = document.querySelector(".btn-update-event");
        var calendarsEvents = {
            Danger: "danger",
            Success: "success",
            Primary: "primary",
            Warning: "warning",
        };
        /*=====================*/
        // Calendar Elements and options
        /*=====================*/
        var calendarEl = document.querySelector("#calendar");
        var checkWidowWidth = function() {
            if (window.innerWidth <= 1199) {
                return true;
            } else {
                return false;
            }
        };
        var calendarHeaderToolbar = {
            left: "prev next addEventButton",
            center: "title",
            right: "dayGridMonth,timeGridWeek,timeGridDay",
        };
        var calendarEventsList = [{
                id: 1,
                title: "Event Conf.",
                start: `${newDate.getFullYear()}-${getDynamicMonth()}-01`,
                extendedProps: {
                    calendar: "Danger"
                },
            },
            {
                id: 2,
                title: "Seminar #4",
                start: `${newDate.getFullYear()}-${getDynamicMonth()}-07`,
                end: `${newDate.getFullYear()}-${getDynamicMonth()}-10`,
                extendedProps: {
                    calendar: "Success"
                },
            },
            {
                groupId: "999",
                id: 3,
                title: "Meeting #5",
                start: `${newDate.getFullYear()}-${getDynamicMonth()}-09T16:00:00`,
                extendedProps: {
                    calendar: "Primary"
                },
            },
            {
                groupId: "999",
                id: 4,
                title: "Submission #1",
                start: `${newDate.getFullYear()}-${getDynamicMonth()}-16T16:00:00`,
                extendedProps: {
                    calendar: "Warning"
                },
            },
            {
                id: 5,
                title: "Seminar #6",
                start: `${newDate.getFullYear()}-${getDynamicMonth()}-11`,
                end: `${newDate.getFullYear()}-${getDynamicMonth()}-13`,
                extendedProps: {
                    calendar: "Danger"
                },
            },
            {
                id: 6,
                title: "Meeting 3",
                start: `${newDate.getFullYear()}-${getDynamicMonth()}-12T10:30:00`,
                end: `${newDate.getFullYear()}-${getDynamicMonth()}-12T12:30:00`,
                extendedProps: {
                    calendar: "Success"
                },
            },
            {
                id: 7,
                title: "Meetup #",
                start: `${newDate.getFullYear()}-${getDynamicMonth()}-12T12:00:00`,
                extendedProps: {
                    calendar: "Primary"
                },
            },
            {
                id: 8,
                title: "Submission",
                start: `${newDate.getFullYear()}-${getDynamicMonth()}-12T14:30:00`,
                extendedProps: {
                    calendar: "Warning"
                },
            },
            {
                id: 9,
                title: "Attend event",
                start: `${newDate.getFullYear()}-${getDynamicMonth()}-13T07:00:00`,
                extendedProps: {
                    calendar: "Success"
                },
            },
            {
                id: 10,
                title: "Project submission #2",
                start: `${newDate.getFullYear()}-${getDynamicMonth()}-28`,
                extendedProps: {
                    calendar: "Primary"
                },
            },
        ];
        /*=====================*/
        // Calendar Select fn.
        /*=====================*/
        var calendarSelect = function(info) {
            getModalAddBtnEl.style.display = "block";
            getModalUpdateBtnEl.style.display = "none";
            myModal.show();
            getModalStartDateEl.value = info.startStr;
            getModalEndDateEl.value = info.endStr;
        };
        /*=====================*/
        // Calendar AddEvent fn.
        /*=====================*/
        var calendarAddEvent = function() {
            var currentDate = new Date();
            var dd = String(currentDate.getDate()).padStart(2, "0");
            var mm = String(currentDate.getMonth() + 1).padStart(2, "0"); //January is 0!
            var yyyy = currentDate.getFullYear();
            var combineDate = `${yyyy}-${mm}-${dd}T00:00:00`;
            getModalAddBtnEl.style.display = "block";
            getModalUpdateBtnEl.style.display = "none";
            myModal.show();
            getModalStartDateEl.value = combineDate;
        };

        /*=====================*/
        // Calender Event Function
        /*=====================*/
        var calendarEventClick = function(info) {
            var eventObj = info.event;

            if (eventObj.url) {
                window.open(eventObj.url);

                info.jsEvent.preventDefault();
            } else {
                var getModalEventId = eventObj._def.publicId;
                var getModalEventLevel = eventObj._def.extendedProps["calendar"];
                var getModalCheckedRadioBtnEl = document.querySelector(
                    `input[value="${getModalEventLevel}"]`
                );

                getModalTitleEl.value = eventObj.title;
                getModalStartDateEl.value = eventObj.startStr.slice(0, 10);
                getModalEndDateEl.value = eventObj.endStr.slice(0, 10);
                getModalCheckedRadioBtnEl.checked = true;
                getModalUpdateBtnEl.setAttribute(
                    "data-fc-event-public-id",
                    getModalEventId
                );
                getModalAddBtnEl.style.display = "none";
                getModalUpdateBtnEl.style.display = "block";
                myModal.show();
            }
        };

        /*=====================*/
        // Active Calender
        /*=====================*/
        var calendar = new FullCalendar.Calendar(calendarEl, {
            selectable: true,
            height: checkWidowWidth() ? 900 : 1052,
            initialView: checkWidowWidth() ? "listWeek" : "dayGridMonth",
            initialDate: `${newDate.getFullYear()}-${getDynamicMonth()}-07`,
            headerToolbar: calendarHeaderToolbar,
            events: calendarEventsList,
            select: calendarSelect,
            unselect: function() {
                console.log("unselected");
            },
            customButtons: {
                addEventButton: {
                    text: "Add Event",
                    click: calendarAddEvent,
                },
            },
            eventClassNames: function({
                event: calendarEvent
            }) {
                const getColorValue =
                    calendarsEvents[calendarEvent._def.extendedProps.calendar];
                return ["event-fc-color fc-bg-" + getColorValue];
            },

            eventClick: calendarEventClick,
            windowResize: function(arg) {
                if (checkWidowWidth()) {
                    calendar.changeView("listWeek");
                    calendar.setOption("height", 900);
                } else {
                    calendar.changeView("dayGridMonth");
                    calendar.setOption("height", 1052);
                }
            },
        });

        /*=====================*/
        // Update Calender Event
        /*=====================*/
        getModalUpdateBtnEl.addEventListener("click", function() {
            var getPublicID = this.dataset.fcEventPublicId;
            var getTitleUpdatedValue = getModalTitleEl.value;
            var setModalStartDateValue = getModalStartDateEl.value;
            var setModalEndDateValue = getModalEndDateEl.value;
            var getEvent = calendar.getEventById(getPublicID);
            var getModalUpdatedCheckedRadioBtnEl = document.querySelector(
                'input[name="event-level"]:checked'
            );

            var getModalUpdatedCheckedRadioBtnValue =
                getModalUpdatedCheckedRadioBtnEl !== null ?
                getModalUpdatedCheckedRadioBtnEl.value :
                "";

            getEvent.setProp("title", getTitleUpdatedValue);
            getEvent.setDates(setModalStartDateValue, setModalEndDateValue);
            getEvent.setExtendedProp("calendar", getModalUpdatedCheckedRadioBtnValue);
            myModal.hide();
        });
        /*=====================*/
        // Add Calender Event
        /*=====================*/
        getModalAddBtnEl.addEventListener("click", function() {
            var getModalCheckedRadioBtnEl = document.querySelector(
                'input[name="event-level"]:checked'
            );

            var getTitleValue = getModalTitleEl.value;
            var setModalStartDateValue = getModalStartDateEl.value;
            var setModalEndDateValue = getModalEndDateEl.value;
            var getModalCheckedRadioBtnValue =
                getModalCheckedRadioBtnEl !== null ? getModalCheckedRadioBtnEl.value : "";

            calendar.addEvent({
                id: 12,
                title: getTitleValue,
                start: setModalStartDateValue,
                end: setModalEndDateValue,
                allDay: true,
                extendedProps: {
                    calendar: getModalCheckedRadioBtnValue
                },
            });
            myModal.hide();
        });
        /*=====================*/
        // Calendar Init
        /*=====================*/
        calendar.render();
        var myModal = new bootstrap.Modal(document.getElementById("eventModal"));
        var modalToggle = document.querySelector(".fc-addEventButton-button ");
        document
            .getElementById("eventModal")
            .addEventListener("hidden.bs.modal", function(event) {
                getModalTitleEl.value = "";
                getModalStartDateEl.value = "";
                getModalEndDateEl.value = "";
                var getModalIfCheckedRadioBtnEl = document.querySelector(
                    'input[name="event-level"]:checked'
                );
                if (getModalIfCheckedRadioBtnEl !== null) {
                    getModalIfCheckedRadioBtnEl.checked = false;
                }
            });
    }
    
</script>