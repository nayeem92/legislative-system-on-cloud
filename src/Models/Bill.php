<?php

class Bill {
    private $bill_id;
    private $title;
    private $description;
    private $author_id;
    private $status;
    private $created_at;

    public function __construct($bill_id, $title, $description, $author_id, $status, $created_at) {
        $this->bill_id = $bill_id;
        $this->title = $title;
        $this->description = $description;
        $this->author_id = $author_id;
        $this->status = $status;
        $this->created_at = $created_at;
    }

    // Getters
    public function getBillId() {
        return $this->bill_id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getAuthorId() {
        return $this->author_id;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    // Additional methods related to bill can be added here
}
?>
