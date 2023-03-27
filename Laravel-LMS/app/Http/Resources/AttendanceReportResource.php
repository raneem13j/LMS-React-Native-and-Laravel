<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        
        return [
            'id' => $this->id,
            'role' => $this->role,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'status' => $this->attendance ? $this->attendance->status : null,
            'section' => $this->attendance?->LevelSection?->Section[0]['sectionName'] ?? null,
            'grade' => $this->attendance?->LevelSection?->Level[0]['levelName'] ?? null,
            'capacity'=>20
        ];
    }
}