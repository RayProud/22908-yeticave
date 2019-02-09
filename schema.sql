CREATE DATABASE yeticave
	DEFAULT CHARACTER SET utf8
	DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE category (
  category_id INT AUTO_INCREMENT PRIMARY KEY,
  title CHAR(32) NOT NULL UNIQUE
);

CREATE TABLE lot (
  lot_id INT AUTO_INCREMENT PRIMARY KEY,
  created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  title CHAR(128) NOT NULL,
  description TEXT(1024),
  image CHAR(255),
  start_price DECIMAL NOT NULL,
  end_at TIMESTAMP NOT NULL,
  bet_step DECIMAL NOT NULL,
  author_id INT NOT NULL,
  winner_id INT,
  category_id INT NOT NULL
);

CREATE TABLE bet (
  bet_id INT AUTO_INCREMENT PRIMARY KEY,
  created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  amount DECIMAL NOT NULL,
  author_id INT NOT NULL,
  lot_id INT NOT NULL
);

CREATE TABLE user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  registered_at TIMESTAMP,
  email CHAR(255) UNIQUE,
  name CHAR(255),
  password CHAR(32),
  image CHAR(255),
  contacts TEXT(1024),
  lots CHAR(255)
);
