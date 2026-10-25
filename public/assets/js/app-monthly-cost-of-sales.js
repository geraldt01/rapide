'use strict';

$(function () {
  const monthInput = document.getElementById('report-month');
  const currency = new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP',
    minimumFractionDigits: 2
  });
  const formatMoney = value => currency.format(Number(value) || 0);
  const escapeHtml = value => $('<div>').text(value == null ? '' : String(value)).html();
  const renderText = (value, type) => type === 'display' ? escapeHtml(value) : value;
  let reportRows = [];

  const table = $('#monthly-cost-of-sales-table').DataTable({
    ajax: {
      url: '/json/monthly-cost-of-sales',
      data: request => { request.month = monthInput.value; },
      dataSrc: response => {
        const summary = response.summary;
        reportRows = response.data;
        $('#monthly-item-count').text(summary.item_count);
        $('#monthly-total-cost').text(formatMoney(summary.total_cost));
        $('#monthly-total-sell').text(formatMoney(summary.total_sell_price));
        $('#monthly-report-title').text(`Cost of Sales Details - ${monthInput.value}`);
        return response.data;
      },
      error: () => {
        reportRows = [];
        $('#monthly-item-count').text('0');
        $('#monthly-total-cost, #monthly-total-sell').text(formatMoney(0));
        $('#monthly-cost-of-sales-table tbody').empty();
      }
    },
    columns: [
      { data: 'date', defaultContent: '', render: renderText },
      { data: 'customer_name', defaultContent: '', render: renderText },
      { data: 'address', defaultContent: '', render: renderText },
      {
        data: 'car_details',
        defaultContent: '',
        render: (value, type, row) => {
          const details = escapeHtml(value);
          return type === 'display' && row.job_order_id
            ? `<a href="/app/job-order/${encodeURIComponent(row.job_order_id)}"><b>${details}</b></a>`
            : details;
        }
      },
      { data: 'invoice_number', defaultContent: '', render: renderText },
      { data: 'job_order_number', defaultContent: '', render: renderText },
      { data: 'qty', defaultContent: '', render: renderText },
      { data: 'details', defaultContent: '', render: renderText },
      { data: 'part_number', defaultContent: '', render: renderText },
      { data: 'supplier', defaultContent: '', render: renderText },
      { data: 'supplier_invoice', defaultContent: '', render: renderText },
      { data: 'unit_cost', render: formatMoney },
      { data: 'total_cost', render: formatMoney },
      { data: 'unit_selling_price', render: formatMoney },
      { data: 'total_sell_price', render: formatMoney },
      {
        data: 'job_order_id',
        orderable: false,
        searchable: false,
        render: (value, type) => type === 'display' && value
          ? `<a href="/app/job-order/${encodeURIComponent(value)}" class="btn btn-sm btn-icon" aria-label="Edit job order"><i class="mdi mdi-pencil-outline"></i></a>`
          : ''
      }
    ],
    ordering: false,
    pageLength: 20,
    lengthMenu: [20, 50, 100],
    language: {
      search: '',
      searchPlaceholder: 'Search report',
      info: 'Displaying _START_ to _END_ of _TOTAL_ entries'
    },
    scrollX: true
  });

  const loadMonth = () => {
    if (!/^\d{4}-(0[1-9]|1[0-2])$/.test(monthInput.value)) return;
    window.history.replaceState({}, '', `/app/monthly-cost-of-sales/${monthInput.value}`);
    table.ajax.reload();
  };

  $('#apply-month').on('click', loadMonth);
  $(monthInput).on('change', loadMonth);

  $('#export-month').on('click', function () {
    const headers = ['Date', 'Customer Name', 'Address', 'Car Details', 'INV#', 'JO#', 'QTY', 'DETAILS', 'PART NO.', 'SUPPLIER', 'SUPPLIER INV', 'UNIT COST', 'TOTAL COST', 'UNIT SELLING PRICE WITH LABOR', 'TOTAL SELL PRICE'];
    const fields = ['date', 'customer_name', 'address', 'car_details', 'invoice_number', 'job_order_number', 'qty', 'details', 'part_number', 'supplier', 'supplier_invoice', 'unit_cost', 'total_cost', 'unit_selling_price', 'total_sell_price'];
    const csvValue = value => `"${String(value == null ? '' : value).replace(/"/g, '""')}"`;
    const lines = [headers, ...reportRows.map(row => fields.map(field => row[field]))]
      .map(line => line.map(csvValue).join(','));
    const blob = new Blob([`\ufeff${lines.join('\r\n')}`], { type: 'text/csv;charset=utf-8;' });
    const downloadUrl = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = downloadUrl;
    link.download = `cost-of-sales-${monthInput.value}.csv`;
    link.click();
    URL.revokeObjectURL(downloadUrl);
  });
});