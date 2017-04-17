<?php

class kayttaja extends BaseModel {

    public $kayttaja_id, $tunnus, $salasana, $vSalasana;

    public function __construct($ab) {
        parent::__construct($ab);
        $this->validators = array_merge(array('kayttajaValidoija'), $this->validators);
    }

    public static function all() {

        $query = DB::connection()->prepare('SELECT * FROM Kayttaja');
        $query->execute();
        $rows = $query->fetchAll();
        $kayttajat = array();

        foreach ($rows as $row) {
            $kayttajat[] = new kayttaja(array(
                'kayttaja_id' => $row['kayttaja_id'],
                'tunnus' => $row['tunnus'],
                'salasana' => $row['salasana']
            ));
        }
        return $kayttajat;
    }

    public static function findwithName($tunnus) {
        $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE tunnus = :tunnus LIMIT 1');
        $query->execute(array('tunnus' => $tunnus));
        $row = $query->fetch();

        if ($row) {
            $kayttaja = new kayttaja(array(
                'kayttaja_id' => $row['kayttaja_id'],
                'tunnus' => $row['tunnus'],
                'salasana' => $row['salasana']
            ));

            return $kayttaja;
        }

        return null;
    }

    public static function find($kayttaja_id) {

        $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE kayttaja_id = :kayttaja_id LIMIT 1');
        $query->execute(array('kayttaja_id' => $kayttaja_id));
        $row = $query->fetch();

        if ($row) {
            $kayttaja = new kayttaja(array(
                'kayttaja_id' => $row['kayttaja_id'],
                'tunnus' => $row['tunnus'],
                'salasana' => $row['salasana']
            ));

            return $kayttaja;
        }

        return null;
    }

    public static function authenticate($tunnus, $salasana) {

        $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE tunnus = :tunnus AND salasana = :salasana LIMIT 1');
        $query->execute(array('tunnus' => $tunnus, 'salasana' => $salasana));
        $row = $query->fetch();
        if ($row) {
            $kayttaja = new kayttaja(array(
                'kayttaja_id' => $row['kayttaja_id'],
                'tunnus' => $row['tunnus'],
                'salasana' => $row['salasana']
            ));
            return $kayttaja;
        } else {
            return null;
        }
    }

    public function tallenna() {
        $query = DB::connection()->prepare('INSERT INTO Kayttaja (tunnus, salasana) VALUES (:tunnus, :salasana) RETURNING kayttaja_id');
        $query->execute(array('tunnus' => $this->tunnus, 'salasana' => $this->salasana));
        $row = $query->fetch();
        $this->kayttaja_id = $row['kayttaja_id'];
    }

    public function kayttajaValidoija() {
        $errors = array();
        $chars = str_split($this->salasana);
        $nmrLkm = 0;
        $kLkm = 0;


        foreach ($chars as $c) {
            if (Is_numeric($c)) {
                $nmrLkm++;
            }
        }

        if (kayttaja::findwithName($this->tunnus) != null) {
            $errors[] = 'Tunnus on jo käytössä, valitse toinen nimi tunnukselle.';
        }

        if (strlen($this->tunnus) < 4) {
            $errors[] = 'Tunnuksen tulee olla vähintään neljä merkkiä pitkä!';
        }

        if ($this->salasana != $this->vSalasana) {
            $errors[] = 'Salasanat eivät täsmää!';
        }

        if ($nmrLkm < 3) {
            $errors[] = 'Salasanassa tulee olla vähintään kolme numeroa!';
        }

        if (strlen($this->salasana) < 6) {
            $errors[] = 'Salasanan pituuden tulee olla vähintään kuusi merkkiä pitkä!';
        }


        return $errors;
    }

}
