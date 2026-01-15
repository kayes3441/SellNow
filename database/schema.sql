-- Intentionally imperfect schema
-- Inconsistent naming, mixed casing, missing FKs

DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email VARCHAR(255) NOT NULL,
    username VARCHAR(50) NOT NULL,
    full_Name VARCHAR(100),
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS products;
CREATE TABLE products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    title VARCHAR(255),
    slug VARCHAR(255),
    description TEXT,
    price DECIMAL(10,2),
    image_path VARCHAR(255),
    file_path VARCHAR(255),
    is_active TINYINT DEFAULT 1
    CONSTRAINT products_user_id
    FOREIGN KEY (user_id)
    REFERENCES users(id);
);

DROP TABLE IF EXISTS carts;
CREATE TABLE carts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    session_id VARCHAR(255),
    product_id INTEGER,
    quantity INTEGER DEFAULT 1,
    created_at DATETIME
);

DROP TABLE IF EXISTS orders;
CREATE TABLE orders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    product_id INT,
    total_amount DECIMAL(10,2),
    payment_provider VARCHAR(50),
    payment_status VARCHAR(20),
    transaction_id VARCHAR(100),
    order_date DATETIME
);

DROP TABLE IF EXISTS payment_providers;
CREATE TABLE payment_providers (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    provider_name VARCHAR(50),
    api_key VARCHAR(255),
    api_secret VARCHAR(255),
    is_enabled TINYINT
);
