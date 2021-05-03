<?php


namespace Library;

class RegistryRequest {

    private Int $userId;
    private $data;
    private String signature;


    public function makeRequest() {
        // TODO
    }

    public function setUserId(Int $userId) {
        $this->userId = $userId;
    }

    public function getData() {
        return $this->data;
    }
}