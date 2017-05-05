<?php

class luokka extends BaseModel {
    
    //luokka Id on luokan id, kuvaus luokan kuvaus, käyttäjä id kelle käyttäjälle
    // se kuuluu.
    //tehtavaLkm on apumuuttuja joka ei kuulu tietokantaan, vaan näyttää
    // Monta tehtävää yhteen luokkaan kuuluu.
    // Tehtäväliitos on apumuuttuja joka ei kuulu tietokantaan,
    //vaan sitä käytetään näyttämään onko sillä liitos tietyn tehtävän kanssa.

    public $luokka_id, $nimi, $kuvaus, $kayttaja_id, $tehtavaLkm, $tehtavaLiitos;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array_merge(array('validoiNimi'), $this->validators);
    }

    /* Hakee kaikki yksittäisen käyttäjän luokat. */

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
            'kuvaus' => $row['kuvaus'],
            'kayttaja_id' => $row['kayttaja_id'],
            'tehtavaLkm' => luokka::haeLukumaara($row['luokka_id']),
            'tehtavaLiitos' => 0
            ));
        }

        return $luokat;
    }

    /* Hakee yksittäisen luokan. */

    public static function find($luokka_id) {
        $query = DB::connection()->prepare('SELECT * FROM Luokka WHERE luokka_id =:luokka_id LIMIT 1');
        $query->execute(array('luokka_id' => $luokka_id));
        $row = $query->fetch();

        if ($row) {
            $luokka = new luokka(array(
                'luokka_id' => $row['luokka_id'],
                'nimi' => $row['nimi'],
                'kuvaus' => $row['kuvaus'],
                'kayttaja_id' => $row['kayttaja_id'],
            ));

            return $luokka;
        }

        return null;
    }

    /* Tallentaa uuden luokan tietokantaan. */

    public function tallenna() {
        $kayttaja = BaseController::get_user_logged_in();
        $kId = $kayttaja->kayttaja_id;
        $query = DB::connection()->prepare('INSERT INTO Luokka (nimi, kuvaus, kayttaja_id) VALUES (:nimi, :kuvaus, :kayttaja_id) RETURNING luokka_id');
        $query->execute(array('nimi' => $this->nimi, 'kuvaus' => $this->kuvaus, 'kayttaja_id' => $kId));
        $row = $query->fetch();
        $this->luokka_id = $row['luokka_id'];
    }

    /* Poistaa luokan tietokannasta. */

    public function poista() {
        $query = DB::connection()->prepare('DELETE FROM Liitostaulukko WHERE luokka_id = :luokka_id');
        $query->execute(array('luokka_id' => $this->luokka_id));
        $query = DB::connection()->prepare('DELETE FROM Luokka WHERE luokka_id = :luokka_id');
        $query->execute(array('luokka_id' => $this->luokka_id));
    }

    /* Hakee monta tehtävää yhdellä luokalla on. */

    public static function haeLukumaara($luokka_id) {
        $tehtavat = tehtava::findMonta($luokka_id);
        $laskin = 0;

        foreach ($tehtavat as $tehtava) {
            $laskin = $laskin + 1;
        }
        return $laskin;
    }

    public function paivita() {
        $query = DB::connection()->prepare('UPDATE Luokka SET nimi = :nimi, kuvaus = :kuvaus WHERE luokka_id = :luokka_id');
        $query->execute(array('luokka_id' => $this->luokka_id, 'nimi' => $this->nimi, 'kuvaus' => $this->kuvaus));
    }

    /* Validoi että luokan nimen pituus on vähintään 3 kirjainta. */

    public function validoiNimi() {
        $errors = array();

        if (strlen($this->nimi) < 3) {
            $errors[] = 'Nimen pituuden tulee olla vähintään kolme merkkiä pitkä!';
        }
        
        if (strlen($this->nimi) > 30) {
            $errors[] = 'Nimen pituuden tulee olla enintään kolmekymmentä merkkiä pitkä!';
        }
        return $errors;
    }

    /* Hakee kaikki liitokset yhdelle tehtävälle luokkina. */
    public static function haeKaikkiLiitokset($tehtava_id) {
        $luokat = luokka::all();
        $tehtava = tehtava::find($tehtava_id);

        foreach ($luokat as $luokka) {
            foreach ($tehtava->luokat as $liitos) {
                if ($luokka->luokka_id == $liitos->luokka_id) {
                    $luokka->tehtavaLiitos = 1;
                }
            }
        }
        return $luokat;
    }

}
