<?php

namespace App\Controllers;
use App\Models\EventModel as Event;

class EventController
{
    private $model;

    public function __construct()
    {
        $this->model = new Event;
    }

    public function index()
    {
        $events = $this->model->fetchAllEvents();
        echo json_encode([
            'events' => $events
        ]);
    }

    public function show($event_id)
    {
        $event = $this->model->fetchEvent($event_id);
        echo json_encode([
            'event' => $event
        ]);
    }

    public function eventsByCategory($category_id)
    {
        $events = $this->model->fetchEventByCategory($category_id);
        echo json_encode([
            'events' => $events
        ]);
    }

}