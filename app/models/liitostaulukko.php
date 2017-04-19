<?php

class liitos extends BaseModel {
    /* Apuluokka jolla toteutetaan tietokantojen monen suhde moneen attribuutti.
     * Jos tehtävällä on luokka, niin sillon se kuuluu siihen luokkaan ja vice-versa. */

    // Attribuutit
    public $tehtava_id, $luokka_id;

    // Konstruktori
    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    /* Hakee liitokset tehtavan perusteella. */

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

    /* Hakee liitokset luokan perusteella. */

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
    
    /* Poistaa yksittäiseen tehtävään liittyvät liitokset. */
    
    public static function poistaTehtavaLiitokset($tehtava_id) {
        $query = DB::connection()->prepare('DELETE FROM Liitostaulukko WHERE tehtava_id = :tehtava_id');
        $query->execute(array('tehtava_id' => $tehtava_id));
    }

    /* Tallentaa uuden liitoksen. */

    public function tallenna() {
        $query = DB::connection()->prepare('INSERT INTO Liitostaulukko (tehtava_id, luokka_id) VALUES (:tehtava_id, :luokka_id)');
        $query->execute(array('tehtava_id' => $this->tehtava_id, 'luokka_id' => $this->luokka_id));
        $row = $query->fetch();
    }

}
