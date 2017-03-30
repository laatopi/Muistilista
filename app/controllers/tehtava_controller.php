<?php

class TehtavaController extends BaseController {

    public static function lista() {

        $tehtavat = tehtava::all();
        

        View::make('listakaikista.html', array('tehtavat' => $tehtavat));
    }

    public static function yksittainen($tehtava_id) {

        $tehtava = tehtava::find($tehtava_id);

        View::make('yksittainen.html', array('tehtava' => $tehtava));
    }

    public static function uusi() {
        $luokat = luokka::all();
        
        View::make('uusitehtava.html', array('luokat' => $luokat));
    }

    public static function varastoi() {
        $params = $_POST;

        $tehtava = new tehtava(array(
            'nimi' => $params['nimi'],
            'tarkeysaste' => $params['tarkeysaste'],
            'deadline' => $params['deadline'],
            'kuvaus' => $params['kuvaus'],
        ));

        $tehtava->tallenna();
        
        $luokat = $params['luokat'];
        foreach ($luokat as $luokka) {
            $uusiliitos = new liitos(array(
                'tehtava_id' => $tehtava->tehtava_id,
                'luokka_id' => $luokka
            ));
            $uusiliitos->tallenna();
        }

        Redirect::to('/tehtava/' . $tehtava->tehtava_id, array('message' => 'Tehtävä lisätty!'));
    }

}
