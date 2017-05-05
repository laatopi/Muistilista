<?php

class tehtava extends BaseModel {
    /* Muuttujien pitäisi olla nimensämukaisesti itseselkoisia. Luokat ei ole
     * varsinainen tietokantaan liittyvä muuttuja, vaan apumuuttuja jolla haetaan luokat
     * johon tehtävä kuuluu. */

    public $tehtava_id, $nimi, $lisayspaiva, $suoritettu, $tarkeysaste, $deadline, $kuvaus, $kayttaja_id, $luokat;

    public function __construct($ab) {
        parent::__construct($ab);
        $this->validators = array_merge(array('pvmValidoija', 'validoiNimi'), $this->validators);
    }

    /* Palauttaa kaikki tehtävät tietokannasta tietylle käyttäjätunnukselle. */

    public static function all() {

        $kayttaja = BaseController::get_user_logged_in();
        $kayttaja_id = $kayttaja->kayttaja_id;

        $query = DB::connection()->prepare('SELECT * FROM Tehtava WHERE kayttaja_id =:kayttaja_id');
        $query->execute(array('kayttaja_id' => $kayttaja_id));
        $rows = $query->fetchAll();
        $tehtavat = array();

        foreach ($rows as $row) {
            $tehtava = new tehtava(array(
                'tehtava_id' => $row['tehtava_id'],
                'nimi' => $row['nimi'],
                'lisayspaiva' => $row['lisayspaiva'],
                'suoritettu' => $row['suoritettu'],
                'tarkeysaste' => $row['tarkeysaste'],
                'deadline' => $row['deadline'],
                'kuvaus' => $row['kuvaus'],
                'kayttaja_id' => $row['kayttaja_id']
            ));
            $luokat = array();

            //Hakee myös luokat muuttujaan kaikki luokat johon tehtävä kuuluu.
            $liitokset = liitos::findAllWithTehtavaId($tehtava->tehtava_id);
            foreach ($liitokset as $liitos) {
                $luokka = luokka::find($liitos->luokka_id);
                $luokat[] = $luokka;
            }
            $tehtava->luokat = $luokat;
            $tehtavat[] = $tehtava;
        }
        return $tehtavat;
    }

    /* Palauttaa yksittäisen tehtävän tietokannasta. */

    public static function find($tehtava_id) {

        $query = DB::connection()->prepare('SELECT * FROM Tehtava WHERE tehtava_id = :tehtava_id LIMIT 1');
        $query->execute(array('tehtava_id' => $tehtava_id));
        $row = $query->fetch();

        if ($row) {
            $tehtava = new tehtava(array(
                'tehtava_id' => $row['tehtava_id'],
                'nimi' => $row['nimi'],
                'lisayspaiva' => $row['lisayspaiva'],
                'suoritettu' => $row['suoritettu'],
                'tarkeysaste' => $row['tarkeysaste'],
                'deadline' => $row['deadline'],
                'kuvaus' => $row['kuvaus'],
                'kayttaja_id' => $row['kayttaja_id']
            ));

            //Hakee myös luokat muuttujaan kaikki luokat johon tehtävä kuuluu.
            $luokat = array();
            $liitokset = liitos::findAllWithTehtavaId($tehtava->tehtava_id);
            foreach ($liitokset as $liitos) {
                $luokka = luokka::find($liitos->luokka_id);
                $luokat[] = $luokka;
            }
            $tehtava->luokat = $luokat;

            return $tehtava;
        }

        return null;
    }

    /* Palauttaa yksittäisen luokan kaikki tehtävät.  */

    public static function findMonta($luokka_id) {
        $tehtavat = array();
        $liitokset = liitos::findAllWithLuokkaId($luokka_id);
        foreach ($liitokset as $liitos) {
            $tehtavat[] = new tehtava(tehtava::find($liitos->tehtava_id));
        }
        return $tehtavat;
    }

    /* Tallentaa uuden tehtävän tietokantaan. */

    public function tallenna() {
        $kayttaja = BaseController::get_user_logged_in();
        $kId = $kayttaja->kayttaja_id;
        $query = DB::connection()->prepare('INSERT INTO Tehtava (nimi, lisayspaiva, tarkeysaste, deadline, kuvaus, kayttaja_id) VALUES (:nimi, NOW(), :tarkeysaste, :deadline, :kuvaus, :kayttaja_id) RETURNING tehtava_id');
        $query->execute(array('nimi' => $this->nimi, 'tarkeysaste' => $this->tarkeysaste, 'deadline' => $this->deadline, 'kuvaus' => $this->kuvaus, 'kayttaja_id' => $kId));
        $row = $query->fetch();
        $this->tehtava_id = $row['tehtava_id'];
    }

    /* Validoi että päivämäärä on oikeassa muodossa.  */

    public function pvmValidoija() {
        $errors = array();
        $d = DateTime::createFromFormat('Y-m-d', $this->deadline);
        if (($d && $d->format('Y-m-d') === $this->deadline) === FALSE) {
            $errors[] = 'Ei ole validi päivämäärä, lue malli!';
        }
        return $errors;
    }

    /* Validoi tehtävän nimen.  */

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

    /* Poistaa tehtävän tietokannasta. */

    public function poista() {
        $query = DB::connection()->prepare('DELETE FROM Liitostaulukko WHERE tehtava_id = :tehtava_id');
        $query->execute(array('tehtava_id' => $this->tehtava_id));
        $query = DB::connection()->prepare('DELETE FROM Tehtava WHERE tehtava_id = :tehtava_id');
        $query->execute(array('tehtava_id' => $this->tehtava_id));
    }

    /* Päivittää yhden tehtävän tiedot tietokantaan muokkaamisen jälkeen. */

    public function paivita() {
        $query = DB::connection()->prepare('UPDATE Tehtava
SET nimi = :nimi, suoritettu = :suoritettu, tarkeysaste = :tarkeysaste, deadline = :deadline, kuvaus = :kuvaus
WHERE tehtava_id = :tehtava_id');
        $query->execute(array('tehtava_id' => $this->tehtava_id, 'nimi' => $this->nimi, 'suoritettu' => $this->suoritettu, 'tarkeysaste' => $this->tarkeysaste, 'deadline' => $this->deadline, 'kuvaus' => $this->kuvaus));
    }

    /* Hakee luokkien nimet yhdelle tehtävälle. Testimetodi jota ei käytetä koodissa. */

    public static function haeLuokkienNimetTehtavalle($tehtava_id) {
        $query = DB::connection()->prepare('SELECT * FROM Liitostaulukko WHERE tehtava_id =:tehtava_id');
        $query->execute(array('tehtava_id' => $tehtava_id));
        $rows = $query->fetchAll();
        $luokat = array();

        foreach ($rows as $row) {
            $liitokset[] = new liitos(array(
                'tehtava_id' => $row['tehtava_id'],
                'luokka_id' => $row['luokka_id']
            ));
        }


        foreach ($liitokset as $liitos) {
            $luokat[] = $liitos;
        }
        $nimet = array();
        foreach ($luokat as $luokka) {
            $indeksi = luokka::find($liitos->luokka_id);
            $indeksi = $indeksi->nimi;
            $nimet[] = $indeksi;
        }

        return $luokat;
    }

}
