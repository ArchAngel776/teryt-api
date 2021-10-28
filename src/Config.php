<?php

namespace gq_group\Teryt;

use DateTime;
use mrcnpdlk\Teryt\Config as ConfigBase;

/**
 * Class Config
 * @package gq_group\Teryt
 *
 * @property DateTime|null $dateState
 */
class Config extends ConfigBase {
    protected $dateState;

    public function __construct(array $config = []) {
        parent::__construct($config);
        $this->dateState = null;
    }

    public function setDateState(DateTime $dateState) : void {
        $this->dateState = $dateState;
    }

    public function request(string $method, array $args = [], bool $addDate = true) {
        if (!array_key_exists('DataStanu', $args) && $addDate && $this->dateState) {
            $args['DataStanu'] = $this->dateState->format("Y-m-d");
            $this->dateState = null;
        }
        return parent::request($method, $args, $addDate);
    }
}