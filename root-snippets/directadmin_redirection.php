<?php

require_once 'init.php';

$clientId = $_REQUEST['clientId'] ?? null;
$serviceId = $_REQUEST['serviceId'] ?? null;

if (!is_null($clientId) && !is_null($serviceId)) {
    $currentUser = new \WHMCS\Authentication\CurrentUser();
    $client = $currentUser->client();

    if ($client) {
        $service = localAPI('GetClientsProducts', [
            'clientid' => $clientId,
            'serviceid' => $serviceId
        ])['products']['product'][0];

        if ($client->id === $service['clientid']) {
            $username = $service['username'];
            $password = $service['password'];

            echo <<<EOT
            <form
                action="https://cloud30.linknacional.com.br:2222/CMD_LOGIN"
                id="lkn_directadmin_login"
            ><input
                type="hidden"
                name="username"
                value="$username"
                ><input
                type="hidden"
                name="password"
                value="$password"
                ></form>
            <script>
                document.querySelector('#lkn_directadmin_login').submit();
            </script>
            EOT;
        } else {
            // Client does not own the service.
            echo 'Client does not own the service.';
        }
    } else {
        // User is not logged.
        echo 'There is not an authenticated Client.';
    }
} else {
    echo '$_REQUEST is empty.';
}
