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
BEFORE INSERT ON ROZGRYWKI
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

CREATE OR REPLACE PROCEDURE aktualizuj_pkt(grana VARCHAR, zwyciezca NUMBER, DRUGI NUMBER) IS
    pkt_zwyciezcy INT;
    pkt_drugiego INT;
    K INT := 20;
    D INT := 400;
    EW FLOAT;
    EP FLOAT;
BEGIN 
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

COMMIT;