<?php

$routes->get('/', function() {
    HelloWorldController::etusivu();
});

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

$routes->get('/login', function() {
    HelloWorldController::login();
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

$routes->get('/tehtava/1/edit', function() {
    HelloWorldController::edit();
});

$routes->post('/tehtava', function() {
    TehtavaController::varastoi();
});

