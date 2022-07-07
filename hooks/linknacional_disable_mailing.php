<?php

use WHMCS\Database\Capsule;

defined('WHMCS') or die('This hook should not be run directly');
/*
add_hook('EmailPreSend', 1, function ($vars) {
    // TODO: disparar email de criação de senha e fatura independentemente.
    $clientId = $vars['mergefields']['client_id'];
    $isVerificationEmail = isset($vars['mergefields']['verification_url']);

    $clientVerified = Capsule::table('tblclients')
            ->where('id', $clientId)
            ->value('email_verified');

    if ($clientVerified == 1 || $isVerificationEmail) {
        return [];
    } else {
        return ['abortsend' => true];
    }
});
*/

add_hook('EmailPreSend', 1, function ($vars) {
    // TODO: verificar se o e-mail é válido e tratar (abrir ticket, ...).
    $isVerificationEmail = isset($vars['mergefields']['verification_url']);

    if ($isVerificationEmail) {
        return [];
    } else {
        $disableMailingCustomFieldName = 'Desativar notificações por e-mail';
        $disableMailingCustomFieldNameAsKey = preg_replace('/[^a-z]/', '', strtolower($disableMailingCustomFieldName));
        $disableMailing = $vars['mergefields']['client_custom_field_' . $disableMailingCustomFieldNameAsKey];

        $abortSend = $disableMailing === 'on';

        return $abortSend ? ['abortsend' => true] : [];
    }
});

add_hook('UserEmailVerificationComplete', 1, function ($vars) {
    $clientId = $vars['userId'];
    $disableMailCustomFieldId = 18;

    Capsule::table('tblcustomfieldsvalues')
        ->where('fieldid', $disableMailCustomFieldId)
        ->where('relid', $clientId)
        ->update(['value' => '']);
});
