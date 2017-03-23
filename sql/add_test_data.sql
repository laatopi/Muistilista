-- Lisää INSERT INTO lauseet tähän tiedostoon
INSERT INTO Player (name, password) VALUES ('Henri', 'Henri123');
INSERT INTO Kayttaja (tunnus, salasana) VALUES ('Taneli','salasana');
INSERT INTO Tehtava (nimi, lisayspaiva, tarkeysaste, deadline, kayttaja_id) VALUES ('Pyykkien pesu', '2013-03-27', 3, '2013-03-30', 1);
INSERT INTO Luokka (nimi) VALUES ('Kotityöt');
INSERT INTO Luokka (nimi) VALUES ('Hygienia');

INSERT INTO LuokkaApuTaulukko (tehtava_id, luokka_id) VALUES (1, 1);

INSERT INTO AlaluokkaApuTaulukko(ylaluokka_id, alaluokka_id) VALUES (1, 2);