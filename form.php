<?php

require_once(__DIR__ . '/vendor/autoload.php');

use Introvert\ApiClient;
use Introvert\ApiException;
use Introvert\Configuration;

Configuration::getDefaultConfiguration()->setApiKey('key', '23bc075b710da43f0ffb50ff9e889aed');
Configuration::getDefaultConfiguration()->setHost('https://api.s1.yadrocrm.ru/tmp');

$api = new ApiClient();

try {
    $result = $api->account->allStatuses();

    $pipeline = array_keys($result['result'])[match ($_GET['form']) {
        'second' => 1,
        default => 0
    }];

    $status = array_key_first($result['result'][$pipeline]);

    $_POST['kvnukov_status'] = $status;
} catch (ApiException $e) {
    echo 'Exception when calling AccountApi->allStatuses: ', $e->getMessage(), PHP_EOL;
}

try {
    $result = $api->yadro->getUsers();

    $emails = array_slice(array_column($result['result'], 'email'), match ($_GET['form']) {
        'second' => 3,
        default => 0
    }, 3);

    $_POST['kvnukov_intr_group'] = implode(';', $emails);
} catch (ApiException $e) {
    echo 'Exception when calling YadroApi->getUsers: ', $e->getMessage(), PHP_EOL;
}

$_POST['new_entities'] = 'contact;lead';

require_once($_SERVER['DOCUMENT_ROOT'] . '/introvert_save.php');
