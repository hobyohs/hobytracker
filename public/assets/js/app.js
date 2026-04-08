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
function initCardFilter(searchId, listId, pillSelector, cardSelector) {
  var searchEl = document.getElementById(searchId);
  var listEl   = document.getElementById(listId);
  if (!listEl) return;

  cardSelector = cardSelector || '.ht-card-row';
  var cards    = listEl.querySelectorAll(cardSelector);
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
  "order": [[0, "asc"]],
  "columnDefs": [
    { responsivePriority: 1, targets: 0 },
    { responsivePriority: 2, targets: 1 }
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
    googleCalendarApiKey: 'AIzaSyDAbuEahdHQAFiBW88EkVUf9hlnPEGpLRk',
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

  // Faces page filters (same shape as card lists, different card class)
  initCardFilter('facesAmbSearch',   'facesAmbGrid',   '.faces-amb-pill',   '.ht-face-card');
  initCardFilter('facesStaffSearch', 'facesStaffGrid', '.faces-staff-pill', '.ht-face-card');
  initCardFilter('facesGroupSearch', 'facesGroupGrid', '.faces-group-pill', '.ht-face-card');

  // Tom Select on any element with the .ht-tomselect class
  // (added to the EntityType <select> in form templates)
  if (typeof TomSelect !== 'undefined') {
    document.querySelectorAll('.ht-tomselect').forEach(function(el) {
      new TomSelect(el, {
        allowEmptyOption: true,
        maxOptions: 500,
        placeholder: el.getAttribute('placeholder') || 'Type to search…'
      });
    });
  }

  // Bootstrap tooltip
  $('[data-toggle="tooltip"]').tooltip({ container: 'body', html: true, placement: 'bottom' });

  // ── DataTables ────────────────────────────────────────────
  // After cleanup pass: VIEW eyeball column removed from all
  // navigation tables. Col 0 is now First Name (linkified).
  // Priority 1 = last to collapse, high = first to collapse.

  // dietary: First(0) Last(1) Group(2) Restrictions(3) Info(4)
  $('table#dietary-table').DataTable({ order: [[1,"asc"],[0,"asc"]], columnDefs: [
    {responsivePriority:1,targets:0},{responsivePriority:2,targets:1},
    {responsivePriority:3,targets:3},{responsivePriority:4,targets:4},
    {responsivePriority:5,targets:2}
  ]});

  // ec: First(0) Last(1) Group(2) ECFirst(3) ECLast(4) Rel(5) Phone1(6) Phone2(7)
  $('table#ec-table').DataTable({ order: [[1,"asc"],[0,"asc"]], columnDefs: [
    {responsivePriority:1,targets:0},{responsivePriority:2,targets:1},
    {responsivePriority:3,targets:6},{responsivePriority:4,targets:3},
    {responsivePriority:5,targets:4},{responsivePriority:6,targets:5},
    {responsivePriority:7,targets:7},{responsivePriority:8,targets:2}
  ]});

  // medical: First(0) Last(1) Group(2) Rx(3) Conditions(4) Exercise(5) Allergies(6) MedAllergies(7)
  $('table#med-table').DataTable({ order: [[1,"asc"],[0,"asc"]], columnDefs: [
    {responsivePriority:1,targets:0},{responsivePriority:2,targets:1},
    {responsivePriority:3,targets:3},{responsivePriority:4,targets:4},
    {responsivePriority:5,targets:6},{responsivePriority:6,targets:7},
    {responsivePriority:7,targets:5},{responsivePriority:8,targets:2}
  ]});

  // ambassadors (legacy table — only present if a non-redesigned page still uses it)
  $('table#ambassadors-table').DataTable({ order: [[1,"asc"],[0,"asc"]], columnDefs: [
    {responsivePriority:1,targets:0},{responsivePriority:2,targets:1},
    {responsivePriority:3,targets:2},{responsivePriority:4,targets:3}
  ]});

  // applicant: Evaluate(0) First(1) Last(2) Position(3) R1(4) R2(5) Decision(6)
  // (NOT touched by cleanup pass — col 0 is an Evaluate action, not view)
  $('table#applicant-table').DataTable({ order: [[2,"asc"],[1,"asc"]], columnDefs: [
    {responsivePriority:1,targets:1},{responsivePriority:2,targets:2},
    {responsivePriority:3,targets:6},{responsivePriority:4,targets:4},
    {responsivePriority:5,targets:5},{responsivePriority:6,targets:3},
    {responsivePriority:7,targets:0}
  ]});

  // psm: First(0) Last(1) Group(2) School(3) Forms(4) Deposit(5) [Method](6) [Notes](7) CG(8) [emails 9-11]
  $('table#psm-table').DataTable({ order: [[1,"asc"],[0,"asc"]], columnDefs: [
    {responsivePriority:1,targets:0},{responsivePriority:2,targets:1},
    {responsivePriority:3,targets:4},{responsivePriority:4,targets:5},
    {responsivePriority:5,targets:8},{responsivePriority:6,targets:3},
    {responsivePriority:7,targets:2}
  ]});

  // calls: First(0) Last(1) Cell(2) School(3) Junior(4) Forms(5) Disposition(6) Notes(7)
  $('table#calls-table').DataTable({ order: [[1,"asc"],[0,"asc"]], columnDefs: [
    {responsivePriority:1,targets:0},{responsivePriority:2,targets:1},
    {responsivePriority:3,targets:6},{responsivePriority:4,targets:2},
    {responsivePriority:5,targets:5},{responsivePriority:6,targets:7},
    {responsivePriority:7,targets:4},{responsivePriority:8,targets:3}
  ]});

  // bus: First(0) Last(1) Group(2) School(3) BusTo(4) BusFrom(5)
  $('table#bus-table').DataTable({ order: [[1,"asc"],[0,"asc"]], columnDefs: [
    {responsivePriority:1,targets:0},{responsivePriority:2,targets:1},
    {responsivePriority:3,targets:4},{responsivePriority:4,targets:5},
    {responsivePriority:5,targets:3},{responsivePriority:6,targets:2}
  ]});

  // staff-req: First(0) Last(1) Position(2) [Email](3) Paperwork(4) BG(5) App(6) Hours(7) AmbReg(8) Fundraising(9) Notes(10)
  // (NOT touched by cleanup pass — col 0 was an Edit Requirements action; user/requirements.html.twig still has the edit pencil column)
  $('table#staff-req-table').DataTable({ order: [[2,"asc"],[1,"asc"]], columnDefs: [
    {responsivePriority:1, targets:1},{responsivePriority:2, targets:2},
    {responsivePriority:3, targets:5},{responsivePriority:4, targets:6},
    {responsivePriority:5, targets:7},{responsivePriority:6, targets:8},
    {responsivePriority:7, targets:9},{responsivePriority:8, targets:10},
    {responsivePriority:9, targets:3},{responsivePriority:10,targets:11},
    {responsivePriority:11,targets:0}
  ]});

  // letter-interview: Letter(0) Assignment(1)
  $('table#letter-interview-table').DataTable({ order: [[0,"asc"]], columnDefs: [
    {responsivePriority:1,targets:0},{responsivePriority:2,targets:1}
  ]});

  // bc: First(0) Last(1) Group(2) [Floor](3) [Sort](4) Dorm(5) Room(6) Bed col(7)
  $('table#bc-table').DataTable({ order: [[5,"asc"],[3,"asc"],[4,"asc"],[0,"asc"]], columnDefs: [
    {responsivePriority:1,targets:0},{responsivePriority:2,targets:1},
    {responsivePriority:3,targets:7},{responsivePriority:4,targets:5},
    {responsivePriority:5,targets:6},{responsivePriority:6,targets:2}
  ], buttons: ['excelHtml5','pdfHtml5','print']});

  // kd: First(0) Last(1) Group(2) Deposit(3) Details(4)
  $('table#kd-table').DataTable({ order: [[1,"asc"],[0,"asc"]], columnDefs: [
    {responsivePriority:1,targets:0},{responsivePriority:2,targets:1},
    {responsivePriority:3,targets:3},{responsivePriority:4,targets:4},
    {responsivePriority:5,targets:2}
  ]});

  // noshow: First(0) Last(1) Group(2) School(3) Paperwork(4) Cell(5) Home(6)
  $('table#noshow-table').DataTable({ order: [[1,"asc"],[0,"asc"]], columnDefs: [
    {responsivePriority:1,targets:0},{responsivePriority:2,targets:1},
    {responsivePriority:3,targets:4},{responsivePriority:4,targets:5},
    {responsivePriority:5,targets:3},{responsivePriority:6,targets:6},
    {responsivePriority:7,targets:2}
  ]});

  // checkin/checkout (legacy DataTable inits — these pages are now card lists, kept harmless)
  $('table#checkin-table').DataTable({ order:[[0,"asc"]], buttons:[] });
  $('table#checkout-table').DataTable({ order:[[0,"asc"]], buttons:[] });

  // staff (user index — legacy, page is now a card list)
  $('table#staff-table').DataTable({ order:[[1,"asc"],[0,"asc"]], columnDefs: [
    {responsivePriority:1,targets:0},{responsivePriority:2,targets:1},
    {responsivePriority:3,targets:2}
  ]});

  // dorm: First(0) Last(1) Group(2) [Floor](3) [Sort](4) Dorm(5) Room(6) Shirt(7)
  $('table#dorm-table').DataTable({ order:[[5,"asc"],[3,"asc"],[4,"asc"]], columnDefs: [
    {responsivePriority:1,targets:0},{responsivePriority:2,targets:1},
    {responsivePriority:3,targets:5},{responsivePriority:4,targets:6},
    {responsivePriority:5,targets:7},{responsivePriority:6,targets:2}
  ]});

  // letter-groups (all groups): Group(0) First(1) Last(2) Role(3) School(4) [Sort](5)
  $('table#letter-groups-table').DataTable({ order:[[0,"asc"],[5,"asc"],[2,"asc"]], columnDefs: [
    {responsivePriority:1,targets:1},{responsivePriority:2,targets:2},
    {responsivePriority:3,targets:0},{responsivePriority:4,targets:3},
    {responsivePriority:5,targets:4}
  ]});

  // all-thankyous: First(0) Last(1) Group(2) Type(3) Assignment(4)
  $('table#all-thankyous-table').DataTable({ order:[[2,"asc"],[0,"asc"]], columnDefs: [
    {responsivePriority:1,targets:0},{responsivePriority:2,targets:1},
    {responsivePriority:3,targets:3},{responsivePriority:4,targets:4},
    {responsivePriority:5,targets:2}
  ]});

  // staff duty assignments (cc/checkin/checkout assignments pages):
  // First(0) Last(1) Position(2) Assignment(3) Notes(4)
  $('table#staff-duty-assignments-table').DataTable({ order:[[1,"asc"],[0,"asc"]], columnDefs: [
    {responsivePriority:1,targets:0},{responsivePriority:2,targets:1},
    {responsivePriority:3,targets:3},{responsivePriority:4,targets:2},
    {responsivePriority:5,targets:4}
  ]});

  // letter_group/calls per-group call list: First(0) Last(1) School(2) Status(3) [Call button](4)
  $('table#my-group-calls-ambassadors-table').DataTable({ order:[[0,"asc"],[1,"asc"]], columnDefs: [
    {responsivePriority:1,targets:0},{responsivePriority:2,targets:1},
    {responsivePriority:3,targets:4},{responsivePriority:4,targets:3},
    {responsivePriority:5,targets:2}
  ]});

  // letter_group/thankyous per-group: First(0) Last(1) Type(2) Assignment(3)
  $('table#group-thankyou-table').DataTable({ order:[[0,"asc"]], columnDefs: [
    {responsivePriority:1,targets:0},{responsivePriority:2,targets:1},
    {responsivePriority:3,targets:3},{responsivePriority:4,targets:2}
  ]});

  // evaluations (NOT touched — col 0 is an Edit pencil action)
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

  // Checkout deposit toggle
  $('#checkout_checkout_deposit_decision').change(function() {
    var val = $(this).val();
    if (val === "return") { $('#checkout-deposit-return').show(); $('#checkout-deposit-keep').hide(); }
    else { $('#checkout-deposit-keep').show(); $('#checkout-deposit-return').hide(); }
  });

  // Eval bars
  $('.show-bars').barrating('show', { theme: 'bars-movie' });

});
