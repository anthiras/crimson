<?php
namespace App\Domain;

use Illuminate\Support\Collection;

interface CourseRepository
{
	public function course(CourseId $courseId): Course;
    public function courses(): Collection;
    public function save(Course $course);
    public function delete(CourseId $courseId);
    public function nextId(): CourseId;
}