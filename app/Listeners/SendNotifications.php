<?php

namespace App\Listeners;

use App\Events\Notifications;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class SendNotifications
{
    /**
     * Create the event listener.
     *
     * @return void
     */


    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Notifications  $event
     * @return void
     */
    public function handle(Notifications $event)
    {

        $type = $event->info['type'];
        $db_name = $event->info['db_name'];

        // Add new notification
        if ('add' === $type) {
            $notification = DB::connection($db_name)->table($db_name . '.notifications')->insert($event->data);
        }
        // Update existing notification
        elseif ('update' === $type) {
            $notification = DB::connection($db_name)->table($db_name . '.notifications')
                ->where('id_project', $event->data['id_project'])
                ->where('object_id', $event->data['object_id'])
                ->update($event->data);
        }
    }
}
