<?php 
  include_once __DIR__ . '/../includes/config/_init.php';  
  
  $PageTitle = "Create Invoice | NIXAR POS";
  $CssPath = "assets/css/styles.css";
  $JSPath = "assets/js/scripts.js";

  include_once '../includes/head.php'; 
  SessionManager::checkSession();
?>

<div class="container-fluid p-0 m-0 py-3 px-4 d-flex flex-column" style="min-height:100vh;">
  <div class="w-100 d-flex justify-content-between align-items-center mb-3">
    <h2 class="fw-bold">Create Invoice</h2>
    <a href="javascript:window.history.back()" class="text-muted">X</a>
  </div>

  <div class="w-100 flex-grow-1 row g-0" style="min-height:0;">
    <div class="col-4 bg-white p-4">
      <h6 class="fw-semibold mb-3">Details</h6>
  <table class="checkout-items-table">
        <thead>
          <tr>
            <th>Item</th>
            <th>Qty</th>
            <th>Unit Price</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="max-width:140px;">Windshield Glass Toyota</td>
            <td>1</td>
            <td>₱5,000.00</td>
            <td>₱5,000.00</td>
          </tr>
          <tr>
            <td>Windshield Rubber Toyota</td>
            <td>1</td>
            <td>₱2,000.00</td>
            <td>₱2,000.00</td>
          </tr>
          <tr>
            <td>Super Black Tint</td>
            <td>140 x 79cm</td>
            <td>₱1.00/cm</td>
            <td>₱1,000.00</td>
          </tr>
        </tbody>
      </table>

      <div class="d-flex justify-content-between mt-3">
        <span>Subtotal</span>
        <strong>₱8,000.00</strong>
      </div>
      <div class="d-flex justify-content-between mt-1">
        <span class="text-danger">Add Discount</span>
        <span>₱500.00</span>
      </div>
      <div class="d-flex justify-content-between mt-2">
        <strong>Total</strong>
        <strong>₱7,500.00</strong>
      </div>

      <div class="mt-4">
        <h6 class="fw-semibold">Payment Method</h6>
        <select class="form-select mt-2">
          <option>G-Cash</option>
          <option>Cash</option>
          <option>Card</option>
        </select>
      </div>

      <div class="mt-4">
        <button class="generate-btn btn w-100">Confirm</button>
      </div>
    </div>

    <div class="col-8" style="background: rgba(0,0,0,0.45); display:flex; align-items:center; justify-content:center;">
      <div style="background:white; width:360px; height:720px; display:flex; align-items:center; justify-content:center;">
        <span>[Receipt Preview]</span>
      </div>
    </div>
  </div>
</div>

<?php include_once '../includes/footer.php'; ?>