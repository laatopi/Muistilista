CREATE TABLE Kayttaja(
  kayttaja_id SERIAL PRIMARY KEY,
  tunnus varchar (15) NOT NULL,
  salasana varchar (10) NOT NULL
);

CREATE TABLE Tehtava(
  tehtava_id SERIAL PRIMARY KEY,
  nimi varchar(50) NOT NULL, 
  lisayspaiva DATE,
  suoritettu INTEGER DEFAULT 0,
  tarkeysaste INTEGER NOT NULL,
  deadline DATE,
  kuvaus varchar(255),
  kayttaja_id INTEGER REFERENCES Kayttaja(kayttaja_id)
);

CREATE TABLE Luokka(
  luokka_id SERIAL PRIMARY KEY,
  nimi varchar(50) NOT NULL,
  ylaluokka INTEGER REFERENCES Luokka(luokka_id)
);

CREATE TABLE LuokkaApuTaulukko(
  tehtava_id INTEGER REFERENCES Tehtava(tehtava_id) NOT NULL,
  luokka_id INTEGER REFERENCES Luokka(luokka_id) NOT NULL
);

CREATE TABLE AlaluokkaApuTaulukko(
  ylaluokka_id INTEGER REFERENCES Luokka(luokka_id) NOT NULL,  
  alaluokka_id INTEGER REFERENCES Luokka(luokka_id) NOT NULL
);