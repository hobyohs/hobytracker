/* ============================================================
   HOBYtracker — app.js  (UI Redesign 2026)
   ============================================================ */

// ── Custom DataTables Responsive Renderer ─────────────────
// Renders collapsed columns as ht-section-row key/value pairs.
// Filters out <th class="hidden"> and <th class="none"> columns
// so sort-only / always-hidden columns never surface in child rows.
function htResponsiveRenderer(api, rowIdx, columns) {
  var rows = columns.filter(function(col) {
    if (!col.hidden) return false;
    var th = api.column(col.columnIndex).header();
    if ($(th).hasClass('hidden') || $(th).hasClass('none')) return false;
    if (!col.title || col.title.trim() === '') return false;
    return true;
  }).map(function(col) {
    return '<div class="ht-section-row">' +
      '<span class="ht-section-key">' + col.title + '</span>' +
      '<span class="ht-section-val">' + (col.data || '<span class="ht-empty">—</span>') + '</span>' +
      '</div>';
  }).join('');

  return rows ? '<div class="ht-responsive-child">' + rows + '</div>' : false;
}

// ── Card List Filter ──────────────────────────────────────
function initCardFilter(searchId, listId, pillSelector) {
  var searchEl = document.getElementById(searchId);
  var listEl   = document.getElementById(listId);
  if (!listEl) return;

  var cards    = listEl.querySelectorAll('.ht-card-row');
  var noResults = listEl.querySelector('.ht-no-results');
  var activeFilter = 'all';

  function applyFilters() {
    var q = searchEl ? searchEl.value.toLowerCase().trim() : '';
    var visible = 0;
    cards.forEach(function(card) {
      var name   = (card.dataset.name   || '').toLowerCase();
      var school = (card.dataset.school || '').toLowerCase();
      var sub    = (card.dataset.sub    || '').toLowerCase();
      var group  = (card.dataset.group  || '');
      var matchesSearch = !q || name.includes(q) || school.includes(q) || sub.includes(q) || group.toLowerCase().includes(q);
      var matchesGroup  = activeFilter === 'all' || group === activeFilter;
      if (matchesSearch && matchesGroup) { card.classList.remove('ht-hidden'); visible++; }
      else { card.classList.add('ht-hidden'); }
    });
    if (noResults) noResults.style.display = (visible === 0 && (q || activeFilter !== 'all')) ? 'block' : 'none';
  }

  if (searchEl) searchEl.addEventListener('input', applyFilters);
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
  responsive: {
    details: {
      type: 'inline',
      renderer: htResponsiveRenderer
    }
  },
  paging: false,
  dom: 'Bfrtip',
  buttons: [
    { extend: 'copy',  className: 'btn btn-primary', text: '<i class="fa fa-copy"></i> Copy',        exportOptions: { columns: ':not(.no-export)' } },
    { extend: 'excel', className: 'btn btn-primary', text: '<i class="fa fa-file-excel"></i> Excel', exportOptions: { columns: ':not(.no-export)' } },
    { extend: 'pdf',   className: 'btn btn-primary', text: '<i class="fa fa-file-pdf"></i> PDF',     exportOptions: { columns: ':not(.no-export)' } },
    { extend: 'print', className: 'btn btn-primary', text: '<i class="fa fa-print"></i> Print',      exportOptions: { columns: ':not(.no-export)' } }
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
    events: { googleCalendarId: 'hobyohiosouth.org_kn2ogfi3d0ddfjef20h8hj7734@group.calendar.google.com' },
    height: 'auto',
    views: { timeGridDay: { slotDuration: '00:15:00', slotLabelInterval: '01:00:00', slotMinTime: '07:00:00' } },
    initialDate: theDate,
    validRange: { start: '2025-06-11', end: '2025-06-16' },
    eventDidMount: function(event) {
      var colorMap = { "Meal":'#a10008', "Panel/Speaker":'#1e2399', "Activity":'#006d00', "Breakout/Reflection":'#6a4d81', "Ceremony":'#00a195' };
      event.event.setProp('backgroundColor', colorMap[event.event.extendedProps.description] || '#616161');
    },
    eventContent: function(event) {
      var loc = event.event.extendedProps.location;
      var time = moment(event.event.start).format('h:mma') + '&ndash;' + moment(event.event.end).format('h:mma');
      return { html: '<strong>' + event.event.title + '</strong>' + (loc ? ' &#124; ' + loc : '') + ' &#124; <small>' + time + '</small>' };
    }
  });
  calendar.render();
});

// ── DOM Ready ─────────────────────────────────────────────
$(document).ready(function() {

  // Offcanvas sidebar
  var sidebarEl = document.getElementById('mainSidebar');
  if (sidebarEl) {
    var sidebar = new bootstrap.Offcanvas(sidebarEl);
    $('.ht-hamburger').on('click', function(e) { e.stopPropagation(); sidebar.show(); });
  }

  // Card list filters
  initCardFilter('ambSearch',      'ambList',      '.amb-pill');
  initCardFilter('staffSearch',    'staffList',    '.staff-pill');
  initCardFilter('checkinSearch',  'checkinList',  '.checkin-pill');
  initCardFilter('checkoutSearch', 'checkoutList', '.checkout-pill');

  // Bootstrap tooltip
  $('[data-toggle="tooltip"]').tooltip({ container: 'body', html: true, placement: 'bottom' });

  // ── DataTables ────────────────────────────────────────────
  // Priority: 1 = last to collapse, high = first to collapse
  // Always put name cols (1,2) as 1,2. View/action col last.

  // dietary: View(0) First(1) Last(2) Group(3) Restrictions(4) Info(5)
  $('table#dietary-table').DataTable({ order: [[2,"asc"],[1,"asc"]], columnDefs: [
    {responsivePriority:1,targets:1},{responsivePriority:2,targets:2},
    {responsivePriority:3,targets:4},{responsivePriority:4,targets:5},
    {responsivePriority:5,targets:3},{responsivePriority:6,targets:0}
  ]});

  // ec: View(0) First(1) Last(2) Group(3) ECFirst(4) ECLast(5) Rel(6) Phone1(7) Phone2(8)
  $('table#ec-table').DataTable({ order: [[2,"asc"],[1,"asc"]], columnDefs: [
    {responsivePriority:1,targets:1},{responsivePriority:2,targets:2},
    {responsivePriority:3,targets:7},{responsivePriority:4,targets:4},
    {responsivePriority:5,targets:5},{responsivePriority:6,targets:6},
    {responsivePriority:7,targets:8},{responsivePriority:8,targets:3},
    {responsivePriority:9,targets:0}
  ]});

  // medical: View(0) First(1) Last(2) Group(3) Rx(4) Conditions(5) Exercise(6) Allergies(7) MedAllergies(8)
  $('table#med-table').DataTable({ order: [[2,"asc"],[1,"asc"]], columnDefs: [
    {responsivePriority:1,targets:1},{responsivePriority:2,targets:2},
    {responsivePriority:3,targets:4},{responsivePriority:4,targets:5},
    {responsivePriority:5,targets:7},{responsivePriority:6,targets:8},
    {responsivePriority:7,targets:6},{responsivePriority:8,targets:3},
    {responsivePriority:9,targets:0}
  ]});

  // ambassadors: View(0) First(1) Last(2) Group(3) School(4)
  $('table#ambassadors-table').DataTable({ order: [[2,"asc"],[1,"asc"]], columnDefs: [
    {responsivePriority:1,targets:1},{responsivePriority:2,targets:2},
    {responsivePriority:3,targets:3},{responsivePriority:4,targets:4},
    {responsivePriority:5,targets:0}
  ]});

  // applicant: Evaluate(0) First(1) Last(2) Position(3) R1(4) R2(5) Decision(6)
  $('table#applicant-table').DataTable({ order: [[2,"asc"],[1,"asc"]], columnDefs: [
    {responsivePriority:1,targets:1},{responsivePriority:2,targets:2},
    {responsivePriority:3,targets:6},{responsivePriority:4,targets:4},
    {responsivePriority:5,targets:5},{responsivePriority:6,targets:3},
    {responsivePriority:7,targets:0}
  ]});

  // psm: View(0) First(1) Last(2) Group(3) School(4) Forms(5) Deposit(6) [Method](7) [Notes](8) CG(9) [emails 10-12]
  $('table#psm-table').DataTable({ order: [[2,"asc"],[1,"asc"]], columnDefs: [
    {responsivePriority:1,targets:1},{responsivePriority:2,targets:2},
    {responsivePriority:3,targets:5},{responsivePriority:4,targets:6},
    {responsivePriority:5,targets:9},{responsivePriority:6,targets:4},
    {responsivePriority:7,targets:3},{responsivePriority:8,targets:0}
  ]});

  // calls: View(0) First(1) Last(2) Cell(3) School(4) Junior(5) Forms(6) Disposition(7) Notes(8)
  $('table#calls-table').DataTable({ order: [[2,"asc"],[1,"asc"]], columnDefs: [
    {responsivePriority:1,targets:1},{responsivePriority:2,targets:2},
    {responsivePriority:3,targets:7},{responsivePriority:4,targets:3},
    {responsivePriority:5,targets:6},{responsivePriority:6,targets:8},
    {responsivePriority:7,targets:5},{responsivePriority:8,targets:4},
    {responsivePriority:9,targets:0}
  ]});

  // bus: View(0) First(1) Last(2) Group(3) School(4) BusTo(5) BusFrom(6)
  $('table#bus-table').DataTable({ order: [[2,"asc"],[1,"asc"]], columnDefs: [
    {responsivePriority:1,targets:1},{responsivePriority:2,targets:2},
    {responsivePriority:3,targets:5},{responsivePriority:4,targets:6},
    {responsivePriority:5,targets:4},{responsivePriority:6,targets:3},
    {responsivePriority:7,targets:0}
  ]});

  // staff-req: View(0) First(1) Last(2) Position(3) [Email](4) Paperwork(5) BG(6) App(7) Hours(8) AmbReg(9) Fundraising(10) Notes(11)
  $('table#staff-req-table').DataTable({ order: [[2,"asc"],[1,"asc"]], columnDefs: [
    {responsivePriority:1, targets:1},{responsivePriority:2, targets:2},
    {responsivePriority:3, targets:5},{responsivePriority:4, targets:6},
    {responsivePriority:5, targets:7},{responsivePriority:6, targets:8},
    {responsivePriority:7, targets:9},{responsivePriority:8, targets:10},
    {responsivePriority:9, targets:3},{responsivePriority:10,targets:11},
    {responsivePriority:11,targets:0}
  ]});

  // letter-interview: View(0) Letter(1) Assignment(2)
  $('table#letter-interview-table').DataTable({ order: [[1,"asc"]], columnDefs: [
    {responsivePriority:1,targets:1},{responsivePriority:2,targets:2},{responsivePriority:3,targets:0}
  ]});

  // letter-index (legacy, replaced by card grid but keep init)
  $('table#letter-index-table').DataTable({ order: [[1,"asc"]], columnDefs: [
    {responsivePriority:1,targets:1},{responsivePriority:2,targets:3},{responsivePriority:3,targets:2},{responsivePriority:4,targets:0}
  ]});

  // bc: View(0) First(1) Last(2) Group(3) [Floor](4) [Sort](5) Dorm(6) Room(7) Bed col(8)
  $('table#bc-table').DataTable({ order: [[6,"asc"],[4,"asc"],[5,"asc"],[1,"asc"]], columnDefs: [
    {responsivePriority:1,targets:1},{responsivePriority:2,targets:2},
    {responsivePriority:3,targets:8},{responsivePriority:4,targets:6},
    {responsivePriority:5,targets:7},{responsivePriority:6,targets:3},
    {responsivePriority:7,targets:0}
  ], buttons: ['excelHtml5','pdfHtml5','print']});

  // kd: View(0) First(1) Last(2) Group(3) Deposit(4) Details(5)
  $('table#kd-table').DataTable({ order: [[2,"asc"],[1,"asc"]], columnDefs: [
    {responsivePriority:1,targets:1},{responsivePriority:2,targets:2},
    {responsivePriority:3,targets:4},{responsivePriority:4,targets:5},
    {responsivePriority:5,targets:3},{responsivePriority:6,targets:0}
  ]});

  // noshow: View(0) First(1) Last(2) Group(3) School(4) Paperwork(5) Cell(6) Home(7)
  $('table#noshow-table').DataTable({ order: [[2,"asc"],[1,"asc"]], columnDefs: [
    {responsivePriority:1,targets:1},{responsivePriority:2,targets:2},
    {responsivePriority:3,targets:5},{responsivePriority:4,targets:6},
    {responsivePriority:5,targets:4},{responsivePriority:6,targets:7},
    {responsivePriority:7,targets:3},{responsivePriority:8,targets:0}
  ]});

  // checkin/checkout
  $('table#checkin-table').DataTable({ order:[[1,"asc"],[0,"asc"]], columnDefs: [
    {responsivePriority:1,targets:0},{responsivePriority:2,targets:2},{responsivePriority:3,targets:1}
  ], buttons:[] });
  $('table#checkout-table').DataTable({ order:[[1,"asc"],[0,"asc"]], columnDefs: [
    {responsivePriority:1,targets:0},{responsivePriority:2,targets:2},{responsivePriority:3,targets:1}
  ], buttons:[] });

  // staff: View(0) First(1) Last(2) Position(3)
  $('table#staff-table').DataTable({ order:[[2,"asc"],[1,"asc"]], columnDefs: [
    {responsivePriority:1,targets:1},{responsivePriority:2,targets:2},
    {responsivePriority:3,targets:3},{responsivePriority:4,targets:0}
  ]});

  // dorm: View(0) First(1) Last(2) Group(3) [Floor](4) [Sort](5) Dorm(6) Room(7) Shirt(8)
  $('table#dorm-table').DataTable({ order:[[6,"asc"],[4,"asc"],[5,"asc"]], columnDefs: [
    {responsivePriority:1,targets:1},{responsivePriority:2,targets:2},
    {responsivePriority:3,targets:6},{responsivePriority:4,targets:7},
    {responsivePriority:5,targets:8},{responsivePriority:6,targets:3},
    {responsivePriority:7,targets:0}
  ]});

  // letter-groups (all groups): Action(0) Group(1) First(2) Last(3) Role(4) School(5) [Sort](6)
  $('table#letter-groups-table').DataTable({ order:[[1,"asc"],[6,"asc"],[3,"asc"]], columnDefs: [
    {responsivePriority:1,targets:2},{responsivePriority:2,targets:3},
    {responsivePriority:3,targets:1},{responsivePriority:4,targets:4},
    {responsivePriority:5,targets:5},{responsivePriority:6,targets:0}
  ]});

  // all-thankyous: View(0) First(1) Last(2) Group(3) Type(4) Assignment(5)
  $('table#all-thankyous-table').DataTable({ order:[[3,"asc"],[1,"asc"]], columnDefs: [
    {responsivePriority:1,targets:1},{responsivePriority:2,targets:2},
    {responsivePriority:3,targets:4},{responsivePriority:4,targets:5},
    {responsivePriority:5,targets:3},{responsivePriority:6,targets:0}
  ]});

  $('table#staff-duty-assignments-table').DataTable({ order:[[2,"asc"],[1,"asc"]] });

  // group sub-tables
  $('table#group-facilitators-table').DataTable({ order:[[4,"asc"],[3,"asc"],[1,"asc"]], buttons:null, dom:'rtip', info:false });
  $('table#group-ambassadors-table').DataTable({ order:[[1,"asc"]], buttons:null, dom:'rtip', info:false });
  $('table#my-group-ambassadors-table').DataTable({ order:[[1,"asc"]], columnDefs: [
    {responsivePriority:1,targets:1},{responsivePriority:2,targets:2},
    {responsivePriority:3,targets:4},{responsivePriority:4,targets:5},
    {responsivePriority:5,targets:3},{responsivePriority:6,targets:0}
  ], dom:'Brtip', info:false });
  $('table#my-group-calls-ambassadors-table').DataTable({ order:[[1,"asc"],[2,"asc"]], columnDefs: [
    {responsivePriority:1,targets:1},{responsivePriority:2,targets:2},
    {responsivePriority:3,targets:5},{responsivePriority:4,targets:4},
    {responsivePriority:5,targets:3},{responsivePriority:6,targets:0}
  ]});
  $('table#my-group-ae-table').DataTable({ order:[[1,"asc"]], columnDefs: [
    {responsivePriority:1,targets:1},{responsivePriority:2,targets:2},
    {responsivePriority:3,targets:7},{responsivePriority:4,targets:3},
    {responsivePriority:5,targets:4},{responsivePriority:6,targets:5},
    {responsivePriority:7,targets:6},{responsivePriority:8,targets:8},
    {responsivePriority:9,targets:0}
  ]});
  $('table#my-group-se-table').DataTable({ order:[[1,"asc"]], columnDefs: [
    {responsivePriority:1,targets:1},{responsivePriority:2,targets:2},
    {responsivePriority:3,targets:4},{responsivePriority:4,targets:3},
    {responsivePriority:5,targets:0}
  ]});
  $('table#group-thankyou-table').DataTable({ order:[[1,"asc"]], columnDefs: [
    {responsivePriority:1,targets:1},{responsivePriority:2,targets:2},
    {responsivePriority:3,targets:4},{responsivePriority:4,targets:3},
    {responsivePriority:5,targets:0}
  ]});

  // Checkout deposit toggle
  $('#checkout_checkout_deposit_decision').change(function() {
    var val = $(this).val();
    if (val === "return") { $('#checkout-deposit-return').show(); $('#checkout-deposit-keep').hide(); }
    else { $('#checkout-deposit-keep').show(); $('#checkout-deposit-return').hide(); }
  });

  // Eval bars
  $('.show-bars').barrating('show', { theme: 'bars-movie' });

});
