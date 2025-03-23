CREATE DATABASE SockAZon;
USE SockAZon;

CREATE TABLE users (
  uid SERIAL NOT NULL,
  username VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  pfp BLOB,
  email VARCHAR(255) NOT NULL,
  cart int NOT NULL,
  saved int NOT Null,
  PRIMARY KEY (uid)
);

CREATE TABLE products (
  pid SERIAL NOT NULL,
  name varchar(255) NOT NULL UNIQUE,
  price float NOT NULL,
  product_image VARCHAR(255),
  PRIMARY KEY (pid)

);

-- PLACEHOLDER VALUES
INSERT INTO users (username, password) VALUES ('Guest', 'password123');
INSERT INTO products (name, price, product_image) VALUES ('Thunder Sock', '12.99','..\images\StormSock.png');