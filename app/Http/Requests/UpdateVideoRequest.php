<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\VideoStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVideoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'file', 'image', 'max:2048'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'video' => [
                'nullable',
                'file',
                'mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime',
                'max:102400',
            ],
            'status' => ['required', 'string', Rule::in(VideoStatus::cases())],
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'title',
            'description' => 'description',
            'thumbnail' => 'thumbnail',
            'video' => 'video',
            'status' => 'status',
        ];
    }
}
