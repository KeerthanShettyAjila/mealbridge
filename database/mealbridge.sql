CREATE DATABASE IF NOT EXISTS mealbridge CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mealbridge;
CREATE TABLE IF NOT EXISTS users (
 id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, email VARCHAR(190) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL, role ENUM('admin','donor','orphanage') NOT NULL,
 status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
 phone VARCHAR(20) NOT NULL, city VARCHAR(100) NOT NULL, address VARCHAR(500) NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS donations (
 id INT AUTO_INCREMENT PRIMARY KEY, donor_id INT NOT NULL, orphanage_id INT NULL,
 title VARCHAR(120) NOT NULL, food_type ENUM('Vegetarian','Non-vegetarian','Vegan') NOT NULL,
 category ENUM('Cooked meals','Produce','Bakery','Packaged food') NOT NULL,
 quantity INT NOT NULL, city VARCHAR(100) NOT NULL, address VARCHAR(500) NOT NULL,
 description TEXT NOT NULL, allergens VARCHAR(255) NOT NULL, deadline DATETIME NOT NULL,
 status ENUM('pending','accepted','collected','cancelled','expired') NOT NULL DEFAULT 'pending',
 pickup_note VARCHAR(500) NOT NULL DEFAULT '', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 collected_at DATETIME NULL, FOREIGN KEY(donor_id) REFERENCES users(id), FOREIGN KEY(orphanage_id) REFERENCES users(id),
 INDEX(status, deadline)
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS donation_events (
 id INT AUTO_INCREMENT PRIMARY KEY, donation_id INT NOT NULL, actor_id INT NOT NULL,
 message VARCHAR(255) NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(donation_id) REFERENCES donations(id), FOREIGN KEY(actor_id) REFERENCES users(id)
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS messages (
 id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, email VARCHAR(190) NOT NULL,
 message TEXT NOT NULL, is_read TINYINT NOT NULL DEFAULT 0, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
