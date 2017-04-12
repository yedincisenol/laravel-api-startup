<?php

/**
 * @param $deviceIds
 * @param $title
 * @param $body
 * @param $data
 * @param int $ttl
 */
function notification($deviceIds, $title, $body, $data, $ttl = 259200) {

    OneSignal::sendNotificationCustom(
        ['include_player_ids' => $deviceIds, 'contents' => $body, 'data' => $data,
            'headings' => $title, 'ttl' => $ttl]);

}