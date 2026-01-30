-- =============================================
-- Lab 2: Intermediate Union SQL Injection
-- Database: lab2_sqli
-- Difficulty: Medium (Basic WAF - case-sensitive)
-- =============================================

CREATE DATABASE IF NOT EXISTS lab2_sqli;
USE lab2_sqli;

-- Customers table (visible to users)
CREATE TABLE customers (
    id INT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20)
);

INSERT INTO customers VALUES
(1, 'Somchai Jaidi', 'somchai@email.com', '081-234-5678'),
(2, 'Somying Rakrien', 'somying@email.com', '089-876-5432'),
(3, 'Wichai Kengmak', 'wichai@email.com', '062-345-6789'),
(4, 'Nida Suayngam', 'nida@email.com', '095-111-2222');

-- Admin users table (hidden)
CREATE TABLE admin_users (
    id INT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(100),
    role VARCHAR(20)
);

INSERT INTO admin_users VALUES
(1, 'admin', 'Sup3r$ecretP@ss', 'administrator'),
(2, 'moderator', 'M0d#Pass2024', 'moderator');

-- Secret data table (target - contains flag)
CREATE TABLE secret_data (
    id INT PRIMARY KEY,
    secret_key VARCHAR(100),
    secret_value VARCHAR(200)
);

INSERT INTO secret_data VALUES
(1, 'api_key', 'sk-test-xxxx-yyyy-zzzz'),
(2, 'flag', 'FLAG{WAF_Byp4ss_C4s3_Var1at10n}'),
(3, 'encryption_key', 'AES-256-CBC-key-do-not-share');
