<?php
    include_once __DIR__ . '/../includes/config/_init.php';  

    $PageTitle = "Transaction | NIXAR POS";
    $CssPath = "assets/css/styles.css";
    $JSPath = "assets/js/scripts.js";

    include_once '../includes/head.php';

    SessionManager::checkSession();

    $Conn = DatabaseConnection::getInstance()->getConnection();
    $NixarProduct = new NixarProduct($Conn);

    $Products = $NixarProduct->fetchPaginated();
    $CategoryCounts = $NixarProduct->countPerCategory();
?>
    <div class="container-fluid p-0 m-0 py-3 px-4 d-flex flex-column align-items-center gap-2" style="height: 100vh; overflow: hidden;">
        <?php include_once '../includes/components/nav.php'; ?>
        <div class="w-100 flex-grow-1 d-sm-none d-flex justify-content-center align-items-center">
            <p class="text-center">Transaction Functionalities are only available on larger devices.</p>
        </div>
        <div class="w-100 d-none d-sm-flex flex-grow-1 row g-2" style="overflow:hidden;">
            <div class="col-8" style="height: 98%">
                <div class="w-100 h-100 d-flex flex-column gap-2">
                    <div class="w-100 d-flex flex-shrink-0 justify-content-between align-items-center">
                        <h2>Product List</h2>
                      <div class="input-wrapper">
                        <input
                          type="text"
                          placeholder="Search"
                          class="text-input"
                          id="search-bar"
                        />
                        <i class="fa-solid fa-magnifying-glass" id="icon-magnifying"></i>
                      </div>
                    </div>
                    <div class="w-full d-flex flex-shrink-0 justify-content-between align-items-center gap-2 z-1 bg-white pb-2">
                        <div class="filter-tile" data-category="all">
                            <h6>All Items</h6>
                            <p class="text-muted"><?= $CategoryCounts['all_products'] ?? 0 ?> Items</p>
                        </div>
                        <div class="filter-tile" data-category="Glass">
                            <h6>Glass</h6>
                            <p class="text-muted"><?= $CategoryCounts['glass'] ?? 0 ?> Items</p>
                        </div>
                        <div class="filter-tile" data-category="Tints">
                            <h6>Tints</h6>
                            <p class="text-muted"><?= $CategoryCounts['tints'] ?? 0 ?> Items</p>
                        </div>
                        <div class="filter-tile" data-category="Rubber">
                            <h6>Rubber</h6>
                            <p class="text-muted"><?= $CategoryCounts['rubber'] ?? 0 ?> Items</p>
                        </div>
                        <input type="hidden" id="category" name="category" value="all">
                    </div>
                    <!-- =============== PRODUCT CONTAINER =============== -->
                    <div id="product-containers" class="w-full overflow-y-auto row g-3 overflow-x-hidden" style="min-height: 0;">
                    </div>
                </div>
            </div>
            <div class="col-4" style="height: 98%">
                <div class="w-100 h-100 d-flex flex-column justify-content-start gap-2 rounded-3 border p-3">
                    <h2>Order Detail</h2>
                    <p>0 items selected</p>
                    <div class="flex-grow-1 border shadow-sm rounded-2">

                    </div>
                    <button class="btn">
                        Proceed to Payment
                        <i class="fa-solid fa-chevron-right text-white"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- =============== TRANSACTION PAGE SPECIFIC SCRIPT =============== -->
    <script src="assets/js/transaction.js?v=<?=filemtime('assets/js/transaction.js')?>"></script>
<?php include_once '../includes/footer.php'; ?>
