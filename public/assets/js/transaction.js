const productContainers = document.getElementById('product-containers');
const searchBar = document.getElementById('search-bar');
const filterTiles = document.querySelectorAll('.filter-tile');
const filterCategory = document.getElementById('category');
const orderContainer = document.getElementById('order-container');
const totalPrice = document.getElementById('total-price');

const LIMIT = 10;
let currentPage = 1;
let searchTimeout;

// Cart object to hold selected products
let cart = {};

filterTiles.forEach(tile => {
    tile.addEventListener('click', async () => {
        const category = tile.getAttribute('data-category');
        filterCategory.value = category;

        fetch(`handlers/filter_category_products.php?category=${ encodeURIComponent(category) }`)
            .then(res => res.json())
            .then(data => {
                console.log(data);
                if(!data.products || data.products.length === 0) {
                    productContainers.innerHTML = `
                        <p class="text-center">No products found from the database.</p>
                    `;
                    return;
                }
                renderProducts(data.products);
            })
            .catch(err => {
                console.log(err);
            })
    });
});

searchBar.addEventListener('input', () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(searchProducts, 500);
})

const fetchProducts = async (page = 1) => {
    try {
        const response = await fetch(`handlers/fetch_inventory.php?limit=${ LIMIT }&page=${ page }`)
        const data = await response.json();
        console.log(data.inventory);
        console.log(data.inventory.length);
        if(!data.inventory || data.inventory.length === 0) {
            productContainers.innerHTML = `
                <p>No products found from the database.</p>
            `;
            return;
        }
        console.log(data);
        renderProducts(data.inventory);
    } catch (err) {
        console.error(err.message);
    }
}

const createProductCard = (data) => {
    return `
    <div class="col-12 col-lg-6 col-xxl-4">
        <div class="product-card w-100 h-100 border rounded-3 shadow-sm p-2 d-flex flex-column justify-content-between"
             data-sku="${ data.nixar_product_sku }"
             data-name="${ data.product_name }"
             data-price="${ data.final_price }"
             data-current-stock="${ data.current_stock }"
             data-inventory-id="${ data.inventory_id }"
            >
            <div class="w-100 d-flex flex-column align-items-center gap-2">
                <div class="w-100 ratio ratio-4x3">
                    <img
                        src="${ data.product_img_url }"
                        alt="${ data.product_name }"
                        class="img-fluid w-100 rounded-2 bg-secondary object-fit-cover"
                    />
                </div>
                <div class="w-100">
                    <h3 class="text-left">${ data.product_name }</h3>
                    <p class="fs-5">${ data.category }</p>
                </div>
            </div>
            <div class="w-100 mt-auto"> 
                <p class="fs-6">Stocks Available: <span class="fw-bold">${ data.current_stock }</span></p>
            </div>
            <div class="w-100 d-flex align-items-center justify-content-between py-2">
                <h3>₱ ${ data.final_price }</h3>
                <div class="position-relative d-flex align-items-center">
                    <button class="transaction-btn add-btn position-absolute start-0">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                    <div class="quantity-display px-4 py-1 rounded-pill text-center" id="selectedCartStock">
                        ${ cart[data.nixar_product_sku]?.quantity || 0 }
                    </div>
                    <button class="transaction-btn remove-btn position-absolute end-0">
                        <i class="fa-solid fa-minus"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    `
}

const renderProducts = (data) => {
    productContainers.innerHTML = '';
    const htmlString = data.map(products => createProductCard(products)).join('\n');
    productContainers.innerHTML = htmlString;
    attachCartEventListeners();
}

const extractCardData = (card) => {
    return {
        sku: card.dataset.sku,
        name: card.dataset.name,
        price: card.dataset.price,
        current_stock: card.dataset.currentStock,
        inventory_id: card.dataset.inventoryId
    };
};

const addToCart = (productData) => {
    if(cart[productData.sku]) {
        cart[productData.sku].quantity += 1;
    } else {
        cart[productData.sku] = {
            ...productData,
            quantity: 1
        }
    }
    console.log(cart);
}

const removeFromCart = (productData) => {
    if(!cart[productData.sku]) return;

    cart[productData.sku].quantity--;

    if (cart[productData.sku].quantity <= 0) {
        delete cart[productData.sku];
    }

    console.log(cart);
}

const updateQuantityDisplay = (btn, sku) => {
    btn.parentElement.querySelector('.quantity-display').textContent = cart[sku]?.quantity || 0;
    const totalItems = Object.values(cart).reduce((sum, item) => sum + item.quantity, 0);
    const totalCost = Object.values(cart).reduce((sum, item) => sum + (item.price * item.quantity), 0);

    const itemCount = document.querySelector('.total-order');
    itemCount.textContent = `${totalItems} selected`;
    totalPrice.textContent = `₱ ${totalCost}`;
}

const updateOrderContainer = () => {
    orderContainer.innerHTML  = Object.values(cart).map(product => {
        return `
            <div class="d-flex justify-content-between align-items-center">
                <p class="mb-0 text-truncate">
                    ${product.quantity}x ${product.name}
                </p>
                <p class="mb-0 ms-4 text-end flex-shrink-0">
                    ₱${product.price * product.quantity}
                </p>
            </div>
        `
    }).join("");
}

const attachCartEventListeners = () => {
    const addButtons = document.querySelectorAll('.add-btn');
    const removeButtons = document.querySelectorAll('.remove-btn');
    addButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const productData = extractCardData(btn.closest('.product-card'));
            
            // Initial check if we have stock to be added for our cart
            btn.disabled = parseInt(productData.current_stock) === 0;
            
            addToCart(productData);
            updateQuantityDisplay(btn, productData.sku);
            updateOrderContainer();
        
            // Re-check by fetching the updated count
            const currentSelectedCount = parseInt(document.getElementById('selectedCartStock').textContent);
            btn.disabled = currentSelectedCount >= productData.current_stock;
        });
    });

    removeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const productData = extractCardData(btn.closest('.product-card'));
            removeFromCart(productData);
            updateQuantityDisplay(btn, productData.sku);
            updateOrderContainer();

            // Re-enable add button if selected stock count is below current stocks
            const addBtn = btn.closest('.product-card').querySelector('.add-btn');
            const currentSelectedCount = parseInt(document.getElementById('selectedCartStock').textContent);

            if (currentSelectedCount < parseInt(productData.current_stock)) {
                addBtn.disabled = false;
            }
        });
    });
}

const updateCheckoutTotals = () => {
    const subtotal = Object.values(cart).reduce((sum, item) => sum + (item.price * item.quantity), 0).toFixed(2);
    const discountInput = document.querySelector('.discount-input');
    const receiptDiscount = document.querySelector('.receipt-discount');
    const receiptTotal = document.querySelector('.receipt-total');
    const discount = parseFloat(discountInput.value).toFixed(2) || 0;
    const total = Math.max(0, subtotal - discount);

    document.querySelector('.total-display').textContent = `₱${total}`;
    receiptDiscount.textContent = `₱${discount}`;
    receiptTotal.textContent = `₱${total}`;
}

const populateCheckoutModal = () => {
    const tableBody = document.getElementById('checkout-table-body');
    const subtotalElement = document.querySelector('.subtotal-display');
    const totalElement = document.querySelector('.total-display');

    if (Object.keys(cart).length === 0) {
        tableBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No items in cart.</td></tr>';
        return;
    }

    // order details
    const rows = Object.values(cart).map(item => {
        const itemTotal = (item.price * item.quantity).toFixed(2);
        return `
            <tr>
                <td class="text-muted">${item.name}</td>
                <td class="text-center text-muted">${item.quantity}</td>
                <td class="text-end text-muted">₱${parseFloat(item.price).toFixed(2)}</td>
                <td class="text-end text-muted">₱${itemTotal}</td>
            </tr>
        `;
    }).join('');

    const subtotal = Object.values(cart).reduce((sum, item) => sum + (item.price * item.quantity), 0).toFixed(2);

    tableBody.innerHTML = rows;
    subtotalElement.textContent = `₱${subtotal}`;
    totalElement.textContent = `₱${subtotal}`;

    // receipt
    const receipt = document.getElementById('receipt');
    const receiptSubtotal = document.querySelector('.receipt-subtotal');
    const receiptTotal = document.querySelector('.receipt-total');

    const items = Object.values(cart).map(item => {
        const itemTotal = (item.price * item.quantity).toFixed(2);
        return `
            <div class="w-100 d-flex justify-content-between">
                <p class="text-muted">${item.quantity} x ${item.name}</p>
                <p class="text-muted">₱${itemTotal}</p>
            </div>
        `;
    }).join('');

    receipt.innerHTML = items;
    receiptSubtotal.textContent = `₱${subtotal}`;
    receiptTotal.textContent = `₱${subtotal}`;
}

const searchProducts = (page = 1) => {
    const query = searchBar.value.trim();
    // add query check
    if(!query) {
        productContainers.innerHTML = "";
        fetchProducts();
        return;
    }

    currentPage = page;
    fetch(`handlers/search_products.php?q=${ encodeURIComponent(query) }&limit=${ LIMIT }&page=${ page }`)
        .then(res => res.json())
        .then(data => {
            console.log(data);
            renderProducts(data.inventory);
        })
        .catch(err => {
            console.error(err);
        })
}

const handleCheckout = async (checkoutForm, endpoint, checkoutData) => {
    // Reference checkout modal for toggle later
    const modalEl = checkoutForm.closest('.modal');
    let modal = bootstrap.Modal.getInstance(modalEl);
    if (!modal) modal = new bootstrap.Modal(modalEl);

    try {
        const response = await fetch(endpoint, {
            method: 'POST',
            body: checkoutData
        });

        const result = await response.json();
        if (!result.success) {
            throw new Error(result.message || 'Checkout failed.');
        }

        modal.hide();
        checkoutForm.reset();
        console.log('Checkout success:', result.message);
        fetchProducts();
    } catch(err) {
        console.error(err.message);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    fetchProducts();

    const checkoutModal = document.getElementById('checkoutModal');
    checkoutModal.addEventListener('show.bs.modal', () => {
        populateCheckoutModal();

        const discountInput = document.querySelector('.discount-input');
        const receiptDiscount = document.querySelector('.receipt-discount');
        discountInput.value = '0';
        receiptDiscount.textContent = '₱0';
        discountInput.addEventListener('blur', updateCheckoutTotals);

        // References of Customer Inputs for Receipt Display
        const custName = document.getElementById('customer-name');
        const custAddress = document.getElementById('customer-address');
        const custPhoneNo = document.getElementById('customer-phone');
        // References of Receipt Display for Customer Information
        const receiptName = document.getElementById('cust-name');
        const receiptPhoneNo = document.getElementById('cust-phone-no');
        const receiptAddress = document.getElementById('cust-address');


        custName.addEventListener('input', () => {
            receiptName.textContent = custName.value;
        })

        custAddress.addEventListener('input', () => {
            receiptAddress.textContent = custAddress.value;
        })

        custPhoneNo.addEventListener('input', () => {
            receiptPhoneNo.textContent = custPhoneNo.value;
        })

        // Get all form-related information for processing
        const checkoutForm = document.getElementById('checkout-form');
        checkoutForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const endpoint = checkoutForm.getAttribute('action');
            const formData = new FormData(checkoutForm);
            
            const cartData = Object.values(cart);
            const total = document.querySelector(".total-display").textContent.replace('₱','').trim();
            formData.append('total_amount', parseFloat(total));
            console.log('cart: ', JSON.stringify(cartData));
            formData.append('checkout_products', JSON.stringify(cartData));
            console.log('Entries: ', ...formData.entries());
            await handleCheckout(checkoutForm, endpoint, formData);
        });


    });
})