<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'action' => '<div class="btn-group"><button class="btn btn-success" data-toggle="modal" data-target="#saveUserData" onclick="viewUser('.$this->id.')">View</button>
            <button class="btn btn-info" data-toggle="modal" data-target="#saveUserData" onclick="editUser('.$this->id.')">Edit</button>
            <button class="btn btn-danger" onclick="deleteUser('.$this->id.')">Delete</button>',
            // 'created_at' => $this->created_at,
        ];
    }
}
