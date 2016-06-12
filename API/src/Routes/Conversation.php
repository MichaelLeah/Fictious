<?php 

use API\Models\Contact;
use API\Models\Client;
use API\Models\Employee;
use API\Models\Conversation;

/**
 * /conversation/client/{id} Route
 * Returns a list of all conversations for a given client_id from
 * the database, ordered chronologically
 */
$app->get('/conversation/client/{id}', function ($request, $response, $args) {
    /**
     * Validate we have a valid client id
     */
    $client = Client::where('id', $args['id']);
    if (!$client) {
        $failedResponse = $response->withStatus(400);
        $failedResponse->getBody()->write(json_encode([
            'result' => false,
            'error'  => 'Invalid Client ID passed through to end point.'
        ]));

        return $failedResponse->withHeader('Access-Control-Allow-Origin', '*');
    }

    $conversations = 
        Conversation::where('Conversation.client_id', $args['id'])
                    ->join('Contact', 'Conversation.contact_id', '=', 'Contact.id')
                    ->join('Employee', 'Conversation.employee_id', '=', 'Employee.id')
                    ->join('Client', 'Conversation.client_id', '=', 'Client.id')
                    ->select('Conversation.details', 
                             'Employee.name AS employee_name', 
                             'Contact.name AS contact_name',
                             'Client.name AS client_name',
                             'Conversation.created_at AS datetime')
                    ->get();


    if (!$conversations) {
        $failedResponse = $response->withStatus(400);
        $failedResponse->getBody()->write(json_encode([
            'result' => false,
            'error'  => 'Unable to find any conversations for specified client id'
        ]));

        return $failedResponse->withHeader('Access-Control-Allow-Origin', '*');
    }

    $successfulResponse = json_encode([
        'result' => true,
        'conversations' => $conversations
    ]);

    $response->getBody()->write($successfulResponse);
    return $response->withHeader('Access-Control-Allow-Origin', '*'); 
});

/**
 * /conversation/contact/{id} Route
 * Returns a list of conversations for a specific contact
 */
$app->get('/conversation/contact/{id}', function ($request, $response, $args) {
    /**
     * Validate we have a valid client id
     */
    $contact = Contact::where('id', $args['id']);
    if (!$contact) {
        $failedResponse = $response->withStatus(400);
        $failedResponse->getBody()->write(json_encode([
            'result' => false,
            'error'  => 'Invalid Contact ID passed through to end point.'
        ]));

        return $failedResponse->withHeader('Access-Control-Allow-Origin', '*');
    }

    $conversations = 
        Conversation::where('Conversation.contact_id', $args['id'])
                    ->join('Contact', 'Conversation.contact_id', '=', 'Contact.id')
                    ->join('Employee', 'Conversation.employee_id', '=', 'Employee.id')
                    ->join('Client', 'Conversation.client_id', '=', 'Client.id')
                    ->select('Conversation.details', 
                             'Employee.name AS employee_name', 
                             'Contact.name AS contact_name',
                             'Client.name AS client_name',
                             'Conversation.created_at AS datetime')
                    ->get();


    if (!$conversations) {
        $failedResponse = $response->withStatus(400);
        $failedResponse->getBody()->write(json_encode([
            'result' => false,
            'error'  => 'Unable to find any conversations for specified contact id'
        ]));

        return $failedResponse->withHeader('Access-Control-Allow-Origin', '*');
    }

    $successfulResponse = json_encode([
        'result' => true,
        'conversations' => $conversations
    ]);

    $response->getBody()->write($successfulResponse);
    return $response->withHeader('Access-Control-Allow-Origin', '*'); 
});

/**
 * /conversation/add Route
 * Creates a new conversation record within the database.
 */
$app->post('/conversation/add', function ($request, $response, $args) {
    $requiredFields = ['details', 'employee_id', 'contact_id', 'client_id'];
    
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

    $employee = Employee::where('id', $_POST['employee_id']);
    $contact  = Contact::where('id', $_POST['contact_id']);
    $client   = Client::where('id', $_POST['client_id']);

    if (!$employee || !$contact || !$client) {
        $failedResponse = $response->withStatus(400);
        $failedResponse->getBody()->write(json_encode([
            'result' => false,
            'error'  => 'Please ensure all passed over IDs are correct.'
        ]));

        return $failedResponse->withHeader('Access-Control-Allow-Origin', '*');
    }


    /**
     * Create a new client record from the supplied values.
     */
    $conversation = Conversation::create([
        'details'     => $_POST['details'],
        'employee_id' => $_POST['employee_id'],
        'contact_id'  => $_POST['contact_id'],
        'client_id'   => $_POST['client_id']
    ]);

    /**
     * Return a 500 if for some reason Eloquent is unable to create a new database record.
     */
    if (!$conversation) {
        $failedResponse = $response->withStatus(500);
        $failedResponse->getBody()->write(json_encode([
            'result' => false,
            'error'  => 'We are unable to create a new conversation record at this time, please try again later.'
        ]));

        return $failedResponse->withHeader('Access-Control-Allow-Origin', '*');
    }

    $successfulResponse = json_encode([
        'result' => true,
        'conversation' => $conversation
    ]);

    $response->getBody()->write($successfulResponse);
    return $response->withHeader('Access-Control-Allow-Origin', '*');
});