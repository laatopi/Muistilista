<?php

class KayttajaController extends BaseController {
    
    /* Luo kirjautumisen vaativan näkymän. */
    public static function login() {
        View::make('kirjautuminen.html');
    }

    /* Käsittelee kirjautumisen, $_POST sisältää käyttäjätunnuksen
     * sekä salasanan. Jos täsmäävät johonkin tietokannassa olevaan tunnukseen luo session,
     * muuten pyytää uudeestaan. */
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
    
    /* Lopettaa nykyisen session ja uudelleenohjaa etusivulle.. */
    public static function logout() {
        $_SESSION['kayttaja'] = null;
        Redirect::to('/login', array('message' => 'Olet kirjautunut ulos!'));
    }
    
    
    /* Luo uuden käyttäjätunnuksen. Tarkistaa että tunnus on tarpeeksi pitkä,
     * että salasanassa on tarpeeksi kirjaimia ja on tarpeeksi pitkä, sekä
     * myös että salasanan vahvistus täsmää.
     * Mikäli ei ongelmia, luo käyttäjän. */
    public static function uusi() {
        $params = $_POST;

        $kayttaja = new kayttaja(array(
            'tunnus' => $params['tunnus'],
            'salasana' => $params['salasana'],
            'vsalasana'=> $params['vsalasana']
        ));

        $errors = $kayttaja->errors();
        
        
        if (count($errors) == 0) {
            $kayttaja->tallenna();
            Redirect::to('/', array('message' => 'Kayttaja luotu!'));
        } else {
            View::make('rekisterointi.html', array('errors' => $errors));
        }
    }
    
    /* Luo rekisteröinti näkymän. */
    public static function luo() {
        View::make('rekisterointi.html');
    }

}
