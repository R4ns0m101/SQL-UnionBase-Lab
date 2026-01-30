-- Lab 3: Advanced Union-based SQL Injection with Advanced WAF
-- Databases: lab3_ecommerce และ lab3_internal

-- Database 1: lab3_ecommerce
CREATE DATABASE IF NOT EXISTS lab3_ecommerce;
USE lab3_ecommerce;

CREATE TABLE IF NOT EXISTS orders (
    order_id INT PRIMARY KEY,
    customer_name VARCHAR(100),
    product_name VARCHAR(100),
    total_price DECIMAL(10, 2)
);

INSERT INTO orders (order_id, customer_name, product_name, total_price) VALUES
(1, 'วิชัย ประกอบธุรกิจ', 'MacBook Pro M3', 89900.00),
(2, 'นิภา สมบูรณ์', 'Sony WH-1000XM5', 13900.00),
(3, 'ธนากร รวยเงิน', 'Samsung QLED TV 65"', 45900.00),
(4, 'พรทิพย์ มีสุข', 'Nintendo Switch OLED', 12900.00),
(5, 'อนันต์ ชาญชัย', 'Canon EOS R6', 79900.00);

CREATE TABLE IF NOT EXISTS payment_methods (
    id INT PRIMARY KEY AUTO_INCREMENT,
    method_name VARCHAR(50),
    is_active BOOLEAN
);

INSERT INTO payment_methods (method_name, is_active) VALUES
('Credit Card', TRUE),
('Bank Transfer', TRUE),
('Cash on Delivery', TRUE),
('E-Wallet', TRUE);

-- Database 2: lab3_internal
CREATE DATABASE IF NOT EXISTS lab3_internal;
USE lab3_internal;

CREATE TABLE IF NOT EXISTS confidential_files (
    file_id INT PRIMARY KEY AUTO_INCREMENT,
    file_name VARCHAR(100),
    file_content TEXT,
    classification VARCHAR(20)
);

INSERT INTO confidential_files (file_name, file_content, classification) VALUES
('company_strategy.pdf', 'Q1 2024 Business Strategy...', 'CONFIDENTIAL'),
('employee_salaries.xlsx', 'Salary data for 2024...', 'RESTRICTED'),
('flag.txt', 'FLAG{4DV4NC3D_BL1ND_SQL1_H3X_M4ST3R}', 'TOP_SECRET'),
('api_credentials.json', 'API Keys and Secrets...', 'SECRET');

CREATE TABLE IF NOT EXISTS system_credentials (
    id INT PRIMARY KEY AUTO_INCREMENT,
    system_name VARCHAR(50),
    username VARCHAR(50),
    password VARCHAR(100)
);

INSERT INTO system_credentials (system_name, username, password) VALUES
('production_db', 'db_admin', 'P@ssw0rd_Pr0d_2024'),
('backup_server', 'backup_user', 'B4ckup_S3cr3t!'),
('monitoring', 'monitor_admin', 'M0n1t0r_K3y#2024');
