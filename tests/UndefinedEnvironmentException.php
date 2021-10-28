<?php

namespace gq_group\Tests;

use Exception;
use Throwable;

class UndefinedEnvironmentException extends Exception {
    public function __construct($environment, Throwable $previous = null) {
        parent::__construct("Undefined environment: $environment", -1, $previous);
    }
}