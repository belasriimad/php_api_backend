<?php

namespace App\Models;
use App\Database\Database as DB;
use PDO;


class EventModel
{
    private $conn;

    public function __construct()
    {
        $database = new DB;
        $this->conn = $database->connect();
    }

    public function fetchAllEvents()
    {
        $data = [];
        $stmt = $this->conn->prepare("SELECT * FROM events");
        $stmt->execute();

        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($events as $event) {
            $category = $this->fetchEventCategory($event);
            array_push($data, array_merge($event, $category));
        }
        return $data;
    }

    public function fetchEventCategory($event)
    {
        $stmt = $this->conn->prepare("SELECT name as category_name FROM categories
            WHERE id = :category_id");
        $stmt->bindParam(':category_id', $event['category_id'], PDO::PARAM_INT);
        $stmt->execute();

        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        return $category;
    }

    public function fetchEvent($event_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM events
        WHERE id = :event_id");
        $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
        $stmt->execute();

        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$event) {
            http_response_code(404);
            return $data = [
                'error' => true,
                'message' => 'The event you are looking for does not exist.'
            ];
        }

        $category = $this->fetchEventCategory($event);
        $data = array_merge($event, $category);
        return $data;
    }

    public function fetchEventByCategory($category_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM events
        WHERE category_id = :category_id");
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->execute();

        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(!$events) {
            http_response_code(404);
            return $data = [
                'error' => true,
                'message' => 'No results found.'
            ];
        }

        return $events;
    }
}