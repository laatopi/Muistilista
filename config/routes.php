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
    HelloWorldController::all();
});

$routes->get('/tehtava/1', function() {
    HelloWorldController::single();
});

$routes->get('/tehtava/1/edit', function() {
    HelloWorldController::edit();
});

