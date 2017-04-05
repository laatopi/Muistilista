<?php

class tehtava extends BaseModel {

    public $tehtava_id, $nimi, $lisayspaiva, $suoritettu, $tarkeysaste, $deadline, $kuvaus, $kayttaja_id;

    public function __construct($ab) {
        parent::__construct($ab);
        $this->validators = array_merge(array('pvmValidoija'), $this->validators);
    }

    public static function all() {

        $kayttaja = BaseController::get_user_logged_in();
        $kayttaja_id = $kayttaja->kayttaja_id;



        $query = DB::connection()->prepare('SELECT * FROM Tehtava WHERE kayttaja_id =:kayttaja_id');
        $query->execute(array('kayttaja_id' => $kayttaja_id));
        $rows = $query->fetchAll();
        $tehtavat = array();

        foreach ($rows as $row) {
            $tehtavat[] = new tehtava(array(
                'tehtava_id' => $row['tehtava_id'],
                'nimi' => $row['nimi'],
                'lisayspaiva' => $row['lisayspaiva'],
                'suoritettu' => $row['suoritettu'],
                'tarkeysaste' => $row['tarkeysaste'],
                'deadline' => $row['deadline'],
                'kuvaus' => $row['kuvaus'],
                'kayttaja_id' => $row['kayttaja_id']
            ));
        }
        return $tehtavat;
    }

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
                'kuvaus' => $row['kuvaus'],
                'deadline' => $row['deadline'],
                'kayttaja_id' => $row['kayttaja_id']
            ));

            return $tehtava;
        }

        return null;
    }

    public function tallenna() {
        $query = DB::connection()->prepare('INSERT INTO Tehtava (nimi, lisayspaiva, tarkeysaste, deadline, kuvaus, kayttaja_id) VALUES (:nimi, NOW(), :tarkeysaste, :deadline, :kuvaus, 1) RETURNING tehtava_id');
        $query->execute(array('nimi' => $this->nimi, 'tarkeysaste' => $this->tarkeysaste, 'deadline' => $this->deadline, 'kuvaus' => $this->kuvaus));
        $row = $query->fetch();
        $this->tehtava_id = $row['tehtava_id'];
    }

    public function pvmValidoija() {
        $errors = array();
        $d = DateTime::createFromFormat('Y-m-d', $this->deadline);
        if (($d && $d->format('Y-m-d') === $this->deadline) === FALSE) {
            $errors[] = 'Ei ole validi paivamaara, lue malli!';
        }
        return $errors;
    }

    public function poista() {

        $query = DB::connection()->prepare('DELETE FROM Liitostaulukko WHERE tehtava_id = :tehtava_id');
        $query->execute(array('tehtava_id' => $this->tehtava_id));
        $query = DB::connection()->prepare('DELETE FROM Tehtava WHERE tehtava_id = :tehtava_id');
        $query->execute(array('tehtava_id' => $this->tehtava_id));
    }

    public function paivita() {
        $query = DB::connection()->prepare('UPDATE Tehtava
SET nimi = :nimi, suoritettu = :suoritettu, tarkeysaste = :tarkeysaste, deadline = :deadline, kuvaus = :kuvaus
WHERE tehtava_id = :tehtava_id');
        $query->execute(array('tehtava_id' => $this->tehtava_id, 'nimi' => $this->nimi, 'suoritettu' => $this->suoritettu, 'tarkeysaste' => $this->tarkeysaste, 'deadline' => $this->deadline, 'kuvaus' => $this->kuvaus));
    }

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
        
        
        foreach ($liitokset as $liitos){
            $luokat[] = $liitos;
        }
        $nimet = array();
        foreach($luokat as $luokka) {
            $indeksi = luokka::find($liitos->luokka_id);
            $indeksi = $indeksi->nimi;
            $nimet[] = $indeksi;
        }
        
        return $luokat;
    }

}
