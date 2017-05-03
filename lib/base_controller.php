<?php

class BaseController {

    public static function get_user_logged_in() {
        if (isset($_SESSION['kayttaja'])) {
            $kayttaja_id = $_SESSION['kayttaja'];
            $kayttaja = kayttaja::find($kayttaja_id);

            return $kayttaja;
        }
        return null;
    }

    public static function check_logged_in() {
        if (!isset($_SESSION['kayttaja'])) {
            Redirect::to('/login', array('message' => 'Kirjaudu ensin sisään!'));
        }
    }

    public static function check_logged_oikea_käyttaja($param) {
        $kayttaja = self::get_user_logged_in();

        if ($param == null) {
            Redirect::to('/', array('message' => 'Ei oikeutta mennä sivulle!'));
        }

        if ($param->kayttaja_id != $kayttaja->kayttaja_id) {
            Redirect::to('/', array('message' => 'Ei oikeutta mennä sivulle!'));
        }
    }

}
