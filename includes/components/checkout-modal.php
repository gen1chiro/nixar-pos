<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header w-100 d-flex justify-content-between align-items-center py-2 px-3">
                <h1 class="modal-title fs-4">Create Invoice</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body w-100">
                <div class="row">
                    <form 
                        method="POST"
                        action="/nixar-pos/public/handlers/handle_checkout.php"
                        id="checkout-form"
                        class="col-12 col-lg-5 d-flex flex-column justify-content-start align-items-start gap-1"
                    >
                        <!-- Order Details -->
                        <h6>Order Details</h6>
                        <table class="table table-bordered rounded-4 table-fixed">
                            <thead>
                            <tr>
                                <th class="fw-normal text-muted" style="width: 40%;">Item</th>
                                <th class="text-center fw-normal text-muted" style="width: 20%;">Qty</th>
                                <th class="text-center fw-normal text-muted" style="width: 20%;">Unit Price</th>
                                <th class="text-end fw-normal text-muted" style="width: 20%;">Total</th>
                            </tr>
                            </thead>
                            <tbody id="checkout-table-body">
                            <!-- Populated dynamically via JS -->
                            </tbody>
                        </table>
                        <div class="w-100 d-flex justify-content-between align-items-center">
                            <p class="text-muted">Subtotal</p>
                            <p class="subtotal-display">₱0</p>
                        </div>
                        <div class="w-100 d-flex justify-content-between align-items-center">
                            <p class="text-danger">Discount</p>
                            <input type="number" class="text-end text-input discount-input" style="width: 30%;" name="discount"/>
                        </div>
                        <div class="w-100 d-flex justify-content-between align-items-center">
                            <p class="fw-medium">Total</p>
                            <p class="total-display">₱0</p>
                        </div>

                        <!-- Customer Details -->
                        <h6 class="mt-4">Customer Details</h6>
                        <div class="w-100 d-flex flex-column gap-2">
                            <div class="d-flex gap-2">
                                <div class="w-50">
                                    <label for="customer-name" class="form-label text-muted small mb-0">Full Name</label>
                                    <input
                                        type="text"
                                        id="customer-name"
                                        class="text-input w-100"
                                        placeholder="Enter customer name"
                                        name="cust_name"
                                        required
                                    />
                                </div>
                                <div class="w-50">
                                    <label for="customer-email" class="form-label text-muted small mb-0">Email Address</label>
                                    <input
                                        type="email"
                                        id="customer-email"
                                        class="text-input w-100"
                                        placeholder="customer@example.com"
                                        name="cust_email"
                                        required
                                    />
                                </div>
                            </div>
                            <div>
                                <label for="customer-address" class="fw-semibold form-label text-muted small">Address</label>
                                <input
                                    type="text"
                                    id="customer-address"
                                    class="text-input w-100"
                                    placeholder="Street, City, Province"
                                    name="cust_address"
                                    required
                                />
                            </div>
                            <div class="d-flex gap-2">
                                <div class="w-50">
                                    <label for="customer-phone" class="fw-semibold form-label text-muted small mb-0">Phone Number</label>
                                    <input
                                        type="tel"
                                        id="customer-phone"
                                        class="text-input w-100"
                                        placeholder="09XX XXX XXXX"
                                        name="cust_phone_no"
                                        required
                                    />
                                </div>
                                <div class="w-50">
                                    <label for="payment-method" class="fw-semibold form-label text-muted small mb-0">Payment Method</label>
                                    <select id="payment-method" class="text-input form-select" name="payment_method" required>
                                        <option value="G-Cash">G-Cash</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Card">Card</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Car Details -->
                        <h6 class="mt-4">Car Details</h6>
                        <div class="w-100 d-flex flex-column gap-2 mb-4">
                            <div class="d-flex gap-2">
                                <div class="w-50">
                                    <label for="make" class="form-label text-muted small mb-0" >Make</label>
                                    <input
                                        type="text"
                                        id="make"
                                        class="text-input w-100"
                                        placeholder="Toyota, Ford, etc..."
                                        name="make"
                                        required
                                    />
                                </div>
                                <div class="w-50">
                                    <label for="model" class="form-label text-muted small mb-0">Model</label>
                                    <input
                                        type="text"
                                        id="model"
                                        class="text-input w-100"
                                        placeholder="Vios, Mustang, etc..."
                                        name="model"
                                        required
                                    />
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="w-50">
                                    <label for="year" class="form-label text-muted small mb-0">Year</label>
                                    <input
                                        type="number"
                                        id="year"
                                        class="text-input w-100"
                                        min="1970"
                                        max="3030"
                                        placeholder="e.g. 2001"
                                        name="year"
                                        required
                                    />
                                </div>
                                <div class="w-50">
                                    <label for="cust-car-type" class="fw-semibold form-label text-muted small mb-0">Type</label>
                                    <select id="cust-car-type" class="text-input form-select" name="car_type" required>
                                        <option selected value="default" disabled>Select Car Type</option>
                                        <?php foreach($CarTypes as $Type): ?>
                                            <option value="<?= $Type ?>">
                                                <?= $Type; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label for="plate_no" class="form-label text-muted small mb-0">Plate No.</label>
                                <input
                                    type="text"
                                    id="plate_no"
                                    class="text-input w-100"
                                    placeholder="ABC-1234 or ABC-123"
                                    name="plate_no"
                                    pattern="^[A-Z]{3}-\d{3,4}$"
                                    required
                                />
                            </div>
                        </div>
                        <button type="submit" class="generate-btn btn w-100">Confirm</button>
                    </form>
                    <div class="d-none col-7 color-placeholder-gray rounded-3 d-lg-flex justify-content-center align-items-center">
                        <div class="bg-white w-75 rounded-3 p-4">
                            <!-- ========== RECEIPT HEADER ============ -->    
                            <div class="w-100 text-center mb-3 d-flex flex-column gap-1 border-bottom mb-1 pb-2">
                                <h6 class="fs-4">Nixar Auto Glass & Car Tint</h6>
                                <div>
                                    <p class="text-muted fs-6">26 Lizares St, Bacolod, 6100 Negros Occidental</p>
                                    <p class="text-muted"><span class="fw-semibold">TIN: </span>431-132-312-312</p>
                                    <p class="text-muted"><span class="fw-semibold">Tel. No: </span>(032) 432 3761</p>
                                    <p class="text-muted">ByteMe! Point-of Sales | Bacolod City</p>
                                </div>
                            </div>      
                            <!-- ========== RECEIPT CUSTOMER INFORMATION ============ -->
                            <div class="w-100 d-flex flex-column justify-content-between align-items-left">
                                <p class="fs-6 fw-semibold pb-2">Customer Information</p>
                                <div class="d-flex justify-content-between">
                                    <p class="text-muted w-50">Sold To: <span class="fw-semibold" id="cust-name"></span></p>
                                    <p class="text-muted w-50">Phone No: <span class="fw-semibold" id="cust-phone-no"></span></p>
                                </div>
                                <p class="text-muted">Address: <span class="fw-semibold" id="cust-address"></span></p>
                            </div>
                            <div class="w-100 d-flex justify-content-between align-items-center py-2 border-bottom mb-3">
                                <p class="fs-6 fw-semibold">Qty x Product Name</p>
                                <p class="fs-6 fw-semibold">Price</p>
                            </div>                      
                            <!-- ========== RECEIPT DETAILS CONTAINER ============ -->    
                            <div id="receipt" class="mb-3 border-bottom pb-4"></div>
                            <div class="w-100 d-flex justify-content-between align-items-center">
                                <p class="fw-semibold">Sub Total</p>
                                <p class="receipt-subtotal"></p>
                            </div>
                            <div class="w-100 d-flex justify-content-between align-items-center">
                                <p class="fw-semibold">Discount</p>
                                <p class="receipt-discount">₱0</p>
                            </div>
                            <div class="w-100 d-flex justify-content-between align-items-center">
                                <p class="fw-semibold">Total</p>
                                <p class="receipt-total"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
