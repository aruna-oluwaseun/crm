<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * View notifications
     * @return mixed
     */
    public function index()
    {
        return 'Notifications list will be available soon';
    }

    /**
     * Mark as read
     */
    public function markAsRead($id = false)
    {
        if($id)
        {
            $notifications = get_user()->notificationsUnread()->where('id',$id);
        }
        else
        {
            $notifications = get_user()->notificationsUnread();
        }

        if($notifications && $notifications->count())
        {
            if($notifications->update(['read' => date('Y-m-d H:i:s')]))
            {
                if(\request()->ajax()) {
                    return response()->json([
                        'success' => true
                    ]);
                }

                return true;
            }
        }

        if(\request()->ajax()) {
            return response()->json([
                'success' => false
            ]);
        }

        return false;
    }

    /**
     * Take user to notification url
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function view($id)
    {
        $url = \request()->get('url');

        $this->markAsRead($id);

        return redirect($url);
    }

}
