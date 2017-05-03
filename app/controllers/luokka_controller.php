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

        self::check_logged_oikea_käyttaja($luokka);

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

        //katsotaan onko luodussa luokassa ristiriitoja validaattorin kanssa.
        $errors = $luokka->errors();
        if (count($errors) == 0) {
            //jos ei siirrytään luokkien näkymään ja tallenetaan luokka tietokantaan.
            $luokka->tallenna();
            Redirect::to('/luokka', array('message' => 'Luokka lisätty!'));
        } else {
            //muuten näytetään virheet samassa näkymässä.
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

    /* Luokan muokkaamisen metodi joka tallentaa muokatut tiedot tietokantaan. */

    public static function paivita($luokka_id) {
        self::check_logged_in();
        $params = $_POST;

        $attributes = array(
            'luokka_id' => $luokka_id,
            'nimi' => $params['nimi'],
            'kuvaus' => $params['kuvaus']
        );

        //katsoo onko luokassa mitään validaattori virheitä.
        $luokka = new luokka($attributes);
        $errors = $luokka->errors();

        if (count($errors) > 0) {
            //jos on, niin palataan takaisin luokanmuokkaamis näkymään.
            View::make('luokanmuokkaaminen.html', array('errors' => $errors, 'luokka' => $attributes));
        } else {
            //jos ei, päivitetään tiedot tietokantaan.
            $luokka->paivita();
            Redirect::to('/luokka/' . $luokka->luokka_id, array('message' => 'Luokkaa muokattu!'));
        }
    }

    /* Luo näkymän tehtävän muokkaamiselle. */

    public static function muokkaa($luokka_id) {
        self::check_logged_in();
        $luokka = luokka::find($luokka_id);
        
        self::check_logged_oikea_käyttaja($luokka);

        View::make('luokanmuokkaaminen.html', array('luokka' => $luokka));
    }

}
