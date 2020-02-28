<?php

require_once '/app/vendor/autoload.php';

use Helpers\RequestListHelper;
use Helpers\HubspotClientHelper;
use Helpers\OAuth2Helper;

getEnvOrException('PROCESS_COUNT');

if (!OAuth2Helper::isAuthenticated()) {
    echo 'In order to continue please go to http://localhost:8999 and authorize via OAuth.'.PHP_EOL;
    while (true) {
        sleep(1);
        if (OAuth2Helper::isAuthenticated()) {
            break;
        }
    }
}

echo 'Start'.PHP_EOL;

$hubspot = HubspotClientHelper::createFactory();


while (true) {
    echo PHP_EOL.'Request: Get contacts'.PHP_EOL;
    $able = RequestListHelper::ableToPerform();
    
    while ($able == false) {
        echo 'Able To Perform = '.($able ? 'yes' : 'no').PHP_EOL;
        echo 'Sleeping...'.PHP_EOL;
        sleep(10);
        $able = RequestListHelper::ableToPerform();
    }
    
    echo 'Able To Perform = '.($able ? 'yes' : 'no').PHP_EOL;
    
    RequestListHelper::addTimestamp();
    $response = $hubspot->crm()->contacts()->basicApi()->getPage();
    
    echo 'Response received'.PHP_EOL;
}