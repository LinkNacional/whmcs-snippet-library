<?php

// use WHMCS\Database\Capsule;

defined('WHMCS') or die('This hook should not be run directly');

add_hook('EmailPreSend', 1, function ($vars) {
    // Modo de baixa perfomance:
    // $disableMailCustomFieldId = 18;
    // $clientId = $vars['mergefields']['client_id'];
    // $dbWay = Capsule::table('tblcustomfieldsvalues')
    //         ->where('relid', $clientId)
    //         ->where('fieldid', $disableMailCustomFieldId)
    //         ->value('value');

    // Por questões de perfomance, não pegamos o valor do campo pelo banco de dados.
    // Alterar o nome do campo personalizado aqui também.
    $disableMailingCustomFieldName = 'Desativar notificações por e-mail';
    $disableMailingCustomFieldNameAsKey = preg_replace('/[^a-z]/', '', strtolower($disableMailingCustomFieldName));

    $disableMailing = $vars['mergefields']['client_custom_field_' . $disableMailingCustomFieldNameAsKey];
    $abortSend = $disableMailing === 'on';

    return $abortSend ? ['abortsend' => true] : [];
});
