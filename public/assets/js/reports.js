/* ================= REPORT REFERENCES ================= */
const categoryTable = document.getElementById('sales-table-category');
const timeTable = document.getElementById('sales-table-time');
const soldTable = document.getElementById('inventory-table-sold');
const sellingTable = document.getElementById('inventory-table-selling');
const stockTable = document.getElementById('inventory-table-sock');

//for switching from inventory to sales(in dropdown)/vice versa
document.getElementById('reportType').addEventListener('change', function() {
    const sales = document.getElementById('salesReport');
    const inventory = document.getElementById('inventoryReport');
    if (this.value === 'sales') {
        sales.style.display = 'block';
        inventory.style.display = 'none';
    } else {
        sales.style.display = 'none';
        inventory.style.display = 'block';
    }
});

const fetchSalesMetrics = async () => {
    try {
        const response = await fetch('handlers/fetch_reports_metrics.php');
        const data = await response.json();

        if (Object.keys(data).length === 0) {
            console.log("No metrics found");
            document.getElementById('totalRevenue').innerText = 'No data';
            document.getElementById('numOfTransactions').innerText = 'No data';
            document.getElementById('avgTransactionValue').innerText = 'No data';
            document.getElementById('profitPerformance').innerText = 'No data';
            return;
        }
        document.getElementById('totalRevenue').innerText = '₱' + data.total_revenue;
        document.getElementById('numOfTransactions').innerText = data.total_transactions;
        document.getElementById('avgTransactionValue').innerText = '₱' + data.avg_transaction_value;
        document.getElementById('profitPerformance').innerText = data.profit_performance + '%';

        console.log(data); 
    } catch (err) {
        console.error(err.message);
    }
};

const fetchInventoryMetricRevenue = async () => {
    try {
        const response = await fetch('handlers/fetch_inventory_reports_metrics_revenue.php'); 
        const data = await response.json();

        if (Object.keys(data).length === 0) {
            console.log("No metrics found");
            document.getElementById('best-selling-revenue').innerText = 'No data';
            document.getElementById('best-selling-category').innerText = 'No data';
            return;
        }
        document.getElementById('best-selling-revenue').innerText = data.product_name;
        document.getElementById('best-selling-category').innerText = data.category;

        console.log(data); 
    } catch (err) {
        console.error(err.message);
    }
};

const fetchInventoryMetricStock = async () => {
    try {
        const response = await fetch('handlers/fetch_inventory_reports_stocks_count.php'); 
        const data = await response.json();

        if (Object.keys(data).length === 0) {
            console.log("No metrics found");
            document.getElementById('low-stock').innerText = 'No data';
            return;
        }
        document.getElementById('low-stock').innerText = data.low_stock;

        console.log(data); 
    } catch (err) {
        console.error(err.message);
    }
};

const fetchInventoryMetricMostSold = async () => {
    try {
        const response = await fetch('handlers/fetch_inventory_reports_metrics_sold.php'); 
        const data = await response.json();

        if (Object.keys(data).length === 0) {
            console.log("No metrics found");
            document.getElementById('most-sold-item').innerText = 'No data';
            return;
        }
        document.getElementById('most-sold-item').innerText = data;

        console.log(data); 
    } catch (err) {
        console.error(err.message);
    }
};

const generateSalesMetricCategory= (data) => {
  return data.map(item => `
    <tr>
      <td>${item.category}</td>
      <td>${item.category_performance}</td>
    </tr>
  `).join('\n');
};

const renderSalesMetricCategory = (tableElement, data) => {
  const rowsHtml = generateSalesMetricCategory(data);
  tableElement.innerHTML = rowsHtml;
};

const fetchSalesMetricCategory = async () => {
    try {
        const response = await fetch('handlers/fetch_reports_category.php'); 
        const data = await response.json();

        renderSalesMetricCategory(categoryTable, data);

    } catch (err) {
        console.error(err);
        table.innerHTML = `<tr><td colspan="2" style="text-align:center;">Error loading data</td></tr>`;
    }
};

const generateSalesMetricTime = (data) => {
  return data.map(item => `
    <tr>
      <td>${item.hour_label}</td>
      <td>${item.total_orders}</td>
    </tr>
  `).join('\n');
};

const renderSalesMetricTime = (tableElement, data) => {
  const rowsHtml = generateSalesMetricTime(data);
  tableElement.innerHTML = rowsHtml;
};

const fetchSalesMetricTime = async () => {
    try {
        const response = await fetch('handlers/fetch_reports_time.php'); 
        const data = await response.json();

        renderSalesMetricTime(timeTable, data);

    } catch (err) {
        console.error(err);
        table.innerHTML = `<tr><td colspan="2" style="text-align:center;">Error loading data</td></tr>`;
    }
};

const generateInventoryMetricSold = (data) => {
  return data.map(item => `
    <tr>
      <td>${item.product_name}</td>
      <td>${item.total_quantity_sold}</td>
    </tr>
  `).join('\n');
};

const renderInventoryMetricSold = (tableElement, data) => {
  const rowsHtml = generateInventoryMetricSold(data);
  tableElement.innerHTML = rowsHtml;
};

const fetchInventoryMetricSold = async () => {
    try {
        const response = await fetch('handlers/fetch_inventory_reports_sold.php'); 
        const data = await response.json();

        renderInventoryMetricSold(soldTable, data);
    } catch (err) {
        console.error(err);
        table.innerHTML = `<tr><td colspan="2" style="text-align:center;">Error loading data</td></tr>`;
    }
};

const generateInventoryMetricSelling = (data) => {
  return data.map(item => `
    <tr>
      <td>${item.product_name}</td>
      <td>${item.grouped_price}</td>
    </tr>
  `).join('\n');
};

const renderInventoryMetricSelling = (tableElement, data) => {
  const rowsHtml = generateInventoryMetricSelling(data);
  tableElement.innerHTML = rowsHtml;
};

const fetchInventoryMetricSelling = async () => {
    try {
        const response = await fetch('handlers/fetch_inventory_reports_selling.php'); 
        const data = await response.json();

        renderInventoryMetricSelling(sellingTable, data);
    } catch (err) {
        console.error(err);
        table.innerHTML = `<tr><td colspan="2" style="text-align:center;">Error loading data</td></tr>`;
    }
};

const generateInventoryMetricStock = (data) => {
  return data.map(item => `
    <tr>
      <td>${item.product_name}</td>
      <td>${item.current_stock}</td>
    </tr>
  `).join('\n');
};

const renderInventoryMetricStock = (tableElement, data) => {
  const rowsHtml = generateInventoryMetricStock(data);
  tableElement.innerHTML = rowsHtml;
};

const fetchInventoryMetricListStock = async () => {
    try {
        const response = await fetch('handlers/fetch_inventory_reports_stocks.php'); 
        const data = await response.json();

        renderInventoryMetricStock(stockTable, data);
        console.log(data)
    } catch (err) {
        console.error(err);
        table.innerHTML = `<tr><td colspan="2" style="text-align:center;">Error loading data</td></tr>`;
    }
};

document.addEventListener('DOMContentLoaded', () =>{
    fetchInventoryMetricStock();
    fetchInventoryMetricListStock();
    fetchInventoryMetricMostSold();
    fetchInventoryMetricRevenue();
    fetchSalesMetrics();
    fetchSalesMetricTime();
    fetchSalesMetricCategory();
    fetchInventoryMetricSold();
    fetchInventoryMetricSelling()
});
