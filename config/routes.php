<?php

$routes->get('/', function() {
    YleisController::etusivu();
});

$routes->get('/hiekkalaatikko', function() {
    YleisController::etusivu();
});

$routes->get('/login', function() {
    YleisController::login();
});

$routes->get('/tehtava', function() {
    TehtavaController::lista();
});

$routes->get('/tehtava/uusi', function() {
    TehtavaController::uusi();
});


$routes->get('/tehtava/:tehtava_id', function($tehtava_id) {
    TehtavaController::yksittainen($tehtava_id);
});

$routes->get('/tehtava/:tehtava_id/edit', function($tehtava_id) {
    TehtavaController::muokkaa($tehtava_id);
});

$routes->post('/tehtava/:tehtava_id/edit', function($tehtava_id ) {
    TehtavaController::paivita($tehtava_id);
});

$routes->post('/tehtava/:tehtava_id/poista', function($tehtava_id) {
    TehtavaController::poista($tehtava_id);
});

$routes->post('/luokka/:luokka_id/poista', function($luokka_id) {
    LuokkaController::poista($luokka_id);
});

$routes->post('/tehtava', function() {
    TehtavaController::varastoi();
});

$routes->get('/luokka', function() {
    LuokkaController::lista();
});

$routes->get('/luokka/uusi', function() {
    LuokkaController::uusi();
});

$routes->get('/luokka/:luokka_id', function($id) {
    LuokkaController::yksiLuokka($id);
});

$routes->post('/luokka', function() {
    LuokkaController::varastoi();
});

$routes->get('/login', function() {
    KayttajaController::login();
});

$routes->post('/login', function() {
    KayttajaController::handle_login();
});

$routes->post('/logout', function() {
    KayttajaController::logout();
});

$routes->post('/uusitunnus', function() {
    KayttajaController::uusi();
});

$routes->get('/uusi', function() {
    KayttajaController::luo();
});

$routes->get('/luokka/:luokka_id/edit', function($luokka_id ) {
    LuokkaController::muokkaa($luokka_id);
});

$routes->post('/luokka/:luokka_id/edit', function($luokka_id ) {
    LuokkaController::paivita($luokka_id);
});




