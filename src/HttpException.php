<?php

use Lukasoppermann\Httpstatus\Httpstatus;
use Lukasoppermann\Httpstatus\Httpstatuscodes;

class HttpException extends Exception implements Httpstatuscodes {

    public function __construct() {
        $args = func_get_args();
        if (empty($args)) {
            throw new \InvalidArgumentException('At least one argument is expected');
        }

        if (count($args) === 1) {
            if (!is_int($args[0])) {
                throw new \InvalidArgumentException('The status code must always be specified');
            }
            
            $code = $args[0];
        } else if (count($args) === 2) {
            if (is_int($args[0]) && is_string($args[1])) {
                throw new \InvalidArgumentException('The status code must always come after the message');
            }
            
            if (is_string($args[0]) && is_int($args[1])) {
                $message = $args[0];
                $code = $args[1];
            } else if (is_int($args[0])) {
                $code = $args[0];
                $previous = $args[1];
            }
        } else if (count($args) >= 3) {
            if (is_int($args[0]) && is_string($args[1])) {
                throw new \InvalidArgumentException('The status code must always come after the message');
            }

            $message = $args[0];
            $code = $args[1];
            $previous = $args[2];
        }

        if (!isset($message)) {
            $message = (new Httpstatus())->getReasonPhrase($code);
        }

        if (!isset($previous)) {
            $previous = null;
        }

        parent::__construct($message, $code, $previous);
    }

}
