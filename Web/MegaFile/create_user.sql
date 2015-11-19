CREATE USER 'mf-dba'@'localhost' IDENTIFIED BY 'ThisIsMuhDBpassword!';
CREATE USER 'mf-dba-del'@'localhost' IDENTIFIED BY 'ThisIsMuhDELpassword!';
CREATE USER 'mf-dba-upload'@'localhost' IDENTIFIED BY 'ThisIsMuhUPLOADpassword!';
GRANT SELECT,INSERT on MegaFile.* to 'mf-dba'@'localhost';
GRANT SELECT,DELETE on MegaFile.shares to 'mf-dba-del'@'localhost';
GRANT SELECT,DELETE on MegaFile.files to 'mf-dba-del'@'localhost';
GRANT SELECT,INSERT,UPDATE on MegaFile.files to 'mf-dba-upload'@'localhost';
GRANT SELECT,INSERT,UPDATE on MegaFile.users to 'mf-dba-upload'@'localhost';
FLUSH PRIVILEGES;

