-- =============================================
-- Lab 1: Basic Union-based SQL Injection
-- Database: lab1_sqli
-- Difficulty: Easy (No WAF)
-- =============================================

CREATE DATABASE IF NOT EXISTS lab1_sqli;
USE lab1_sqli;

-- Products table (visible to users)
CREATE TABLE products (
    id INT PRIMARY KEY,
    name VARCHAR(100),
    price DECIMAL(10,2),
    description TEXT
);

INSERT INTO products VALUES
(1, 'Laptop Gaming', 35900.00, 'High-performance gaming laptop with RTX 4060'),
(2, 'Mechanical Keyboard', 2490.00, 'RGB mechanical keyboard with Cherry MX switches'),
(3, 'Gaming Mouse', 1590.00, 'Wireless gaming mouse 25600 DPI'),
(4, 'Monitor 27 inch', 8990.00, '27 inch IPS 165Hz gaming monitor');

-- Users table (target - contains flag)
CREATE TABLE users (
    id INT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(100)
);

INSERT INTO users VALUES
(1, 'admin', 'admin@securepassword'),
(2, 'staff', 'staff1234'),
(3, 'flaguser', 'FLAG{SQL_Inj3ct10n_B4s1c_Mast3r}');
