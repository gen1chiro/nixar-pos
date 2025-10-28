<?php
  // Configuration
  $mode = $mode ?? 'add';
  $modalId = $mode === 'add' ? 'addProductModal' : 'editProductModal';
  $modalTitle = $mode === 'add' ? 'Add Product' : 'Edit Product';
  $formId = $mode === 'add' ? 'addProductForm' : 'editProductForm';
  $prefix = $mode === 'edit' ? 'edit' : '';
  $submitBtnText = $mode === 'add' ? 'Add Product' : 'Update Product';
  $showSteps = $mode === 'add'; // Only show steps for add mode
  $EndPoint = $mode === 'add' ? 'handle_add_product.php' : 'handle_edit_product.php';
  
  $Product = new NixarProduct($Conn);
  $ProductMaterials = $Product->fetchMaterials();
?>

<div class="modal fade" id="<?= $modalId ?>" tabindex="-1" aria-labelledby="<?= $modalId ?>Label" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-4" id="<?= $modalId ?>Label"><?= $modalTitle ?></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <form action="/nixar-pos/public/handlers/<?= $EndPoint ?>" enctype="multipart/form-data" id="<?= $formId ?>">
          <?php if ($mode === 'edit'): ?>
            <input type="hidden" id="<?= $prefix ?>productId" name="productId">
          <?php endif; ?>

          <!-- STEP 1: Product Details -->
          <div id="<?= $prefix ?>step1" class="step">
            <div class="mb-3">
              <label for="<?= $prefix ?>productImage" class="form-label">Product Image</label>
              <input class="form-control" type="file" id="<?= $prefix ?>productImage" accept="image/*" name="product_image">
              <div class="mt-3 text-center d-flex justify-content-center">
                <img id="<?= $prefix ?>imagePreview" src="#" alt="Image Preview"
                     style="display:none; max-width:200px; max-height:200px; border-radius:8px; object-fit:cover;">
              </div>
            </div>
            <!-- ================= Product Metadata - Related Fields ================= -->
            <div class="mb-3 d-flex gap-3">
              <div class="w-50">
                <label for="<?= $prefix ?>productName" class="form-label">Product Name</label>
                <input type="text" class="text-input" id="<?= $prefix ?>productName" placeholder="Enter product name" name="product_name" required>
              </div>
              <div class="w-50">
                <label for="<?= $prefix ?>productName" class="form-label">Product SKU</label>
                <input type="text" class="text-input" id="<?= $prefix ?>productSku" placeholder="e.g. NX-ABC-001" name="product_sku" pattern="^NX-[A-Za-z]{3}-\d+$" required>
              </div>
            </div>
            <!-- ================= Material - Related Fields ================= -->
            <div class="mb-3 d-flex gap-3">
              <div class="w-50">
                <label for="<?= $prefix ?>productMaterial" class="form-label">Material</label>
                <select class="form-select" id="<?= $prefix ?>productMaterial" name="product_material_id">
                  <?php foreach($ProductMaterials as $Material): ?>
                    <option value="<?= $Material['product_material_id'] ?>"><?= $Material['material_name'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="w-50">
                <label for="<?= $prefix ?>carTypes" class="form-label">Car Type</label>
                <select class="form-select" id="<?= $prefix ?>carTypes" name="car_type" required>
                  <?php foreach($CarTypes as $Type): ?>
                    <option value="<?= $Type ?>"><?= $Type ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <!-- ================= Inventory - Related Fields ================= -->
            <div class="mb-3 d-flex gap-3">
              <div class="w-50">
                <label for="<?= $prefix ?>stocks" class="form-label">Stocks</label>
                <input type="number" class="text-input" id="<?= $prefix ?>stocks" min="1" placeholder="Enter # of stocks" name="stock_count" required>
              </div>
              <div class="w-50">
                <label for="<?= $prefix ?>threshold" class="form-label">Minimum Threshold</label>
                <input type="number" class="text-input" id="<?= $prefix ?>threshold" min="1" placeholder="Enter min. # of stocks" name="min_threshold" required>
              </div>
              <div class="w-50">
                <label for="<?= $prefix ?>markUp" class="form-label">Mark-up Percentage</label>
                <div class="d-flex gap-2 align-items-center">
                  <span>₱</span>
                  <input type="number" class="text-input" id="<?= $prefix ?>markUp  " min="1" max="100" placeholder="Enter price" name="mark_up" required>
                </div>
              </div>
            </div>
            <!-- ================= Supplier - Related Fields ================= -->
            <div class="mb-3 d-flex gap-3">
              <div class="w-50">
                <label for="<?= $prefix ?>productSupplier" class="form-label">Supplier</label>
                <select class="form-select" id="<?= $prefix ?>productSupplier" name="product_supplier_id">
                  <?php foreach($SupplierData as $Supplier): ?>
                    <option value="<?= $Supplier['supplier_id'] ?>"><?= $Supplier['supplier_name'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="w-50">
                <label for="<?= $prefix ?>price" class="form-label">Supplier Base Price</label>
                <div class="d-flex gap-2 align-items-center">
                  <span>₱</span>
                  <input type="number" class="text-input" id="<?= $prefix ?>price" min="1" placeholder="Enter base price" name="base_price" required>
                </div>
              </div>
            </div>
          </div>
          

          <!-- STEP 2: Car Compatibility Info (only for add mode) -->
          <?php if ($showSteps): ?>
          <div id="<?= $prefix ?>step2" class="step" style="display:none;">
            <h5 class="mb-3">Compatible Cars Information</h5>
            <div class="mb-3">
              <div id="<?= $prefix ?>carModelContainer">
                <div class="d-flex align-items-stretch gap-2 mb-2 car-model-input">
                  <div class="w-50">
                    <label class="form-label">Make</label>
                    <input type="text" class="text-input w-100" placeholder="Enter car make" name="car_make[]" required>
                  </div>
                  <div class="w-50">
                    <label class="form-label">Model</label>
                    <input type="text" class="text-input w-100" placeholder="Enter car model" name="car_model[]" required>
                  </div>
                  <div class="w-50">
                    <label class="form-label">Year</label>
                    <input type="number" class="text-input w-100" min="1900" max="2050" placeholder="Year" name="car_year[]" required>
                  </div>
                  <div class="d-flex align-items-end">
                    <button type="button" class="btn btn-link text-danger remove-model" style="visibility:hidden;">
                      <i class="fa-solid fa-trash"></i>
                    </button>
                  </div>
                </div>
              </div>
              <button type="button" class="btn-dashed btn w-100" id="<?= $prefix ?>addCarModelBtn">+ Add Model</button>
            </div>
          </div>
          <?php else: ?>
          <!-- Edit mode: Single car compatibility -->
          <div class="mb-3 d-flex gap-3">
            <div class="w-50">
              <label for="<?= $prefix ?>carModel" class="form-label">Compatible Car Model</label>
              <input type="text" class="text-input" id="<?= $prefix ?>carModel" placeholder="Enter car model">
            </div>
            <div class="w-50">
              <label for="<?= $prefix ?>year" class="form-label">Year</label>
              <input type="number" class="text-input" id="<?= $prefix ?>year" min="1900" max="2050" placeholder="Enter year">
            </div>
          </div>
          <?php endif; ?>
        </form>
      </div>

      <div class="modal-footer d-flex justify-content-end">
        <?php if ($showSteps): ?>
          <button type="button" class="btn" id="<?= $prefix ?>prevStep" style="display:none;">Back</button>
          <button type="button" class="btn" id="<?= $prefix ?>nextStep">Next</button>
          <button type="submit" class="btn" form="<?= $formId ?>" id="submitProduct" style="display:none;"><?= $submitBtnText ?></button>
        <?php else: ?>
          <button type="submit" class="btn" form="<?= $formId ?>"><?= $submitBtnText ?></button>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

