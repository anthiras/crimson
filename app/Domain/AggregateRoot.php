<?php
namespace App\Domain;

use Illuminate\Support\Arr;

abstract class AggregateRoot
{
	abstract public function id();

	private $domainEvents = array();

	protected function registerEvent($event)
    {
        Arr::prepend($this->domainEvents, $event);
    }

    public function dispatchEvents()
    {
        foreach ($this->domainEvents as $event)
        {
            event($event);
        }
        $this->domainEvents = array();
    }
}