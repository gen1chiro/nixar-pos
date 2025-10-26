<?php
// Configuration
$mode = $mode ?? 'add';
$modalId = $mode === 'add' ? 'addProductModal' : 'editProductModal';
$modalTitle = $mode === 'add' ? 'Add Product' : 'Edit Product';
$formId = $mode === 'add' ? 'addProductForm' : 'editProductForm';
$prefix = $mode === 'edit' ? 'edit' : '';
$submitBtnText = $mode === 'add' ? 'Add Product' : 'Update Product';
$showSteps = $mode === 'add'; // Only show steps for add mode
?>

<div class="modal fade" id="<?= $modalId ?>" tabindex="-1" aria-labelledby="<?= $modalId ?>Label" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-4" id="<?= $modalId ?>Label"><?= $modalTitle ?></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <form action="" enctype="multipart/form-data" id="<?= $formId ?>">
          <?php if ($mode === 'edit'): ?>
            <input type="hidden" id="<?= $prefix ?>productId" name="productId">
          <?php endif; ?>

          <!-- STEP 1: Product Details -->
          <div id="<?= $prefix ?>step1" class="step">
            <div class="mb-3">
              <label for="<?= $prefix ?>productImage" class="form-label">Product Image</label>
              <input class="form-control" type="file" id="<?= $prefix ?>productImage" accept="image/*">
              <div class="mt-3 text-center d-flex justify-content-center">
                <img id="<?= $prefix ?>imagePreview" src="#" alt="Image Preview"
                     style="display:none; max-width:200px; max-height:200px; border-radius:8px; object-fit:cover;">
              </div>
            </div>

            <div class="mb-3">
              <label for="<?= $prefix ?>productName" class="form-label">Product Name</label>
              <input type="text" class="text-input" id="<?= $prefix ?>productName" placeholder="Enter product name">
            </div>

            <div class="mb-3 d-flex gap-3">
              <div class="w-50">
                <label for="<?= $prefix ?>productMaterial" class="form-label">Material</label>
                <select class="form-select" id="<?= $prefix ?>productMaterial">
                  <?php foreach($ProductMaterials as $Id => $Material): ?>
                    <option value="<?= $Material ?>"><?= $Material ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="w-50">
                <label for="<?= $prefix ?>carTypes" class="form-label">Car Type</label>
                <select class="form-select" id="<?= $prefix ?>carTypes">
                  <?php foreach($CarTypes as $Type): ?>
                    <option value="<?= $Type ?>"><?= $Type ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="mb-3 d-flex gap-3">
              <div class="w-50">
                <label for="<?= $prefix ?>stocks" class="form-label">Stocks</label>
                <input type="number" class="text-input" id="<?= $prefix ?>stocks" min="1" placeholder="Enter # of stocks">
              </div>
              <div class="w-50">
                <label for="<?= $prefix ?>price" class="form-label">Price</label>
                <div class="d-flex gap-2 align-items-center">
                  <span>₱</span>
                  <input type="number" class="text-input" id="<?= $prefix ?>price" min="1" placeholder="Enter price">
                </div>
              </div>
            </div>
          </div>

          <!-- STEP 2: Car Compatibility Info (only for add mode) -->
          <?php if ($showSteps): ?>
          <div id="<?= $prefix ?>step2" class="step" style="display:none;">
            <h5 class="mb-3">Compatible Car Information</h5>
            <div class="mb-3">
              <div id="<?= $prefix ?>carModelContainer">
                <div class="d-flex align-items-stretch gap-2 mb-2 car-model-input">
                  <div class="w-50">
                    <label class="form-label">Compatible Car Model</label>
                    <input type="text" class="text-input w-100" placeholder="Enter car model">
                  </div>
                  <div class="w-50">
                    <label class="form-label">Year</label>
                    <input type="number" class="text-input w-100" min="1900" max="2050" placeholder="Year">
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

