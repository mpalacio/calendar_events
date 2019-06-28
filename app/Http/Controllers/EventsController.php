<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Response;

class EventsController extends Controller
{
    public function index(Request $request)
    {
        $date_from = Carbon::createFromFormat('Y-m-d', $request->get('date_from'));
        $date_to   = Carbon::createFromFormat('Y-m-d', $request->get('date_to'));

        $months = $this->load_events($date_from, $date_to);

        return Response::json(array(
            'events' => $months
        ));
    }

    public function update(Request $request)
    {
        $event_name = $request->get('name');
        $date_from  = Carbon::createFromFormat('Y-m-d', $request->get('date_from'));
        $date_to    = Carbon::createFromFormat('Y-m-d', $request->get('date_to'));
        $days       = $request->get('days');

        if (count($days > 0)) {
            $date = $date_from->copy();
            while ($date <= $date_to) {
                if (in_array($date->format('w'), $days)) {
                    $event = Event::where('date', $date->format('Y-m-d'))->first();
                    if (!$event) {
                        $event = new Event;
                    }

                    $event->name = $event_name;
                    $event->date = $date->format('Y-m-d');

                    $event->save();
                } else {
                    $event = Event::where('date', $date->format('Y-m-d'))->delete();
                }

                $date->addDay();
            }
        }

        $months = $this->load_events($date_from, $date_to);

        return Response::json(array(
            'events' => $months,
            'date_from' => $date_from
        ));
    }

    private function load_events($date_from, $date_to)
    {
        $events = Event::whereBetween('date', array($date_from->format('Y-m-d'), $date_to->format('Y-m-d')))->get();

        $months = array();
        while ($date_from <= $date_to) {
            $month = $date_from->format('M Y');
            if (!in_array($month, array_keys($months))) {
                $months[$month] = array();
            }

            $event = $events->where('date', $date_from->format('Y-m-d'))->first();

            array_push($months[$month], array(
                'day'   => $date_from->format('j D'),
                'event' => $event ? $event->name : ''
            ));

            $date_from->addDay();
        }

        return $months;
    }
}
