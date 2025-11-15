# NIXAR POS

## System Overview
NIXAR POS (Point of Sales) is a web-based application designed to manage sales, inventory, suppliers, customers, and transactions for Nixar Auto Glass and Car Tint Shop located in Lizares St., Bacolod City. The system supports role-based access (admin and cashier), product-supplier relationships, inventory monitoring, checkout operations that create receipts and transactions, and a reporting module that aggregates sales and inventory metrics. This system is created as a prerequisite for the course **Cloud-Based Application Development**.

---
## ByteMe! JuJoRaToJu + Ja
- Octavio, John Roland — Backend Developer / Project Lead
- Elizan, Jared Ramon — Frontend Developer  
- Javellana, Jul Leo — Frontend Developer  
- Javelona, Ignatius Warren Benjamin — Database Developer  
- Labistre, Josh Dane — Database Developer  
- Tamayo, Raean Chrissean — Frontend Developer

---

## Demo Login Credentials
| Role       | Username        | Password   |
|------------|-----------------|------------|
| Admin      | raeantamayo     | admin123   |
| Cashier    | sunny123        | cashier123 |

--- 

## Features
- Session Management
  - Centralized session handling via SessionManager
  - Role-based redirection to default pages (admin → reports, cashier → transaction)
  - Protected pages and automatic redirect to login when session is absent
  - Session creation and destruction (login / logout)
- Inventory management
  - Product CRUD (add, edit, soft delete)
  - Supplier associations and multiple supplier offers per product
  - Stock levels with minimum threshold alerts
  - Product images upload and storage
- Transaction & Checkout
  - Create receipts and receipt details
  - Customer and car details capture
  - Inventory stock update on checkout
- Suppliers management
  - Manage suppliers and product-supplier associations (This is where Hard Delete is applied)
  - Update supplier base prices and product offerings
- Reporting
  - Sales aggregate metrics (total revenue, avg transaction, transaction count)
  - Inventory metrics (most sold, best selling by revenue, low stock items)
  - List metrics (sales by time, category performance)
  - Report preview page that uses session data for printable report previews
- Search, filters and pagination across inventory and suppliers
- Client-side validation and progressive UI with modals and toasts

---

## Cashier Side Pages
Pages and features typically used by a cashier:
- Login page (index.php) — sign-in and redirect to appropriate dashboard
- Transaction (transaction.php)
  - Browse and search products
  - Category filters
  - Add/remove items to a cart
  - Checkout modal to capture customer/car details and payment method
  - Performs inventory decrement and stores transaction/receipt/customer data

---

## Admin Side Pages
Pages and features typically accessible to an admin:
- Inventory (inventory.php)
  - Full product listing with supplier & price view
  - Add / Edit product modal (two-step flow: product details + compatible cars)
  - Delete (soft-delete) product
  - Low-stock indicators, mark-up management, supplier base price editing
- Suppliers (supplier.php)
  - List suppliers, view supplier products
  - Edit supplier info, update product base prices, remove product-supplier mapping
- Reports (reports.php & report-preview.php)
  - Generate sales/inventory reports for a date range
  - Metrics and list metrics (category, time-of-day, most sold, revenue, low stock, etc.)
  - Preview page populated from sessionStorage for printable/exportable views

---

## Tech Stack
- Frontend: HTML, CSS (custom + Bootstrap), JavaScript (vanilla)
- Backend: PHP (procedural + OOP components)
- Database: MySQL (database schema, seed data, and helpful views)

---

## Database Design (Overview)

Database name: `nixar_autoglass_db`

Primary Tables
- `users`
  - user_id (PK), role, name, password_hashed
  - Holds account credentials and role (admin / cashier)

- `receipts`
  - receipt_id (PK), total_amount, discount, created_at
  - Stores receipt totals and discount applied

- `transactions`
  - transaction_id (PK), issuer_id (FK → users.user_id), receipt_id (FK → receipts.receipt_id), customer_id (FK → customers.customer_id), created_at, payment_method
  - Links receipts to users and customers

- `customers`
  - customer_id (PK), name, email, address, phone_no
  - Customer contact info

- `car_models`
  - car_model_id (PK), make, model, year, type

- `car_details`
  - car_detail_id (PK), customer_id (FK → customers.customer_id), car_model_id (FK → car_models.car_model_id), plate_no
  - Associates customers with specific cars (plate numbers)

- `product_materials`
  - product_material_id (PK), material_name, category
  - Defines categories like Glass, Tints, Mirrors, Accessories

- `nixar_products`
  - nixar_product_sku (PK), product_material_id (FK → product_materials.product_material_id), product_supplier_id (FK → product_suppliers.product_supplier_id), product_name, product_img_url, mark_up, is_deleted
  - Represents a product (SKU). Note: product_supplier_id relates to an entry in product_suppliers that indicates a chosen supplier/offer.

- `product_suppliers`
  - product_supplier_id (PK), nixar_product_sku, supplier_id (FK → suppliers.supplier_id), base_price
  - Many-to-many-ish join table that lists supplier offers for a product

- `suppliers`
  - supplier_id (PK), supplier_name, contact_no

- `inventory`
  - inventory_id (PK), nixar_product_sku (FK → nixar_products.nixar_product_sku), current_stock, min_threshold, updated_at

- `receipt_details`
  - receipt_details_id (PK), receipt_id (FK → receipts.receipt_id), nixar_product_sku (FK → nixar_products.nixar_product_sku), quantity

- `product_compatibility`
  - product_compatibility_id (PK), nixar_product_sku (FK → nixar_products.nixar_product_sku), car_model_id (FK → car_models.car_model_id)
  - Maps products to compatible car models

Views
- `product_inventory_view`
  - Joins nixar_products, product_materials, inventory, product_suppliers, suppliers, product_compatibility and car_models
  - Computes final_price = base_price + base_price * (mark_up / 100) and groups compatible cars
- `count_low_stock_items_view`, `low_stock_items_view`
  - Helpers to determine low-stock items and counts
- `most_sold_item_by_qty_view`, `best_selling_item_by_revenue_view`
  - Aggregations for inventory reporting
- `sales_report_view`, `category_performance_view`, `sales_by_time_view`
  - Aggregated sales metrics used by the Reports module

## ER relationships (summary)
- `users` 1 — * `transactions`
- `receipts` 1 — * `transactions`
- `receipts` 1 — * `receipt_details`
- `nixar_products` 1 — * `receipt_details`
- `nixar_products` 1 — * `inventory`
- `product_suppliers` * — 1 `suppliers`
- `nixar_products` * — * `suppliers` (via `product_suppliers`)
- `car_models` * — * `nixar_products` (via `product_compatibility`)
- `customers` 1 — * `car_details`
- `car_models` 1 — * `car_details`

---