<?php

namespace App\Utils;

class Message
{
    private $text;
    private $type;

    public function success(string $message): Message
    {
        $this->type = 'success';
        $this->text = $message;
        return $this;
    }

    public function error(string $message): Message
    {
        $this->type = 'error';
        $this->text = $message;
        return $this;
    }

    public function render(): string
    {
        return "<div class='message {$this->type}'>{$this->text}</div>";
    }
}
