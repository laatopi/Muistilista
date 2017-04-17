<?php

class luokka extends BaseModel {

    public $luokka_id, $nimi, $kayttaja_id, $tehtavaLkm;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array_merge(array('validoiNimi'), $this->validators);
    }

    public static function all() {

        $kayttaja = BaseController::get_user_logged_in();
        $kayttaja_id = $kayttaja->kayttaja_id;

        $query = DB::connection()->prepare('SELECT * FROM Luokka WHERE kayttaja_id =:kayttaja_id');
        $query->execute(array('kayttaja_id' => $kayttaja_id));
        $rows = $query->fetchAll();
        $luokat = array();

        foreach ($rows as $row) {
            $luokat[] = new luokka(array(
                'luokka_id' => $row['luokka_id'],
                'nimi' => $row['nimi'],
                'kayttaja_id' => $row['kayttaja_id'],
                'tehtavaLkm' => luokka::haeLukumaara($row['luokka_id'])
            ));
        }

        return $luokat;
    }

    public static function find($luokka_id) {
        $query = DB::connection()->prepare('SELECT * FROM Luokka WHERE luokka_id =:luokka_id LIMIT 1');
        $query->execute(array('luokka_id' => $luokka_id));
        $row = $query->fetch();

        if ($row) {
            $luokka = new luokka(array(
                'luokka_id' => $row['luokka_id'],
                'nimi' => $row['nimi'],
                'kayttaja_id' => $row['kayttaja_id'],
            ));

            return $luokka;
        }

        return null;
    }

    public function tallenna() {
        $kayttaja = BaseController::get_user_logged_in();
        $kId = $kayttaja->kayttaja_id;
        $query = DB::connection()->prepare('INSERT INTO Luokka (nimi, kayttaja_id) VALUES (:nimi, :kayttaja_id) RETURNING luokka_id');
        $query->execute(array('nimi' => $this->nimi, 'kayttaja_id' => $kId));
        $row = $query->fetch();
        $this->luokka_id = $row['luokka_id'];
    }

    public function poista() {
        $query = DB::connection()->prepare('DELETE FROM Liitostaulukko WHERE luokka_id = :luokka_id');
        $query->execute(array('luokka_id' => $this->luokka_id));
        $query = DB::connection()->prepare('DELETE FROM Luokka WHERE luokka_id = :luokka_id');
        $query->execute(array('luokka_id' => $this->luokka_id));
    }

    public static function haeLukumaara($luokka_id) {
        $tehtavat = tehtava::findMonta($luokka_id);
        $laskin = 0;

        foreach ($tehtavat as $tehtava) {
            $laskin = $laskin + 1;
        }
        return $laskin;
    }
    
    public function validoiNimi() {
        $errors = array();
        
        if (strlen($this->nimi) < 3) {
            $errors[] = 'Nimen pituuden tulee olla vähintään kolme merkkiä pitkä!';
        }
        return $errors;
    }

}
