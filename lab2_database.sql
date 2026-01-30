-- Lab 2: Intermediate Union-based SQL Injection with WAF
-- Database: lab2_sqli

CREATE DATABASE IF NOT EXISTS lab2_sqli;
USE lab2_sqli;

-- ตาราง customers
CREATE TABLE IF NOT EXISTS customers (
    id INT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20)
);

INSERT INTO customers (id, name, email, phone) VALUES
(1, 'สมชาย ใจดี', 'somchai@email.com', '081-234-5678'),
(2, 'สมหญิง รักสวย', 'somying@email.com', '082-345-6789'),
(3, 'ประยุทธ์ มั่นคง', 'prayut@email.com', '083-456-7890'),
(4, 'สุดารัตน์ สวยงาม', 'sudarat@email.com', '084-567-8901');

-- ตาราง admin_users
CREATE TABLE IF NOT EXISTS admin_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50),
    password VARCHAR(100),
    level VARCHAR(20)
);

INSERT INTO admin_users (username, password, level) VALUES
('superadmin', 'super_secret_2024', 'super'),
('moderator', 'mod_pass_456', 'moderate'),
('support', 'support_789', 'basic');

-- ตาราง secret_data ที่มี FLAG
CREATE TABLE IF NOT EXISTS secret_data (
    id INT PRIMARY KEY AUTO_INCREMENT,
    data_type VARCHAR(50),
    secret_value TEXT,
    created_date DATE
);

INSERT INTO secret_data (data_type, secret_value, created_date) VALUES
('api_key', 'sk_test_abc123xyz789', '2024-01-10'),
('master_password', 'admin_master_2024', '2024-01-11'),
('flag', 'FLAG{W4F_BYP4SS_C4S3_V4R14T10N}', '2024-01-12'),
('encryption_key', 'aes256_key_secret', '2024-01-13');
