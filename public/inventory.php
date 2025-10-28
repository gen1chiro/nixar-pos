<?php
  include_once __DIR__ . '/../includes/config/_init.php';  

  $PageTitle = "Admin - Inventory | NIXAR POS";
  $CssPath = "assets/css/styles.css";
  $JSPath = "assets/js/scripts.js";

  $CarTypes = [
      'Sedan', 'Hatchback', 'SUV', 'Pickup', 'Coupe',
      'Convertible', 'Van', 'Minivan', 'Wagon', 'Jeep', 
      'Truck', 'Electric Vehicle'
  ];
  
  $ProductCategories = [
    'Glass', 'Accessories', 'Tints', 'Mirrors'
  ];
  
  
  $Conn = DatabaseConnection::getInstance()->getConnection();
  $Supplier = new Supplier($Conn);
  $SupplierData = $Supplier->fetchAll();
  
  $ProductMaterials = [
    'laminatedGlass' => 'Laminated Glass', 
    'temperedGlass' => 'Tempered Glass', 
    'ceramicFilm' => 'Ceramic Film', 
    'plasticAcrylicComposite' => 'Plastic/Acrylic Composite', 
    'rubberMetalComposite' => 'Rubber and Metal Composite'
  ];
  
  include_once '../includes/head.php';
  
  SessionManager::checkSession();
?>
  <div class="container-fluid p-0 m-0 h-100 px-4 py-3  d-flex flex-column overflow-x-hidden">
    <?php include_once '../includes/components/nav.php'; ?>
    
    <div class="row container-fluid p-0 m-0 mt-3 flex-fill mb-3 gap-3">
      <!-- Left Sidebar - Filters -->
      <div class="col-md-2 bg-white p-4 border border-3 shadow-sm rounded-3">
        <h4 class="fw-bold mb-4">Filter Search</h4>
        
        <!-- Product Category -->
        <div class="mb-4">
          <h6 class="fw-semibold mb-3">Product Materials</h6>
          <?php foreach($ProductMaterials as $Id => $Material): ?>
            <div class="form-check">
              <input 
                class="form-check-input" 
                type="radio" 
                name="category" 
                id="<?= $Id ?>"
                value="<?= $Material ?>"
              >
              <label class="form-check-label" for="<?= $Id ?>"><?= $Material ?></label>
            </div>
          <?php endforeach; ?>
        </div>
        
        <!-- Car Model -->
        <div class="mb-4">
          <label for="carModel" class="fw-semibold mb-3">Car Model</label>
          <input type="text" placeholder="e.g., Fortuner, Toyota, ..." class="text-input" id="carModel">
        </div>
        
        <!-- Car Type -->
        <div class="mb-4">
          <label for="carType" class="fw-semibold mb-3">Car Type</label>
          <select id="carType" class="form-select">
            <option selected value="default" disabled>Select Car Type</option>
            <?php foreach($CarTypes as $Type): ?>
              <option value="<?= $Type ?>">
                <?= $Type; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        
        <!-- Stock Availability -->
        <div class="mb-4">
          <h6 class="fw-semibold mb-3">Stock Availability</h6>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="stockStatus" id="stock" value="inStock">
            <label class="form-check-label" for="stock">Stock</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="stockStatus" id="outOfStock" value="notInStock">
            <label class="form-check-label" for="outOfStock">Out of Stock</label>
          </div>
        </div>
        
        <!-- Price Range -->
        <div class="d-flex justify-content-between align-items-center mb-2">
          <label for="priceRange" class="fw-semibold">Price Range</label>
          <div class="d-flex align-items-center gap-2">
            <span>₱</span>
            <input
              type="number"
              id="priceValue"
              class="text-input"
              min="0"
              max="10000"
              step="100"
              value="0"
              oninput="document.getElementById('priceRange').value = this.value"
            >
          </div>
        </div>
        
        <input
          type="range"
          class="form-range"
          id="priceRange"
          min="0"
          max="25000"
          step="100"
          value="0"
          oninput="document.getElementById('priceValue').value = this.value"
        />
        
        <div class="d-flex flex-column gap-1 mt-2">
          <button class="btn w-100" onClick="searchByFilters()">Apply Filter</button>
          <button class="btn w-100" onclick="resetFilters()">Reset Filter</button>
        </div>
      </div>
      
      <!-- Right Content - Inventory Table -->
      <div class="col bg-light p-4 rounded-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h2 class="fw-bold">Inventory</h2>
          <?php if(SessionManager::get('role') === 'admin'): ?>
            <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#addProductModal">
              Add Product
            </button>
          <?php endif; ?>
        </div>
        
        <div class="d-flex gap-2 mb-4">
          <input type="text" class="text-input" id="search-input" placeholder="Search by car model, type, or product...">
          <button class="btn" onClick="searchProducts()">Search</button>
        </div>
        
        <!--=================  INVENTORY TABLE  =================-->
        <div class="table-responsive" id="container-inventory-tbl">
          <table class="table table-striped bg-white">
            <thead class="color-primary-red">
            <tr>
              <th>Product Name</th>
              <th>Category</th>
              <th>Stocks</th>
              <th>Mark Up</th>
              <th>Price</th>
              <th>Actions</th>
            </tr>
            </thead>
            <tbody id="container-inventory-data">
              <!-- ===== HOST ALL INVENTORY ROWS FETCHED FROM JS ===== -->
            </tbody>
          </table>
        </div>
        
        <!-- =============== PAGINATION CONTROLS =============== -->
        <nav>
          <ul class="pagination justify-content-center" id="pagination-container"></ul>
        </nav>
      </div>
    </div>
  </div>
  <!-- =============== MODALS =============== -->
  <?php $mode='add'; include_once '../includes/components/product-modal.php'; ?>
  <?php $mode = 'edit'; include  '../includes/components/product-modal.php'; ?>
  <?php include_once '../includes/components/delete-product-modal.php'; ?>

<!-- =============== INVENTORY PAGE SPECIFIC SCRIPT =============== -->
<script src="assets/js/inventory.js?v=<?=filemtime('assets/js/inventory.js')?>"></script>
<?php include_once '../includes/footer.php'; ?>