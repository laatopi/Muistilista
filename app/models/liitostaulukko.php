<?php

class liitos extends BaseModel {

    // Attribuutit
    public $tehtava_id, $luokka_id;

    // Konstruktori
    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function findAllWithTehtavaId($tehtava_id) {
        $query = DB::connection()->prepare('SELECT * FROM Liitostaulukko WHERE tehtava_id =:tehtava_id');
        $query->execute(array('tehtava_id' => $tehtava_id));
        $rows = $query->fetchAll();
        $liitokset = array();

        foreach ($rows as $row) {
            $liitokset[] = new Liitos(array(
                'tehtava_id' => $row['tehtava_id'],
                'luokka_id' => $row['luokka_id'],
            ));
        }

        return $liitokset;
    }

    public static function findAllWithLuokkaId($luokka_id) {
        $query = DB::connection()->prepare('SELECT * FROM Liitostaulukko WHERE luokka_id =:luokka_id');
        $query->execute(array('luokka_id' => $luokka_id));
        $rows = $query->fetchAll();
        $liitokset = array();

        foreach ($rows as $row) {
            $liitokset[] = new Liitos(array(
                'tehtava_id' => $row['tehtava_id'],
                'luokka_id' => $row['luokka_id'],
            ));
        }

        return $liitokset;
    }

    public function tallenna() {
        $query = DB::connection()->prepare('INSERT INTO Liitostaulukko (tehtava_id, luokka_id) VALUES (:tehtava_id, :luokka_id)');
        $query->execute(array('tehtava_id' => $this->tehtava_id, 'luokka_id' => $this->luokka_id));
        $row = $query->fetch();
    }

}
