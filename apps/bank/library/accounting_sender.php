<?php


namespace Library;

class AccountingSender {

    public function __construct(private ?array $data = null) {
    }

    public function setData(array $data):void {
        $this->data = $data;
    }

    public function sendTable():bool {
        // DO STUFF ...
        // ...

        return true;
    }

    public function getData(): ?array {
        return $this->data;
    }
    
}