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

    public static function muokkaa($tehtava_id) {
        $tehtava = tehtava::find($tehtava_id);

        View::make('muokkaaminen.html', array('tehtava' => $tehtava));
    }

    public static function paivita($tehtava_id) {
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
            View::make('muokkaaminen.html', array('errors' => $errors, 'attributes' => $attributes));
        } else {
            $tehtava->paivita();

            Redirect::to('/tehtava/' . $tehtava->tehtava_id, array('message' => 'Tehtävä lisätty!'));
        }
    }

    public static function poista($tehtava_id) {
        $tehtava = new tehtava(array('tehtava_id' => $tehtava_id));
        $tehtava->poista();

        Redirect::to('/tehtava', array('message' => 'Tehtava on poistettu onnistuneesti!'));
    }

    public static function varastoi() {
        $params = $_POST;

        $tehtava = new tehtava(array(
            'nimi' => $params['nimi'],
            'tarkeysaste' => $params['tarkeysaste'],
            'deadline' => $params['deadline'],
            'kuvaus' => $params['kuvaus'],
        ));

        $errors = $tehtava->errors();

        if (count($errors) == 0) {


            $tehtava->tallenna();

            if (array_key_exists('luokat', $params)) {
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
            $luokat = luokka::all();
            View::make('uusitehtava.html', array('errors' => $errors, 'ab' => $params, 'luokat' => $luokat));
        }
    }

}
