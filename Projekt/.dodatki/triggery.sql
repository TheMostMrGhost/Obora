DROP SEQUENCE idkonto_seq;
DROP SEQUENCE idrozgrywka_seq;
DROP SEQUENCE idturniej_seq;


CREATE SEQUENCE idkonto_seq
START WITH 1
INCREMENT BY 1;

CREATE SEQUENCE idrozgrywka_seq
START WITH 1
INCREMENT BY 1;

CREATE SEQUENCE idturniej_seq
START WITH 1
INCREMENT BY 1;

CREATE OR REPLACE TRIGGER idkonto_trigger
BEFORE INSERT ON KONTO
FOR EACH ROW
BEGIN
  SELECT idkonto_seq.nextval INTO :NEW.id FROM dual;
END;
/

CREATE OR REPLACE TRIGGER idrozgrywka_trigger
BEFORE INSERT ON ROZGRYWKA
FOR EACH ROW
BEGIN
  SELECT idrozgrywka_seq.nextval INTO :NEW.id_rozgrywki FROM dual;
END;
/

CREATE OR REPLACE TRIGGER idturniej_trigger
BEFORE INSERT ON TURNIEJ
FOR EACH ROW
BEGIN
  SELECT idturniej_seq.nextval INTO :NEW.id_turnieju FROM dual;
END;
/

CREATE OR REPLACE TRIGGER poprawny_zwyciezca
BEFORE INSERT OR UPDATE ON ROZGRYWKA
FOR EACH ROW
BEGIN
    IF :NEW.id_zwyciezcy != :NEW.gracz1 AND :NEW.id_zwyciezcy != :NEW.gracz2 AND :NEW.id_zwyciezcy != :NEW.gracz3 AND :NEW.id_zwyciezcy != :NEW.gracz4 THEN
        raise_application_error(-20022,'Zwyciezca musi byc graczem');
    END IF;
END;
/

CREATE OR REPLACE TRIGGER poprawny_zwyciezca_tur
BEFORE INSERT OR UPDATE ON HISTORIA_TURNIEJU
FOR EACH ROW
BEGIN
    IF :NEW.id_zwyciezcy != :NEW.gracz1 AND :NEW.id_zwyciezcy != :NEW.gracz2 AND :NEW.id_zwyciezcy != :NEW.gracz3 AND :NEW.id_zwyciezcy != :NEW.gracz4 THEN
        raise_application_error(-20023,'Zwyciezca musi byc graczem turnieju');
    END IF;
END;
/


CREATE OR REPLACE PROCEDURE aktualizuj_pkt_elo(id_gry NUMBER) IS
    pkt_zwyciezcy INT;
    pkt_drugiego INT;
    K INT := 20;
    D INT := 400;
    EW FLOAT;
    EP FLOAT;
    ta_gra ROZGRYWKA%ROWTYPE;
    grana VARCHAR(20);
    zwyciezca NUMBER;
    drugi NUMBER;
BEGIN 
    SELECT * INTO ta_gra FROM ROZGRYWKA WHERE id_rozgrywki = id_gry;
    grana := ta_gra.gra;
    zwyciezca := ta_gra.id_zwyciezcy; 
    IF zwyciezca = ta_gra.gracz1 THEN
        drugi := ta_gra.gracz2;
    ELSE
        drugi := ta_gra.gracz1;
    END IF;

    SELECT liczba_punktow INTO pkt_zwyciezcy FROM PUNKTY
    WHERE id_gracza = zwyciezca AND gra = grana;
    SELECT liczba_punktow INTO pkt_drugiego FROM PUNKTY
    WHERE id_gracza = drugi AND gra = grana;

    EP := 1 / (1 + POWER(10, (pkt_zwyciezcy - pkt_drugiego) / D));
    EW := 1 / (1 + POWER(10, (pkt_drugiego - pkt_zwyciezcy) / D));
    UPDATE PUNKTY SET liczba_punktow = liczba_punktow + K * (1 - EW) WHERE id_gracza = zwyciezca AND gra = grana;
    UPDATE PUNKTY SET liczba_punktow = liczba_punktow - (K * EP) WHERE id_gracza = drugi AND gra = grana;
END;
/

CREATE OR REPLACE PROCEDURE aktualizuj_pkt_BRIDGE(id_gry NUMBER) IS
    pkt_zwyciezcy INT;
    pkt_drugiego INT;
    przyznane INT;
    ta_gra ROZGRYWKA%ROWTYPE;
    przewaga INT;
    zwyciezca NUMBER;
BEGIN 
    SELECT * INTO ta_gra FROM ROZGRYWKA WHERE id_rozgrywki = id_gry;
    zwyciezca := ta_gra.id_zwyciezcy;
    SELECT pkt_przewagi INTO przewaga FROM ROZGRYWKA WHERE id_rozgrywki = id_gry;
    SELECT min(imp) INTO przyznane FROM PUNKTACJA WHERE max > przewaga;
    UPDATE PUNKTY SET liczba_punktow = liczba_punktow + przyznane WHERE id_gracza = ta_gra.id_zwyciezcy AND gra = ta_gra.gra;
    IF zwyciezca = ta_gra.gracz1 THEN
        UPDATE PUNKTY SET liczba_punktow = liczba_punktow + przyznane WHERE id_gracza = ta_gra.gracz2 AND gra = ta_gra.gra;
        UPDATE PUNKTY SET liczba_punktow = liczba_punktow - przyznane WHERE (id_gracza = ta_gra.gracz3 OR id_gracza = ta_gra.gracz4) AND gra = ta_gra.gra;
    END IF;
    IF zwyciezca = ta_gra.gracz2 THEN
        UPDATE PUNKTY SET liczba_punktow = liczba_punktow + przyznane WHERE id_gracza = ta_gra.gracz1 AND gra = ta_gra.gra;
        UPDATE PUNKTY SET liczba_punktow = liczba_punktow - przyznane WHERE (id_gracza = ta_gra.gracz3 OR id_gracza = ta_gra.gracz4) AND gra = ta_gra.gra;
    END IF;
    IF zwyciezca = ta_gra.gracz3 THEN
        UPDATE PUNKTY SET liczba_punktow = liczba_punktow + przyznane WHERE id_gracza = ta_gra.gracz4 AND gra = ta_gra.gra;
        UPDATE PUNKTY SET liczba_punktow = liczba_punktow - przyznane WHERE (id_gracza = ta_gra.gracz3 OR id_gracza = ta_gra.gracz4) AND gra = ta_gra.gra;
    END IF;
    IF zwyciezca = ta_gra.gracz4 THEN
        UPDATE PUNKTY SET liczba_punktow = liczba_punktow + przyznane WHERE id_gracza = ta_gra.gracz3 AND gra = ta_gra.gra;
        UPDATE PUNKTY SET liczba_punktow = liczba_punktow - przyznane WHERE (id_gracza = ta_gra.gracz3 OR id_gracza = ta_gra.gracz4) AND gra = ta_gra.gra;
    END IF;
END;
/

CREATE OR REPLACE PROCEDURE aktualizuj_pkt(id_gry NUMBER) IS
    sys VARCHAR(20);
    grana VARCHAR(20);
BEGIN 
    select gra INTO grana FROM ROZGRYWKA WHERE id_rozgrywki = id_gry;
    SELECT nazwa_rankingu INTO sys FROM GRA WHERE nazwa = grana;
    IF sys = 'BRYDZOWY' THEN
        aktualizuj_pkt_BRIDGE(id_gry);
    ELSE 
        aktualizuj_pkt_elo(id_gry);
    END IF;
END;
/

COMMIT;