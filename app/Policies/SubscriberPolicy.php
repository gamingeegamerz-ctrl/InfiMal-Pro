<?php

namespace App\Policies;

use App\Models\Subscriber;
use App\Models\User;

class SubscriberPolicy
{
    public function view(User $user, Subscriber $subscriber)
    {
        return $user->id === $subscriber->user_id;
    }

    public function update(User $user, Subscriber $subscriber)
    {
        return $user->id === $subscriber->user_id;
    }

    public function delete(User $user, Subscriber $subscriber)
    {
        return $user->id === $subscriber->user_id;
    }
}
