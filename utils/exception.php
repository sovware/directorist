<?php

namespace Directorist\Utils;

\defined('ABSPATH') || exit;

use Throwable;

class Exception extends \Exception {
    private $messages = [];
    /**
     * @param string $message
     * @param integer $code
     * @param Throwable|null $previous
     * @param array $messages
     */
    public function __construct(string $message = "", int $code = 404, $previous = null, array $messages = [])
    {
        parent::__construct($message, $code, $previous);
        $this->messages = $messages;
    }
    public function get_messages()
    {
        return $this->messages;
    }
    public function set_messages(array $messages)
    {
        $this->messages = $messages;
        return $this;
    }
}
