<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Requests;
use Illuminate\Http\Request;

use Auth;

class EventsController extends Controller
{
    public function index()
    {
        return view('index');
    }
}
