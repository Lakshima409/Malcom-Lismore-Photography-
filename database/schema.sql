-- =====================================================================
--  Malcolm Lismore Photography - Database Schema
--  Target: MySQL / MariaDB (developed on XAMPP MariaDB 10.4)
--  Run this file first, then sample-data.sql
-- =====================================================================

CREATE DATABASE IF NOT EXISTS malcolm_lismore
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE malcolm_lismore;

-- ---------------------------------------------------------------------
--  Table: categories
--  The five gallery categories. One category has many photographs.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS categories (
  category_id  INT AUTO_INCREMENT PRIMARY KEY,
  name         VARCHAR(100)  NOT NULL,
  slug         VARCHAR(100)  NOT NULL UNIQUE,
  description  TEXT          NULL,
  created_at   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
--  Table: photos
--  Every gallery photograph. Belongs to exactly one category.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS photos (
  photo_id     INT AUTO_INCREMENT PRIMARY KEY,
  category_id  INT           NOT NULL,
  title        VARCHAR(150)  NOT NULL,
  description  TEXT          NULL,
  image_path   VARCHAR(255)  NOT NULL,
  alt_text     VARCHAR(255)  NOT NULL,
  location     VARCHAR(150)  NULL,
  created_at   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_photos_category
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  INDEX idx_photos_category (category_id),
  INDEX idx_photos_created  (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
--  Table: services
--  Packages shown on the prices page. Kept in the database so prices
--  are not hard-coded into the page.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS services (
  service_id     INT AUTO_INCREMENT PRIMARY KEY,
  name           VARCHAR(100)    NOT NULL,
  description    TEXT            NULL,
  starting_price DECIMAL(10,2)   NULL,
  price_note     VARCHAR(150)    NULL,
  includes       TEXT            NULL,
  image_path     VARCHAR(255)    NULL,
  alt_text       VARCHAR(255)    NULL,
  sort_order     INT             NOT NULL DEFAULT 0,
  is_active      TINYINT(1)      NOT NULL DEFAULT 1,
  created_at     TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_services_active (is_active, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
--  Table: enquiries
--  Booking enquiries submitted through the enquiry form.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS enquiries (
  enquiry_id    INT AUTO_INCREMENT PRIMARY KEY,
  name          VARCHAR(100)    NOT NULL,
  email         VARCHAR(150)    NOT NULL,
  phone         VARCHAR(40)     NULL,
  service_type  VARCHAR(100)    NULL,
  event_date    DATE            NULL,
  location      VARCHAR(150)    NULL,
  budget        VARCHAR(60)     NULL,
  message       TEXT            NOT NULL,
  status        ENUM('new','contacted','booked','closed')
                                NOT NULL DEFAULT 'new',
  created_at    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_enquiries_status  (status),
  INDEX idx_enquiries_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
--  Table: admins  (optional administrative area)
--  Stores administrator login details with a hashed password.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS admins (
  admin_id       INT AUTO_INCREMENT PRIMARY KEY,
  username       VARCHAR(60)   NOT NULL UNIQUE,
  password_hash  VARCHAR(255)  NOT NULL,
  created_at     TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
