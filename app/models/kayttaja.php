<?php

class kayttaja extends BaseModel {

    public $kayttaja_id, $tunnus, $salasana;

    public function __construct($ab) {
        parent::__construct($ab);
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

}
