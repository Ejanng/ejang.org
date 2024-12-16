<?php
// Define a Node class for the linked list
class Node {
    public $data; // Holds the message text
    public $next; // Points to the next node

    public function __construct($data) {
        $this->data = $data;
        $this->next = null;
    }
}

// Define a LinkedList class
class LinkedList {
    public $head; // Points to the first node in the list

    public function __construct() {
        $this->head = null;
    }

    // Add a new node to the end of the list
    public function append($data) {
        $newNode = new Node($data);

        if ($this->head === null) {
            $this->head = $newNode; // If the list is empty, set head to the new node
        } else {
            $current = $this->head;
            while ($current->next !== null) {
                $current = $current->next; // Traverse to the end of the list
            }
            $current->next = $newNode; // Attach the new node to the end
        }
    }

    // Retrieve all data as an array (for display/search)
    public function toArray() {
        $dataArray = [];
        $current = $this->head;
        while ($current !== null) {
            $dataArray[] = $current->data;
            $current = $current->next; // Move to the next node
        }
        return $dataArray;
    }
}
?>
