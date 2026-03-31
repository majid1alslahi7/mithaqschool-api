<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BackupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['id'] ?? null,
            'filename' => $this['filename'] ?? null,
            'size' => $this['size'] ?? null,
            'type' => $this['type'] ?? null,
            'created_at' => $this['created_at'] ?? null,
            'download_url' => $this['download_url'] ?? null,
        ];
    }
}
