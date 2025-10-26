<?php 
  include_once __DIR__ . '/../includes/config/_init.php';  
  
  $PageTitle = "Admin - Reports | NIXAR POS";
  $CssPath = "assets/css/styles.css";
  $JSPath = "assets/js/scripts.js";

  include_once '../includes/head.php'; 
  SessionManager::checkSession();
?>

  <div class="container-fluid p-0 m-0 h-100 px-4 py-3 d-flex flex-column">
    <?php include_once '../includes/components/nav.php'; ?>
    
    <div class="row container-fluid p-0 m-0 mt-3 flex-fill mb-3 gap-3">
      <div class="col-12 bg-light p-4 rounded-3">
        <div class="report-dashboard">

          <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h2 class="fw-bold mb-0">Reports</h2>
              <div class="d-flex align-items-center gap-2">
                <input type="date" class="form-control" value="2025-01-01">
                <span>to</span>
                <input type="date" class="form-control" value="2025-02-02">
                <button class="btn w-100">Generate Report</button>
              </div>
            </div>
            <div class="card-grid">
              <div class="card text-left">
                <div class="card-image"> <img src="../assets/svg/nixar-logo-red.svg" class="card-img" alt="Placeholder Image"></div>
                <h6>Total Revenue</h6>
                <p class="metric">₱99,999</p>
              </div>
              <div class="card text-left">
                <div class="card-image"> <img src="../assets/svg/nixar-logo-red.svg" class="card-img" alt="Placeholder Image"></div>
                <h6>Number of Transactions</h6>
                <p class="metric">67</p>
              </div>
              <div class="card text-left">
                <div class="card-image"> <img src="../assets/svg/nixar-logo-red.svg" class="card-img" alt="Placeholder Image"></div>
                <h6>Average Transaction Value</h6>
                <p class="metric">₱200</p>
              </div>
              <div class="card text-left">
                <div class="card-image"> <img src="../assets/svg/nixar-logo-red.svg" class="card-img" alt="Placeholder Image"></div>
                <h6>Profit Performance (vs. last month)</h6>
                <p class="metric">30% ↑</p>
              </div>
            </div>
          </section>

          <section>
            <h2 class="fw-bold">Inventory</h2>
            <div class="card-grid">
              <div class="card text-left">
                <div class="card-image"> <img src="../assets/svg/nixar-logo-red.svg" class="card-img" alt="Placeholder Image"></div>
                <h6>Most Sold Item</h6>
                <p class="metric">Windshield Glass</p>
              </div>
              <div class="card text-left">
                <div class="card-image"> <img src="../assets/svg/nixar-logo-red.svg" class="card-img" alt="Placeholder Image"></div>
                <h6>Best-selling Item (by revenue)</h6>
                <p class="metric">Wiper</p>
              </div>
              <div class="card text-left">
                <div class="card-image"> <img src="../assets/svg/nixar-logo-red.svg" class="card-img" alt="Placeholder Image"></div>
                <h6>Best-selling Category</h6>
                <p class="metric">Glass</p>
              </div>
              <div class="card text-left">
                <div class="card-image"> <img src="../assets/svg/nixar-logo-red.svg" class="card-img" alt="Placeholder Image"></div>
                <h6>Low Stock Items</h6>
                <p class="metric">9</p>
              </div>
            </div>
          </section>

        </div>
      </div>
    </div>
  </div>

<?php include_once '../includes/footer.php'; ?>
