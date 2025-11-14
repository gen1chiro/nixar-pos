// AUTHORS: Raean Chrissean R. Tamayo, John Roland Octavio
/* ================= INVENTORY REFERENCES AND VARIABLES ================= */
const searchBox = document.getElementById('search-input');
const inventoryTbl = document.getElementById('container-inventory-data');
const pagination = document.getElementById('pagination-container');

let searchTimeout;
let queryString = '';
const LIMIT = 10;
let currentPage = 1;

/* ================= FORM REFERENCES AND VARIABLES ================= */
const editProductForm = document.getElementById('editProductForm');
const addProductForm = document.getElementById('addProductForm');
const deleteProductForm = document.getElementById('deleteProductForm');

/* ================= INVENTORY SEARCH FUNCTIONS ================= */
searchBox.addEventListener('input', () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(searchProducts, 500);
  console.log(`Search Value: ${searchBox.value} Query String: ${queryString}`);
})

const searchProducts = (page = 1) => {
  const query = searchBox.value.trim();
  if (!query) {
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
      queryString = "";
    })
    .catch (err => {
      showToast("Failed to search for products", err.message,"error");
      console.error(err.message);
    });
}

const renderRows = (data) => {
  const htmlString = data.map(product => {
    const productData = encodeURIComponent(JSON.stringify(product));
    // console.log(productData);
    return `
      <tr>
        <td>${product.product_name}</td>
        <td>${product.category}</td>
        <td>${product.current_stock}</td>
        <td>${product.supplier_name}</td>
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
  console.log('fillEditModal: ' + JSON.stringify(data));
  resetStepForm('edit');

  document.getElementById('inventoryId').value = data.inventory_id
  document.getElementById('productSupplierId').value = data.product_supplier_id;
  document.getElementById('editproductName').value = data.product_name;
  document.getElementById('editproductSku').value = data.nixar_product_sku;
  document.getElementById('editproductMaterial').value = String(data.product_material_id);
  document.getElementById('editstocks').value = data.current_stock;
  document.getElementById('editthreshold').value = data.min_threshold;
  document.getElementById('editmarkUp').value = data.mark_up;
  document.getElementById('editproductSupplier').value = String(data.supplier_id);
  document.getElementById('editbasePrice').value = data.base_price;

  const imageInput = document.getElementById('editproductImage');
  const preview = document.getElementById('editimagePreview');
  const displayUrl = document.getElementById('editProductImageUrl');

  if (data.product_img_url) {
    displayUrl.textContent = data.product_img_url.match(/[^\/]+$/)[0];
    preview.src = data.product_img_url
    preview.alt = `Image of ${ data.product_name }`;
    preview.style.display = 'block';
    displayUrl.style.display = 'block';
  } else {
    preview.src = '#';
    preview.style.display = 'none';
    displayUrl.textContent = '';
  }

  imageInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) {
      if (displayUrl) displayUrl.style.display = 'none';
    }
  });
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

    currentPage = data.currentPage;
    console.log(data);
    renderRows(data.inventory);
    updatePagination(data.totalPages, data.currentPage);
  } catch (err) {
      showToast("Failed to load inventory data", err.message, 'error');
      console.error(err.message);
  }
}

const updatePagination = (totalPages, currentPage, isFiltered = false) => {
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

      if (isFiltered) {
        searchByFilters(page);
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
  console.log(JSON.stringify(filterValues));
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
    updatePagination(filtered.totalPages, filtered.currentPage, true);
  } catch (err) {
    showToast("Failed to filter products", err.message, 'error');
    console.error(err.message)
  }
}

const buildFilterParams = ({ productMaterial, carModel, carType, isInStock, priceRange }) => {
  const filter = {};

  if(productMaterial) filter.material = productMaterial;
  if(carModel) filter.model = carModel;
  if(carType) filter.type = carType;
  if(isInStock) filter.stock = isInStock;
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

let addCarTypes;
document.addEventListener('DOMContentLoaded', () => {
  addCarTypes = document.getElementById('carTypes');

  fetchInventory();

  if (addProductForm) handleProductForm(addProductForm);
  if (editProductForm) handleProductForm(editProductForm);
  if (deleteProductForm) handleDeleteProduct();

  setupStepLogic('');     // For 'add' modal
  setupStepLogic('edit'); // For 'edit' modal
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
// Add new car model input (Only for ADD modal)
const addBtn = document.getElementById(`addCarModelBtn`);
const container = document.getElementById(`carModelContainer`);
addBtn.addEventListener('click', () => {
  // Extract car-type data from initial select
  const ds = addCarTypes.dataset.carTypes;
  console.log(ds);
  const carTypeData = JSON.parse(ds);
  // Build option string values from car-type data attribute
  const optionsHtmlString = carTypeData.map(type => `<option value=${ type }>${ type }</option>`).join('\n');
  const newInput = document.createElement('div');
  newInput.className = 'd-flex align-items-stretch gap-2 mb-2 car-model-input';
  newInput.innerHTML = `
    <input type="text" class="text-input flex-grow-1" placeholder="Enter car make" name="car_make[]">
    <input type="text" class="text-input flex-grow-1" placeholder="Enter car model" name="car_model[]">
    <input type="number" class="text-input" min="1900" max="2050" placeholder="Year" name="car_year[]">
    <select class="form-select" name="car_type[]" required>
      <option value="" disabled>Car Type</option>
      ${ optionsHtmlString }
    </select>
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
  const inputs = step.querySelectorAll('input[required], select[required]');
  for (const input of inputs) {
    if (!input.checkValidity()) {
      input.reportValidity();
      return false;
    }
  }
  return true;
}

/* ================= TWO STEP FORM FUNCTIONS (REFACTORED) ================= */
const resetStepForm = (prefix) => {
  const step1 = document.getElementById(`${prefix}step1`);
  const step2 = document.getElementById(`${prefix}step2`);
  const nextBtn = document.getElementById(`${prefix}nextStep`);
  const prevBtn = document.getElementById(`${prefix}prevStep`);
  const submitBtn = document.getElementById(`${prefix}submitProduct`);

  if (step1) step1.style.display = 'block';
  if (step2) step2.style.display = 'none';
  if (nextBtn) nextBtn.style.display = 'inline-block';
  if (prevBtn) prevBtn.style.display = 'none';
  if (submitBtn) submitBtn.style.display = 'none';

  if (prefix === '') { // Special reset for 'add'
    const container = document.getElementById('carModelContainer');
    if(container) {
      // Remove all but the first car-model-input
      const allCars = container.querySelectorAll('.car-model-input');
      allCars.forEach((car, index) => {
        if (index > 0) car.remove();
      });
      // Reset the first form's values
      container.querySelector('form')?.reset();
    }
  } else if (prefix === 'edit') { // Special reset for 'edit'
    const container = document.getElementById('editCompatibleCarsContainer');
    if (container) container.innerHTML = '<p>Loading compatible cars...</p>';
  }
}

const setupStepLogic = (prefix) => {
  const step1 = document.getElementById(`${prefix}step1`);
  const step2 = document.getElementById(`${prefix}step2`);
  const nextBtn = document.getElementById(`${prefix}nextStep`);
  const prevBtn = document.getElementById(`${prefix}prevStep`);
  const submitBtn = document.getElementById(`${prefix}submitProduct`);

  if (!step1 || !step2 || !nextBtn || !prevBtn || !submitBtn) {
    return;
  }

  nextBtn.addEventListener('click', async () => {
    // Do not proceed to the next step if there are empty inputs
    if (!validateStep(step1)) return;

    if (prefix === 'edit') {
      const skuInput = document.getElementById('editproductSku');
      if (skuInput && skuInput.value) {
        await fetchAndDisplayCompatibleCars(skuInput.value);
      } else {
        const container = document.getElementById('editCompatibleCarsContainer');
        if (container) container.innerHTML = '<p class="text-danger">Could not find product SKU to fetch compatibility.</p>';
      }
    }

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

    if (prefix === 'edit') {
      const container = document.getElementById('editCompatibleCarsContainer');
      if (container) container.innerHTML = '<p>Loading compatible cars...</p>'; // Reset
    }
  });
}

const fetchAndDisplayCompatibleCars = async (sku) => {
  const container = document.getElementById('editCompatibleCarsContainer');
  console.log('Fetching compatible cars for SKU:', sku);
  if (!container) return;

  container.innerHTML = '<p>Loading compatible cars...</p>'; // Show loading state

  try {
    const response = await fetch(`handlers/fetch_compatibility.php?sku=${encodeURIComponent(sku)}`);
    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

    const result = await response.json();
    console.log('Compatible cars data:', result);

    if (result.success && result.rows.length > 0) {
      // Build inputs for each compatible car
      container.innerHTML = ''; // clear loading text
      result.rows.forEach(car => {
        const newInput = document.createElement('div');
        newInput.className = 'd-flex align-items-stretch gap-2 mb-2 car-model-input';

        newInput.innerHTML = `
          <input type="text" class="text-input flex-grow-1" placeholder="Enter car make" name="edit_car_make[]" value="${car.make}" required>
          <input type="text" class="text-input flex-grow-1" placeholder="Enter car model" name="edit_car_model[]" value="${car.model}" required>
          <input type="number" class="text-input" min="1900" max="2050" placeholder="Year" name="edit_car_year[]" value="${car.year}" required>
          <select class="form-select" name="edit_car_type[]" required>
            <option value="" disabled>Car Type</option>
            ${getCarTypeOptions(car.type)}
          </select>
          <button type="button" class="btn btn-danger d-flex align-items-center justify-content-center remove-model">
            <i class="fa-solid fa-trash text-white"></i>
          </button>
        `;

        container.appendChild(newInput);
      });
    } else {
      container.innerHTML = '<p class="text-center p-3">No compatible cars are listed for this product.</p>';
    }
  } catch (error) {
    showToast("Failed to load compatible car model", error.message, 'error');
    console.error('Error fetching compatible cars:', error);
  }
};

/**
 * Helper: builds <option> HTML for car types and sets selected value
 */
const getCarTypeOptions = (selectedType) => {
  if (!addCarTypes) return '<option value="" disabled>Car Type</option>';
  const carTypeData = JSON.parse(addCarTypes.dataset.carTypes);
  return carTypeData
    .map(type => `<option value="${type}" ${type === selectedType ? 'selected' : ''}>${type}</option>`)
    .join('\n');
};


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
      if (queryString) searchProducts(currentPage);
      else fetchInventory(currentPage);

      if(modal) modal.hide();
      showToast("Product deleted successfully", result.message, 'info');
    } catch (err) {
      showToast("Failed to delete product", err.message, 'error');
      console.error(err);
    }
  })
}

const handleProductForm = (form) => {
  const modalEl = form.closest('.modal');
  let modal = bootstrap.Modal.getInstance(modalEl);
  if (!modal) modal = new bootstrap.Modal(modalEl);
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

      // Refresh page
      if (queryString) searchProducts(currentPage);
      else fetchInventory(currentPage);
      // Hide and Reset Form
      if(modal) modal.hide();
      form.reset();

      if (form.id === 'addProductForm') {
        resetStepForm('');
      } else if (form.id === 'editProductForm') {
        resetStepForm('edit');
      }
      
      showToast("Product saved successfully", result.message, 'info');
    } catch (err) {
      showToast("Failed to save product", err.message, 'error');
      console.error(err.message);
    }
  })
}