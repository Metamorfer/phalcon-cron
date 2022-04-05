<?php

namespace Phalcon\Cron\Job;

use Phalcon\Di\DiInterface;
use Objectsystems\Phalcon\Cron\Exception;
use Objectsystems\Phalcon\Cron\Job;

class Phalcon extends Job
{
    protected array $body;

    public function __construct(DiInterface $di, string $expression, $body = [])
    {
        $this->di = $di;
        parent::__construct($expression);
        $this->body = $body;
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function runInForeground() : string
    {
        $di = $this->getDI();
        $console = $di->get("console");

        ob_start();

        $console->handle(
            $this->getBody()
        );

        $contents = ob_get_contents();

        ob_end_clean();

        return $contents;
    }
}
