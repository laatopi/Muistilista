<?php

class LuokkaController extends BaseController {

    public static function lista() {

        $luokat = luokka::all();

        View::make('luokat.html', array('luokat' => $luokat));
    }

    public static function yksiLuokka($luokka_id) {

        $luokka = luokka::find($luokka_id);

        View::make('yksiluokka.html', array('luokka' => $luokka));
    }

    public static function uusi() {
        View::make('uusiluokka.html');
    }

    public static function varastoi() {
        $params = $_POST;

        $luokka = new luokka(array(
            'nimi' => $params['nimi'],
        ));

        $luokka->tallenna();

        Redirect::to('/luokka/' . $luokka->luokka_id, array('message' => 'Luokka lisätty!'));
    }

}
