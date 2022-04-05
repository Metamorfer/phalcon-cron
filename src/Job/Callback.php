<?php

namespace Objectsystems\Phalcon\Cron\Job;

use Objectsystems\Phalcon\Cron\Job;

class Callback extends Job
{
    /**
     * @var callable
     */
    protected $callback;

    public function __construct(string $expression, callable $callback)
    {
        parent::__construct($expression);
        
        $this->callback = $callback;
    }

    public function getCallback() : callable
    {
        return $this->callback;
    }

    /**
     * @return mixed
     */
    public function runInForeground()
    {
        $contents = call_user_func($this->callback);
        
        return $contents;
    }
}
