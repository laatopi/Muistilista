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

}
