/*
 * We assume that GUI_ACL_* tables have already been created and have been populated
 * We assume that the role_id of the administrator role is 5
 * We assume that the role_id of the guest role is 1
 * */
INSERT OR IGNORE INTO GUI_ACL_RESOURCES VALUES(NULL,'route:Token');
INSERT OR IGNORE INTO GUI_ACL_PRIVILEGES VALUES(1,last_insert_rowid(),'');
INSERT OR IGNORE INTO GUI_ACL_RESOURCES VALUES(NULL,'route:TokenWebAPI');
INSERT OR IGNORE INTO GUI_ACL_PRIVILEGES VALUES(5,last_insert_rowid(),'');

CREATE TABLE IF NOT EXISTS GUI_USERS_TOKEN (
	USERNAME INTEGER NOT NULL,
	TOKEN CHAR(60) NOT NULL,
	CREATION_TIME INTEGER NOT NULL
);