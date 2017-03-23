<?php

class HelloWorldController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        echo 'Tämä on etusivu!';
    }

    public static function sandbox() {
        View::make('muokkaaminen.html');
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
