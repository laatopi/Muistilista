<?php

class TestiController extends BaseController {
    
    /* Lähinnä jäämä tavaraa sekä debuggaus metodeja. */

    public static function index() {
        echo 'Tämä on etusivu!';
    }

    public static function sandbox() {

        $errors = tehtava::haeLuokkienNimetTehtavalle(19);

        Kint::dump($errors);
    }
    
    /* Luo etusivun näkymän. */
    public static function etusivu() {
        View::make('etusivu.html');
    }
    
    /* Luo kirjautumisen näkymän. */
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
