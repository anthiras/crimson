<?php

namespace App\Events;

use App\Domain\CourseId;
use App\Domain\UserId;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CourseParticipantStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $courseId;
    public $userId;
    public $status;

    /**
     * Create a new event instance.
     *
     * @param CourseId $courseId
     * @param UserId $userId
     * @param string $status
     */
    public function __construct(CourseId $courseId, UserId $userId, string $status)
    {
        $this->courseId = $courseId;
        $this->userId = $userId;
        $this->status = $status;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
