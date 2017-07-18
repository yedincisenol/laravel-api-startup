<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotificationRequest;

class NotificationController extends Controller
{
    /**
     * Send notification
     * @param NotificationRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function send(NotificationRequest $request)
    {
        notification([$request->device_id], [
                'en' => $request->get('notification_body')
            ], null, $request->get('notification_data'), 0);

        return $this->response->created();
    }

}