#CREATE USER 'ts-dba'@'localhost' IDENTIFIED BY 'ThisIsMuhDBpassword!';
CREATE USER 'ts-dba-find'@'localhost' IDENTIFIED BY 'ThisIsMuhFINDpassword!';
GRANT SELECT,INSERT on TopSecret.* to 'ts-dba'@'localhost';
GRANT SELECT on TopSecret.users to 'ts-dba-find'@'localhost';
FLUSH PRIVILEGES;

