<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-4" id="deleteProductModalLabel">Delete Product</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form action="../../public/handlers/handle_delete_product.php" id="deleteProductForm">
                    <p>Are you sure you want to delete product: <span class="fw-bold" id="productToDelete"></span></p>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn " data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-secondary" form="deleteProductForm">Delete Product</button>
            </div>
        </div>
    </div>
</div>

