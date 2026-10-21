'use strict';

$(function () {
  const monthInput = document.getElementById('report-month');
  const currency = new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP',
    minimumFractionDigits: 2
  });
  const formatMoney = value => currency.format(Number(value) || 0);
  const table = $('.monthly-sales-table').DataTable({
    ajax: {
      url: '/json/monthly-sales-report',
      data: request => { request.month = monthInput.value; },
      dataSrc: response => {
        const summary = response.summary;
        $('#monthly-order-count').text(summary.order_count);
        $('#monthly-order-total').text(formatMoney(summary.order_total));
        $('#monthly-collected').text(formatMoney(summary.collected));
        $('#monthly-cash').text(formatMoney(summary.cash));
        $('#monthly-gcash').text(formatMoney(summary.gcash));
        $('#monthly-mobile').text(formatMoney(summary.mobile));
        $('#monthly-others').text(formatMoney(summary.others));
        $('#monthly-footer-total').text(formatMoney(summary.order_total));
        $('#monthly-report-title').text(`Sales Details - ${monthInput.value}`);
        return response.data;
      }
    },
    columns: [
      { data: 'date' },
      { data: 'invoice_number', defaultContent: '' },
      { data: 'customer_name', defaultContent: '' },
      { data: 'plate_number', defaultContent: '' },
      { data: 'cash', render: formatMoney },
      { data: 'gcash', render: formatMoney },
      { data: 'mobile', render: formatMoney },
      { data: 'others', render: formatMoney },
      { data: 'order_total', render: formatMoney }
    ],
    order: [[0, 'asc']],
    pageLength: 20,
    lengthMenu: [20, 50, 100],
    language: {
      search: '',
      searchPlaceholder: 'Search report',
      info: 'Displaying _START_ to _END_ of _TOTAL_ entries'
    },
    responsive: true
  });

  $('#apply-month').on('click', function () {
    if (monthInput.value) table.ajax.reload();
  });
  $(monthInput).on('change', function () {
    if (monthInput.value) table.ajax.reload();
  });
});