$.extend( true, $.fn.dataTable.defaults, {
    "order": [[1,"asc"]],
    "columnDefs": [
        { responsivePriority: 1, targets: 1},
        { responsivePriority: 2, targets: 2},
        { responsivePriority: 3, targets: 0},
        { targets: 0, orderable: false}
    ],
    "autoWidth": true,
    responsive: true,
    paging: false,
//     "iDisplayLength": 500,
//     bLengthChange: false,
    dom: 'Bfrtip',
    buttons: [
        { extend: 'copy', className: 'btn btn-primary', text: '<i class="fa fa-copy"></i> Copy', exportOptions: {columns: ':not(.no-export)'} },
        { extend: 'excel', className: 'btn btn-primary', text: '<i class="fa fa-file-excel"></i> Excel', exportOptions: {columns: ':not(.no-export)'} },
        { extend: 'pdf', className: 'btn btn-primary', text: '<i class="fa fa-file-pdf"></i> PDF', exportOptions: {columns: ':not(.no-export)'}  },
        { extend: 'print', className: 'btn btn-primary', text: '<i class="fa fa-print"></i> Print', exportOptions: {columns: ':not(.no-export)'}  }
    ],
    fixedHeader: true
} );

// Calendar
var today = new Date();

const offset = today.getTimezoneOffset();
today = new Date(today.getTime() - (offset*60*1000));
today = today.toISOString().split('T')[0];


var validDates = ["2025-06-12", "2025-06-13", "2025-06-14", "2025-06-15"];
var theDate;
if(validDates.includes(today)) {
    theDate = today;
} else {
    theDate = "2025-06-12";
}

// *** Also have to update the dates below in validRange

document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'timeGridDay',
    googleCalendarApiKey: 'AIzaSyBEndjaolbHxZ7O8r6cx2pmyGwxoY_KoNk',
    events: {
      googleCalendarId: 'hobyohiosouth.org_kn2ogfi3d0ddfjef20h8hj7734@group.calendar.google.com'
    },
    height: 'auto',
    views: {
        timeGridDay: {
            slotDuration: '00:15:00',
            slotLabelInterval: '01:00:00',
            slotMinTime: '07:00:00'
        }
    },
    initialDate: theDate,
    validRange: {
        start: '2025-06-11',
        end: '2025-06-16'
    },
    eventDidMount: function(event) {
        // event.event.setProp('borderColor', '#fff');
        if ( event.event.extendedProps.description == "Meal") {
            event.event.setProp('backgroundColor', '#a10008');
        } else if ( event.event.extendedProps.description == "Panel/Speaker") {
           event.event.setProp('backgroundColor', '#1e2399');
        } else if ( event.event.extendedProps.description == "Activity") {
            event.event.setProp('backgroundColor', '#006d00');
        } else if ( event.event.extendedProps.description == "Breakout/Reflection") {
            event.event.setProp('backgroundColor', '#6a4d81');
        } else if ( event.event.extendedProps.description == "Ceremony") {
            event.event.setProp('backgroundColor', '#00a195');
        } else {
            event.event.setProp('backgroundColor', '#616161');
        } 
    },
    eventContent: function(event) {
        return {html:  ( event.event.extendedProps.location ) ? '<strong>' + event.event.title + '</strong> &#124; ' + event.event.extendedProps.location + ' &#124; <small>' + moment(event.event.start).format('h:mma') + '&ndash;' + moment(event.event.end).format('h:mma') + '</small>' : '<strong>' + event.event.title + '</strong> &#124; <small>' + moment(event.event.start).format('h:mma') + '&ndash;' + moment(event.event.end).format('h:mma') + '</small>'}
    }
  });
  
  calendar.render();
});

$(document).ready(function() {
    
    // SmartMenus init
    $('#main-menu').smartmenus({
        mainMenuSubOffsetX: -1,
        subMenusSubOffsetX: 10,
        subMenusSubOffsetY: 0
    });
    
    // SmartMenus mobile menu toggle button
    var $mainMenuState = $('#main-menu-state');
    if ($mainMenuState.length) {
        // animate mobile menu
        $mainMenuState.change(function(e) {
            var $menu = $('#main-menu');
            if (this.checked) {
                $menu.hide().slideDown(250, function() { $menu.css('display', ''); });
            } else {
                $menu.show().slideUp(250, function() { $menu.css('display', ''); });
            }
        });
        // hide mobile menu beforeunload
        $(window).bind('beforeunload unload', function() {
            if ($mainMenuState[0].checked) {
                $mainMenuState[0].click();
            }
        });
    }
       
    // Bootstrap tooltip
    $(function () {
      $('[data-toggle="tooltip"]').tooltip({container: 'body',html: true, placement: 'bottom'})
    })
    
    // DataTables

    $('table#dietary-table').DataTable({
        order: [
            [2, "asc"],
            [1, "asc"]
        ],
    });
    $('table#ec-table').DataTable({
        order: [
            [2, "asc"],
            [1, "asc"]
        ],
    }); 
    $('table#med-table').DataTable({
        "order": [
            [2, "asc"],
            [1, "asc"]
        ],
        "columnDefs": [
            { responsivePriority: 1, targets: 0},
            { responsivePriority: 2, targets: 2},
            { responsivePriority: 3, targets: 4},
            { responsivePriority: 4, targets: 5},
            { responsivePriority: 5, targets: 8},
        ]
    }); 
    $('table#ambassadors-table').DataTable({
        "order": [
            [2, "asc"],
            [1, "asc"]
        ],
        "columnDefs": [
            { responsivePriority: 1, targets: 1},
            { responsivePriority: 2, targets: 2},
            { responsivePriority: 3, targets: 0},
            { responsivePriority: 4, targets: 3},
        ],
    }); 
    $('table#applicant-table').DataTable({
        "order": [
            [2, "asc"],
            [1, "asc"]
        ],
        "columnDefs": [
            { responsivePriority: 1, targets: 1},
            { responsivePriority: 2, targets: 2},
            { responsivePriority: 3, targets: 0},
            { responsivePriority: 4, targets: 6},
            { responsivePriority: 5, targets: 4},
            { responsivePriority: 6, targets: 5},
            { responsivePriority: 7, targets: 3},
        ],
    }); 
    $('table#psm-table').DataTable({
        "order": [
            [2, "asc"],
            [1, "asc"]
        ],
        "columnDefs": [
            { responsivePriority: 1, targets: 1},
            { responsivePriority: 2, targets: 2},
            { responsivePriority: 3, targets: 0},
            { responsivePriority: 4, targets: 3},
        ],
    }); 
    $('table#calls-table').DataTable({
        "order": [
            [2, "asc"],
            [1, "asc"]
        ],
        "columnDefs": [
            { responsivePriority: 1, targets: 1},
            { responsivePriority: 2, targets: 2},
            { responsivePriority: 3, targets: 7},
            { responsivePriority: 4, targets: 8},
            { responsivePriority: 5, targets: 6},
            { responsivePriority: 6, targets: 5},
        ],
    }); 
    $('table#bus-table').DataTable({
        "order": [
            [2, "asc"],
            [1, "asc"]
        ],
        "columnDefs": [
            { responsivePriority: 1, targets: 2},
            { responsivePriority: 2, targets: 1},
            { responsivePriority: 3, targets: 0},
        ]
    }); 
    $('table#staff-req-table').DataTable({
        "order": [
            [2, "asc"],
            [1, "asc"]
        ],
        "columnDefs": [
            { responsivePriority: 1, targets: 1},
            { responsivePriority: 2, targets: 2},
            { responsivePriority: 3, targets: 0},
            { responsivePriority: 4, targets: 3},
        ]
    }); 
    $('table#letter-interview-table').DataTable({
        "order": [
            [1, "asc"]
        ],
        "columnDefs": [
            { responsivePriority: 1, targets: 1},
            { responsivePriority: 2, targets: 2},
            { responsivePriority: 3, targets: 0}
        ],
    });
    $('table#letter-index-table').DataTable({
        "order": [
            [1, "asc"]
        ],
        "columnDefs": [
            { responsivePriority: 1, targets: 0},
            { responsivePriority: 2, targets: 1},
            { responsivePriority: 3, targets: 3},
            { responsivePriority: 4, targets: 2},
        ],
    });
    /*
    $('table#applicant-table').DataTable({
        "order": [
            [3, "asc"],
            [2, "asc"]
        ],
        "columnDefs": [
            { responsivePriority: 1, targets: 0},
            { responsivePriority: 2, targets: 1},
            { responsivePriority: 3, targets: 2},
        ],
        buttons: [
            'excelHtml5',
            'pdfHtml5',
            'print'
        ]
    }); */
    $('table#bc-table').DataTable({
        "order": [
            [6, "asc"],
            [4, "asc"],
            [5, "asc"],
            [1, "asc"]
        ],
        "columnDefs": [
            { responsivePriority: 1, targets: 0},
            { responsivePriority: 2, targets: 1},
            { responsivePriority: 3, targets: 6},
            { responsivePriority: 4, targets: 7},
            { responsivePriority: 5, targets: 8},
            { responsivePriority: 6, targets: 9},
            { responsivePriority: 7, targets: 5},
            { responsivePriority: 8, targets: 2},
        ],
        buttons: [
            'excelHtml5',
            'pdfHtml5',
            'print'
        ]
    }); 
    $('table#kd-table').DataTable({
        "order": [
            [2, "asc"],
            [1, "asc"]
        ],
        "columnDefs": [
            { responsivePriority: 1, targets: 1},
            { responsivePriority: 2, targets: 2},
            { responsivePriority: 3, targets: 0},
            { responsivePriority: 4, targets: 3},
        ]
    }); 
    $('table#noshow-table').DataTable({
        "order": [
            [2, "asc"],
            [1, "asc"]
        ],
        "columnDefs": [
            { responsivePriority: 1, targets: 2},
            { responsivePriority: 2, targets: 1},
            { responsivePriority: 3, targets: 0},
        ],
    }); 
    $('table#checkin-table').DataTable({
        "order": [
            [1, "asc"],
            [0, "asc"]
        ],
        "columnDefs": [
            { responsivePriority: 1, targets: 0},
            { responsivePriority: 2, targets: 2},
            { responsivePriority: 3, targets: 1},
        ],
        buttons: []
    });
    $('table#checkout-table').DataTable({
        "order": [
            [1, "asc"],
            [0, "asc"]
        ],
        "columnDefs": [
            { responsivePriority: 1, targets: 0},
            { responsivePriority: 2, targets: 2},
            { responsivePriority: 3, targets: 1},
        ],
        buttons: []
    }); 
    $('table#staff-table').DataTable({
        "order": [
            [2, "asc"],
            [1, "asc"]
        ]
    });  /*
    $('table#staff-face-table').DataTable({
        "order": [
            [3, "asc"],
            [2, "asc"]
        ]
    }); 
    $('table#amb-face-table').DataTable({
        "order": [
            [3, "asc"],
            [2, "asc"]
        ]
    }); 
    $('table#amb-group-face-table').DataTable({
        "columnDefs": [
            { responsivePriority: 1, targets: 1},
            { responsivePriority: 2, targets: 2},
            { responsivePriority: 3, targets: 3},
            { responsivePriority: 4, targets: 0},
        ],
        "order": [
            [2, "asc"],
        ],
    });  */
    $('table#letter-groups-table').DataTable({
        "order": [
            [1, "asc"],
            [6, "asc"],
            [3, "asc"]
        ],
        "columnDefs": [
            { responsivePriority: 1, targets: 1},
            { responsivePriority: 2, targets: 2},
            { responsivePriority: 3, targets: 3},
            { responsivePriority: 4, targets: 0},
        ],
    });
    $('table#my-group-calls-ambassadors-table').DataTable({
        "order": [
            [1, "asc"],
            [2, "asc"]
        ],
        "columnDefs": [
            { responsivePriority: 1, targets: 1},
            { responsivePriority: 2, targets: 2},
            { responsivePriority: 3, targets: 5},
            { responsivePriority: 4, targets: 4},
        ],
    });
    
    // Comings and Goings table is initialized in app/Resources/views/comingsandgoings/index.html.twig in order to inject 'New' route
     
    $('table#dorm-table').DataTable({
        "order": [
            [6, "asc"],
            [4, "asc"],
            [5, "asc"]
        ],
        "columnDefs": [
            { responsivePriority: 1, targets: 0},
            { responsivePriority: 2, targets: 1},
            { responsivePriority: 3, targets: 6},
            { responsivePriority: 4, targets: 7},
            { responsivePriority: 6, targets: 8},
            { responsivePriority: 7, targets: 2},
            { responsivePriority: 8, targets: 3},
        ],
    }); 
    $('table#group-facilitators-table').DataTable({
        "order": [
            [4, "asc"],
            [3, "asc"],
            [1, "asc"]
        ],
        buttons: null,
        dom: 'rtip',
        info: false,
    });
    $('table#group-ambassadors-table').DataTable({
        "order": [
            [1, "asc"]
        ],
        buttons: null,
        dom: 'rtip',
        info: false,
    });
    $('table#my-group-ambassadors-table').DataTable({
        "order": [
            [1, "asc"]
        ],
        "columnDefs": [
            { responsivePriority: 1, targets: 0},
            { responsivePriority: 2, targets: 1},
            { responsivePriority: 3, targets: 2},
            { responsivePriority: 4, targets: 5},
            { responsivePriority: 5, targets: 6},
            { responsivePriority: 7, targets: 4},
            { responsivePriority: 8, targets: 5},
            { responsivePriority: 9, targets: 2}
        ],
        dom: 'Brtip',
        info: false,
    }); 
    $('table#my-group-ae-table').DataTable({
        "order": [
            [1, "asc"]
        ],
        "columnDefs": [
            { responsivePriority: 1, targets: 0},
            { responsivePriority: 2, targets: 1},
            { responsivePriority: 3, targets: 2},
            { responsivePriority: 4, targets: 7},
            { responsivePriority: 5, targets: 3},
            { responsivePriority: 6, targets: 4},
            { responsivePriority: 7, targets: 5},
            { responsivePriority: 8, targets: 6},
            { responsivePriority: 9, targets: 8}
        ],
    }); 
    
    $('table#my-group-se-table').DataTable({
        "order": [
            [1, "asc"]
        ],
        "columnDefs": [
            { responsivePriority: 1, targets: 0},
            { responsivePriority: 2, targets: 1},
            { responsivePriority: 3, targets: 2},
            { responsivePriority: 4, targets: 4},
            { responsivePriority: 5, targets: 3}
        ],
    }); 
    
    $('table#group-thankyou-table').DataTable({
        "order": [
            [1, "asc"]
        ],
        "columnDefs": [
            { responsivePriority: 1, targets: 0},
            { responsivePriority: 2, targets: 1},
            { responsivePriority: 3, targets: 2},
            { responsivePriority: 4, targets: 4},
            { responsivePriority: 5, targets: 3}
        ]
    });
    
    $('table#all-thankyous-table').DataTable({
        "order": [
            [3, "asc"],
            [1, "asc"]
        ],
        "columnDefs": [
            { responsivePriority: 1, targets: 0},
            { responsivePriority: 2, targets: 1},
            { responsivePriority: 3, targets: 2},
            { responsivePriority: 4, targets: 4},
            { responsivePriority: 5, targets: 5},
            { responsivePriority: 6, targets: 3}
        ]
    });
    
    $('table#staff-duty-assignments-table').DataTable({
        "order": [
            [2, "asc"],
            [1, "asc"]
        ]
    });
    
    $('#checkout_checkout_deposit_decision').change(function(){
        var i= $('#checkout_checkout_deposit_decision').val();
        if(i=="return") {
            $('#checkout-deposit-return').show();
            $('#checkout-deposit-keep').hide();
        } else {
            $('#checkout-deposit-keep').show();
            $('#checkout-deposit-return').hide();
            
        }
    });

    // Eval bars
    $('.show-bars').barrating('show',{
        theme: 'bars-movie'
      });
      
    

//     $('#calendar').fullCalendar({
// 
//         
//         
//        
//         defaultDate: theDate,
//         validRange: {
//             start: '2023-06-07',
//             end: '2023-06-12'
//         },
//         eventClick: function(event) {
//             if (event.url) {
//                 return false;
//             }
//         },
//         eventRender: function (event, element, view) {
//             if ( event.description == "Meal") {
//                 element.css({'background-color': '#a10008',
//                              'border':'1px solid #fff'});
//             } else if ( event.description == "Panel/Speaker") {
//                 element.css({'background-color': '#1e2399',
//                              'border':'1px solid #fff'});
//             } else if ( event.description == "Activity") {
//                 element.css({'background-color': '#006d00',
//                              'border':'1px solid #fff'});
//             } else if ( event.description == "Breakout/Reflection") {
//                 element.css({'background-color': '#6a4d81',
//                              'border':'1px solid #fff'});
//             } else if ( event.description == "Ceremony") {
//                 element.css({'background-color': '#00a195',
//                              'border':'1px solid #fff'});
//             } else {
//                 element.css({'background-color': '#616161',
//                              'border':'1px solid #fff'});
//             }
//             var title = ( event.location ) ? '<strong>' + event.title + '</strong> &#124; ' + event.location + '<br><small>' + moment(event.start).format('h:mma') + '&ndash;' + moment(event.end).format('h:mma') + '</small>' : '<strong>' + event.title + '</strong><br><small>' + moment(event.start).format('h:mma') + '&ndash;' + moment(event.end).format('h:mma') + '</small>';
//             element.html(title);
//         }
//     })

});

