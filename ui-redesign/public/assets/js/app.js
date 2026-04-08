/* ============================================================
   HOBYtracker — app.js  (UI Redesign 2026)
   SmartMenus removed. Bootstrap offcanvas nav replaces it.
   Card list filter/search added for ambassador + staff lists.
   All DataTables configurations preserved.
   ============================================================ */

// ── Card List Filter ──────────────────────────────────────
function initCardFilter(searchId, listId, pillSelector) {
  var searchEl = document.getElementById(searchId);
  var listEl   = document.getElementById(listId);
  if (!listEl) return;

  var cards     = listEl.querySelectorAll('.ht-card-row');
  var noResults = listEl.querySelector('.ht-no-results');
  var activeFilter = 'all';

  function applyFilters() {
    var q = searchEl ? searchEl.value.toLowerCase().trim() : '';
    var visible = 0;
    cards.forEach(function(card) {
      var name   = (card.dataset.name   || '').toLowerCase();
      var school = (card.dataset.school || '').toLowerCase();
      var sub    = (card.dataset.sub    || '').toLowerCase(); // position for staff
      var group  = (card.dataset.group  || '');
      var matchesSearch = !q || name.includes(q) || school.includes(q) || sub.includes(q) || group.toLowerCase().includes(q);
      var matchesGroup  = activeFilter === 'all' || group === activeFilter;
      if (matchesSearch && matchesGroup) {
        card.classList.remove('ht-hidden');
        visible++;
      } else {
        card.classList.add('ht-hidden');
      }
    });
    if (noResults) {
      noResults.style.display = (visible === 0 && (q || activeFilter !== 'all')) ? 'block' : 'none';
    }
  }

  if (searchEl) {
    searchEl.addEventListener('input', applyFilters);
  }

  document.querySelectorAll(pillSelector).forEach(function(pill) {
    pill.addEventListener('click', function() {
      document.querySelectorAll(pillSelector).forEach(function(p) { p.classList.remove('active'); });
      this.classList.add('active');
      activeFilter = this.dataset.filter;
      applyFilters();
    });
  });
}

// ── DataTable Global Defaults ─────────────────────────────
$.extend(true, $.fn.dataTable.defaults, {
  "order": [[1, "asc"]],
  "columnDefs": [
    { responsivePriority: 1, targets: 1 },
    { responsivePriority: 2, targets: 2 },
    { responsivePriority: 3, targets: 0 },
    { targets: 0, orderable: false }
  ],
  "autoWidth": true,
  responsive: true,
  paging: false,
  dom: 'Bfrtip',
  buttons: [
    { extend: 'copy',  className: 'btn btn-primary', text: '<i class="fa fa-copy"></i> Copy',    exportOptions: { columns: ':not(.no-export)' } },
    { extend: 'excel', className: 'btn btn-primary', text: '<i class="fa fa-file-excel"></i> Excel', exportOptions: { columns: ':not(.no-export)' } },
    { extend: 'pdf',   className: 'btn btn-primary', text: '<i class="fa fa-file-pdf"></i> PDF', exportOptions: { columns: ':not(.no-export)' } },
    { extend: 'print', className: 'btn btn-primary', text: '<i class="fa fa-print"></i> Print',  exportOptions: { columns: ':not(.no-export)' } }
  ],
  fixedHeader: true
});

// ── Calendar Setup ────────────────────────────────────────
var today = new Date();
var offset = today.getTimezoneOffset();
today = new Date(today.getTime() - (offset * 60 * 1000));
today = today.toISOString().split('T')[0];

var validDates = ["2025-06-12", "2025-06-13", "2025-06-14", "2025-06-15"];
var theDate = validDates.includes(today) ? today : "2025-06-12";

document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');
  if (!calendarEl) return;

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
    validRange: { start: '2025-06-11', end: '2025-06-16' },
    eventDidMount: function(event) {
      var colorMap = {
        "Meal": '#a10008',
        "Panel/Speaker": '#1e2399',
        "Activity": '#006d00',
        "Breakout/Reflection": '#6a4d81',
        "Ceremony": '#00a195'
      };
      var bg = colorMap[event.event.extendedProps.description] || '#616161';
      event.event.setProp('backgroundColor', bg);
    },
    eventContent: function(event) {
      var loc = event.event.extendedProps.location;
      var time = moment(event.event.start).format('h:mma') + '&ndash;' + moment(event.event.end).format('h:mma');
      var html = '<strong>' + event.event.title + '</strong>';
      if (loc) html += ' &#124; ' + loc;
      html += ' &#124; <small>' + time + '</small>';
      return { html: html };
    }
  });
  calendar.render();
});

// ── DOM Ready ─────────────────────────────────────────────
$(document).ready(function() {

  // Card list filters
  initCardFilter('ambSearch',   'ambList',   '.amb-pill');
  initCardFilter('staffSearch', 'staffList', '.staff-pill');

  // Bootstrap tooltip
  $('[data-toggle="tooltip"]').tooltip({ container: 'body', html: true, placement: 'bottom' });

  // ── DataTables ──────────────────────────────────────────

  $('table#dietary-table').DataTable({ order: [[2, "asc"], [1, "asc"]] });
  $('table#ec-table').DataTable({ order: [[2, "asc"], [1, "asc"]] });

  $('table#med-table').DataTable({
    order: [[2, "asc"], [1, "asc"]],
    columnDefs: [
      { responsivePriority: 1, targets: 0 },
      { responsivePriority: 2, targets: 2 },
      { responsivePriority: 3, targets: 4 },
      { responsivePriority: 4, targets: 5 },
      { responsivePriority: 5, targets: 8 }
    ]
  });

  $('table#ambassadors-table').DataTable({
    order: [[2, "asc"], [1, "asc"]],
    columnDefs: [
      { responsivePriority: 1, targets: 1 },
      { responsivePriority: 2, targets: 2 },
      { responsivePriority: 3, targets: 0 },
      { responsivePriority: 4, targets: 3 }
    ]
  });

  $('table#applicant-table').DataTable({
    order: [[2, "asc"], [1, "asc"]],
    columnDefs: [
      { responsivePriority: 1, targets: 1 },
      { responsivePriority: 2, targets: 2 },
      { responsivePriority: 3, targets: 0 },
      { responsivePriority: 4, targets: 6 },
      { responsivePriority: 5, targets: 4 },
      { responsivePriority: 6, targets: 5 },
      { responsivePriority: 7, targets: 3 }
    ]
  });

  $('table#psm-table').DataTable({
    order: [[2, "asc"], [1, "asc"]],
    columnDefs: [
      { responsivePriority: 1, targets: 1 },
      { responsivePriority: 2, targets: 2 },
      { responsivePriority: 3, targets: 0 },
      { responsivePriority: 4, targets: 3 }
    ]
  });

  $('table#calls-table').DataTable({
    order: [[2, "asc"], [1, "asc"]],
    columnDefs: [
      { responsivePriority: 1, targets: 1 },
      { responsivePriority: 2, targets: 2 },
      { responsivePriority: 3, targets: 7 },
      { responsivePriority: 4, targets: 8 },
      { responsivePriority: 5, targets: 6 },
      { responsivePriority: 6, targets: 5 }
    ]
  });

  $('table#bus-table').DataTable({
    order: [[2, "asc"], [1, "asc"]],
    columnDefs: [
      { responsivePriority: 1, targets: 2 },
      { responsivePriority: 2, targets: 1 },
      { responsivePriority: 3, targets: 0 }
    ]
  });

  $('table#staff-req-table').DataTable({
    order: [[2, "asc"], [1, "asc"]],
    columnDefs: [
      { responsivePriority: 1, targets: 1 },
      { responsivePriority: 2, targets: 2 },
      { responsivePriority: 3, targets: 0 },
      { responsivePriority: 4, targets: 3 }
    ]
  });

  $('table#letter-interview-table').DataTable({
    order: [[1, "asc"]],
    columnDefs: [
      { responsivePriority: 1, targets: 1 },
      { responsivePriority: 2, targets: 2 },
      { responsivePriority: 3, targets: 0 }
    ]
  });

  $('table#letter-index-table').DataTable({
    order: [[1, "asc"]],
    columnDefs: [
      { responsivePriority: 1, targets: 0 },
      { responsivePriority: 2, targets: 1 },
      { responsivePriority: 3, targets: 3 },
      { responsivePriority: 4, targets: 2 }
    ]
  });

  $('table#bc-table').DataTable({
    order: [[6, "asc"], [4, "asc"], [5, "asc"], [1, "asc"]],
    columnDefs: [
      { responsivePriority: 1, targets: 0 },
      { responsivePriority: 2, targets: 1 },
      { responsivePriority: 3, targets: 6 },
      { responsivePriority: 4, targets: 7 },
      { responsivePriority: 5, targets: 8 },
      { responsivePriority: 6, targets: 9 },
      { responsivePriority: 7, targets: 5 },
      { responsivePriority: 8, targets: 2 }
    ],
    buttons: ['excelHtml5', 'pdfHtml5', 'print']
  });

  $('table#kd-table').DataTable({
    order: [[2, "asc"], [1, "asc"]],
    columnDefs: [
      { responsivePriority: 1, targets: 1 },
      { responsivePriority: 2, targets: 2 },
      { responsivePriority: 3, targets: 0 },
      { responsivePriority: 4, targets: 3 }
    ]
  });

  $('table#noshow-table').DataTable({
    order: [[2, "asc"], [1, "asc"]],
    columnDefs: [
      { responsivePriority: 1, targets: 2 },
      { responsivePriority: 2, targets: 1 },
      { responsivePriority: 3, targets: 0 }
    ]
  });

  $('table#checkin-table').DataTable({
    order: [[1, "asc"], [0, "asc"]],
    columnDefs: [
      { responsivePriority: 1, targets: 0 },
      { responsivePriority: 2, targets: 2 },
      { responsivePriority: 3, targets: 1 }
    ],
    buttons: []
  });

  $('table#checkout-table').DataTable({
    order: [[1, "asc"], [0, "asc"]],
    columnDefs: [
      { responsivePriority: 1, targets: 0 },
      { responsivePriority: 2, targets: 2 },
      { responsivePriority: 3, targets: 1 }
    ],
    buttons: []
  });

  $('table#staff-table').DataTable({ order: [[2, "asc"], [1, "asc"]] });

  $('table#letter-groups-table').DataTable({
    order: [[1, "asc"], [6, "asc"], [3, "asc"]],
    columnDefs: [
      { responsivePriority: 1, targets: 1 },
      { responsivePriority: 2, targets: 2 },
      { responsivePriority: 3, targets: 3 },
      { responsivePriority: 4, targets: 0 }
    ]
  });

  $('table#my-group-calls-ambassadors-table').DataTable({
    order: [[1, "asc"], [2, "asc"]],
    columnDefs: [
      { responsivePriority: 1, targets: 1 },
      { responsivePriority: 2, targets: 2 },
      { responsivePriority: 3, targets: 5 },
      { responsivePriority: 4, targets: 4 }
    ]
  });

  $('table#dorm-table').DataTable({
    order: [[6, "asc"], [4, "asc"], [5, "asc"]],
    columnDefs: [
      { responsivePriority: 1, targets: 0 },
      { responsivePriority: 2, targets: 1 },
      { responsivePriority: 3, targets: 6 },
      { responsivePriority: 4, targets: 7 },
      { responsivePriority: 6, targets: 8 },
      { responsivePriority: 7, targets: 2 },
      { responsivePriority: 8, targets: 3 }
    ]
  });

  $('table#group-facilitators-table').DataTable({
    order: [[4, "asc"], [3, "asc"], [1, "asc"]],
    buttons: null, dom: 'rtip', info: false
  });

  $('table#group-ambassadors-table').DataTable({
    order: [[1, "asc"]],
    buttons: null, dom: 'rtip', info: false
  });

  $('table#my-group-ambassadors-table').DataTable({
    order: [[1, "asc"]],
    columnDefs: [
      { responsivePriority: 1, targets: 0 },
      { responsivePriority: 2, targets: 1 },
      { responsivePriority: 3, targets: 2 },
      { responsivePriority: 4, targets: 5 },
      { responsivePriority: 5, targets: 6 },
      { responsivePriority: 7, targets: 4 },
      { responsivePriority: 8, targets: 5 },
      { responsivePriority: 9, targets: 2 }
    ],
    dom: 'Brtip', info: false
  });

  $('table#my-group-ae-table').DataTable({
    order: [[1, "asc"]],
    columnDefs: [
      { responsivePriority: 1, targets: 0 },
      { responsivePriority: 2, targets: 1 },
      { responsivePriority: 3, targets: 2 },
      { responsivePriority: 4, targets: 7 },
      { responsivePriority: 5, targets: 3 },
      { responsivePriority: 6, targets: 4 },
      { responsivePriority: 7, targets: 5 },
      { responsivePriority: 8, targets: 6 },
      { responsivePriority: 9, targets: 8 }
    ]
  });

  $('table#my-group-se-table').DataTable({
    order: [[1, "asc"]],
    columnDefs: [
      { responsivePriority: 1, targets: 0 },
      { responsivePriority: 2, targets: 1 },
      { responsivePriority: 3, targets: 2 },
      { responsivePriority: 4, targets: 4 },
      { responsivePriority: 5, targets: 3 }
    ]
  });

  $('table#group-thankyou-table').DataTable({
    order: [[1, "asc"]],
    columnDefs: [
      { responsivePriority: 1, targets: 0 },
      { responsivePriority: 2, targets: 1 },
      { responsivePriority: 3, targets: 2 },
      { responsivePriority: 4, targets: 4 },
      { responsivePriority: 5, targets: 3 }
    ]
  });

  $('table#all-thankyous-table').DataTable({
    order: [[3, "asc"], [1, "asc"]],
    columnDefs: [
      { responsivePriority: 1, targets: 0 },
      { responsivePriority: 2, targets: 1 },
      { responsivePriority: 3, targets: 2 },
      { responsivePriority: 4, targets: 4 },
      { responsivePriority: 5, targets: 5 },
      { responsivePriority: 6, targets: 3 }
    ]
  });

  $('table#staff-duty-assignments-table').DataTable({ order: [[2, "asc"], [1, "asc"]] });

  // Checkout deposit decision toggle
  $('#checkout_checkout_deposit_decision').change(function() {
    var val = $(this).val();
    if (val === "return") {
      $('#checkout-deposit-return').show();
      $('#checkout-deposit-keep').hide();
    } else {
      $('#checkout-deposit-keep').show();
      $('#checkout-deposit-return').hide();
    }
  });

  // Eval bars
  $('.show-bars').barrating('show', { theme: 'bars-movie' });

});
