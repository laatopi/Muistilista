<?php

class LuokkaController extends BaseController {

    public static function lista() {
        self::check_logged_in();

        $luokat = luokka::all();

        View::make('luokat.html', array('luokat' => $luokat));
    }

    public static function yksiLuokka($luokka_id) {
        self::check_logged_in();

        $luokka = luokka::find($luokka_id);
        $tehtavat = tehtava::findMonta($luokka_id);

        View::make('yksiluokka.html', array('luokka' => $luokka, 'tehtavat' => $tehtavat));
    }

    public static function uusi() {
        self::check_logged_in();
        View::make('uusiluokka.html');
    }

    public static function varastoi() {
        self::check_logged_in();
        $params = $_POST;

        $luokka = new luokka(array(
            'nimi' => $params['nimi'],
        ));

        $errors = $luokka->errors();
        if (count($errors) == 0) {
            $luokka->tallenna();

            Redirect::to('/luokka/' . $luokka->luokka_id, array('message' => 'Luokka lisätty!'));
        } else {
            View::make('uusiluokka.html', array('errors' => $errors));
        }
    }

    public static function poista($luokka_id) {
        self::check_logged_in();
        $luokka = new luokka(array('luokka_id' => $luokka_id));
        $luokka->poista();

        Redirect::to('/luokka', array('message' => 'Luokka on poistettu onnistuneesti!'));
    }

}
