CREATE TABLE Kayttaja(
  kayttaja_id SERIAL PRIMARY KEY,
  tunnus varchar (15) NOT NULL,
  salasana varchar (10) NOT NULL
);

CREATE TABLE Tehtava(
  tehtava_id SERIAL PRIMARY KEY,
  nimi varchar(50) NOT NULL, 
  lisayspaiva DATE, NOT NULL
  tarkeyasaste INTEGER, NOT NULL
  deadline DATE,
  kayttaja_id INTEGER REFERENCES Kayttaja(kayttaja_id)
);

CREATE TABLE Luokka(
  luokka_id SERIAL PRIMARY KEY,
  nimi varchar(50) NOT NULL,
  ylaluokka  NOT NULL
);

CREATE TABLE Player(
  id SERIAL PRIMARY KEY,
  name varchar(50) NOT NULL,
  password varchar(50) NOT NULL
);

CREATE TABLE Player(
  id SERIAL PRIMARY KEY, -- SERIAL tyyppinen pääavain pitää huolen, että tauluun lisätyllä rivillä on aina uniikki pääavain. Kätevää!
  name varchar(50) NOT NULL, -- Muista erottaa sarakkeiden määrittelyt pilkulla!
  password varchar(50) NOT NULL
);