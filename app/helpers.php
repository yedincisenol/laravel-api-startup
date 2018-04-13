<?php

/**
 * @param $deviceIds
 * @param $title
 * @param $body
 * @param $data
 * @param int $ttl
 */
function notification($deviceIds, $title, $body, $data, $ttl = 259200)
{
    OneSignal::sendNotificationCustom(
        ['include_player_ids' => $deviceIds, 'contents' => $body, 'data' => $data,
            'headings'        => $title, 'ttl' => $ttl, ]);
}

/**
 * Set timezone to date
 * @param $date
 * @return null
 */
function timezone($date)
{
    if (!$date) {
        return null;
    }
    return $date->timezone(session('timezone'));
}
