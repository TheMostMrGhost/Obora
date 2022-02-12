DROP TABLE FRIENDS;
DROP TABLE USERS;

CREATE TABLE USERS (
    id NUMBER(6) PRIMARY KEY,
    login VARCHAR2(15) NOT NULL,
    password VARCHAR2(15) NOT NULL,
    email VARCHAR2(18),
    region VARCHAR2(20)
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

insert into USERS values (1, 'ghost', 'ghost', 'ghost@ghost.ghost', 'Poland');
insert into USERS values (2, 'adam', 'adam', 'adam@adam.adam', 'Poland');
insert into USERS values (3, 'MCarl', 'MCarl', '1@gmail.com', 'Sweden');
insert into USERS values (4, 'Carlsbad', 'CBD', '2@gmail.com', 'Ponta Delgada');

COMMIT;