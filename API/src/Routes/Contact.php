<?php 

use API\Models\Contact;
use API\Models\Client;

/**
 * /contact Route
 * Returns a list of all contacts from the database.
 */
$app->get('/contact', function ($request, $response, $args) {
     $contactList = Contact::join('Client', 'Contact.client_id', '=', 'Client.id')
                        ->select('Contact.*', 'Client.name AS client_name')
                        ->get();

    if (!$contactList) {
        $failedResponse = $response->withStatus(500);
        $failedResponse->getBody()->write(json_encode([
            'result' => false,
            'error'  => 'We were unable to load all contacts at this time, please try again later.'
        ]));

        return $failedResponse->withHeader('Access-Control-Allow-Origin', '*');
    }

    $successfulResponse = json_encode([
        'result'     => true,
        'contactList' => $contactList
    ]);

    $response->getBody()->write($successfulResponse);
    return $response->withHeader('Access-Control-Allow-Origin', '*');
});

/**
 * /contact/{id} Route
 * Returns a specific contact filtered by their ID.
 */
$app->get('/contact/{id}', function ($request, $response, $args) {
    $contactId = $args['id'];

    /**
     * If the end user has not passed over a valid integer then there 
     * is no point querying the database, so we'll just error early.
     */
    if (!is_numeric($contactId) || !$contactId > 0) {
        $failedResponse = $response->withStatus(400);
        $failedResponse->getBody()->write(json_encode([
            'result' => false,
            'error'  => 'Please ensure that the ID field is a valid integer value.'
        ]));

        return $failedResponse->withHeader('Access-Control-Allow-Origin', '*');
    }
    
    $contact = Contact::where('Contact.id', $contactId)
                        ->join('Client', 'Contact.client_id', '=', 'Client.id')
                        ->select('Contact.*', 'Client.name AS client_name')
                        ->first();

    /**
     * If we do not get a valid contact back from our query we want to 
     * alert the end user to the issue, and return a 400 Bad Request.
     */
    if (!$contact) {
        $failedResponse = $response->withStatus(400);
        $failedResponse->getBody()->write(json_encode([
            'result' => false,
            'error'  => 'We could not find a contact for the specified ID.'
        ]));

        return $failedResponse->withHeader('Access-Control-Allow-Origin', '*');
    }

    $successfulResponse = json_encode([
        'result' => true,
        'contact' => $contact
    ]);

    $response->getBody()->write($successfulResponse);
    return $response->withHeader('Access-Control-Allow-Origin', '*');
});

/**
 * /contact/add Route
 * Creates a new contact record based on the posted across values.
 * Expected Post Fields:
 * @param String name
 * @param String job_role
 * @param integer number
 * @param String email
 * @param integer client_id
 */
$app->post('/contact/add', function ($request, $response, $args) {
    $requiredFields = ['name', 'job_role', 'number', 'email', 'client_id'];
    
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
     * Ensure a valid client id has been passed and it matches a record in our database.
     */
    $client = Client::where('id', $_POST['client_id']);
    if (!$client) {
        $failedResponse = $response->withStatus(400);
        $failedResponse->getBody()->write(json_encode([
            'result' => false,
            'error'  => 'We could not find the Client record associated to the passed client id.'
        ]));

        return $failedResponse->withHeader('Access-Control-Allow-Origin', '*');
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
    $contact = Contact::create([
        'name'      => $_POST['name'],
        'job_role'  => $_POST['job_role'],
        'number'    => $_POST['number'],
        'email'     => $_POST['email'],
        'client_id' => $_POST['client_id']
    ]);

    /**
     * Return a 500 if for some reason Eloquent is unable to create a new database record.
     */
    if (!$contact) {
        $failedResponse = $response->withStatus(500);
        $failedResponse->getBody()->write(json_encode([
            'result' => false,
            'error'  => 'We are unable to create a new contact record at this time, please try again later.'
        ]));

        return $failedResponse->withHeader('Access-Control-Allow-Origin', '*');
    }

    $successfulResponse = json_encode([
        'result' => true,
        'contact' => $contact
    ]);

    $response->getBody()->write($successfulResponse);
    return $response->withHeader('Access-Control-Allow-Origin', '*');
});

/**
 * /contact/update/{id} Route
 * Updates a specified contact with the posted across values.
 */
$app->post('/contact/update/{id}', function ($request, $response, $args) {
    $contactId = $args['id'];

    /**
     * If the end user has not passed over a valid integer then there 
     * is no point querying the database, so we'll just error early.
     */
    if (!is_numeric($contactId) || !$contactId > 0) {
        $failedResponse = $response->withStatus(400);
        $failedResponse->getBody()->write(json_encode([
            'result' => false,
            'error'  => 'Please ensure that the ID field is a valid integer value.'
        ]));

        return $failedResponse->withHeader('Access-Control-Allow-Origin', '*');
    }
    
    /**
     * Ensure that the a contact record for this ID exists in our records.
     */
    $contact = Contact::where('Contact.id', $contactId)
                        ->join('Client', 'Contact.client_id', '=', 'Client.id')
                        ->select('Contact.*', 'Client.name AS client_name')
                        ->first();

    if (!$contact) {
        $failedResponse = $response->withStatus(400);
        $failedResponse->getBody()->write(json_encode([
            'result' => false,
            'error'  => 'We could not find a contact for the specified ID.'
        ]));

        return $failedResponse->withHeader('Access-Control-Allow-Origin', '*');
    }

    /**
     * Build up the update array from the passed in POST parameters
     * @todo : validation on values.
     */
    $updateDetails   = [];
    $optionalUpdates = ['name', 'job_role', 'number', 'email', 'client_id'];
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
    $contactUpdated = $contact->update($updateDetails);
    if (!$contactUpdated) {
        $failedResponse = $response->withStatus(500);
        $failedResponse->getBody()->write(json_encode([
            'result' => false,
            'error'  => 'We could not update that contact at this time, please try again later.'
        ]));

        return $failedResponse->withHeader('Access-Control-Allow-Origin', '*');
    }

    $successfulResponse = json_encode([
        'result' => true,
        'contact' => $contact
    ]);

    $response->getBody()->write($successfulResponse);
    return $response->withHeader('Access-Control-Allow-Origin', '*');
});

/**
 * /contact/delete/{id} Route
 * Delete a specified contact based on the passed over ID.
 */
$app->delete('/contact/delete/{id}', function ($request, $response, $args) {
    $contactId = $args['id'];

    /**
     * If the end user has not passed over a valid integer then there 
     * is no point querying the database, so we'll just error early.
     */
    if (!is_numeric($contactId) || !$contactId > 0) {
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
    $contact = Contact::where('id', $contactId)->first();
    if (!$contact) {
        $failedResponse = $response->withStatus(400);
        $failedResponse->getBody()->write(json_encode([
            'result' => false,
            'error'  => 'We could not find a contact for the specified ID.'
        ]));

        return $failedResponse->withHeader('Access-Control-Allow-Origin', '*');
    }

    $contactDeleted = $contact->delete();
    if (!$contactDeleted) {
        $failedResponse = $response->withStatus(500);
        $failedResponse->getBody()->write(json_encode([
            'result' => false,
            'error'  => 'We could not delete that contact at this time, please try again later'
        ]));

        return $failedResponse->withHeader('Access-Control-Allow-Origin', '*');
    }

    $successfulResponse = json_encode([
        'result' => true,
    ]);

    $response->getBody()->write($successfulResponse);
    return $response->withHeader('Access-Control-Allow-Origin', '*');
});