DROP TABLE USERS;

CREATE TABLE USERS (
    login VARCHAR2(15) PRIMARY KEY,
    password VARCHAR2(15) NOT NULL
);

-- DROP SEQUENCE id_s;
-- CREATE SEQUENCE id_s;

-- CREATE OR REPLACE TRIGGER auto_id
--     BEFORE INSERT ON USERS
--     FOR EACH ROW
-- begin
--     select auto_id
--     into :new.id
--     from DUAL
-- end;
-- /

insert into USERS values ('ghost', 'ghost');

COMMIT;