-- Lab 1: Basic Union-based SQL Injection Database
-- Database: lab1_sqli

CREATE DATABASE IF NOT EXISTS lab1_sqli;
USE lab1_sqli;

-- ตาราง products สำหรับการค้นหาปกติ
CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY,
    name VARCHAR(100),
    price DECIMAL(10, 2),
    description TEXT
);

INSERT INTO products (id, name, price, description) VALUES
(1, 'Laptop Dell XPS 15', 45900.00, 'Intel Core i7, 16GB RAM, 512GB SSD'),
(2, 'iPhone 15 Pro', 42900.00, 'A17 Pro chip, 256GB, Titanium design'),
(3, 'Samsung Galaxy S24', 28900.00, 'Snapdragon 8 Gen 3, 12GB RAM'),
(4, 'iPad Air M2', 24900.00, 'M2 chip, 11-inch display, 128GB');

-- ตาราง users ที่มี flag ซ่อนอยู่
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50),
    password VARCHAR(100),
    email VARCHAR(100),
    role VARCHAR(20)
);

INSERT INTO users (username, password, email, role) VALUES
('admin', 'admin_pass_2024', 'admin@lab1.local', 'administrator'),
('john_doe', 'john12345', 'john@lab1.local', 'user'),
('jane_smith', 'jane@secure', 'jane@lab1.local', 'user'),
('flaguser', 'FLAG{UN10N_B4S1C_SQL1_M4ST3R}', 'flag@lab1.local', 'secret');

-- ตารางเพิ่มเติมสำหรับการสำรวจ
CREATE TABLE IF NOT EXISTS orders (
    order_id INT PRIMARY KEY,
    user_id INT,
    product_id INT,
    quantity INT,
    order_date DATE
);

INSERT INTO orders (order_id, user_id, product_id, quantity, order_date) VALUES
(1001, 1, 1, 1, '2024-01-15'),
(1002, 2, 2, 2, '2024-01-16'),
(1003, 3, 3, 1, '2024-01-17');
