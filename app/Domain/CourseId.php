<?php
namespace App\Domain;

use Webpatser\Uuid\Uuid;

class CourseId
{
	private $id;

	public function __construct($id)
	{
		$this->id = $id;
	}

	public static function create(): CourseId
    {
        return new CourseId(Uuid::generate()->string);
    }

	public function id()
	{
		return $this->id;
	}

	public function equals(CourseId $courseId)
	{
		return $this->id() == $courseId->id();
	}

	public function __toString()
	{
		return $this->id();
	}
}