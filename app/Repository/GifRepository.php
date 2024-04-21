<?php

namespace App\Repository;

use App\Models\Gif;

class GifRepository
{
    /**
     * @param string $id
     * @return Gif|null
     */
    public function getGifById(string $id): ?Gif
    {
        return Gif::where('gif_id', $id)->first();
    }

    /**
     * @param string $id
     * @return Gif
     */
    public function saveGif(string $id): Gif
    {
        return Gif::create([
            'gif_id' => $id
        ]);
    }
}
