<?php

class LuokkaController extends BaseController {
    /* self::check_logged_in() tarkistaa että on kirjauduttu,
     * eli kaikki metodit jotka sisältävät sen vaativat 
     * kirjautumisen. */


    /* Luo näkymän kaikista yksittäisen käyttäjän luokista,
      tekemällä listan niistä ja laittamalla ne näkymään. */

    public static function lista() {
        self::check_logged_in();

        $luokat = luokka::all();

        View::make('luokat.html', array('luokat' => $luokat));
    }

    /* Luo näkymän yksittäisestä luokasta ja sen tehtävistä.  
      Hakeee ensin yhden luokan ja sen jälkeen sen kattavat tehtävät. */

    public static function yksiLuokka($luokka_id) {
        self::check_logged_in();

        $luokka = luokka::find($luokka_id);
        $tehtavat = tehtava::findMonta($luokka_id);

        View::make('yksiluokka.html', array('luokka' => $luokka, 'tehtavat' => $tehtavat));
    }

    /* Luo näkymän joka sisältää lomakkeen uuden luokan tekemisestä. */

    public static function uusi() {
        self::check_logged_in();
        View::make('uusiluokka.html');
    }

    /* Tallentaa uuden luokan. Sisältää vain validaattorin joka tarkistaa
      että luokan nimen pituus on tarpeeksi pitkä. */

    public static function varastoi() {
        self::check_logged_in();
        $params = $_POST;

        $luokka = new luokka(array(
            'nimi' => $params['nimi'],
            'kuvaus' => $params['kuvaus']
        ));

        $errors = $luokka->errors();
        if (count($errors) == 0) {
            $luokka->tallenna();

            Redirect::to('/luokka/' . $luokka->luokka_id, array('message' => 'Luokka lisätty!'));
        } else {
            View::make('uusiluokka.html', array('errors' => $errors));
        }
    }

    /* Poistaa luokan tietokannasta. */

    public static function poista($luokka_id) {
        self::check_logged_in();
        $luokka = new luokka(array('luokka_id' => $luokka_id));
        $luokka->poista();

        Redirect::to('/luokka', array('message' => 'Luokka on poistettu onnistuneesti!'));
    }

    public static function paivita($luokka_id) {
        self::check_logged_in();
        $params = $_POST;

        $attributes = array(
            'luokka_id' => $luokka_id,
            'nimi' => $params['nimi'],
            'kuvaus' => $params['kuvaus']
        );

        $luokka = new luokka($attributes);
        $errors = $luokka->errors();

        if (count($errors) > 0) {
            View::make('luokanmuokkaaminen.html', array('errors' => $errors, 'luokka' => $attributes));
        } else {
            $luokka->paivita();

            Redirect::to('/luokka/' . $luokka->luokka_id, array('message' => 'Luokkaa muokattu!'));
        }
    }

    public static function muokkaa($luokka_id) {
        self::check_logged_in();
        $luokka = luokka::find($luokka_id);

        View::make('luokanmuokkaaminen.html', array('luokka' => $luokka));
    }

}
