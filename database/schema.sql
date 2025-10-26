/*
    Author: John Roland L. Octavio

    `schema.sql` contains the database schema of nixar
    main tables: users, customers, car_models, nixar_products, transactions, receipts, inventory, and suppliers
*/

CREATE DATABASE IF NOT EXISTS nixar_autoglass_db;

CREATE TABLE IF NOT EXISTS users (
    user_id INTEGER PRIMARY KEY AUTO_INCREMENT,
    role VARCHAR(20),
    name VARCHAR(60) NOT NULL,
    password_hashed VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS receipts (
    receipt_id INTEGER PRIMARY KEY AUTO_INCREMENT,
    sale_date DATETIME NOT NULL,
    total_amount FLOAT NOT NULL,
    discount FLOAT NOT NULL DEFAULT 0.0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP()
);

CREATE TABLE IF NOT EXISTS customers (
    customer_id INTEGER PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(60) NOT NULL,
    email VARCHAR(60) NOT NULL,
    address VARCHAR(128) NOT NULL,
    phone_no VARCHAR(12) NOT NULL
);

CREATE TABLE IF NOT EXISTS transactions (
    transaction_id INTEGER PRIMARY KEY AUTO_INCREMENT,
    issuer_id INTEGER,
    receipt_id INTEGER, 
    customer_id INTEGER, 
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP(),
    payment_method VARCHAR(20),

    FOREIGN KEY (issuer_id) REFERENCES users(user_id),
    FOREIGN KEY (receipt_id) REFERENCES receipts(receipt_id),
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id)
);

CREATE TABLE IF NOT EXISTS product_materials (
    product_material_id INTEGER PRIMARY KEY AUTO_INCREMENT,
    material_name VARCHAR(60) NOT NULL,
    category VARCHAR(30) NOT NULL
);

CREATE TABLE IF NOT EXISTS nixar_products (
    nixar_product_sku VARCHAR(50) PRIMARY KEY NOT NULL,
    product_material_id INTEGER,
    product_name VARCHAR(60) NOT NULL,
    product_img_url VARCHAR(255) NOT NULL,
    mark_up FLOAT NOT NULL,
    is_deleted TINYINT(1) DEFAULT 0,

    FOREIGN KEY (product_material_id) REFERENCES product_materials(product_material_id)
);

CREATE TABLE IF NOT EXISTS receipt_details (
    receipt_details_id INTEGER PRIMARY KEY AUTO_INCREMENT,
    receipt_id INTEGER,
    nixar_product_sku VARCHAR(30),
    quantity FLOAT,
    
    FOREIGN KEY (receipt_id) REFERENCES receipts(receipt_id),
    FOREIGN KEY (nixar_product_sku) REFERENCES nixar_products(nixar_product_sku)
);

CREATE TABLE IF NOT EXISTS car_models (
    car_model_id INTEGER PRIMARY KEY AUTO_INCREMENT,
    make VARCHAR(30) NOT NULL,
    model VARCHAR(30) NOT NULL,
    year INTEGER NOT NULL,
    type VARCHAR(30) NOT NULL
);

CREATE TABLE IF NOT EXISTS car_details (
    car_detail_id INTEGER PRIMARY KEY AUTO_INCREMENT,
    customer_id INTEGER,
    car_model_id INTEGER, 
    plate_no VARCHAR(10) NOT NULL,

    FOREIGN KEY (customer_id) REFERENCES customers(customer_id),
    FOREIGN KEY (car_model_id) REFERENCES car_models(car_model_id)
);


CREATE TABLE IF NOT EXISTS product_compatibility (
    product_compatibility_id INTEGER PRIMARY KEY AUTO_INCREMENT,
    nixar_product_sku VARCHAR(30),
    car_model_id INTEGER,

    FOREIGN KEY (nixar_product_sku) REFERENCES nixar_products(nixar_product_sku),
    FOREIGN KEY (car_model_id) REFERENCES car_models(car_model_id)
);

CREATE TABLE IF NOT EXISTS inventory (
    inventory_id INTEGER PRIMARY KEY AUTO_INCREMENT,
    nixar_product_sku VARCHAR(30),
    current_stock INTEGER NOT NULL,
    min_threshold INTEGER NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP(),

    FOREIGN KEY (nixar_product_sku) REFERENCES nixar_products(nixar_product_sku)
);

CREATE TABLE IF NOT EXISTS product_suppliers (
    product_supplier_id INTEGER PRIMARY KEY AUTO_INCREMENT,
    nixar_product_sku VARCHAR(30),
    supplier_id INTEGER,
    base_price FLOAT NOT NULL
);

CREATE TABLE IF NOT EXISTS suppliers (
    supplier_id INTEGER PRIMARY KEY AUTO_INCREMENT,
    supplier_name VARCHAR(60) NOT NULL,
    contact_no VARCHAR(12) NOT NULL
);