<?php 

use API\Models\Client;

/**
 * /client Route
 * Returns a list of all clients from the database.
 */
$app->get('/client', function ($request, $response, $args) {
    $clientList = Client::all();

    if (!$clientList) {
        $failedResponse = $response->withStatus(500);
        $failedResponse->getBody()->write(json_encode([
            'result' => false,
            'error'  => 'We were unable to load all clients at this time, please try again later.'
        ]));

        return $failedResponse->withHeader('Access-Control-Allow-Origin', '*');
    }

    $successfulResponse = json_encode([
        'result'     => true,
        'clientList' => $clientList
    ]);

    $response->getBody()->write($successfulResponse);
    return $response->withHeader('Access-Control-Allow-Origin', '*');
});

/**
 * /client/{id} Route
 * Returns a specific client based on the passed in client ID.
 */
$app->get('/client/{id}', function ($request, $response, $args) {
    $clientId = $args['id'];

    /**
     * If the end user has not passed over a valid integer then there 
     * is no point querying the database, so we'll just error early.
     */
    if (!is_numeric($clientId) || !$clientId > 0) {
        $failedResponse = $response->withStatus(400);
        $failedResponse->getBody()->write(json_encode([
            'result' => false,
            'error'  => 'Please ensure that the ID field is a valid integer value.'
        ]));

        return $failedResponse->withHeader('Access-Control-Allow-Origin', '*');
    }
    
    $client = Client::where('id', $clientId)->first();

    /**
     * If we do not get a valid client back from our query we want to 
     * alert the end user to the issue, and return a 400 Bad Request.
     */
    if (!$client) {
        $failedResponse = $response->withStatus(400);
        $failedResponse->getBody()->write(json_encode([
            'result' => false,
            'error'  => 'We could not find a client for the specified ID.'
        ]));

        return $failedResponse->withHeader('Access-Control-Allow-Origin', '*');
    }

    $successfulResponse = json_encode([
        'result' => true,
        'client' => $client
    ]);

    $response->getBody()->write($successfulResponse);
    return $response->withHeader('Access-Control-Allow-Origin', '*');
});

/**
 * /client/add Route
 * Creates a new client record based on the posted across values.
 * Expected Post Fields:
 * @param String name
 * @param String address
 * @param integer number
 * @param String email
 */
$app->post('/client/add', function ($request, $response, $args) {
    $requiredFields = ['name', 'address', 'number', 'email'];
    
    /**
     * We want to make sure we have all the valid fields necessary
     * before we continue with adding a new client record.
     */
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $failedResponse = $response->withStatus(400);
            $failedResponse->getBody()->write(json_encode([
                'result' => false,
                'error'  => $field . ' is a required post parameter, please ensure it is passed across to the request.'
            ]));

            return $failedResponse->withHeader('Access-Control-Allow-Origin', '*');
        }
    }

    /**
     * Validate the end user's supplied email address.
     */
    if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
        $failedResponse = $response->withStatus(400);
        $failedResponse->getBody()->write(json_encode([
            'result' => false,
            'error'  => 'Please ensure that you have supplied a valid email address for the request.'
        ]));

        return $failedResponse->withHeader('Access-Control-Allow-Origin', '*');
    }

    /**
     * Create a new client record from the supplied values.
     */
    $client = Client::create([
        'name'    => $_POST['name'],
        'address' => $_POST['address'],
        'number'  => $_POST['number'],
        'email'   => $_POST['email']
    ]);

    /**
     * Return a 500 if for some reason Eloquent is unable to create a new database record.
     */
    if (!$client) {
        $failedResponse = $response->withStatus(500);
        $failedResponse->getBody()->write(json_encode([
            'result' => false,
            'error'  => 'We are unable to create a new client record at this time, please try again later.'
        ]));

        return $failedResponse->withHeader('Access-Control-Allow-Origin', '*');
    }

    $successfulResponse = json_encode([
        'result' => true,
        'client' => $client
    ]);

    $response->getBody()->write($successfulResponse);
    return $response->withHeader('Access-Control-Allow-Origin', '*');
});

/**
 * /client/update/{id} Route
 * Updates a client model dependent on the specified ID passed across.
 */
$app->post('/client/update/{id}', function ($request, $response, $args) {
    $clientId = $args['id'];

    /**
     * If the end user has not passed over a valid integer then there 
     * is no point querying the database, so we'll just error early.
     */
    if (!is_numeric($clientId) || !$clientId > 0) {
        $failedResponse = $response->withStatus(400);
        $failedResponse->getBody()->write(json_encode([
            'result' => false,
            'error'  => 'Please ensure that the ID field is a valid integer value.'
        ]));

        return $failedResponse->withHeader('Access-Control-Allow-Origin', '*');
    }
    
    /**
     * Ensure that the a client record for this ID exists in our records.
     */
    $client = Client::where('id', $clientId)->first();
    if (!$client) {
        $failedResponse = $response->withStatus(400);
        $failedResponse->getBody()->write(json_encode([
            'result' => false,
            'error'  => 'We could not find a client for the specified ID.'
        ]));

        return $failedResponse->withHeader('Access-Control-Allow-Origin', '*');
    }

    /**
     * Build up the update array from the passed in POST parameters
     * @todo : validation on values.
     */
    $updateDetails   = [];
    $optionalUpdates = ['name', 'address', 'number', 'email'];
    foreach ($optionalUpdates as $update) {
        if (!empty($_POST[$update])) {
            $updateDetails[$update] = $_POST[$update];
        }
    }

    if (empty($updateDetails)) {
        $failedResponse = $response->withStatus(400);
        $failedResponse->getBody()->write(json_encode([
            'result' => false,
            'error'  => 'No valid update fields passed through to the request.'
        ]));

        return $failedResponse->withHeader('Access-Control-Allow-Origin', '*');
    }

    /**
     * Attempt to update the active client model
     */
    $clientUpdated = $client->update($updateDetails);
    if (!$clientUpdated) {
        $failedResponse = $response->withStatus(500);
        $failedResponse->getBody()->write(json_encode([
            'result' => false,
            'error'  => 'We could not update that client at this time, please try again later.'
        ]));

        return $failedResponse->withHeader('Access-Control-Allow-Origin', '*');
    }

    $successfulResponse = json_encode([
        'result' => true,
        'client' => $client
    ]);

    $response->getBody()->write($successfulResponse);
    return $response->withHeader('Access-Control-Allow-Origin', '*');
});

/**
 * /client/delete/{id} Route
 * Delete a client record, utilises Eloquent's SoftDeletes
 */
$app->delete('/client/delete/{id}', function ($request, $response, $args) {
    $clientId = $args['id'];

    /**
     * If the end user has not passed over a valid integer then there 
     * is no point querying the database, so we'll just error early.
     */
    if (!is_numeric($clientId) || !$clientId > 0) {
        $failedResponse = $response->withStatus(400);
        $failedResponse->getBody()->write(json_encode([
            'result' => false,
            'error'  => 'Please ensure that the ID field is a valid integer value.'
        ]));

        return $failedResponse->withHeader('Access-Control-Allow-Origin', '*');
    }
    
    /**
     * Ensure that the a client record for this ID exists in our records.
     */
    $client = Client::where('id', $clientId)->first();
    if (!$client) {
        $failedResponse = $response->withStatus(400);
        $failedResponse->getBody()->write(json_encode([
            'result' => false,
            'error'  => 'We could not find a client for the specified ID.'
        ]));

        return $failedResponse->withHeader('Access-Control-Allow-Origin', '*');
    }

    $clientDeleted = $client->delete();
    if (!$clientDeleted) {
        $failedResponse = $response->withStatus(500);
        $failedResponse->getBody()->write(json_encode([
            'result' => false,
            'error'  => 'We could not delete that client at this time, please try again later'
        ]));

        return $failedResponse->withHeader('Access-Control-Allow-Origin', '*');
    }

    $successfulResponse = json_encode([
        'result' => true,
    ]);

    $response->getBody()->write($successfulResponse);
    return $response->withHeader('Access-Control-Allow-Origin', '*');
});