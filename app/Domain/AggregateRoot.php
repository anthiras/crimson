<?php
namespace App\Domain;

abstract class AggregateRoot
{
	abstract public function id();

	private $domainEvents = array();

	protected function registerEvent($event)
    {
        array_push($this->domainEvents, $event);
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