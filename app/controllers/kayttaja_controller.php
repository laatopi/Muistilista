<?php

class KayttajaController extends BaseController {

    public static function login() {
        View::make('kirjautuminen.html');
    }

    public static function handle_login() {
        $params = $_POST;

        $kayttaja = kayttaja::authenticate($params['tunnus'], $params['salasana']);


        if (!$kayttaja) {
            View::make('kirjautuminen.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'tunnus' => $params['tunnus']));
        } else {

            $_SESSION['kayttaja'] = $kayttaja->kayttaja_id;

            Redirect::to('/', array('message' => 'Tervetuloa takaisin ' . $kayttaja->tunnus . '!'));
        }
    }

    public static function logout() {
        $_SESSION['kayttaja'] = null;
        Redirect::to('/login', array('message' => 'Olet kirjautunut ulos!'));
    }

    public static function uusi() {
        $params = $_POST;

        $kayttaja = new kayttaja(array(
            'tunnus' => $params['tunnus'],
            'salasana' => $params['salasana'],
        ));

        $errors = $kayttaja->errors();
        
        
        if (count($errors) == 0) {
            $kayttaja->tallenna();
            Redirect::to('/', array('message' => 'Kayttaja luotu!'));
        } else {
            View::make('rekisterointi.html', array('errors' => $errors));
        }
    }

    public static function luo() {
        View::make('rekisterointi.html');
    }

}
