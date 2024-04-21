<?php

namespace App\Repository;

use App\Models\Gif;
use App\Models\User;

class UserRepository
{
    /**
     * @param string $email
     * @return User
     */
    public function getUserByEmail(string $email): User
    {
        return User::whereEmail($email)->first();
    }

    /**
     * @param int $id
     * @return User
     */
    public function getUserById(int $id): User
    {
        return User::whereId($id)->first();
    }

    /**
     * @param User $user
     * @param Gif $gif
     * @param string $alias
     * @return void
     */
    public function syncGif(User $user, Gif $gif, string $alias): void
    {
        $user->gifs()->sync([$gif->id => ['alias' => $alias]]);
    }
}
