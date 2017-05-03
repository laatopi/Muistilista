<?php

class TehtavaController extends BaseController {
    /* self::check_logged_in() tarkistaa että on kirjauduttu,
     * eli kaikki metodit jotka sisältävät sen vaativat 
     * kirjautumisen. */


    /* Näyttää näkymän kaikista tehtväistä yhtenä listana. */

    public static function lista() {
        self::check_logged_in();
        $tehtavat = tehtava::all();
        View::make('listakaikista.html', array('tehtavat' => $tehtavat));
    }

    /* Luo näkymän yksittäisestä tehtäväsä */

    public static function yksittainen($tehtava_id) {
        self::check_logged_in();
        $tehtava = tehtava::find($tehtava_id);
       
        self::check_logged_oikea_käyttaja($tehtava);
        
        View::make('yksittainen.html', array('tehtava' => $tehtava));
    }

    /* Luo näkymän uuden tehtävän lomakkeesta. */

    public static function uusi() {
        self::check_logged_in();
        $luokat = luokka::all();
        View::make('uusitehtava.html', array('luokat' => $luokat));
    }

    /* Luo näkymän ennaltaolemassa tehtävän muokkauksesta. */

    public static function muokkaa($tehtava_id) {
        self::check_logged_in();
        $tehtava = tehtava::find($tehtava_id);
        
        self::check_logged_oikea_käyttaja($tehtava);

        $luokat = luokka::haeKaikkiLiitokset($tehtava_id);
        View::make('muokkaaminen.html', array('tehtava' => $tehtava, 'luokat' => $luokat));
    }

    /* Päivittää ennaltaolemassa olevan tehävän
     *  tiedot jotka on syötetty muokkauksessa */

    public static function paivita($tehtava_id) {
        self::check_logged_in();
        $params = $_POST;
        $suoritettu = 0;
        if (array_key_exists('suoritettu', $params)) {
            $suoritettu = 1;
        }

        $attributes = array(
            'tehtava_id' => $tehtava_id,
            'suoritettu' => $suoritettu,
            'nimi' => $params['nimi'],
            'tarkeysaste' => $params['tarkeysaste'],
            'deadline' => $params['deadline'],
            'kuvaus' => $params['kuvaus']
        );

        $tehtava = new tehtava($attributes);
        $errors = $tehtava->errors();

        if (count($errors) > 0) {
            View::make('muokkaaminen.html', array('errors' => $errors, 'tehtava' => $attributes, 'luokat' => luokka::haeKaikkiLiitokset($tehtava_id)));
        } else {
            $tehtava->paivita();

            //poistaa vanhat liitokset.
            liitos::poistaTehtavaLiitokset($tehtava_id);

            //jos näkymään on laitettu liitoksia, luodaan ne liitostaulukkoon.
            if (array_key_exists('luokat', $params)) {
                $luokat = $params['luokat'];
                foreach ($luokat as $luokka) {
                    $uusiliitos = new liitos(array(
                        'tehtava_id' => $tehtava->tehtava_id,
                        'luokka_id' => $luokka
                    ));
                    //liitoksen tallentaminen
                    $uusiliitos->tallenna();
                }
            }

            Redirect::to('/tehtava/' . $tehtava->tehtava_id, array('message' => 'Tehtävää muokattu!'));
        }
    }

    /* Poistaa tehtävän tietokannasta. */

    public static function poista($tehtava_id) {
        self::check_logged_in();
        $tehtava = new tehtava(array('tehtava_id' => $tehtava_id));
        $tehtava->poista();
        Redirect::to('/tehtava', array('message' => 'Tehtava on poistettu onnistuneesti!'));
    }

    /* Tallentaa uuden tehtävän tietokantaan. */

    public static function varastoi() {
        self::check_logged_in();
        $params = $_POST;

        $tehtava = new tehtava(array(
            'nimi' => $params['nimi'],
            'tarkeysaste' => $params['tarkeysaste'],
            'deadline' => $params['deadline'],
            'kuvaus' => $params['kuvaus'],
        ));

        //Katsoo rikkovatko syötetyt parametrit mitään validaattoreita.
        $errors = $tehtava->errors();
        if (count($errors) == 0) {
            //Mikäli ei, tallenetaan uusi tehtävä.
            $tehtava->tallenna();

            if (array_key_exists('luokat', $params)) {
                //liitoksien tallentaminen, jos luokkia on lisätty tehtävän luomisessa.
                $luokat = $params['luokat'];
                foreach ($luokat as $luokka) {
                    $uusiliitos = new liitos(array(
                        'tehtava_id' => $tehtava->tehtava_id,
                        'luokka_id' => $luokka
                    ));
                    $uusiliitos->tallenna();
                }
            }
            Redirect::to('/tehtava/' . $tehtava->tehtava_id, array('message' => 'Tehtävä lisätty!'));
        } else {
            //Virheitä sattunut.
            $luokat = luokka::all();
            View::make('uusitehtava.html', array('errors' => $errors, 'ab' => $params, 'luokat' => $luokat));
        }
    }

}
