
DROP TABLE IF EXISTS users;
CREATE TABLE users (
   id INT AUTO_INCREMENT PRIMARY KEY,
   email VARCHAR(255) NOT NULL,
   username VARCHAR(50) NOT NULL,
   full_name VARCHAR(100),
   password VARCHAR(255) NOT NULL,
   created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

   UNIQUE INDEX idx_users_email (email),
   INDEX idx_users_username (username)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS products;
CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  title VARCHAR(255),
  slug VARCHAR(255),
  description TEXT,
  price DECIMAL(10,2),
  image_path VARCHAR(255),
  file_path VARCHAR(255),
  is_active TINYINT(1) DEFAULT 1,

  INDEX idx_products_user_id (user_id),
  INDEX idx_products_slug (slug),

  CONSTRAINT products_user_id
      FOREIGN KEY (user_id)
          REFERENCES users(id)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS carts;
CREATE TABLE carts (
   id INT AUTO_INCREMENT PRIMARY KEY,
   session_id VARCHAR(255) NULL,
   product_id INT,
   user_id INT,
   quantity INT DEFAULT 1,
   created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

   INDEX idx_carts_product_id (product_id),
   INDEX idx_carts_user_id (user_id),
   INDEX idx_carts_session_id (session_id),

   CONSTRAINT carts_product_id
       FOREIGN KEY (product_id)
           REFERENCES products(id)
           ON DELETE CASCADE,

   CONSTRAINT carts_user_id
       FOREIGN KEY (user_id)
           REFERENCES users(id)
           ON DELETE CASCADE
) ENGINE=InnoDB;
DROP TABLE IF EXISTS orders;
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    total_amount DECIMAL(10,2),
    payment_provider VARCHAR(50),
    payment_status VARCHAR(20),
    transaction_id VARCHAR(100),
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_orders_user_id (user_id),
    INDEX idx_orders_product_id (product_id),
    INDEX idx_orders_payment_status (payment_status),
    INDEX idx_orders_transaction_id (transaction_id),

    CONSTRAINT orders_product_id
        FOREIGN KEY (product_id)
            REFERENCES products(id)
            ON DELETE CASCADE,

    CONSTRAINT orders_user_id
        FOREIGN KEY (user_id)
            REFERENCES users(id)
            ON DELETE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS payment_providers;
CREATE TABLE payment_providers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    provider_name VARCHAR(50),
    api_key VARCHAR(255),
    api_secret VARCHAR(255),
    is_enabled TINYINT(1),

    INDEX idx_payment_providers_provider_name (provider_name),
    INDEX idx_payment_providers_is_enabled (is_enabled)
) ENGINE=InnoDB;
