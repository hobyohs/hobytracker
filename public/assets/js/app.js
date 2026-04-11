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
        maxItems: 1,
        maxOptions: 500,
        hideSelected: true,
        closeAfterSelect: true,
        placeholder: el.getAttribute('placeholder') || 'Type to search…',
        // When focused, hide the rendered selection visually so the user
        // gets a clean text input. We do this via a CSS class toggle on
        // the wrapper so we never touch the underlying form value — if
        // the user blurs without picking a new option, the original
        // selection's display reappears unchanged.
        onFocus:  function() { this.wrapper.classList.add('ts-typing'); },
        onBlur:   function() { this.wrapper.classList.remove('ts-typing'); },
        onChange: function() { this.wrapper.classList.remove('ts-typing'); }
      });
    });
  }

  // Bootstrap tooltip
  $('[data-toggle="tooltip"]').tooltip({ container: 'body', html: true, placement: 'bottom' });

  // ── DataTables ────────────────────────────────────────────
  // ── DataTables: ht-responsive-table pattern ──────────────────
  // All tables use responsive:false. Layout is handled by CSS card
  // pattern on mobile. Avatar(0) is always non-orderable/non-searchable.
  // Chevron is always the last column, non-orderable.

  // dietary: Av(0) First(1) Last(2) Group(3) Restrictions(4) Info(5) Chev(6)
  $('table#dietary-table').DataTable({ order: [[2,"asc"],[1,"asc"]], responsive: false, columnDefs: [
    { targets: [0,6], orderable: false }, { targets: 0, searchable: false }
  ], initComplete: function() { $(this).closest('.ht-dt-card').find('.dataTables_filter input').attr('placeholder','Search…'); initSearchClear(this.api().table().container().closest('.ht-dt-card')); }});

  // ec: Av(0) First(1) Last(2) Group(3) ECFirst(4) ECLast(5) Rel(6) Phone1(7) Phone2(8) Chev(9)
  $('table#ec-table').DataTable({ order: [[2,"asc"],[1,"asc"]], responsive: false, columnDefs: [
    { targets: [0,9], orderable: false }, { targets: 0, searchable: false }
  ], initComplete: function() { $(this).closest('.ht-dt-card').find('.dataTables_filter input').attr('placeholder','Search…'); initSearchClear(this.api().table().container().closest('.ht-dt-card')); }});

  // medical: Av(0) First(1) Last(2) Group(3) Rx(4) Conditions(5) Exercise(6) Allergies(7) MedAllergies(8) Chev(9)
  $('table#med-table').DataTable({ order: [[2,"asc"],[1,"asc"]], responsive: false, columnDefs: [
    { targets: [0,9], orderable: false }, { targets: 0, searchable: false }
  ], initComplete: function() { $(this).closest('.ht-dt-card').find('.dataTables_filter input').attr('placeholder','Search…'); initSearchClear(this.api().table().container().closest('.ht-dt-card')); }});

  // applicant: Av(0) First(1) Last(2) Position(3) R1(4) R2(5) Decision(6) Chev(7)
  $('table#applicant-table').DataTable({ order: [[2,"asc"],[1,"asc"]], responsive: false, columnDefs: [
    { targets: [0,6,7], orderable: false }, { targets: 0, searchable: false }
  ], initComplete: function() { $(this).closest('.ht-dt-card').find('.dataTables_filter input').attr('placeholder','Search…'); initSearchClear(this.api().table().container().closest('.ht-dt-card')); }});

  // psm: Av(0) First(1) Last(2) Group(3) School(4) Forms(5) Deposit(6) [Method](7) [Notes](8) CG(9) [emails 10-12] Chev(13)
  $('table#psm-table').DataTable({ order: [[2,"asc"],[1,"asc"]], responsive: false, columnDefs: [
    { targets: [0,13], orderable: false }, { targets: 0, searchable: false }
  ], initComplete: function() { $(this).closest('.ht-dt-card').find('.dataTables_filter input').attr('placeholder','Search…'); initSearchClear(this.api().table().container().closest('.ht-dt-card')); }});

  // preseminarcalls: Av(0) First(1) Last(2) Group(3) Cell(4) School(5) Junior(6) Forms(7) Disposition(8) Notes(9) Chev(10)
  $('table#calls-table').DataTable({ order: [[2,"asc"],[1,"asc"]], responsive: false, columnDefs: [
    { targets: [0,10], orderable: false }, { targets: 0, searchable: false }
  ], initComplete: function() { $(this).closest('.ht-dt-card').find('.dataTables_filter input').attr('placeholder','Search…'); initSearchClear(this.api().table().container().closest('.ht-dt-card')); }});

  // bus: First(0) Last(1) Group(2) School(3) BusTo(4) BusFrom(5)
  // bus: Avatar(0) First(1) Last(2) Group(3) School(4) BusTo(5) BusFrom(6) Chevron(7)
  $('table#bus-table').DataTable({ order: [[2,"asc"],[1,"asc"]], responsive: false, columnDefs: [
    { targets: [0, 7], orderable: false },
    { targets: 0, searchable: false }
  ], initComplete: function() {
    $('#bus-report-wrap .dataTables_filter input').attr('placeholder', 'Search\u2026');
    initSearchClear(document.getElementById('bus-report-wrap'));
  }});

  // staff-req: Av(0) First(1) Last(2) Position(3) [Email](4) Paperwork(5) BG(6) App(7) Hours(8) AmbReg(9) Fundraising(10) Notes(11) Chev(12)
  $('table#staff-req-table').DataTable({ order: [[3,"asc"],[2,"asc"]], responsive: false, columnDefs: [
    { targets: [0,12], orderable: false }, { targets: 0, searchable: false }
  ], initComplete: function() { $(this).closest('.ht-dt-card').find('.dataTables_filter input').attr('placeholder','Search…'); initSearchClear(this.api().table().container().closest('.ht-dt-card')); }});

  // letter-interview: Letter(0) Assignment(1) — no avatar/chevron, leave as-is
  $('table#letter-interview-table').DataTable({ order: [[0,"asc"]], columnDefs: [
    {responsivePriority:1,targets:0},{responsivePriority:2,targets:1}
  ]});

  // bc: First(0) Last(1) Group(2) [Floor](3) [Sort](4) Dorm(5) Room(6) Bed col(7)
  $('table#bc-table').DataTable({ order: [[5,"asc"],[3,"asc"],[4,"asc"],[0,"asc"]], columnDefs: [
    {responsivePriority:1,targets:0},{responsivePriority:2,targets:1},
    {responsivePriority:3,targets:7},{responsivePriority:4,targets:5},
    {responsivePriority:5,targets:6},{responsivePriority:6,targets:2}
  ], buttons: ['excelHtml5','pdfHtml5','print']});

  // kd: Av(0) First(1) Last(2) Group(3) Deposit(4) Details(5) Chev(6)
  $('table#kd-table').DataTable({ order: [[2,"asc"],[1,"asc"]], responsive: false, columnDefs: [
    { targets: [0,6], orderable: false }, { targets: 0, searchable: false }
  ], initComplete: function() { $(this).closest('.ht-dt-card').find('.dataTables_filter input').attr('placeholder','Search…'); initSearchClear(this.api().table().container().closest('.ht-dt-card')); }});

  // noshow: Av(0) First(1) Last(2) Group(3) School(4) Paperwork(5) Cell(6) Home(7) Chev(8)
  $('table#noshow-table').DataTable({ order: [[2,"asc"],[1,"asc"]], responsive: false, columnDefs: [
    { targets: [0,8], orderable: false }, { targets: 0, searchable: false }
  ], initComplete: function() { $(this).closest('.ht-dt-card').find('.dataTables_filter input').attr('placeholder','Search…'); initSearchClear(this.api().table().container().closest('.ht-dt-card')); }});

  // checkin/checkout (legacy DataTable inits — these pages are now card lists, kept harmless)
  $('table#checkin-table').DataTable({ order:[[0,"asc"]], buttons:[] });
  $('table#checkout-table').DataTable({ order:[[0,"asc"]], buttons:[] });

  // staff (user index — page is now a card list)
  $('table#staff-table').DataTable({ order:[[1,"asc"],[0,"asc"]], columnDefs: [
    {responsivePriority:1,targets:0},{responsivePriority:2,targets:1},
    {responsivePriority:3,targets:2}
  ]});

  // dorm: Av(0) First(1) Last(2) Group(3) [Floor](4) [Sort](5) Dorm(6) Room(7) Shirt(8) Chev(9)
  $('table#dorm-table').DataTable({ order:[[6,"asc"],[4,"asc"],[5,"asc"]], responsive: false, columnDefs: [
    { targets: [0,9], orderable: false }, { targets: 0, searchable: false }
  ], initComplete: function() { $(this).closest('.ht-dt-card').find('.dataTables_filter input').attr('placeholder','Search…'); initSearchClear(this.api().table().container().closest('.ht-dt-card')); }});

  // letter-groups (all groups): Av(0) Group(1) First(2) Last(3) Role(4) School(5) [Sort](6) Chev(7)
  $('table#letter-groups-table').DataTable({ order:[[1,"asc"],[6,"asc"],[3,"asc"]], responsive: false, columnDefs: [
    { targets: [0,7], orderable: false }, { targets: 0, searchable: false }
  ], initComplete: function() { $(this).closest('.ht-dt-card').find('.dataTables_filter input').attr('placeholder','Search…'); initSearchClear(this.api().table().container().closest('.ht-dt-card')); }});

  // all-thankyous: Av(0) First(1) Last(2) Group(3) Type(4) Assignment(5) Chev(6)
  $('table#all-thankyous-table').DataTable({ order:[[3,"asc"],[1,"asc"]], responsive: false, columnDefs: [
    { targets: [0,6], orderable: false }, { targets: 0, searchable: false }
  ], initComplete: function() { $(this).closest('.ht-dt-card').find('.dataTables_filter input').attr('placeholder','Search…'); initSearchClear(this.api().table().container().closest('.ht-dt-card')); }});

  // staff duty assignments: Av(0) First(1) Last(2) Position(3) Assignment(4) Notes(5) Chev(6)
  $('table#staff-duty-assignments-table').DataTable({ order:[[2,"asc"],[1,"asc"]], responsive: false, columnDefs: [
    { targets: [0,6], orderable: false }, { targets: 0, searchable: false }
  ], initComplete: function() { $(this).closest('.ht-dt-card').find('.dataTables_filter input').attr('placeholder','Search…'); initSearchClear(this.api().table().container().closest('.ht-dt-card')); }});

  // group calls: Av(0) First(1) Last(2) School(3) Status(4) Chev(5)
  $('table#my-group-calls-ambassadors-table').DataTable({ order:[[1,"asc"],[2,"asc"]], responsive: false, columnDefs: [
    { targets: [0,5], orderable: false }, { targets: 0, searchable: false }
  ], initComplete: function() { $(this).closest('.ht-dt-card').find('.dataTables_filter input').attr('placeholder','Search…'); initSearchClear(this.api().table().container().closest('.ht-dt-card')); }});

  // group thankyous: Av(0) First(1) Last(2) Type(3) Assignment(4) Chev(5)
  $('table#group-thankyou-table').DataTable({ order:[[1,"asc"]], responsive: false, columnDefs: [
    { targets: [0,5], orderable: false }, { targets: 0, searchable: false }
  ], initComplete: function() { $(this).closest('.ht-dt-card').find('.dataTables_filter input').attr('placeholder','Search…'); initSearchClear(this.api().table().container().closest('.ht-dt-card')); }});

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

  // ── Responsive table: search clear button ─────────────────────
  // Works for both DataTables filter inputs (.dataTables_filter input)
  // and custom card-filter inputs (.ht-search-input).
  function addClearBtn(input, clearFn) {
    if (!input || input.dataset.clearInit) return;
    input.dataset.clearInit = '1';

    var btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'ht-search-clear';
    btn.setAttribute('aria-label', 'Clear search');
    btn.innerHTML = '<i class="fa fa-times"></i>';
    btn.style.display = 'none';

    var container = document.createElement('div');
    container.className = 'ht-search-wrap';
    input.parentNode.insertBefore(container, input);
    container.appendChild(input);
    container.appendChild(btn);

    function updateBtn() {
      btn.style.display = input.value.length > 0 ? 'flex' : 'none';
    }

    input.addEventListener('input', updateBtn);
    input.addEventListener('keyup', updateBtn);

    btn.addEventListener('click', function() {
      input.value = '';
      clearFn();
      btn.style.display = 'none';
      input.focus();
    });
  }

  function initSearchClear(wrapper) {
    var input = wrapper ? wrapper.querySelector('.dataTables_filter input') : null;
    if (!input) return;
    addClearBtn(input, function() {
      $(input).closest('.dataTables_wrapper').find('table').DataTable().search('').draw();
    });
  }

  // DataTables tables — also called from each table's initComplete
  document.querySelectorAll('.ht-responsive-table .dataTables_wrapper').forEach(initSearchClear);

  // Custom card-filter inputs (checkin, checkout index pages)
  ['checkinSearch', 'checkoutSearch'].forEach(function(id) {
    var input = document.getElementById(id);
    if (!input) return;
    addClearBtn(input, function() {
      // Trigger the existing card filter by dispatching an input event
      input.dispatchEvent(new Event('input'));
    });
  });

  // ── Responsive table: whole-row click navigation ─────────────
  // Rows with data-href navigate on click. Clicks on <a> or <button>
  // inside the row are ignored so those elements keep their own behaviour.
  document.querySelectorAll('.ht-responsive-table tbody tr[data-href]').forEach(function(row) {
    row.addEventListener('click', function(e) {
      if (e.target.closest('a, button')) return;
      window.location.href = row.dataset.href;
    });
  });

  // ── Script Pages: sticker override buttons ──────────────────
  // Markup: <button class="ht-override-btn" data-override-action="toggle-cg|toggle-meds">
  // Inside <div class="ht-override-pair" data-toggle-url="...">
  // The toggle URL is generated server-side via Twig's path() helper so
  // it works regardless of where the app is mounted (subdirectory, etc).

  // ── Script Pages: C&G remove (deactivate) button ─────────────
  // Markup: <button class="ht-cg-remove-btn" data-deactivate-url="...">
  // POSTs to the deactivate endpoint, then reloads so cg_status is
  // re-evaluated server-side and the correct section is shown.
  document.querySelectorAll('.ht-cg-remove-btn').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      var url = btn.dataset.deactivateUrl;
      if (!url) return;

      btn.disabled = true;
      btn.textContent = 'Removing\u2026';

      fetch(url, { method: 'POST', credentials: 'same-origin' })
        .then(function(r) { return r.json(); })
        .then(function(data) {
          if (data && data.success) {
            window.location.reload();
          } else {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-times"></i> Remove from schedule';
          }
        })
        .catch(function(err) {
          console.error('C&G deactivate failed', err);
          btn.disabled = false;
          btn.innerHTML = '<i class="fa fa-times"></i> Remove from schedule';
        });
    });
  });

  document.querySelectorAll('.ht-override-btn[data-override-action]').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      var pair = btn.closest('.ht-override-pair');
      if (!pair) return;
      var url = pair.dataset.toggleUrl;
      if (!url) return;

      fetch(url, { method: 'POST', credentials: 'same-origin' })
        .then(function(r) { return r.json(); })
        .then(function(data) {
          if (data && data.success) {
            pair.classList.toggle('override-active', data.state === 'on');
          }
        })
        .catch(function(err) {
          console.error('Override toggle failed', err);
        });
    });
  });

  // ── Script Pages: segmented buttons (sync to hidden select) ─
  // Markup: <div class="ht-segmented" data-segmented-for="<select_id>">
  //           <label data-value="x">X</label> ...
  //         </div>
  // The hidden <select> next to it holds the actual form value.
  document.querySelectorAll('.ht-segmented[data-segmented-for]').forEach(function(group) {
    var selectId = group.dataset.segmentedFor;
    var select = document.getElementById(selectId);
    if (!select) return;

    var labels = group.querySelectorAll('label');

    function applyValue(val) {
      labels.forEach(function(l) {
        l.classList.toggle('is-selected', l.dataset.value === val);
      });
    }

    // Initialize visual state from current select value
    applyValue(select.value);

    labels.forEach(function(label) {
      label.addEventListener('click', function() {
        var val = this.dataset.value;
        select.value = val;
        // Fire change event so any listeners (e.g. the checkout toggle) react
        select.dispatchEvent(new Event('change', { bubbles: true }));
        applyValue(val);
      });
    });
  });

  // ── Script Pages: radio cards (sync to hidden select) ───────
  // Markup: <div class="ht-radio-cards" data-radio-cards-for="<select_id>">
  //           <div class="ht-radio-card" data-value="x">...</div> ...
  //         </div>
  document.querySelectorAll('.ht-radio-cards[data-radio-cards-for]').forEach(function(group) {
    var selectId = group.dataset.radioCardsFor;
    var select = document.getElementById(selectId);
    if (!select) return;

    var cards = group.querySelectorAll('.ht-radio-card');

    function applyValue(val) {
      cards.forEach(function(c) {
        c.classList.toggle('is-selected', c.dataset.value === val);
      });
    }

    applyValue(select.value);

    cards.forEach(function(card) {
      card.addEventListener('click', function() {
        var val = this.dataset.value;
        select.value = val;
        select.dispatchEvent(new Event('change', { bubbles: true }));
        applyValue(val);
      });
    });
  });

  // Checkout deposit toggle — show the right script section based on choice
  $('#checkout_checkout_deposit_decision').change(function() {
    var val = $(this).val();
    if (val === "return") { $('#checkout-deposit-return').show(); $('#checkout-deposit-keep').hide(); }
    else if (val === "movie" || val === "lost") { $('#checkout-deposit-keep').show(); $('#checkout-deposit-return').hide(); }
    else { $('#checkout-deposit-return').hide(); $('#checkout-deposit-keep').hide(); }
  }).trigger('change');

  // Eval bars
  $('.show-bars').barrating('show', { theme: 'bars-movie' });

});
