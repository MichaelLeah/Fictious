<?php 

$app->get('/client', function ($request, $response, $args) {});

$app->get('/client/{id}', function ($request, $response, $args) {});

$app->post('/client/add', function ($request, $response, $args) {});

$app->put('/client/update/{id}', function ($request, $response, $args) {});

$app->delete('/client/delete/{id}', function ($request, $response, $args) {});