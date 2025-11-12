/*
    Author: Ignatius Warren Benjamin Javelons

    `seed.sql` inserts sample records into the database tables of nixar
*/

-- DATABASE: nixar_autoglass_db
    -- TABLE: users
    USE nixar_autoglass_db;

    INSERT INTO 
        users (user_id, role, name, password_hashed)
    VALUES
        (1, 'admin', 'raeantamayo', '$2y$10$pAf3CK.k5mvP0Ii2fzz4K.eQbH/iQskBlmv2XtN0mvmsgoTqRdxC2'),
        (2, 'cashier', 'sunny123', '$2y$10$WYefz0uFxo2f2OtLir7HY.9r5ku83giUa0UQ9O2Lw/I/GXhafYM4K');

    -- TABLE: receipts
    INSERT INTO 
        receipts (receipt_id, total_amount, created_at, discount)
    VALUES
        (1001, 13200.00, '2025-10-01 09:15:23', 0),
        (1002, 6790.00, '2025-10-01 10:42:56', 0),
        (1003, 6520.00, '2025-10-02 11:08:19', 0),
        (1004, 4232.00, '2025-10-03 14:15:00', 0),
        (1005, 4527.50, '2025-10-03 15:45:31', 0),
        (1006, 11580.00, '2025-10-04 09:33:11', 0),
        (1007, 6870.00, '2025-10-04 11:15:03', 0),
        (1008, 7060.00, '2025-10-05 16:40:18', 0),
        (1009, 7060.00, '2025-10-06 10:20:00', 0),
        (1010, 3667.00, '2025-10-06 13:45:12', 0),
        (1011, 10580.00, '2025-10-07 09:30:45', 0),
        (1012, 3300.00, '2025-10-07 15:10:33', 0),
        (1013, 14040.00, '2025-10-08 11:22:18', 0),
        (1014, 3565.00, '2025-10-08 16:05:50', 0),
        (1015, 4357.50, '2025-10-09 10:15:00', 0);
        
    -- TABLE: customers
    INSERT INTO 
        customers (customer_id, name, email, address, phone_no)
    VALUES
        (501, 'Juan Dela Cruz', 'juan.delacruz@example.com', '123 Mabini St., Manila', '09171234567'),
        (502, 'Maria Santos', 'maria.santos@example.com', '45 Rizal Ave., Quezon City', '09182345678'),
        (503, 'Jose Ramirez', 'jose.ramirez@example.com', '78 Bonifacio St., Pasig', '09193456789'),
        (504, 'Ana Lopez', 'ana.lopez@example.com', '12 P. Burgos St., Makati', '09204567891'),
        (505, 'Carlo Mendoza', 'carlo.mendoza@example.com', '56 Katipunan Ave., QC', '09215678912'),
        (506, 'Liza Gonzales', 'liza.gonzales@example.com', '89 Taft Ave., Manila', '09226789123'),
        (507, 'Patrick Reyes', 'patrick.reyes@example.com', '34 EDSA, Mandaluyong', '09237891234'),
        (508, 'Ella Cruz', 'ella.cruz@example.com', '90 Ortigas Ave., Pasig', '09248912345'),
        (509, 'Nico Garcia', 'nico.garcia@example.com', '77 Aurora Blvd., San Juan', '09259023456'),
        (510, 'Sofia Lim', 'sofia.lim@example.com', '25 Shaw Blvd., Mandaluyong', '09260134567'),
        (511, 'Ryan Torres', 'ryan.torres@example.com', '18 Buendia Ave., Makati', '09271235678'),
        (512, 'Clarisse Tan', 'clarisse.tan@example.com', '60 Marcos Hwy., Antipolo', '09282346789'),
        (513, 'David Cruz', 'david.cruz@example.com', '11 Magsaysay Blvd., Manila', '09293457890'),
        (514, 'Bea Navarro', 'bea.navarro@example.com', '99 Pioneer St., Mandaluyong', '09304568901'),
        (515, 'Miguel Ramos', 'miguel.ramos@example.com', '150 Katipunan Ext., QC', '09315679012');

    -- TABLE: transactions
    INSERT INTO 
        transactions (transaction_id, issuer_id, receipt_id, customer_id, created_at, payment_method)
    VALUES
        (1,  2, 1001, 501, '2025-10-01 09:15:23', 'Cash'),
        (2,  2, 1002, 502, '2025-10-01 10:42:56', 'GCash'),
        (3,  2, 1003, 503, '2025-10-02 11:08:19', 'GCash'),
        (4,  1, 1004, 504, '2025-10-03 14:15:00', 'Cash'),
        (5,  2, 1005, 505, '2025-10-03 15:45:31', 'GCash'),
        (6,  1, 1006, 506, '2025-10-04 09:33:11', 'Cash'),
        (7,  2, 1007, 507, '2025-10-04 11:15:03', 'Cash'),
        (8,  1, 1008, 508, '2025-10-05 16:40:18', 'GCash'),
        (9,  2, 1009, 509, '2025-10-06 10:20:00', 'Cash'),
        (10, 2, 1010, 510, '2025-10-06 13:45:12', 'GCash'),
        (11, 1, 1011, 511, '2025-10-07 09:30:45', 'GCash'),
        (12, 1, 1012, 512, '2025-10-07 15:10:33', 'Cash'),
        (13, 2, 1013, 513, '2025-10-08 11:22:18', 'GCash'),
        (14, 2, 1014, 514, '2025-10-08 16:05:50', 'Cash'),
        (15, 2, 1015, 515, '2025-10-09 10:15:00', 'Cash');
        
    -- TABLE: product_materials
    INSERT INTO 
        product_materials (product_material_id, material_name, category)
    VALUES
        (1, 'Laminated Glass', 'Glass'),
        (2, 'Tempered Glass', 'Glass'),
        (3, 'Ceramic Film', 'Tints'),
        (4, 'Plastic Composite', 'Mirrors'),
        (5, 'Rubber and Metal Composite', 'Accessories'),
        (6, 'Rubber and Metal Composite', 'Rubber');

	-- TABLE: nixar_products
	INSERT INTO 
		nixar_products (nixar_product_sku, product_material_id,  product_supplier_id, product_name, product_img_url, mark_up, is_deleted)
	VALUES
		('NX-GLS-001', 1, 1,'Toyota Fortuner 2016 Windshield Glass', 'https://example.com/images/fortuner_windshield.jpg', 20.00, 0),
		('NX-GLS-002', 1, 3,'Honda Civic 2018 Windshield Glass', 'https://example.com/images/civic_windshield.jpg', 10.00, 0),
		('NX-GLS-003', 1, 4,'Mitsubishi Montero Sport 2020 Windshield Glass', 'https://example.com/images/montero_windshield.jpg', 15.00, 0),
		('NX-GLS-004', 2, 5,'Nissan Navara 2022 Front Door Glass (LH)', 'https://example.com/images/navara_front_glass_lh.jpg', 20.00, 0),
		('NX-GLS-005', 2, 6,'Nissan Navara 2022 Front Door Glass (RH)', 'https://example.com/images/navara_front_glass_rh.jpg', 5.00, 0),
		('NX-GLS-006', 2, 7,'Ford Ranger 2023 Rear Door Glass (LH)', 'https://example.com/images/ranger_rear_glass_lh.jpg', 10.00, 0),
		('NX-GLS-007', 2, 8,'Ford Ranger 2023 Rear Door Glass (RH)', 'https://example.com/images/ranger_rear_glass_rh.jpg', 15.00, 0),
		('NX-MIR-001', 4, 11,'Toyota Fortuner 2016 Side Mirror (LH)', 'https://example.com/images/fortuner_mirror_lh.jpg', 20.00, 0),
		('NX-MIR-002', 4, 12,'Toyota Fortuner 2016 Side Mirror (RH)', 'https://example.com/images/fortuner_mirror_rh.jpg', 20.00, 0),
		('NX-MIR-003', 4, 13,'Honda Civic 2018 Side Mirror with Signal (LH)', 'https://example.com/images/civic_mirror_lh.jpg', 5.00, 0),
		('NX-MIR-004', 4, 14,'Honda Civic 2018 Side Mirror with Signal (RH)', 'https://example.com/images/civic_mirror_rh.jpg', 10.00, 0),
		('NX-TNT-001', 3, 17,'3M Ceramic Tint Medium Shade', 'https://example.com/images/3m_tint_medium.jpg', 20.00, 0),
		('NX-TNT-002', 3, 19,'3M Ceramic Tint Dark Shade', 'https://example.com/images/3m_tint_dark.jpg', 15.00, 0),
		('NX-TNT-003', 3, 20,'Llumar Platinum Tint 50%', 'https://example.com/images/llumar_tint_50.jpg', 10.00, 0),
		('NX-TNT-004', 3, 21,'Llumar Platinum Tint 35%', 'https://example.com/images/llumar_tint_35.jpg', 15.00, 0),
		('NX-ACC-001', 5, 23,'Universal Wiper Blade Set 22', 'https://example.com/images/wiper_set.jpg', 5.00, 0),
		('NX-ACC-002', 5, 24,'Defogger Repair Kit', 'https://example.com/images/defogger_kit.jpg', 15.00, 0);

    -- TABLE: receipt_details
    INSERT INTO 
        receipt_details (receipt_details_id, receipt_id, nixar_product_sku, quantity)
    VALUES
        (1, 1001, 'NX-GLS-001', 1),
        (2, 1001, 'NX-TNT-001', 1),    
        (3, 1002, 'NX-MIR-003', 1),
        (4, 1002, 'NX-TNT-002', 1),
        (5, 1003, 'NX-TNT-002', 1),
        (6, 1003, 'NX-TNT-003', 1),
        (7, 1004, 'NX-ACC-002', 1),
        (8, 1004, 'NX-TNT-004', 1),
        (9, 1005, 'NX-ACC-001', 1),
        (10, 1005, 'NX-MIR-004', 1),
        (11, 1006, 'NX-GLS-002', 1),
        (12, 1006, 'NX-TNT-001', 1),
        (13, 1007, 'NX-TNT-003', 1),
        (14, 1007, 'NX-MIR-003', 1),
        (15, 1008, 'NX-MIR-001', 1),
        (16, 1008, 'NX-TNT-002', 1),
		(17, 1009, 'NX-MIR-001', 1),
        (18, 1009, 'NX-TNT-002', 1),
        (19, 1010, 'NX-ACC-002', 1),
        (20, 1010, 'NX-TNT-001', 1),
        (21, 1011, 'NX-GLS-003', 1),
        (22, 1012, 'NX-TNT-003', 1),
        (23, 1013, 'NX-GLS-001', 1),
        (24, 1013, 'NX-MIR-002', 1),
        (25, 1014, 'NX-TNT-004', 1),
        (26, 1015, 'NX-MIR-003', 1),
        (27, 1015, 'NX-ACC-001', 1);

    -- TABLE: car_models
    INSERT INTO 
        car_models (car_model_id, make, model, year, type)
    VALUES
        (1, 'Toyota', 'Fortuner', 2016, 'SUV'),
        (2, 'Honda', 'Civic', 2018, 'Sedan'),
        (3, 'Mitsubishi', 'Montero Sport', 2020, 'SUV'),
        (4, 'Nissan', 'Navara', 2022, 'Pickup'),
        (5, 'Ford', 'Ranger', 2023, 'Pickup');

    -- TABLE: car_details
    INSERT INTO 
        car_details (car_detail_id, customer_id, car_model_id, plate_no)
    VALUES
        -- Juan Dela Cruz 
        (1, 501, 1, 'ABC-1234'),
        (2, 501, 2, 'ABC-1235'),
        (3, 501, 3, 'ABC-1236'),

        -- Maria Santos 
        (4, 502, 2, 'DEF-5678'),
        (5, 502, 3, 'DEF-5679'),

        -- Jose Ramirez 
        (6, 503, 3, 'GHI-9012'),

        -- Ana Lopez 
        (7, 504, 1, 'JKL-3456'),
        (8, 504, 2, 'JKL-3457'),

        -- Carlo Mendoza 
        (9, 505, 4, 'MNO-7890'),

        -- Liza Gonzales 
        (10, 506, 2, 'PQR-2345'),
        (11, 506, 3, 'PQR-2346'),
        (12, 506, 1, 'PQR-2347'),

        -- Patrick Reyes 
        (13, 507, 5, 'STU-6789'),

        -- Ella Cruz 
        (14, 508, 3, 'VWX-0123'),
        (15, 508, 4, 'VWX-0124'),

        -- Nico Garcia 
        (16, 509, 4, 'YZA-4567'),

        -- Sofia Lim 
        (17, 510, 5, 'BCD-8901'),
        (18, 510, 1, 'BCD-8902'),
        (19, 510, 2, 'BCD-8903'),

        -- Ryan Torres 
        (20, 511, 1, 'EFG-2345'),

        -- Clarisse Tan 
        (21, 512, 2, 'HIJ-6789'),
        (22, 512, 3, 'HIJ-6790'),

        -- David Cruz 
        (23, 513, 3, 'KLM-0123'),

        -- Bea Navarro 
        (24, 514, 4, 'NOP-4567'),

        -- Miguel Ramos 
        (25, 515, 5, 'QRS-7890'),
        (26, 515, 1, 'QRS-7891');

    -- TABLE: product_compatibility
    INSERT INTO 
        product_compatibility (product_compatibility_id, nixar_product_sku, car_model_id)
    VALUES
        -- Glass 
        (1, 'NX-GLS-001', 1), -- Fortuner
        (2, 'NX-GLS-001', 3), -- Montero
        (3, 'NX-GLS-002', 2), -- Civic
        (4, 'NX-GLS-002', 1), -- Fortuner 
        (5, 'NX-GLS-003', 3), -- Montero
        (6, 'NX-GLS-003', 1), -- Fortuner 
        (7, 'NX-GLS-004', 4), -- Navara
        (8, 'NX-GLS-004', 5), -- Ranger
        (9, 'NX-GLS-005', 4), -- Navara
        (10, 'NX-GLS-005', 5), -- Ranger
        (11, 'NX-GLS-006', 5), -- Ranger
        (12, 'NX-GLS-006', 4), -- Navara 
        (13, 'NX-GLS-007', 5), -- Ranger
        (14, 'NX-GLS-007', 4), -- Navara 

        -- Mirrors
        (15, 'NX-MIR-001', 1), -- Fortuner 
        (16, 'NX-MIR-001', 3), -- Montero 
        (17, 'NX-MIR-002', 1), -- Fortuner 
        (18, 'NX-MIR-002', 3), -- Montero 
        (19, 'NX-MIR-003', 2), -- Civic 
        (20, 'NX-MIR-003', 1), -- Fortuner 
        (21, 'NX-MIR-004', 2), -- Civic 
        (22, 'NX-MIR-004', 1), -- Fortuner 

        -- Universal Tints 
        (23, 'NX-TNT-001', 1),
        (24, 'NX-TNT-001', 2),
        (25, 'NX-TNT-001', 3),
        (26, 'NX-TNT-001', 4),
        (27, 'NX-TNT-001', 5),
        (28, 'NX-TNT-002', 1),
        (29, 'NX-TNT-002', 2),
        (30, 'NX-TNT-002', 3),
        (31, 'NX-TNT-002', 4),
        (32, 'NX-TNT-002', 5),
        (33, 'NX-TNT-003', 1),
        (34, 'NX-TNT-003', 2),
        (35, 'NX-TNT-003', 3),
        (36, 'NX-TNT-003', 4),
        (37, 'NX-TNT-003', 5),
        (38, 'NX-TNT-004', 1),
        (39, 'NX-TNT-004', 2),
        (40, 'NX-TNT-004', 3),
        (41, 'NX-TNT-004', 4),
        (42, 'NX-TNT-004', 5),

        -- Accessories
        (43, 'NX-ACC-001', 1), -- Fortuner
        (44, 'NX-ACC-001', 2), -- Civic
        (45, 'NX-ACC-001', 3), -- Montero
        (46, 'NX-ACC-001', 4), -- Navara
        (47, 'NX-ACC-001', 5), -- Ranger
        (48, 'NX-ACC-002', 1), -- Fortuner
        (49, 'NX-ACC-002', 2), -- Civic
        (50, 'NX-ACC-002', 3), -- Montero
        (51, 'NX-ACC-002', 4), -- Navara
        (52, 'NX-ACC-002', 5); -- Ranger
        
    -- TABLE: inventory
    INSERT INTO 
        inventory (inventory_id, nixar_product_sku, current_stock, min_threshold, updated_at)
    VALUES
        (1, 'NX-GLS-001', 8, 2, '2025-10-13 09:00:00'),
        (2, 'NX-GLS-002', 10, 3, '2025-10-13 09:05:00'),
        (3, 'NX-GLS-003', 6, 2, '2025-10-13 09:10:00'),
        (4, 'NX-GLS-004', 15, 4, '2025-10-13 09:15:00'),
        (5, 'NX-GLS-005', 14, 4, '2025-10-13 09:20:00'),
        (6, 'NX-GLS-006', 3, 2, '2025-10-13 09:25:00'),
        (7, 'NX-GLS-007', 21, 2, '2025-10-13 09:25:00'),
        (8, 'NX-MIR-001', 20, 5, '2025-10-13 09:30:00'),
        (9, 'NX-MIR-002', 18, 5, '2025-10-13 09:35:00'),
        (10, 'NX-MIR-003', 12, 3, '2025-10-13 09:40:00'),
        (11, 'NX-MIR-004', 10, 3, '2025-10-13 09:45:00'),
        (12, 'NX-TNT-001', 30, 8, '2025-10-13 09:50:00'),
        (13, 'NX-TNT-002', 28, 8, '2025-10-13 09:55:00'),
        (14, 'NX-TNT-003', 25, 8, '2025-10-13 10:00:00'),
        (15, 'NX-TNT-004', 22, 8, '2025-10-13 10:05:00'),
        (16, 'NX-ACC-001', 40, 10, '2025-10-13 10:10:00'),
        (17, 'NX-ACC-002', 35, 8, '2025-10-13 10:15:00');

 --    -- TABLE: product_suppliers
    INSERT INTO 
        product_suppliers (product_supplier_id, nixar_product_sku, supplier_id, base_price)
    VALUES
        -- Glass products
        (1, 'NX-GLS-001', 1, 8500.00),
        (2, 'NX-GLS-001', 2, 8600.00), 
        (3, 'NX-GLS-002', 1, 7800.00),
        (4, 'NX-GLS-003', 2, 9200.00),
        (5, 'NX-GLS-004', 2, 4300.00),
        (6, 'NX-GLS-005', 2, 4300.00),
        (7, 'NX-GLS-006', 2, 4400.00),  
        (8, 'NX-GLS-007', 2, 4400.00), 
        (9, 'NX-GLS-006', 3, 4450.00),  
        (10, 'NX-GLS-007', 3, 4450.00), 

        -- Mirrors
        (11, 'NX-MIR-001', 3, 3200.00),
        (12, 'NX-MIR-002', 3, 3200.00),
        (13, 'NX-MIR-003', 3, 3400.00),
        (14, 'NX-MIR-004', 3, 3400.00),
        (15, 'NX-MIR-003', 4, 3450.00), 
        (16, 'NX-MIR-004', 4, 3450.00),

        -- Universal Tints
        (17, 'NX-TNT-001', 4, 2500.00),
        (18, 'NX-TNT-001', 5, 2550.00),
        (19, 'NX-TNT-002', 4, 2800.00),
        (20, 'NX-TNT-003', 4, 3000.00),
        (21, 'NX-TNT-004', 4, 3100.00),
        (22, 'NX-TNT-004', 5, 3150.00),

        -- Accessories
        (23, 'NX-ACC-001', 5, 750.00),
        (24, 'NX-ACC-002', 5, 580.00),
        (25, 'NX-ACC-001', 4, 760.00);

    -- TABLE: suppliers
    INSERT INTO 
        suppliers (supplier_id, supplier_name, contact_no)
    VALUES
        (1, 'AutoGlass Depot PH', '09171234567'),
        (2, 'CarPro Distributors', '09283456789'),
        (3, 'TintTech Supplies', '09394561234'),
        (4, 'VisionParts Imports', '09505672345'),
        (5, 'ClearView Enterprise', '09616783456');