<?php

use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

/**
 * Send notificaton
 * @param $deviceIds
 * @param $ttl
 * @param $title
 * @param $body
 * @param $data
 */
function notification($deviceIds, $ttl, $title, $body, $data) {

    $notificationBuilder = new PayloadNotificationBuilder();
    $notificationBuilder->setTitle($title)
        ->setBody($body);

    $notification = $notificationBuilder->build();

    $optionBuiler = new OptionsBuilder();
    $optionBuiler->setTimeToLive($ttl);
    $option = $optionBuiler->build();

    $dataBuilder = new PayloadDataBuilder();
    $dataBuilder->addData($data);
    $data = $dataBuilder->build();

    FCM::sendTo($deviceIds, $option, $notification, $data);

}