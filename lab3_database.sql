-- =============================================
-- Lab 3: Advanced Union SQL Injection
-- Databases: lab3_ecommerce + lab3_internal
-- Difficulty: Hard (Advanced WAF - keyword removal)
-- =============================================

-- =========================
-- Database 1: lab3_ecommerce
-- =========================
CREATE DATABASE IF NOT EXISTS lab3_ecommerce;
USE lab3_ecommerce;

-- Orders table (visible to users)
CREATE TABLE orders (
    order_id INT PRIMARY KEY,
    customer_name VARCHAR(100),
    product_name VARCHAR(100),
    total_price DECIMAL(10,2)
);

INSERT INTO orders VALUES
(1, 'Alice Johnson', 'MacBook Pro 16 inch', 89900.00),
(2, 'Bob Smith', 'iPhone 15 Pro Max', 52900.00),
(3, 'Charlie Brown', 'iPad Air M2', 27900.00),
(4, 'Diana Prince', 'Apple Watch Ultra', 31900.00),
(5, 'Eve Williams', 'AirPods Pro 2', 8990.00);

-- Payment methods table (hidden)
CREATE TABLE payment_methods (
    id INT PRIMARY KEY,
    customer_name VARCHAR(100),
    card_type VARCHAR(20),
    card_last_four VARCHAR(4)
);

INSERT INTO payment_methods VALUES
(1, 'Alice Johnson', 'VISA', '4321'),
(2, 'Bob Smith', 'Mastercard', '8765'),
(3, 'Charlie Brown', 'AMEX', '1234'),
(4, 'Diana Prince', 'VISA', '5678'),
(5, 'Eve Williams', 'Mastercard', '9012');

-- =========================
-- Database 2: lab3_internal
-- =========================
CREATE DATABASE IF NOT EXISTS lab3_internal;
USE lab3_internal;

-- Confidential files table (target - contains flag)
CREATE TABLE confidential_files (
    id INT PRIMARY KEY,
    filename VARCHAR(100),
    content TEXT,
    classification VARCHAR(20)
);

INSERT INTO confidential_files VALUES
(1, 'employee_data.xlsx', 'Contains all employee personal information', 'RESTRICTED'),
(2, 'financial_report.pdf', 'Q4 2024 financial statements', 'CONFIDENTIAL'),
(3, 'flag.txt', 'FLAG{Adv4nc3d_WAF_Byp4ss_D0uble_Wr1te}', 'TOP SECRET'),
(4, 'network_diagram.png', 'Internal network architecture', 'RESTRICTED');

-- System credentials table (hidden)
CREATE TABLE system_credentials (
    id INT PRIMARY KEY,
    service VARCHAR(50),
    username VARCHAR(50),
    password VARCHAR(100)
);

INSERT INTO system_credentials VALUES
(1, 'Production DB', 'db_admin', 'Pr0d#DB@dmin2024!'),
(2, 'AWS Console', 'cloud_admin', 'Cl0ud$ecure!'),
(3, 'VPN Gateway', 'vpn_admin', 'VPN@cc3ss#Key'),
(4, 'Email Server', 'mail_admin', 'M@il$3rver2024');
