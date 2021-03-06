DROP TABLE GRY_NA_AKTUALNYM_POZIOMIE;
DROP TABLE HISTORIA_TURNIEJU;
DROP TABLE UCZESTNIK_TURNIEJU;
DROP TABLE TURNIEJ ;
DROP TABLE PUNKTY;
DROP TABLE ROZGRYWKI;
DROP TABLE GRA;
DROP TABLE ZNAJOMI;
DROP TABLE KONTO;
DROP TABLE KONTO_DRUZYNOWE;

CREATE TABLE KONTO_DRUZYNOWE (
    id_druzyny NUMBER(6) PRIMARY KEY,
    nazwa VARCHAR(20) NOT NULL
);

CREATE TABLE KONTO (
    id NUMBER(6) PRIMARY KEY,
    region VARCHAR(20) NOT NULL,
    nick VARCHAR(20) NOT NULL,
    haslo VARCHAR(20) NOT NULL,
    email VARCHAR(40) NOT NULL,
    id_druzyny NUMBER(6) REFERENCES KONTO_DRUZYNOWE, --nie jestem pewien ale to sie moze przydac
    nr_konta_bankowego NUMBER(20)
);

CREATE TABLE ZNAJOMI (
    gracz1 NUMBER(6) NOT NULL REFERENCES KONTO,
    gracz2 NUMBER(6) NOT NULL REFERENCES KONTO
);

CREATE TABLE GRA (
    nazwa VARCHAR(20) PRIMARY KEY,
    nazwa_rankingu VARCHAR(20) NOT NULL
);

CREATE TABLE ROZGRYWKI (
    id_rozgrywki NUMBER(8) PRIMARY KEY,
    gracz1 NUMBER(6) NOT NULL REFERENCES KONTO,
    gracz2 NUMBER(6) NOT NULL REFERENCES KONTO,
    gracz3 NUMBER(6) REFERENCES KONTO,
    gracz4 NUMBER(6) REFERENCES KONTO,
    id_zwyciezcy NUMBER(6) REFERENCES KONTO,
    gra VARCHAR(20) NOT NULL REFERENCES GRA,
    przebieg_partii VARCHAR(500)
);

CREATE TABLE PUNKTY (
    id_gracza NUMBER(6) REFERENCES KONTO,
    gra VARCHAR(20) REFERENCES GRA,
    liczba_punktow NUMBER(8)
);

CREATE TABLE TURNIEJ (
    id_tureieju NUMBER(8) PRIMARY KEY,
    nazwa VARCHAR(20) NOT NULL,
    gra VARCHAR(20) NOT NULL REFERENCES GRA,
    data_przeprowadzenia DATE NOT NULL
);

CREATE TABLE UCZESTNIK_TURNIEJU (
    id_gracza NUMBER(6) UNIQUE NOT NULL REFERENCES KONTO,
    id_turenieju NUMBER(8) NOT NULL REFERENCES TURNIEJ,
    punkty_turniejowe NUMBER(8) NOT NULL
);

CREATE TABLE HISTORIA_TURNIEJU (
    id_turnieju NUMBER(8) REFERENCES TURNIEJ,
    gracz1 NUMBER(6) NOT NULL REFERENCES KONTO,
    gracz2 NUMBER(6) NOT NULL REFERENCES KONTO,
    gracz3 NUMBER(6) REFERENCES KONTO,
    gracz4 NUMBER(6) REFERENCES KONTO,
    przebieg_partii VARCHAR(500) NOT NULL
);

CREATE TABLE GRY_NA_AKTUALNYM_POZIOMIE (
    if_turnieju NUMBER(6) REFERENCES TURNIEJ,
    gracz1 NUMBER(6) NOT NULL REFERENCES KONTO,
    gracz2 NUMBER(6) NOT NULL REFERENCES KONTO,
    gracz3 NUMBER(6) REFERENCES KONTO,
    gracz4 NUMBER(6) REFERENCES KONTO,
    wygrany NUMBER(6) NOT NULL REFERENCES KONTO
);
