<?php

namespace App\Http\Controllers\ChannelPartner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('channel_partner.dashboard');
    }
}
