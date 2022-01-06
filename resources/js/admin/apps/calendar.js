"use strict";

$(document).ready(function() {
    $('#event-type input').on('click', function() {
        // remove status if not a holiday
        var val = $(this).val();
        var body = $(this).parents('.modal-body');
        var title = body.find('[name="title"]');

        if($('#event-status').length)
        {
            if(val == 'holiday') {
                $('#event-status').show();
                title.val('Holiday');
            } else {
                $('#event-status').hide();

                if(val == 'authorised-absence') {
                    title.val('Absent');
                } else {
                    title.val('Sick');
                }
            }
        }
    });

    $('#over1day').on('change', function(event) {
        if($(this).is(':checked')) {
            $('#show-end-date').show();
        } else {
            $('#show-end-date').hide();
        }
    });

    $('#edit-over1day').on('change', function(event) {
        if($(this).is(':checked')) {
            $('#show-edit-end-date').show();
        } else {
            $('#show-edit-end-date').hide();
        }
    });

    $('.response-action input').on('click',function(event) {
        var reason = $(this).parents('td').find('.reason');

        if($(this).val() == 'declined')
        {
            reason.show();
        }
        else
        {
            reason.hide();
        }
    });

    if(show_requests_modal) {
        $('#requestsModal').modal('show');
    }
});


!function (NioApp, $) {
  "use strict"; // Variable

  var $win = $(window),
      $body = $('body'),
      breaks = NioApp.Break; // Break={mb:420,sm:576,md:768,lg:992,xl:1200,xxl:1540,any:1/0}

  NioApp.Calendar = function () {
      var today = new Date();
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth() + 1).padStart(2, '0');
      var yyyy = today.getFullYear();
      var tomorrow = new Date(today);
      tomorrow.setDate(today.getDate() + 1);

      var t_dd = String(tomorrow.getDate()).padStart(2, '0');
      var t_mm = String(tomorrow.getMonth() + 1).padStart(2, '0');
      var t_yyyy = tomorrow.getFullYear();
      var yesterday = new Date(today);

      yesterday.setDate(today.getDate() - 1);
      var y_dd = String(yesterday.getDate()).padStart(2, '0');
      var y_mm = String(yesterday.getMonth() + 1).padStart(2, '0');
      var y_yyyy = yesterday.getFullYear();
      var YM = yyyy + '-' + mm;
      var YESTERDAY = y_yyyy + '-' + y_mm + '-' + y_dd;
      var TODAY = yyyy + '-' + mm + '-' + dd;
      var TOMORROW = t_yyyy + '-' + t_mm + '-' + t_dd;

      var month = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
      var calendarEl = document.getElementById('calendar');
      var eventsEl = document.getElementById('externalEvents');
      var removeEvent = document.getElementById('removeEvent');
      var addEventBtn = $('#addEvent');
      var addEventForm = $('#addEventForm');
      var addEventPopup = $('#addEventPopup');
      var updateEventBtn = $('#updateEvent');
      var editEventForm = $('#editEventForm');
      var editEventPopup = $('#editEventPopup');
      var previewEventPopup = $('#previewEventPopup');
      var deleteEventBtn = $('#deleteEvent');
      var mobileView = NioApp.Win.width < NioApp.Break.md ? true : false; //Win={height:n.height(),width:n.outerWidth()}
      var calendar = new FullCalendar.Calendar(calendarEl, {
          timeZone: 'GMT',
          initialView: mobileView ? 'listWeek' : 'dayGridMonth',
          themeSystem: 'bootstrap',
          headerToolbar: {
              left: 'title prev,next',
              center: null,
              right: 'today dayGridMonth,timeGridWeek,timeGridDay,listWeek'
          },
          height: 800,
          contentHeight: 780,
          aspectRatio: 3,
          editable: true,
          droppable: true,
          views: {
              dayGridMonth: {
                  dayMaxEventRows: 2
              }
          },
          direction: NioApp.State.isRTL ? "rtl" : "ltr",
          nowIndicator: true,
          now: TODAY + 'T09:25:00',
          eventDragStart: function eventDragStart(info) {
              $('.popover').popover('hide');
          },
          eventMouseEnter: function eventMouseEnter(info) {
              $(info.el).popover({
                  template: '<div class="popover"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>',
                  title: info.event._def.title,
                  content: info.event._def.extendedProps.description,
                  placement: 'top'
              });
              info.event._def.extendedProps.description ? $(info.el).popover('show') : $(info.el).popover('hide');
          },
          eventMouseLeave: function eventMouseLeave(info) {
              $(info.el).popover('hide');
          },
          eventDrop: function(info) {
              var editTitle = info.event._def.extendedProps.editTitle;
              var description = info.event._def.extendedProps.description;
              var start_type = info.event._def.extendedProps.start_type ? info.event._def.extendedProps.start_type : 'full_day';
              var end_type = info.event._def.extendedProps.end_type ? info.event._def.extendedProps.end_type : 'full_day';
              var type = info.event._def.extendedProps.type;
              var user_id = info.event._def.extendedProps.user_id;
              var start = info.event._instance.range.start;
              var startDate = start.getFullYear() + '-' + String(start.getMonth() + 1).padStart(2, '0') + '-' + String(start.getDate()).padStart(2, '0');
              var startTime = start.toUTCString().split(' ');
              startTime = startTime[startTime.length - 2];
              //startTime = startTime == '00:00:00' ? '' : startTime;
              var end = info.event._instance.range.end;
              var endDate = end.getFullYear() + '-' + String(end.getMonth() + 1).padStart(2, '0') + '-' + String(end.getDate()).padStart(2, '0');
              var endTime = end.toUTCString().split(' ');
              endTime = endTime[endTime.length - 2];
              endTime = endTime == '00:00:00' ? '' : endTime;
              var className = info.event._def.ui.classNames[0].slice(3);
              var eventId = info.event._def.publicId; //Set data in edit form

              // Edit Form
              $('#edit-event-title').val(editTitle);
              $('#edit-event-start-date').val(startDate).datepicker('update');
              $('#edit-event-end-date').val(endDate).datepicker('update');
              //$('#edit-event-start-time').val(startTime);
              //$('#edit-event-end-time').val(endTime);
              $('#edit-event-start-type').val(start_type);
              $('#edit-event-end-type').val(end_type);
              $('#edit-event-description').val(description);
              $('#edit-event-theme').val(className);
              $('#edit-event-id').val(eventId);
              $('#edit-event-type :input[value="' + type + '"]').prop('checked',true);
              $('#edit-user-id').val(user_id).trigger('change.select2');

              // Change the event
              updateEventBtn.trigger('click');

          },
          eventClick: function eventClick(info) {
              // Get data
              var is_background_event = info.event._def.ui.display == 'background' ? true : false;

              if(!is_background_event)
              {
                  var title = info.event._def.title;
                  var editTitle = info.event._def.extendedProps.editTitle ? info.event._def.extendedProps.editTitle : title;
                  var description = info.event._def.extendedProps.description;
                  var start_type = info.event._def.extendedProps.start_type ? info.event._def.extendedProps.start_type : 'full_day';
                  var end_type = info.event._def.extendedProps.end_type ? info.event._def.extendedProps.end_type : 'full_day';
                  var type = info.event._def.extendedProps.type;
                  var user_id = info.event._def.extendedProps.user_id;
                  var start = info.event._instance.range.start;
                  var startDate = start.getFullYear() + '-' + String(start.getMonth() + 1).padStart(2, '0') + '-' + String(start.getDate()).padStart(2, '0');
                  var startTime = start.toUTCString().split(' ');
                  startTime = startTime[startTime.length - 2];
                  //startTime = startTime == '00:00:00' ? '' : startTime;
                  var end = info.event._instance.range.end;
                  var endDate = end.getFullYear() + '-' + String(end.getMonth() + 1).padStart(2, '0') + '-' + String(end.getDate()).padStart(2, '0');
                  var endTime = end.toUTCString().split(' ');
                  endTime = endTime[endTime.length - 2];
                  endTime = endTime == '00:00:00' ? '' : endTime;
                  var className = info.event._def.ui.classNames[0].slice(3);
                  var eventId = info.event._def.publicId; //Set data in edit form

                  // Edit Form
                  $('#edit-event-title').val(editTitle);
                  $('#edit-event-start-date').val(startDate).datepicker('update');
                  $('#edit-event-end-date').val(endDate).datepicker('update');
                  //$('#edit-event-start-time').val(startTime);
                  //$('#edit-event-end-time').val(endTime);
                  $('#edit-event-start-type').val(start_type);
                  $('#edit-event-end-type').val(end_type);
                  $('#edit-event-description').val(description);
                  $('#edit-event-id').val(eventId);
                  $('#edit-event-type :input[value="' + type + '"]').prop('checked',true);
                  $('#edit-user-id').val(user_id).trigger('change.select2');

                  // Preview Event
                  editEventForm.attr('data-id', eventId); // Set data in preview
                  var previewStart = String(start.getDate()).padStart(2, '0') + ' ' + month[start.getMonth()] + ' ' + start.getFullYear() + (startTime ? ' - ' + to12(startTime) : '');
                  var previewEnd = String(end.getDate()).padStart(2, '0') + ' ' + month[end.getMonth()] + ' ' + end.getFullYear() + (endTime ? ' - ' + to12(endTime) : '');
                  $('#preview-event-title').text(title);
                  $('#preview-event-header').addClass('fc-' + className);
                  $('#preview-event-start').text(previewStart);
                  $('#preview-event-end').text(previewEnd);
                  $('#preview-event-description').text(description);
                  !description ? $('#preview-event-description-check').css('display', 'none') : null;
                  previewEventPopup.modal('show');
                  $('.popover').popover('hide');
              }

          },
          events: '/fetch-calendar/' + user_id
      });

      calendar.render(); //Add event

      addEventBtn.on("click", function (e) {
          e.preventDefault();
          var button_text = $(this).text();
          addEventBtn.text('...please wait').attr('disabled','disabled');

          var pending = false;
          if(!$('#event-status').length && $('#event-type [name="type"]').val() == 'holiday')
          {
              pending = true;
          }

          jQuery.ajax({
              method : 'POST',
              url : addEventForm.attr('action'),
              data : addEventForm.serialize() + '&_token=' + $('[name="csrf-token"]').attr('content'),
              success : function(response) {
                  refreshToken();
                  if(response.success)
                  {
                      calendar.refetchEvents();

                      addEventBtn.removeAttr('disabled').text(button_text);
                      addEventPopup.modal('hide');

                      if(pending) {
                          Swal.fire({
                              icon: 'success',
                              title: 'Sorted',
                              text: 'Your holiday request has been sent for approval, you will be notified once request has been looked into.',
                              //footer: '<a href>Why do I have this issue?</a>'
                          });
                      }

                  }
                  else
                  {
                      Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: message ? message : 'Error adding to calendar.',
                          //footer: '<a href>Why do I have this issue?</a>'
                      });
                  }
              },
              error : function(XHR, textStatus, error) {
                  if(XHR.status === 422) {
                      var response = XHR.responseJSON;
                      var errors = response.errors;
                      var message = 'You have errors in your form.';
                      $.each( errors, function( key, value ) {
                          $('#addEventPopup [name="'+ key +'"]').parent().append('<span id="'+ key +'-error" class="invalid">This field is required.</span>');
                          message += value;
                      });
                  }
                  Swal.fire({
                      icon: 'error',
                      title: 'Oops...',
                      text: message ? message : 'Your session may have expired, please re-fresh your page and try again.',
                      //footer: '<a href>Why do I have this issue?</a>'
                  });

                  addEventBtn.removeAttr('disabled').text(button_text);
              }
          })


      });

      updateEventBtn.on("click", function (e) {
          e.preventDefault();

          var button_text = $(this).text();
          updateEventBtn.text('...please wait').attr('disabled','disabled');

          var eventId = $('#edit-event-id').val();

          jQuery.ajax({
              method : 'POST',
              url : editEventForm.attr('action') + '/' + eventId,
              data : editEventForm.serialize() + '&_token=' + $('[name="csrf-token"]').attr('content'),
              success : function(response) {
                  refreshToken();
                  if(response.success)
                  {
                      calendar.refetchEvents();

                      updateEventBtn.removeAttr('disabled').text(button_text);
                      editEventPopup.modal('hide');
                  }
                  else
                  {
                      Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: message ? message : 'Error updating calendar.',
                          //footer: '<a href>Why do I have this issue?</a>'
                      });
                  }
              },
              error : function(XHR, textStatus, error) {
                  if(XHR.status === 422) {
                      var response = XHR.responseJSON;
                      var errors = response.errors;
                      var message = 'You have errors in your form.';
                      $.each( errors, function( key, value ) {
                          $('#addEventPopup [name="'+ key +'"]').parent().append('<span id="'+ key +'-error" class="invalid">This field is required.</span>');
                          message += value;
                      });
                  }
                  Swal.fire({
                      icon: 'error',
                      title: 'Oops...',
                      text: message ? message : 'Your session may have expired, please re-fresh your page and try again.',
                      //footer: '<a href>Why do I have this issue?</a>'
                  });

                  updateEventBtn.removeAttr('disabled').text(button_text);
              }
          })
      });

      deleteEventBtn.on("click", function (e) {
          e.preventDefault();

          var url = $('#delete-event-url').val();
          var event_id = $('#edit-event-id').val();
          url = url + '/'+ event_id;

          var selectEvent = calendar.getEventById(editEventForm[0].dataset.id);

          if(event_id)
          {
              $.ajax({
                  method : 'POST',
                  url : url,
                  data : '_method=delete&_token=' + $('[name="csrf-token"]').attr('content'),
                  success : function(response) {
                      refreshToken();
                      if(response.success)
                      {
                          selectEvent.remove();
                      }
                      else
                      {
                          Swal.fire({
                              icon: 'error',
                              title: 'Oops...',
                              text: response.message ? response.message : 'Error removing from calendar.',
                              //footer: '<a href>Why do I have this issue?</a>'
                          });
                      }
                  },
                  error : function(XHR, textStatus, error) {
                      if(XHR.status === 422) {
                          var response = XHR.responseJSON;
                          var errors = response.errors;
                          var message = 'You have errors in your form.';
                          $.each( errors, function( key, value ) {
                              $('#addEventPopup [name="'+ key +'"]').parent().append('<span id="'+ key +'-error" class="invalid">This field is required.</span>');
                              message += value;
                          });
                      }
                      Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: message ? message : 'Your session may have expired, please re-fresh your page and try again.',
                          //footer: '<a href>Why do I have this issue?</a>'
                      });
                  }
              })
          }
      });

      function refreshToken() {
          $.ajax({
              method : 'get',
              url: '/refresh-token',
              success : function(response)
              {
                  $('meta[name="csrf-token"]').attr('content',response.token);
                  console.log('New token ' + response.token);
              },
              error: function ()
              {
                  console.log('Failed to refresh token');
              }
          })
      }

      function to12(time) {
          time = time.toString().match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

          if (time.length > 1) {
              time = time.slice(1);
              time.pop();
              time[5] = +time[0] < 12 ? ' AM' : ' PM'; // Set AM/PM

              time[0] = +time[0] % 12 || 12;
          }

          time = time.join('');
          return time;
      }

      function customCalSelect(cat) {
          if (!cat.id) {
              return cat.text;
          }

          var $cat = $('<span class="fc-' + cat.element.value + '"> <span class="dot"></span>' + cat.text + '</span>');
          return $cat;
      }

      $(".select-calendar-theme").select2({
          templateResult: customCalSelect
      });

      addEventPopup.on('hidden.bs.modal', function (e) {
          setTimeout(function () {
              $('#addEventForm input[type="text"],#addEventForm textarea').val('');
              $('#event-theme').val('event-primary');
              $('#event-theme').trigger('change.select2');
          }, 1000);
      });

      previewEventPopup.on('hidden.bs.modal', function (e) {
          $('#preview-event-header').removeClass().addClass('modal-header');
      });
  };

  NioApp.coms.docReady.push(NioApp.Calendar);

}(NioApp, jQuery);
