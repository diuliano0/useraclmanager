<?php

namespace BetaGT\UserAclManager\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use BetaGT\UserAclManager\Models\User;

class UsuarioCadastrado
{
    use InteractsWithSockets, SerializesModels;
    /**
     * @var User
     */
    private $user;
    /**
     * @var string
     */
    private $role;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user,string $role = null)
    {
        //
        $this->user = $user;
        $this->role = $role;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
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
