/*
 * We assume that GUI_ACL_* tables have already been created and have been populated
 * */
set @adminroleid := (SELECT ROLE_ID FROM GUI_ACL_ROLES WHERE name = 'administrator');
set @guestroleid := (SELECT ROLE_ID FROM GUI_ACL_ROLES WHERE name = 'guest');
INSERT IGNORE INTO GUI_ACL_RESOURCES VALUES(NULL, 'route:Token');
INSERT IGNORE INTO GUI_ACL_PRIVILEGES VALUES(@guestroleid,LAST_INSERT_ID(),'');
INSERT IGNORE INTO GUI_ACL_RESOURCES VALUES(NULL, 'route:TokenWebAPI');
INSERT IGNORE INTO GUI_ACL_PRIVILEGES VALUES(@adminroleid,LAST_INSERT_ID(),'');

CREATE TABLE IF NOT EXISTS GUI_USERS_TOKEN (
	TOKEN_ID INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	USERNAME INTEGER NOT NULL,
	TOKEN CHAR(60) NOT NULL,
	CREATION_TIME INTEGER NOT NULL
);