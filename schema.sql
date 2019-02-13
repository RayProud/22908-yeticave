CREATE DATABASE yeticave
	DEFAULT CHARACTER SET utf8
	DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE category (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title CHAR(32) NOT NULL UNIQUE
);

CREATE INDEX category_title on category(title);

CREATE TABLE user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  email CHAR(255) UNIQUE NOT NULL,
  name CHAR(255) NOT NULL,
  password CHAR(32) NOT NULL,
  image_url CHAR(255),
  contacts TEXT(1024) NOT NULL
);

CREATE INDEX user_name on user(name);
CREATE INDEX user_email on user(email);

CREATE TABLE lot (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  title CHAR(128) NOT NULL,
  description TEXT(1024) NOT NULL,
  image_url CHAR(255) NOT NULL,
  start_price FLOAT UNSIGNED NOT NULL,
  end_at DATETIME NOT NULL,
  bet_step INT UNSIGNED NOT NULL,
  author_id INT NOT NULL,
  winner_id INT,
  category_id INT NOT NULL,
  FOREIGN KEY (author_id) REFERENCES user(id),
  FOREIGN KEY (winner_id) REFERENCES user(id),
  FOREIGN KEY (category_id) REFERENCES category(id)
);

CREATE INDEX lot_title on lot(title);

CREATE TABLE bet (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  amount INT UNSIGNED NOT NULL,
  author_id INT NOT NULL,
  lot_id INT NOT NULL,
  FOREIGN KEY (author_id) REFERENCES user(id),
  FOREIGN KEY (lot_id) REFERENCES lot(id)
);
