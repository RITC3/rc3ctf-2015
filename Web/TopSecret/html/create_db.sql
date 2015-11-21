DROP DATABASE IF EXISTS TopSecret;
CREATE DATABASE TopSecret;
USE TopSecret;
CREATE TABLE users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username varchar(64) NOT NULL,
    password varchar(128) NOT NULL,
	session varchar(128) NOT NULL,
    admin INT NOT NULL DEFAULT 0
);

CREATE TABLE secrets (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	secret varchar(128) NOT NULL,
	userid INT NOT NULL,

	FOREIGN KEY (userid) REFERENCES users(id)
);
