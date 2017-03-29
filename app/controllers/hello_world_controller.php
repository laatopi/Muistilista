<?php


class HelloWorldController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        echo 'Tämä on etusivu!';
    }

    public static function sandbox() {
        $Taneli = kayttaja::find(1);
        $kayttajat = kayttaja::all();
        $Pyykki = tehtava::find(1);
        $tehtavat = tehtava::all();
        // Kint-luokan dump-metodi tulostaa muuttujan arvon
        Kint::dump($kayttajat);
        Kint::dump($Taneli);
        Kint::dump($Pyykki);
        Kint::dump($tehtavat);
    }

    public static function etusivu() {
        View::make('etusivu.html');
    }

    public static function login() {
        View::make('kirjautuminen.html');
    }

    public static function edit() {
        View::make('muokkaaminen.html');
    }

    public static function single() {
        View::make('yksittainen.html');
    }

    public static function all() {
        View::make('listakaikista.html');
    }

}
