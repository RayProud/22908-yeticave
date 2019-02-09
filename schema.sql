CREATE DATABASE yeticave
	DEFAULT CHARACTER SET utf8
	DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE category (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title CHAR(32) NOT NULL UNIQUE
);

CREATE UNIQUE INDEX category_id on category(id);
CREATE INDEX category_title on category(title);

CREATE TABLE user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  email CHAR(255) UNIQUE,
  name CHAR(255) NOT NULL,
  password CHAR(32) NOT NULL,
  image CHAR(255),
  contacts TEXT(1024)
);

CREATE UNIQUE INDEX user_id on user(id);
CREATE INDEX user_name on user(name);

CREATE TABLE lot (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  title CHAR(128) NOT NULL,
  description TEXT(1024),
  image CHAR(255),
  start_price DECIMAL NOT NULL,
  end_at DATETIME,
  bet_step DECIMAL NOT NULL,
  author_id INT NOT NULL,
  winner_id INT,
  category_id INT NOT NULL,
  FOREIGN KEY (author_id) REFERENCES user(id),
  FOREIGN KEY (winner_id) REFERENCES user(id),
  FOREIGN KEY (category_id) REFERENCES category(id)
);

CREATE UNIQUE INDEX lot_id on lot(id);
CREATE INDEX lot_title on lot(title);

CREATE TABLE bet (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  amount DECIMAL NOT NULL,
  author_id INT NOT NULL,
  lot_id INT NOT NULL,
  FOREIGN KEY (author_id) REFERENCES user(id),
  FOREIGN KEY (lot_id) REFERENCES lot(id)
);

CREATE UNIQUE INDEX bet_id on bet(id);

