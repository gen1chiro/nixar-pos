/* ================= INVENTORY REFERENCES AND VARIABLES ================= */
const searchBox = document.getElementById('search-input');
const inventoryTbl = document.getElementById('container-inventory-data');
const pagination = document.getElementById('pagination-container');

let searchTimeout;
let queryString = '';
const LIMIT = 10;
let currentPage = 1;

/* ================= FORM REFERENCES AND VARIABLES ================= */
const step1 = document.getElementById(`step1`);
const step2 = document.getElementById(`step2`);
const nextBtn = document.getElementById(`nextStep`);
const prevBtn = document.getElementById(`prevStep`);
const submitBtn = document.getElementById(`submitProduct`);

const editProductForm = document.getElementById('editProductForm');
const addProductForm = document.getElementById('addProductForm');
const deleteProductForm = document.getElementById('deleteProductForm');

/* ================= INVENTORY SEARCH FUNCTIONS ================= */
searchBox.addEventListener('input', () => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(searchProducts, 500);
})

const searchProducts = (page = 1) => {
    const query = searchBox.value.trim();
    if (!query || query === "") {
        inventoryTbl.innerHTML = "";
        pagination.innerHTML = "";
        fetchInventory();
        return;
    }

    queryString = query;
    currentPage = page;

    fetch(`handlers/search_products.php?q=${ encodeURIComponent(query) }&limit=${ LIMIT }&page=${ page }`)
        .then(res => res.json())
        .then(data => {
            const rows = data.inventory;
            if(!rows || rows.length == 0) {
                inventoryTbl.innerHTML = `
                    <tr><td colspan="7" style="text-align:center;">No data found.</td></tr>
                `;
                pagination.innerHTML = '';
                return;
            }
            renderRows(rows);
            updatePagination(data.totalPages, data.currentPage);
        })
        .catch(err => {
            console.error(err);
            inventoryTbl.innerHTML = `
                <tr><td colspan="7" style="text-align:center;">${ err.message }</td></tr>
            `;
        });
}
const renderRows = (data) => {
  const htmlString = data.map(product => {
    const productData = encodeURIComponent(JSON.stringify(product));
    console.log(productData);
    return `
      <tr>
        <td>${product.product_name}</td>
        <td>${product.category}</td>
        <td>${product.current_stock}</td>
        <td>${product.mark_up}%</td>
        <td>₱${product.final_price}</td>
        <td>
          <button 
            type="button" 
            class="btn btn-edit" 
            data-bs-toggle="modal" 
            data-bs-target="#editProductModal"
            data-product="${productData}"
          >
            <i class="fa-regular fa-pen-to-square"></i>
          </button>
          <button 
            type="button" 
            class="btn btn-delete"
            data-bs-toggle="modal" 
            data-bs-target="#deleteProductModal"
            data-product="${productData}"
          >
            <i class="fa-solid fa-trash"></i>
          </button>
        </td>
      </tr>
    `;
  }).join('\n');

  inventoryTbl.innerHTML = htmlString;

  // Attach click listeners to edit and delete buttons
  document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', () => {
      const product = JSON.parse(decodeURIComponent(btn.dataset.product));
      fillEditModal(product);
    });
  });

  document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', () => {
      const product = JSON.parse(decodeURIComponent(btn.dataset.product));
      console.log(product);
      fillDeleteModal(product);
    });
  });
};


// Autofill edit modal with product data
const fillEditModal = (data) => {
  document.getElementById('editproductId').value = data.nixar_product_sku;
  document.getElementById('editproductName').value = data.product_name;
  document.getElementById('editcarModel').value = data.car_make_model;
  document.getElementById('edityear').value = data.year;
  document.getElementById('editstocks').value = data.current_stock;
  document.getElementById('editprice').value = data.final_price;
  document.getElementById('editproductMaterial').value = data.material_name;
  document.getElementById('editcarTypes').value = data.type;

  const preview = document.getElementById('editimagePreview');
  if (data.image_path) {
    preview.src = `../uploads/${data.image_path}`; // TODO: Adjust path
    preview.style.display = 'block';
  } else {
    preview.src = '#';
    preview.style.display = 'none';
  }
};

const fillDeleteModal = (data) => {
  console.log('delete modal: ' + JSON.stringify(data));
  const productNameSpan = document.getElementById('productToDelete');

  // Show product name in modal
  productNameSpan.textContent = data.product_name;

  // Store product ID in a hidden input (so it can be submitted)
  let hiddenInput = deleteProductForm.querySelector('input[name="product_sku"]');
  if (!hiddenInput) {
    hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'product_sku';
    deleteProductForm.appendChild(hiddenInput);
  }
  hiddenInput.value = data.nixar_product_sku;
};

/* ================= INVENTORY PAGINATION FUNCTIONS ================= */
const fetchInventory = async (page = 1) => {
    try {
        const response = await fetch(`handlers/fetch_inventory.php?limit=${ LIMIT }&page=${ page }`)
        const data = await response.json();
        
        if(data.inventory.length === 0) {
            inventoryTbl.innerHTML = `
                <tr><td colspan="7" style="text-align:center;">No data found.</td></tr>
            `;
            return;
        }
        console.log(data);
        renderRows(data.inventory);
        updatePagination(data.totalPages, data.currentPage)
    } catch (err) {
        console.error(err.message);
    }
}

const updatePagination = (totalPages, currentPage) => {
    let htmlString = '';
    // Render Previous button if current page is not the first page
    if (currentPage > 1) {
        htmlString += `
        <li class="page-item me-2">
            <a class="page-link" href="#" data-page="${ currentPage - 1 }">
            ← Previous
            </a>
        </li>`;
    }
    
    const MAX_ICONS_VISIBLE = 3;
    let start = Math.max(1, currentPage - 1);
    let end = Math.min(totalPages, start + MAX_ICONS_VISIBLE - 1);
    // Adjust starting value if it is nearing the end page
    if (end - start < MAX_ICONS_VISIBLE - 1) {
        start = Math.max(1, end - MAX_ICONS_VISIBLE + 1);
    }
    // Render all page icons
    for (let i = start; i <= end; i++) {
        htmlString += `
            <li class="page-item ${ i === currentPage ? 'active' : '' }">
                <a class="page-link" href="#" data-page="${ i }">${ i }</a>
            </li>
        `;
    }
    // Render Next button if current page is not the last page
    if (currentPage < totalPages) {
      htmlString += `
        <li class="page-item ms-2">
            <a class="page-link" href="#" data-page="${ currentPage + 1 }">
                Next →
            </a>
        </li>`;
    }
    // Embed pagination buttons into pagination controller
    pagination.innerHTML = htmlString;
    const pageLinks = pagination.querySelectorAll('.page-link');

    pageLinks.forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const page = parseInt(e.target.dataset.page);
            if(isNaN(page)) {
                return;
            }

            if(queryString) searchProducts(page);
            else fetchInventory(page);
        })
    })
}

/* ================= FILTER SEARCH FUNCTIONS ================= */
const searchByFilters = async (page = 1) => {
    const productMaterial = document.querySelector('input[name="category"]:checked')?.value || null;
    const carModel = document.getElementById('carModel').value.trim() || null;
    const carType = document.getElementById('carType').value !== "default" ? 
                    document.getElementById('carType').value : null;
    const isInStock = document.querySelector('input[name="stockStatus"]:checked')?.value || null;
    const priceRange = document.getElementById('priceValue').value || null;

    const filterValues = {
        productMaterial, 
        carModel, 
        carType, 
        isInStock, 
        priceRange
    }

    const params = buildFilterParams({ ...filterValues });
    console.log(params)
    
    try {
        const response = await fetch(`handlers/filter_products.php?${ params }&limit=${ LIMIT }&page=${ page }`);
        const filtered = await response.json();
        console.log('Filtered response:', filtered);
        console.log(`SQL: ${ filtered.sql } Params: ${ filtered.params } Types: ${ filtered.types }`);
        if(!filtered.inventory || filtered.inventory.length == 0) {
            inventoryTbl.innerHTML = `
                <tr><td colspan="7" style="text-align:center;">No data found.</td></tr>
            `;
            return;
        }

        renderRows(filtered.inventory);
        updatePagination(filtered.totalPages, filtered.currentPage);
    } catch(err) {
        console.error(err);
        inventoryTbl.innerHTML = `
            <tr><td colspan="7" style="text-align:center;">${ err.message }</td></tr>
        `;
    }
}

const buildFilterParams = ({ productMaterial, carModel, carType, isInStock, priceRange }) => {
    const filter = {};

    if(productMaterial) filter.material = productMaterial;
    if(carModel) filter.model = carModel;
    if(carType) filter.type = carType;
    if(isInStock) filter.stock = isInStock === "inStock" ? 1 : 0;
    if(priceRange) filter.max_range = priceRange;
    
    params = new URLSearchParams(filter).toString();
    return params;
} 

const resetFilters = () => {
  document.querySelectorAll('input[type="radio"]').forEach(radio => radio.checked = false);
  document.getElementById('carModel').value = '';
  document.getElementById('carType').value = 'default';
  document.getElementById('priceValue').value = '';
  searchProducts();
}

document.addEventListener('DOMContentLoaded', () => {
    fetchInventory();

    if (addProductForm) handleProductForm(addProductForm);
    if (editProductForm) handleProductForm(editProductForm);
    if (deleteProductForm) handleDeleteProduct();
});

/* ================= PRODUCT IMAGE UPLOAD FUNCTIONS ================= */
['', 'edit'].forEach(prefix => {
  const input = document.getElementById(`${prefix}productImage`);
  const preview = document.getElementById(`${prefix}imagePreview`);
  if (!input || !preview) return;

  input.addEventListener('change', event => {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = e => {
        preview.src = e.target.result;
        preview.style.display = 'block';
      };
      reader.readAsDataURL(file);
    } else {
      preview.src = '#';
      preview.style.display = 'none';
    }
  });
});


/* ================= COMPATIBLE CAR MODELS FUNCTIONS ================= */
// Add new car model input
const addBtn = document.getElementById(`addCarModelBtn`);
const container = document.getElementById(`carModelContainer`);
addBtn.addEventListener('click', () => {
  const newInput = document.createElement('div');
  newInput.className = 'd-flex align-items-stretch gap-2 mb-2 car-model-input';
  newInput.innerHTML = `
    <input type="text" class="text-input flex-grow-1" placeholder="Enter car make" name="car_make[]">
    <input type="text" class="text-input flex-grow-1" placeholder="Enter car model" name="car_model[]">
    <input type="number" class="text-input" min="1900" max="2050" placeholder="Year" name="car_year[]">
    <button type="button" class="btn btn-danger d-flex align-items-center justify-content-center remove-model">
      <i class="fa-solid fa-trash text-white"></i>
    </button>
  `;
  container.appendChild(newInput);
});

// Remove car model input
document.addEventListener('click', e => {
  if (e.target.closest('.remove-model')) {
    e.target.closest('.car-model-input').remove();
  }
});

const validateStep = (step) => {
  const inputs = step.querySelectorAll('input, select');
  for (const input of inputs) {
    if (!input.checkValidity()) {
      input.reportValidity();
      return false;
    }
  }
  return true;
}

/* ================= TWO STEP FORM FUNCTIONS ================= */
nextBtn.addEventListener('click', () => {
  // Do not proceed to the next step if there are empty inputs
  if(!validateStep(step1)) return;

  step1.style.display = 'none';
  step2.style.display = 'block';
  nextBtn.style.display = 'none';
  prevBtn.style.display = 'inline-block';
  submitBtn.style.display = 'inline-block';
});

prevBtn.addEventListener('click', () => {
  step1.style.display = 'block';
  step2.style.display = 'none';
  nextBtn.style.display = 'inline-block';
  prevBtn.style.display = 'none';
  submitBtn.style.display = 'none';
});

/* ================= INVENTORY FORMS FUNCTION ================= */
const handleDeleteProduct = () => {
  const modalEl = document.querySelector('#deleteProductModal');
  let modal = bootstrap.Modal.getInstance(modalEl);
  if (!modal) modal = new bootstrap.Modal(modalEl);
  deleteProductForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const endpoint = deleteProductForm.getAttribute('action');
    const formData = new FormData(deleteProductForm);
    console.log([...formData.entries()]);

    try {
      const response = await fetch(endpoint, {
        method: 'POST',
        body: formData
      });

      const result = await response.json();
      if(!result.success) {
        throw new Error(`An HTTP Error occured: ${ result.message }`);
      }
      // Re-fetch inventory to update display
      fetchInventory();
      if(modal) modal.hide();
    } catch (err) {
      console.error(err);
    }
  })
}

const handleProductForm = (form) => {
  const modalEl = form.closest('.modal');
  const modal = bootstrap.Modal.getInstance(modalEl);
  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const endpoint = form.getAttribute('action');
    const formData = new FormData(form);

    
    console.log('Endpoint: ' + endpoint);
    console.log('Form Data: ' + formData);
    console.log([...formData.entries()]);

    try {
      const response = await fetch(endpoint, {
        method: 'POST',
        body: formData
      });

      const result = await response.json();
      if (!result.success) {
        throw new Error(`An HTTP Error has occured! Message: ${ result.message }`);
      }

      fetchInventory();
      // Hide and Reset Form
      if(modal) modal.hide();
      form.reset();
    } catch (err) {
      console.error(err.message);
    }
  })
}
