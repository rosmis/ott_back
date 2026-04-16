<?php

declare(strict_types=1);

namespace App\Dto;

use App\Enums\VideoStatus;
use Illuminate\Http\UploadedFile;

final readonly class UpdateOrCreateVideoDto
{
    public function __construct(
        public string $title,
        public int $categoryId,
        public ?string $description,
        public ?UploadedFile $thumbnail,
        public ?UploadedFile $video,
        public VideoStatus $status
    ) {
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            categoryId: (int) $data['category_id'],
            description: $data['description'] ?? null,
            thumbnail: $data['thumbnail'] ?? null,
            video: $data['video'] ?? null,
            status: VideoStatus::from($data['status']),
        );
    }
}
