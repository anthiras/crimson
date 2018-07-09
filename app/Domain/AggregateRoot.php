<?php
namespace App\Domain;

abstract class AggregateRoot
{
	abstract public function id();
}