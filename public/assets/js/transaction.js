const productContainers = document.getElementById('product-containers');
const searchBar = document.getElementById('search-bar');
const filterTiles = document.querySelectorAll('.filter-tile');
const filterCategory = document.getElementById('category');

const LIMIT = 10;
let currentPage = 1;
let searchTimeout;


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
    <div class="col-12 col-xl-6 col-xxl-4">
        <div class="w-100 h-100 border rounded-3 shadow-sm p-2 d-flex flex-column justify-content-between">
            <div class="w-100 d-flex align-items-start justify-content-between">
                <div class="w-50 ratio ratio-4x3">
                    <img
                        src="${ data.product_img_url }"
                        alt="${ data.product_name }"
                        class="img-fluid w-100 rounded-2 bg-secondary object-fit-cover"
                    />
                </div>
                <div class="w-50 px-3 py-2">
                    <h3 class="text-left">${ data.product_name }</h3>
                    <p class="fs-5">${ data.category }</p>
                </div>
            </div>
            <div class="w-100 d-flex align-items-center justify-content-between py-2">
                <h3>₱ ${ data.final_price }</h3>
                <div class="position-relative d-flex align-items-center">
                    <button class="transaction-btn add-btn position-absolute start-0">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                    <div class="quantity-display px-4 py-1 rounded-pill">
                        s
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
}

const searchProducts = (page = 1) => {
    const query = searchBar.value.trim();
    // add query check
    if(!query || query === "") {
        productContainers.innerHTML = "";
        fetchProducts();
        return;
    }

    currentPage = page;
    fetch(`handlers/search_products.php?q=${ encodeURIComponent(query) }&limit=${ LIMIT }&page=${ page }`)
        .then(res => res.json())
        .then(data => {
            renderProducts(data.inventory);
        })
        .catch(err => {
            console.error(err);
        })
}

document.addEventListener('DOMContentLoaded', () => {
    fetchProducts();
})