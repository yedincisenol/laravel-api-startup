<?php

namespace App\Transformers;

use App\User;

class NotificationTransformer extends Transformer
{
    public $defaultIncludes = ['notifiable' => 'user'];

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Transform to Notification.
     *
     * @param $notification
     *
     * @return array
     */
    public function transform($notification)
    {
        return [
            'id'                    => $notification->id,
            'title'                 => @$notification->data['title'],
            'text'                  => $notification->data['text'],
            'uri'                   => $notification->data['uri'],
            'type'                  => @$notification->data['type'],
            'point'                 => $notification->data['point'],
            'image_url'             => @$notification->data['left_image_url'],
            'note'                  => @$notification->data['note'],
            'read_at'               => (string) timezone($notification->read_at),
            'created_at'            => (string) timezone($notification->created_at)->toDateTimeString(),
            'created_at_readable'   => $this->readable($notification->created_at),
        ];
    }

    private function readable($time)
    {
        $time = $time->diffForHumans(null, true, true);
        $time = str_replace([' dakika', ' saniye', ' gün', ' hafta', ' yıl', ' saat'], ['d', 'sn', 'g', 'h', 'y', 's'], (string) $time);

        return $time;
    }

    public function includeUser($notification)
    {
        @$userID = $notification->data['user_id'];
        $user = User::find($userID);
        if (!$user) {
            return;
        }

        return $this->item($user, new UserTransformer());
    }
}
