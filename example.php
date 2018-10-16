<?php
    require_once __DIR__ . '/vendor/autoload.php';

    $guzzle = new \Http\Adapter\Guzzle6\Client(new GuzzleHttp\Client(['verify'=>false]));
    $client = \Sipwise\Client::createWithHttpClient($guzzle);
    $client->authenticate('ossbss', 'boss.api.');
    $client->setUrl('https://103.7.203.21:1443');
//    print_r($client);die();
    
    $parameters = [ 'customer_id' => 2,
                    'domain_id' => 9,
                    'username' => 64123123,
                    'password' =>'a123123'];
//    $return = $client->api('subscriber')->create($parameters);
//    $parameters = [ "customer_id" => 2,
//                    "username" => 64123123,
//                    "domain_id" => 9,
//                    "password" => "atest123"];
//    $parameters = [
//                    ['op' => 'replace',
//                     'path' => '/profile_id',
//                     'value' => 1]
//                ];
                
//                echo \GuzzleHttp\json_encode($parameters);die();
//    $return = $client->api('subscriberPreferences')->preferences()->edit();//, $parameters);//(['page' => 1, 'rows' => 1]);
    $return = $client->api('voicemail')->update(3181,['folder'=>'INBOX', 'duration'=>9]);//['subscriber_id'=>165572]);//->settings()->remove(165572);//->edit(165572, [['op'=>'replace','path'=>'/pin', 'value'=>'142142']]);//['pin'=>123123,'sms_number'=>64126126]);//all(['subscriber_id' => 165572]);//cfDestinationSets()->show(42118);//all(['subscriber_id' => 165572]);
    print_r($return);die();