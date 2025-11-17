-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2025 at 07:10 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nixar_autoglass_db`
--

-- --------------------------------------------------------

--
-- Stand-in structure for view `best_selling_item_by_revenue_view`
-- (See below for the actual view)
--
CREATE TABLE `best_selling_item_by_revenue_view` (
`category` varchar(30)
,`product_name` varchar(60)
,`grouped_price` double(19,2)
);

-- --------------------------------------------------------

--
-- Table structure for table `car_details`
--

CREATE TABLE `car_details` (
  `car_detail_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `car_model_id` int(11) DEFAULT NULL,
  `plate_no` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `car_details`
--

INSERT INTO `car_details` (`car_detail_id`, `customer_id`, `car_model_id`, `plate_no`) VALUES
(1, 501, 1, 'ABC-1234'),
(2, 501, 2, 'ABC-1235'),
(3, 501, 3, 'ABC-1236'),
(4, 502, 2, 'DEF-5678'),
(5, 502, 3, 'DEF-5679'),
(6, 503, 3, 'GHI-9012'),
(7, 504, 1, 'JKL-3456'),
(8, 504, 2, 'JKL-3457'),
(9, 505, 4, 'MNO-7890'),
(10, 506, 2, 'PQR-2345'),
(11, 506, 3, 'PQR-2346'),
(12, 506, 1, 'PQR-2347'),
(13, 507, 5, 'STU-6789'),
(14, 508, 3, 'VWX-0123'),
(15, 508, 4, 'VWX-0124'),
(16, 509, 4, 'YZA-4567'),
(17, 510, 5, 'BCD-8901'),
(18, 510, 1, 'BCD-8902'),
(19, 510, 2, 'BCD-8903'),
(20, 511, 1, 'EFG-2345'),
(21, 512, 2, 'HIJ-6789'),
(22, 512, 3, 'HIJ-6790'),
(23, 513, 3, 'KLM-0123'),
(24, 514, 4, 'NOP-4567'),
(25, 515, 5, 'QRS-7890'),
(26, 515, 1, 'QRS-7891'),
(27, 517, 6, 'NDI-7831'),
(28, 519, 9, 'XYZ-2902'),
(29, 521, 9, 'XYZ-2902'),
(30, 523, 10, 'NVC-3213'),
(31, 525, 10, 'NVC-3213'),
(32, 527, 11, 'FAS-1239'),
(33, 529, 6, 'NDI-7831'),
(34, 531, 6, 'NDI-7831'),
(35, 533, 6, 'NDI-7831'),
(36, 535, 12, 'NDI-7831'),
(37, 537, 6, 'NDI-7831'),
(38, 539, 6, 'NDI-7831'),
(39, 541, 6, 'NDI-7831'),
(40, 543, 6, 'NDI-7831'),
(41, 545, 2, 'DAS-1231'),
(42, 547, 2, 'FAS-1239'),
(43, 549, 14, 'FAS-1239');

-- --------------------------------------------------------

--
-- Table structure for table `car_models`
--

CREATE TABLE `car_models` (
  `car_model_id` int(11) NOT NULL,
  `make` varchar(30) NOT NULL,
  `model` varchar(30) NOT NULL,
  `year` int(11) NOT NULL,
  `type` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `car_models`
--

INSERT INTO `car_models` (`car_model_id`, `make`, `model`, `year`, `type`) VALUES
(1, 'Toyota', 'Fortuner', 2016, 'SUV'),
(2, 'Honda', 'Civic', 2018, 'Sedan'),
(3, 'Mitsubishi', 'Montero Sport', 2020, 'SUV'),
(4, 'Nissan', 'Navara', 2022, 'Pickup'),
(5, 'Ford', 'Ranger', 2023, 'Pickup'),
(6, 'Toyota', 'Rush', 2019, 'SUV'),
(7, 'Nissan', 'Terra', 2022, 'SUV'),
(8, 'Toyota', 'Rush', 2019, 'Sedan'),
(9, 'Suzuki', 'Ertiga', 2019, 'SUV'),
(10, 'Chevrolet', 'Aveo', 2009, 'Sedan'),
(11, 'Ford', 'Raptor', 2022, 'Pickup'),
(12, 'Toyota', 'Fortuner', 2017, 'SUV'),
(13, 'Toyota', 'Hilux', 2022, 'Pickup'),
(14, 'Toyota', 'Raptor', 2021, 'Pickup');

-- --------------------------------------------------------

--
-- Stand-in structure for view `category_performance_view`
-- (See below for the actual view)
--
CREATE TABLE `category_performance_view` (
`category` varchar(30)
,`category_performance` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `count_low_stock_items_view`
-- (See below for the actual view)
--
CREATE TABLE `count_low_stock_items_view` (
`low_stock` bigint(21)
);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `email` varchar(60) NOT NULL,
  `address` varchar(128) NOT NULL,
  `phone_no` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `name`, `email`, `address`, `phone_no`) VALUES
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
(515, 'Miguel Ramos', 'miguel.ramos@example.com', '150 Katipunan Ext., QC', '09315679012'),
(516, 'John Octavio', 'mygmail123@gmail.com', 'Alijis Bacolod City', '9956446924'),
(517, 'John Octavio', 'mygmail123@gmail.com', 'Alijis Bacolod City', '9956446924'),
(518, 'Toby Javelona', 'toby123@gmail.com', 'Taculing, Bacolod City', '9123456789'),
(519, 'Toby Javelona', 'toby123@gmail.com', 'Taculing, Bacolod City', '9123456789'),
(520, 'Toby Javelona', 'toby123@gmail.com', 'Taculing, Bacolod City', '9123123877'),
(521, 'Toby Javelona', 'toby123@gmail.com', 'Taculing, Bacolod City', '9123123877'),
(522, 'Sunny Eljohn', 'greenforot123@gmail.com', 'La Carlota City', '9123123242'),
(523, 'Sunny Eljohn', 'greenforot123@gmail.com', 'La Carlota City', '9123123242'),
(524, 'Sunny Eljohn', 'greenforot123@gmail.com', 'La Carlota City', '9123123242'),
(525, 'Sunny Eljohn', 'greenforot123@gmail.com', 'La Carlota City', '9123123242'),
(526, 'Josh Dane Labs', 'dasjihdajs@gmail.com', 'Cavite City', '9123123129'),
(527, 'Josh Dane Labs', 'dasjihdajs@gmail.com', 'Cavite City', '9123123129'),
(528, 'John Roland Octavio', 'mygmail123@gmail.com', 'Alijis, Bacolod City', '9956446924'),
(529, 'John Roland Octavio', 'mygmail123@gmail.com', 'Alijis, Bacolod City', '9956446924'),
(530, 'John Roland Octavio', 'mygmail123@gmail.com', 'Alijis, Bacolod City', '9956446924'),
(531, 'John Roland Octavio', 'mygmail123@gmail.com', 'Alijis, Bacolod City', '9956446924'),
(532, 'John Roland Octavio', 'mygmail123@gmail.com', 'Alijis, Bacolod City', '9956446924'),
(533, 'John Roland Octavio', 'mygmail123@gmail.com', 'Alijis, Bacolod City', '9956446924'),
(534, 'Jul Leo Javellana', 'generalboring@gmail.com', 'Brgy. Poblacion, Bago City', '9973812674'),
(535, 'Jul Leo Javellana', 'generalboring@gmail.com', 'Brgy. Poblacion, Bago City', '9973812674'),
(536, 'John Roland Octavio', 'mygmail123@gmail.com', 'Alijis, Bacolod City', '9956446924'),
(537, 'John Roland Octavio', 'mygmail123@gmail.com', 'Alijis, Bacolod City', '9956446924'),
(538, 'John Roland Octavio', 'mygmail123@gmail.com', 'Alijis, Bacolod City', '9956446924'),
(539, 'John Roland Octavio', 'mygmail123@gmail.com', 'Alijis, Bacolod City', '9956446924'),
(540, 'John Roland Octavio', 'mygmail123@gmail.com', 'Alijis, Bacolod City', '9956446924'),
(541, 'John Roland Octavio', 'mygmail123@gmail.com', 'Alijis, Bacolod City', '9956446924'),
(542, 'John Roland Octavio', 'mygmail123@gmail.com', 'Alijis, Bacolod City', '9956446924'),
(543, 'John Roland Octavio', 'mygmail123@gmail.com', 'Alijis, Bacolod City', '9956446924'),
(544, 'Jul Leo Javellana', 'generalboring@gmail.com', 'Brgy. Poblacion, Bago City', '9123123129'),
(545, 'Jul Leo Javellana', 'generalboring@gmail.com', 'Brgy. Poblacion, Bago City', '9123123129'),
(546, 'Jul Leo Javellana', 'generalboring@gmail.com', 'Brgy. Poblacion, Bago City', '9123123877'),
(547, 'Jul Leo Javellana', 'generalboring@gmail.com', 'Brgy. Poblacion, Bago City', '9123123877'),
(548, 'Josh Dane Labs', 'mygmail123@gmail.com', 'Cavite City', '9123123877'),
(549, 'Josh Dane Labs', 'mygmail123@gmail.com', 'Cavite City', '9123123877');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `inventory_id` int(11) NOT NULL,
  `nixar_product_sku` varchar(30) DEFAULT NULL,
  `current_stock` int(11) NOT NULL,
  `min_threshold` int(11) NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`inventory_id`, `nixar_product_sku`, `current_stock`, `min_threshold`, `updated_at`) VALUES
(1, 'NX-GLS-001', 6, 2, '2025-11-14 15:25:15'),
(2, 'NX-GLS-002', 7, 3, '2025-11-14 16:21:16'),
(3, 'NX-GLS-003', 6, 2, '2025-10-13 09:10:00'),
(4, 'NX-GLS-004', 15, 4, '2025-10-13 09:15:00'),
(5, 'NX-GLS-005', 14, 4, '2025-10-13 09:20:00'),
(6, 'NX-GLS-006', 0, 2, '2025-11-14 14:20:09'),
(7, 'NX-GLS-007', 21, 2, '2025-10-13 09:25:00'),
(8, 'NX-MIR-001', 20, 5, '2025-10-13 09:30:00'),
(9, 'NX-MIR-002', 18, 5, '2025-10-13 09:35:00'),
(10, 'NX-MIR-003', 12, 3, '2025-10-13 09:40:00'),
(11, 'NX-MIR-004', 10, 3, '2025-10-13 09:45:00'),
(12, 'NX-TNT-001', 30, 8, '2025-10-13 09:50:00'),
(13, 'NX-TNT-002', 28, 8, '2025-10-13 09:55:00'),
(14, 'NX-TNT-003', 23, 8, '2025-11-14 15:22:12'),
(15, 'NX-TNT-004', 17, 8, '2025-11-14 16:21:16'),
(16, 'NX-ACC-001', 40, 10, '2025-10-13 10:10:00'),
(17, 'NX-ACC-002', 34, 8, '2025-11-14 16:22:32'),
(18, 'NX-RUB-001', 4, 5, '2025-11-14 16:21:16'),
(20, 'NX-TNT-005', 10, 3, '2025-11-14 15:03:55'),
(21, 'NX-TNT-006', 0, 3, '2025-11-14 16:18:38');

-- --------------------------------------------------------

--
-- Stand-in structure for view `low_stock_items_view`
-- (See below for the actual view)
--
CREATE TABLE `low_stock_items_view` (
`nixar_product_sku` varchar(50)
,`product_name` varchar(60)
,`current_stock` int(11)
,`updated_at` datetime
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `most_sold_item_by_qty_view`
-- (See below for the actual view)
--
CREATE TABLE `most_sold_item_by_qty_view` (
`product_name` varchar(60)
,`total_quantity_sold` double
);

-- --------------------------------------------------------

--
-- Table structure for table `nixar_products`
--

CREATE TABLE `nixar_products` (
  `nixar_product_sku` varchar(50) NOT NULL,
  `product_material_id` int(11) DEFAULT NULL,
  `product_name` varchar(60) NOT NULL,
  `product_supplier_id` int(11) NOT NULL,
  `product_img_url` varchar(255) NOT NULL DEFAULT 'default-product.png',
  `mark_up` int(11) NOT NULL,
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nixar_products`
--

INSERT INTO `nixar_products` (`nixar_product_sku`, `product_material_id`, `product_name`, `product_supplier_id`, `product_img_url`, `mark_up`, `is_deleted`) VALUES
('NX-ACC-001', 5, 'Universal Wiper Blade Set 22', 23, 'default-product.png', 5, 0),
('NX-ACC-002', 5, 'Defogger Repair Kit', 24, 'default-product.png', 15, 0),
('NX-GLS-001', 1, 'Toyota Fortuner 2016 Windshield Glass', 1, 'default-product.png', 20, 0),
('NX-GLS-002', 1, 'Honda Civic 2018 Windshield Glass', 3, 'default-product.png', 10, 0),
('NX-GLS-003', 1, 'Mitsubishi Montero Sport 2020 Windshield Glass', 4, 'default-product.png', 15, 0),
('NX-GLS-004', 2, 'Nissan Navara 2022 Front Door Glass (LH)', 5, 'default-product.png', 20, 0),
('NX-GLS-005', 2, 'Nissan Navara 2022 Front Door Glass (RH)', 6, 'default-product.png', 5, 0),
('NX-GLS-006', 2, 'Ford Ranger 2023 Rear Door Glass (LH)', 9, 'default-product.png', 10, 0),
('NX-GLS-007', 2, 'Ford Ranger 2023 Rear Door Glass (RH)', 8, 'default-product.png', 15, 0),
('NX-MIR-001', 4, 'Toyota Fortuner 2016 Side Mirror (LH)', 11, 'default-product.png', 20, 0),
('NX-MIR-002', 4, 'Toyota Fortuner 2016 Side Mirror (RH)', 12, 'default-product.png', 20, 0),
('NX-MIR-003', 4, 'Honda Civic 2018 Side Mirror with Signal (LH)', 13, 'default-product.png', 5, 0),
('NX-MIR-004', 4, 'Honda Civic 2018 Side Mirror with Signal (RH)', 14, 'default-product.png', 10, 0),
('NX-RUB-001', 6, 'Door Strip Rubber 35mm.', 26, '1762414105_690c4e194dfab_door-strip-rubber.jpg', 20, 0),
('NX-TNT-001', 3, '3M Ceramic Tint Medium Shade', 17, 'default-product.png', 20, 0),
('NX-TNT-002', 3, '3M Ceramic Tint Dark Shade', 19, 'default-product.png', 15, 0),
('NX-TNT-003', 3, 'Llumar Platinum Tint 50%', 20, 'default-product.png', 10, 0),
('NX-TNT-004', 3, 'Llumar Platinum Tint 35%', 21, 'default-product.png', 15, 0),
('NX-TNT-005', 3, 'Premium Ceramic Tint Film', 32, '1763129035_691736cbdc13b_premium-ceramic-tint.jpg', 10, 1),
('NX-TNT-006', 3, '75% Ceramic Tint', 33, '1763133518_6917484e2b728_tint-windshield.jpg', 10, 0);

-- --------------------------------------------------------

--
-- Table structure for table `product_compatibility`
--

CREATE TABLE `product_compatibility` (
  `product_compatibility_id` int(11) NOT NULL,
  `nixar_product_sku` varchar(30) DEFAULT NULL,
  `car_model_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_compatibility`
--

INSERT INTO `product_compatibility` (`product_compatibility_id`, `nixar_product_sku`, `car_model_id`) VALUES
(1, 'NX-GLS-001', 1),
(2, 'NX-GLS-001', 3),
(3, 'NX-GLS-002', 2),
(4, 'NX-GLS-002', 1),
(5, 'NX-GLS-003', 3),
(6, 'NX-GLS-003', 1),
(7, 'NX-GLS-004', 4),
(8, 'NX-GLS-004', 5),
(9, 'NX-GLS-005', 4),
(10, 'NX-GLS-005', 5),
(11, 'NX-GLS-006', 5),
(12, 'NX-GLS-006', 4),
(13, 'NX-GLS-007', 5),
(14, 'NX-GLS-007', 4),
(15, 'NX-MIR-001', 1),
(16, 'NX-MIR-001', 3),
(17, 'NX-MIR-002', 1),
(18, 'NX-MIR-002', 3),
(19, 'NX-MIR-003', 2),
(20, 'NX-MIR-003', 1),
(21, 'NX-MIR-004', 2),
(22, 'NX-MIR-004', 1),
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
(43, 'NX-ACC-001', 1),
(44, 'NX-ACC-001', 2),
(45, 'NX-ACC-001', 3),
(46, 'NX-ACC-001', 4),
(47, 'NX-ACC-001', 5),
(48, 'NX-ACC-002', 1),
(49, 'NX-ACC-002', 2),
(50, 'NX-ACC-002', 3),
(51, 'NX-ACC-002', 4),
(52, 'NX-ACC-002', 5),
(53, 'NX-RUB-001', 6),
(54, 'NX-RUB-001', 7),
(56, 'NX-TNT-005', 6),
(57, 'NX-TNT-006', 9),
(58, 'NX-TNT-006', 13);

-- --------------------------------------------------------

--
-- Stand-in structure for view `product_inventory_view`
-- (See below for the actual view)
--
CREATE TABLE `product_inventory_view` (
`product_name` varchar(60)
,`nixar_product_sku` varchar(50)
,`product_img_url` varchar(255)
,`category` varchar(30)
,`material_name` varchar(60)
,`product_material_id` int(11)
,`inventory_id` int(11)
,`min_threshold` int(11)
,`current_stock` int(11)
,`base_price` float
,`supplier_id` int(11)
,`supplier_name` varchar(60)
,`mark_up` int(11)
,`product_supplier_id` int(11)
,`final_price` double(19,2)
,`compatible_cars` mediumtext
);

-- --------------------------------------------------------

--
-- Table structure for table `product_materials`
--

CREATE TABLE `product_materials` (
  `product_material_id` int(11) NOT NULL,
  `material_name` varchar(60) NOT NULL,
  `category` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_materials`
--

INSERT INTO `product_materials` (`product_material_id`, `material_name`, `category`) VALUES
(1, 'Laminated Glass', 'Glass'),
(2, 'Tempered Glass', 'Glass'),
(3, 'Ceramic Film', 'Tints'),
(4, 'Plastic Composite', 'Mirrors'),
(5, 'Rubber and Metal Composite', 'Accessories'),
(6, 'EPDM Rubber', 'Rubber');

-- --------------------------------------------------------

--
-- Table structure for table `product_suppliers`
--

CREATE TABLE `product_suppliers` (
  `product_supplier_id` int(11) NOT NULL,
  `nixar_product_sku` varchar(30) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `base_price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_suppliers`
--

INSERT INTO `product_suppliers` (`product_supplier_id`, `nixar_product_sku`, `supplier_id`, `base_price`) VALUES
(1, 'NX-GLS-001', 1, 5000),
(2, 'NX-GLS-001', 2, 8600),
(3, 'NX-GLS-002', 1, 7300),
(4, 'NX-GLS-003', 2, 9200),
(5, 'NX-GLS-004', 2, 4300),
(6, 'NX-GLS-005', 2, 4300),
(7, 'NX-GLS-006', 2, 4400),
(8, 'NX-GLS-007', 2, 4400),
(9, 'NX-GLS-006', 3, 4450),
(10, 'NX-GLS-007', 3, 4450),
(11, 'NX-MIR-001', 3, 3200),
(13, 'NX-MIR-003', 3, 3400),
(14, 'NX-MIR-004', 3, 3400),
(15, 'NX-MIR-003', 4, 3450),
(16, 'NX-MIR-004', 4, 3450),
(18, 'NX-TNT-001', 5, 2550),
(20, 'NX-TNT-003', 4, 3000),
(21, 'NX-TNT-004', 4, 3100),
(22, 'NX-TNT-004', 5, 3150),
(23, 'NX-ACC-001', 5, 750),
(24, 'NX-ACC-002', 5, 580),
(25, 'NX-ACC-001', 4, 760),
(26, 'NX-RUB-001', NULL, 450),
(29, 'NX-TNT-005', NULL, 2800),
(32, 'NX-TNT-005', 1, 2500),
(33, 'NX-TNT-006', 5, 2300);

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

CREATE TABLE `receipts` (
  `receipt_id` int(11) NOT NULL,
  `total_amount` float NOT NULL,
  `discount` float NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receipts`
--

INSERT INTO `receipts` (`receipt_id`, `total_amount`, `discount`, `created_at`) VALUES
(1001, 13200, 0, '2025-10-01 09:15:23'),
(1002, 6790, 0, '2025-10-01 10:42:56'),
(1003, 6520, 0, '2025-10-02 11:08:19'),
(1004, 4232, 0, '2025-10-03 14:15:00'),
(1005, 4527.5, 0, '2025-10-03 15:45:31'),
(1006, 11580, 0, '2025-10-04 09:33:11'),
(1007, 6870, 0, '2025-10-04 11:15:03'),
(1008, 7060, 0, '2025-10-05 16:40:18'),
(1009, 7060, 0, '2025-10-06 10:20:00'),
(1010, 3667, 0, '2025-10-06 13:45:12'),
(1011, 10580, 0, '2025-10-07 09:30:45'),
(1012, 3300, 0, '2025-10-07 15:10:33'),
(1013, 14040, 0, '2025-10-08 11:22:18'),
(1014, 3565, 0, '2025-10-08 16:05:50'),
(1015, 4357.5, 0, '2025-10-09 10:15:00'),
(1016, 1000, 80, '2025-11-06 16:41:31'),
(1017, 5700, 300, '2025-11-14 22:05:21'),
(1018, 500, 40, '2025-11-14 22:06:55'),
(1019, 540, 0, '2025-11-14 22:08:45'),
(1020, 540, 0, '2025-11-14 22:08:45'),
(1021, 3000, 300, '2025-11-14 22:13:05'),
(1022, 500, 40, '2025-11-14 22:21:24'),
(1023, 3000, 300, '2025-11-14 22:22:12'),
(1024, 7800, 230, '2025-11-14 22:23:22'),
(1025, 5500, 500, '2025-11-14 22:25:15'),
(1026, 6300, 335, '2025-11-14 23:06:00'),
(1027, 6300, 335, '2025-11-14 23:06:20'),
(1028, 6635, 0, '2025-11-14 23:07:22'),
(1029, 6635, 0, '2025-11-14 23:07:22'),
(1030, 8000, 30, '2025-11-14 23:09:43'),
(1031, 11000, 1135, '2025-11-14 23:21:16'),
(1032, 600, 67, '2025-11-14 23:22:32');

-- --------------------------------------------------------

--
-- Table structure for table `receipt_details`
--

CREATE TABLE `receipt_details` (
  `receipt_details_id` int(11) NOT NULL,
  `receipt_id` int(11) DEFAULT NULL,
  `nixar_product_sku` varchar(30) DEFAULT NULL,
  `quantity` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receipt_details`
--

INSERT INTO `receipt_details` (`receipt_details_id`, `receipt_id`, `nixar_product_sku`, `quantity`) VALUES
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
(27, 1015, 'NX-ACC-001', 1),
(28, 1016, 'NX-RUB-001', 2),
(29, 1017, 'NX-GLS-001', 1),
(30, 1018, 'NX-RUB-001', 1),
(31, 1019, 'NX-RUB-001', 1),
(32, 1020, 'NX-RUB-001', 1),
(33, 1021, 'NX-TNT-003', 1),
(34, 1022, 'NX-RUB-001', 1),
(35, 1023, 'NX-TNT-003', 1),
(36, 1024, 'NX-GLS-002', 1),
(37, 1025, 'NX-GLS-001', 1),
(38, 1026, 'NX-TNT-004', 1),
(39, 1026, 'NX-TNT-006', 1),
(40, 1026, 'NX-RUB-001', 1),
(41, 1027, 'NX-TNT-004', 1),
(42, 1027, 'NX-TNT-006', 1),
(43, 1027, 'NX-RUB-001', 1),
(44, 1028, 'NX-TNT-004', 1),
(45, 1028, 'NX-TNT-006', 1),
(46, 1028, 'NX-RUB-001', 1),
(47, 1029, 'NX-TNT-004', 1),
(48, 1029, 'NX-TNT-006', 1),
(49, 1029, 'NX-RUB-001', 1),
(50, 1030, 'NX-GLS-002', 1),
(51, 1031, 'NX-GLS-002', 1),
(52, 1031, 'NX-RUB-001', 1),
(53, 1031, 'NX-TNT-004', 1),
(54, 1032, 'NX-ACC-002', 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `sales_by_time_view`
-- (See below for the actual view)
--
CREATE TABLE `sales_by_time_view` (
`hour_label` varchar(5)
,`total_orders` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `sales_report_view`
-- (See below for the actual view)
--
CREATE TABLE `sales_report_view` (
`total_revenue` double
,`total_transactions` bigint(21)
,`avg_transaction_value` double(19,2)
,`profit_performance` int(1)
);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL,
  `supplier_name` varchar(60) NOT NULL,
  `contact_no` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `supplier_name`, `contact_no`) VALUES
(1, 'Raean Tamayo Residences', '9956446924'),
(2, 'CarPro Distributors', '09283456789'),
(3, 'TintTech Supplies', '09394561234'),
(4, 'VisionParts Imports', '09505672345'),
(5, 'ClearView Enterprise', '09616783456');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `issuer_id` int(11) DEFAULT NULL,
  `receipt_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `payment_method` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `issuer_id`, `receipt_id`, `customer_id`, `created_at`, `payment_method`) VALUES
(1, 2, 1001, 501, '2025-10-01 09:15:23', 'Cash'),
(2, 2, 1002, 502, '2025-10-01 10:42:56', 'GCash'),
(3, 2, 1003, 503, '2025-10-02 11:08:19', 'GCash'),
(4, 1, 1004, 504, '2025-10-03 14:15:00', 'Cash'),
(5, 2, 1005, 505, '2025-10-03 15:45:31', 'GCash'),
(6, 1, 1006, 506, '2025-10-04 09:33:11', 'Cash'),
(7, 2, 1007, 507, '2025-10-04 11:15:03', 'Cash'),
(8, 1, 1008, 508, '2025-10-05 16:40:18', 'GCash'),
(9, 2, 1009, 509, '2025-10-06 10:20:00', 'Cash'),
(10, 2, 1010, 510, '2025-10-06 13:45:12', 'GCash'),
(11, 1, 1011, 511, '2025-10-07 09:30:45', 'GCash'),
(12, 1, 1012, 512, '2025-10-07 15:10:33', 'Cash'),
(13, 2, 1013, 513, '2025-10-08 11:22:18', 'GCash'),
(14, 2, 1014, 514, '2025-10-08 16:05:50', 'Cash'),
(15, 2, 1015, 515, '2025-10-09 10:15:00', 'Cash'),
(16, 1, 1016, 515, '2025-11-06 16:41:31', 'GCash'),
(17, 1, 1017, 519, '2025-11-14 22:05:21', 'Cash'),
(18, 1, 1018, 521, '2025-11-14 22:06:55', 'G-Cash'),
(19, 1, 1019, 523, '2025-11-14 22:08:45', 'Cash'),
(20, 1, 1020, 525, '2025-11-14 22:08:45', 'Cash'),
(21, 1, 1021, 527, '2025-11-14 22:13:05', 'Cash'),
(22, 1, 1022, 529, '2025-11-14 22:21:24', 'G-Cash'),
(23, 1, 1023, 531, '2025-11-14 22:22:12', 'Cash'),
(24, 1, 1024, 533, '2025-11-14 22:23:22', 'Cash'),
(25, 1, 1025, 535, '2025-11-14 22:25:15', 'Cash'),
(26, 1, 1026, 537, '2025-11-14 23:06:00', 'Cash'),
(27, 1, 1027, 539, '2025-11-14 23:06:20', 'Cash'),
(28, 1, 1028, 541, '2025-11-14 23:07:22', 'Cash'),
(29, 1, 1029, 543, '2025-11-14 23:07:22', 'Cash'),
(30, 1, 1030, 545, '2025-11-14 23:09:43', 'Cash'),
(31, 1, 1031, 547, '2025-11-14 23:21:16', 'Cash'),
(32, 2, 1032, 549, '2025-11-14 23:22:32', 'Cash');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `role` varchar(20) DEFAULT NULL,
  `name` varchar(60) NOT NULL,
  `password_hashed` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `role`, `name`, `password_hashed`) VALUES
(1, 'admin', 'raeantamayo', '$2y$10$pAf3CK.k5mvP0Ii2fzz4K.eQbH/iQskBlmv2XtN0mvmsgoTqRdxC2'),
(2, 'cashier', 'sunny123', '$2y$10$WYefz0uFxo2f2OtLir7HY.9r5ku83giUa0UQ9O2Lw/I/GXhafYM4K');

-- --------------------------------------------------------

--
-- Structure for view `best_selling_item_by_revenue_view`
--
DROP TABLE IF EXISTS `best_selling_item_by_revenue_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `best_selling_item_by_revenue_view`  AS SELECT `sub`.`category` AS `category`, `sub`.`product_name` AS `product_name`, sum(`sub`.`final_price`) AS `grouped_price` FROM (`receipt_details` `rd` left join (select `np`.`nixar_product_sku` AS `nixar_product_sku`,`pm`.`category` AS `category`,`np`.`product_name` AS `product_name`,round(`ps`.`base_price` + `ps`.`base_price` * (`np`.`mark_up` / 100),2) AS `final_price` from ((`nixar_products` `np` left join `product_suppliers` `ps` on(`np`.`product_supplier_id` = `ps`.`product_supplier_id`)) left join `product_materials` `pm` on(`np`.`product_material_id` = `pm`.`product_material_id`))) `sub` on(`rd`.`nixar_product_sku` = `sub`.`nixar_product_sku`)) GROUP BY `rd`.`nixar_product_sku` ORDER BY sum(`sub`.`final_price`) DESC LIMIT 0, 5 ;

-- --------------------------------------------------------

--
-- Structure for view `category_performance_view`
--
DROP TABLE IF EXISTS `category_performance_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `category_performance_view`  AS SELECT `product_materials`.`category` AS `category`, count(`product_materials`.`category`) AS `category_performance` FROM `product_materials` GROUP BY `product_materials`.`category` ORDER BY count(`product_materials`.`category`) DESC LIMIT 0, 5 ;

-- --------------------------------------------------------

--
-- Structure for view `count_low_stock_items_view`
--
DROP TABLE IF EXISTS `count_low_stock_items_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `count_low_stock_items_view`  AS SELECT count(`np`.`nixar_product_sku`) AS `low_stock` FROM ((`inventory` `i` join `nixar_products` `np` on(`i`.`nixar_product_sku` = `np`.`nixar_product_sku`)) join `product_materials` `pm` on(`np`.`product_material_id` = `pm`.`product_material_id`)) WHERE `i`.`current_stock` <= `i`.`min_threshold` ;

-- --------------------------------------------------------

--
-- Structure for view `low_stock_items_view`
--
DROP TABLE IF EXISTS `low_stock_items_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `low_stock_items_view`  AS SELECT `np`.`nixar_product_sku` AS `nixar_product_sku`, `np`.`product_name` AS `product_name`, `i`.`current_stock` AS `current_stock`, `i`.`updated_at` AS `updated_at` FROM ((`inventory` `i` join `nixar_products` `np` on(`i`.`nixar_product_sku` = `np`.`nixar_product_sku`)) join `product_materials` `pm` on(`np`.`product_material_id` = `pm`.`product_material_id`)) WHERE `i`.`current_stock` <= `i`.`min_threshold` ORDER BY `i`.`current_stock` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `most_sold_item_by_qty_view`
--
DROP TABLE IF EXISTS `most_sold_item_by_qty_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `most_sold_item_by_qty_view`  AS SELECT `np`.`product_name` AS `product_name`, sum(`rd`.`quantity`) AS `total_quantity_sold` FROM ((`receipt_details` `rd` join `nixar_products` `np` on(`rd`.`nixar_product_sku` = `np`.`nixar_product_sku`)) join `product_materials` `pm` on(`np`.`product_material_id` = `pm`.`product_material_id`)) GROUP BY `np`.`nixar_product_sku`, `np`.`product_name`, `pm`.`category` ORDER BY sum(`rd`.`quantity`) DESC LIMIT 0, 5 ;

-- --------------------------------------------------------

--
-- Structure for view `product_inventory_view`
--
DROP TABLE IF EXISTS `product_inventory_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `product_inventory_view`  AS SELECT `np`.`product_name` AS `product_name`, `np`.`nixar_product_sku` AS `nixar_product_sku`, `np`.`product_img_url` AS `product_img_url`, `pm`.`category` AS `category`, `pm`.`material_name` AS `material_name`, `pm`.`product_material_id` AS `product_material_id`, `i`.`inventory_id` AS `inventory_id`, `i`.`min_threshold` AS `min_threshold`, `i`.`current_stock` AS `current_stock`, `ps`.`base_price` AS `base_price`, `s`.`supplier_id` AS `supplier_id`, `s`.`supplier_name` AS `supplier_name`, `np`.`mark_up` AS `mark_up`, `ps`.`product_supplier_id` AS `product_supplier_id`, round(`ps`.`base_price` + `ps`.`base_price` * (`np`.`mark_up` / 100),2) AS `final_price`, group_concat(distinct concat(`cm`.`make`,' ',`cm`.`model`,' ',`cm`.`year`,' ',`cm`.`type`) separator ', ') AS `compatible_cars` FROM ((((((`nixar_products` `np` join `product_materials` `pm` on(`np`.`product_material_id` = `pm`.`product_material_id`)) join `inventory` `i` on(`np`.`nixar_product_sku` = `i`.`nixar_product_sku`)) left join `product_suppliers` `ps` on(`np`.`nixar_product_sku` = `ps`.`nixar_product_sku`)) join `suppliers` `s` on(`ps`.`supplier_id` = `s`.`supplier_id`)) left join `product_compatibility` `pc` on(`pc`.`nixar_product_sku` = `np`.`nixar_product_sku`)) left join `car_models` `cm` on(`cm`.`car_model_id` = `pc`.`car_model_id`)) WHERE `np`.`is_deleted` = 0 GROUP BY `np`.`nixar_product_sku`, `ps`.`product_supplier_id` ;

-- --------------------------------------------------------

--
-- Structure for view `sales_by_time_view`
--
DROP TABLE IF EXISTS `sales_by_time_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `sales_by_time_view`  AS SELECT date_format(`receipts`.`created_at`,'%l %p') AS `hour_label`, count(0) AS `total_orders` FROM `receipts` GROUP BY date_format(`receipts`.`created_at`,'%l %p'), hour(`receipts`.`created_at`) ORDER BY hour(`receipts`.`created_at`) ASC LIMIT 0, 5 ;

-- --------------------------------------------------------

--
-- Structure for view `sales_report_view`
--
DROP TABLE IF EXISTS `sales_report_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `sales_report_view`  AS SELECT sum(`r`.`total_amount`) AS `total_revenue`, count(`r`.`receipt_id`) AS `total_transactions`, round(avg(`r`.`total_amount`),2) AS `avg_transaction_value`, 0 AS `profit_performance` FROM `receipts` AS `r` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `car_details`
--
ALTER TABLE `car_details`
  ADD PRIMARY KEY (`car_detail_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `car_model_id` (`car_model_id`);

--
-- Indexes for table `car_models`
--
ALTER TABLE `car_models`
  ADD PRIMARY KEY (`car_model_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`inventory_id`),
  ADD KEY `nixar_product_sku` (`nixar_product_sku`);

--
-- Indexes for table `nixar_products`
--
ALTER TABLE `nixar_products`
  ADD PRIMARY KEY (`nixar_product_sku`),
  ADD KEY `product_material_id` (`product_material_id`);

--
-- Indexes for table `product_compatibility`
--
ALTER TABLE `product_compatibility`
  ADD PRIMARY KEY (`product_compatibility_id`),
  ADD KEY `nixar_product_sku` (`nixar_product_sku`),
  ADD KEY `car_model_id` (`car_model_id`);

--
-- Indexes for table `product_materials`
--
ALTER TABLE `product_materials`
  ADD PRIMARY KEY (`product_material_id`);

--
-- Indexes for table `product_suppliers`
--
ALTER TABLE `product_suppliers`
  ADD PRIMARY KEY (`product_supplier_id`);

--
-- Indexes for table `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`receipt_id`);

--
-- Indexes for table `receipt_details`
--
ALTER TABLE `receipt_details`
  ADD PRIMARY KEY (`receipt_details_id`),
  ADD KEY `receipt_id` (`receipt_id`),
  ADD KEY `nixar_product_sku` (`nixar_product_sku`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `issuer_id` (`issuer_id`),
  ADD KEY `receipt_id` (`receipt_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `car_details`
--
ALTER TABLE `car_details`
  MODIFY `car_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `car_models`
--
ALTER TABLE `car_models`
  MODIFY `car_model_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=550;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `product_compatibility`
--
ALTER TABLE `product_compatibility`
  MODIFY `product_compatibility_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `product_materials`
--
ALTER TABLE `product_materials`
  MODIFY `product_material_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `product_suppliers`
--
ALTER TABLE `product_suppliers`
  MODIFY `product_supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `receipts`
--
ALTER TABLE `receipts`
  MODIFY `receipt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1033;

--
-- AUTO_INCREMENT for table `receipt_details`
--
ALTER TABLE `receipt_details`
  MODIFY `receipt_details_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `car_details`
--
ALTER TABLE `car_details`
  ADD CONSTRAINT `car_details_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  ADD CONSTRAINT `car_details_ibfk_2` FOREIGN KEY (`car_model_id`) REFERENCES `car_models` (`car_model_id`);

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`nixar_product_sku`) REFERENCES `nixar_products` (`nixar_product_sku`);

--
-- Constraints for table `nixar_products`
--
ALTER TABLE `nixar_products`
  ADD CONSTRAINT `nixar_products_ibfk_1` FOREIGN KEY (`product_material_id`) REFERENCES `product_materials` (`product_material_id`);

--
-- Constraints for table `product_compatibility`
--
ALTER TABLE `product_compatibility`
  ADD CONSTRAINT `product_compatibility_ibfk_1` FOREIGN KEY (`nixar_product_sku`) REFERENCES `nixar_products` (`nixar_product_sku`),
  ADD CONSTRAINT `product_compatibility_ibfk_2` FOREIGN KEY (`car_model_id`) REFERENCES `car_models` (`car_model_id`);

--
-- Constraints for table `receipt_details`
--
ALTER TABLE `receipt_details`
  ADD CONSTRAINT `receipt_details_ibfk_1` FOREIGN KEY (`receipt_id`) REFERENCES `receipts` (`receipt_id`),
  ADD CONSTRAINT `receipt_details_ibfk_2` FOREIGN KEY (`nixar_product_sku`) REFERENCES `nixar_products` (`nixar_product_sku`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`issuer_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`receipt_id`) REFERENCES `receipts` (`receipt_id`),
  ADD CONSTRAINT `transactions_ibfk_3` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
