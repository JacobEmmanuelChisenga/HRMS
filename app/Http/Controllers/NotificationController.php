<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{

    public function index()
    {
        // Placeholder for all notifications
        $notifications = [];
        
        return view('super-admin.notifications.index', compact('notifications'));
    }

    public function alerts()
    {
        // Placeholder for system alerts
        $alerts = [];
        
        return view('super-admin.notifications.alerts', compact('alerts'));
    }
}
