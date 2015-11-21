DROP DATABASE IF EXISTS MegaFile;
CREATE DATABASE MegaFile;
USE MegaFile;
CREATE TABLE users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username varchar(64) NOT NULL,
	first varchar(64) NOT NULL,
	last varchar(64) NOT NULL,
	bio varchar(8192) NOT NULL,
    password varchar(128) NOT NULL,
    admin INT NOT NULL DEFAULT 0
);

CREATE TABLE files (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	name varchar(256) NOT NULL,
	type varchar(128) NOT NULL,
	size INT NOT NULL,
	content MEDIUMBLOB NOT NULL,
	userid INT NOT NULL,

	FOREIGN KEY (userid) REFERENCES users(id)
);

CREATE TABLE shares (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	ownerid INT NOT NULL,
	shareid INT NOT NULL,
	FOREIGN KEY (ownerid) REFERENCES users(id),
	FOREIGN KEY (shareid) REFERENCES users(id)
);

CREATE TABLE privkeys (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	privkey varchar(2048) NOT NULL,
	userid INT NOT NULL,
	FOREIGN KEY (userid) REFERENCES users(id)
);
