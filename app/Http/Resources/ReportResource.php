<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'title' => $this['title'] ?? null,
            'generated_at' => now(),
            'data' => $this['data'] ?? [],
            'summary' => $this['summary'] ?? [],
            'download_url' => $this['download_url'] ?? null,
        ];
    }
}
