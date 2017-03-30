-- Lisää INSERT INTO lauseet tähän tiedostoon
INSERT INTO Kayttaja (tunnus, salasana) VALUES ('Taneli','salasana');
INSERT INTO Tehtava (nimi, lisayspaiva, tarkeysaste, deadline, kuvaus, kayttaja_id) VALUES ('Pyykkien pesu', NOW(), 3, '2013-03-30', 'Pese kaikki pyykit! -Valkopyykit, lakanat, pyyhkeet sekä villapaidat!', 1);
INSERT INTO Tehtava (nimi, lisayspaiva, suoritettu, tarkeysaste, deadline, kuvaus, kayttaja_id) VALUES ('Hampaiden harjaus', NOW(), 1 , 5, '2013-01-30', 'Harjaa hampaat koska ne on semi mädät', 1);
INSERT INTO Luokka (nimi, kayttaja_id) VALUES ('Kotityöt', 1);
INSERT INTO Luokka (nimi, kayttaja_id) VALUES ('Hygienia', 1);

INSERT INTO Liitostaulukko (tehtava_id, luokka_id) VALUES (1, 1);
