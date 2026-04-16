<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Actions\SaveVideoAction;
use App\Actions\SaveVideoThumbnailAction;
use App\Actions\UpdateOrCreateVideoAction;
use App\Dto\UpdateOrCreateVideoDto;
use App\Enums\VideoStatus;
use App\Models\Category;
use App\Models\User;
use App\Models\Video;
use App\Services\VideoService;
use Database\Factories\CategoryFactory;
use Database\Factories\UserFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

/**
 * @internal
 */
#[CoversClass(VideoService::class)]
final class VideoServiceTest extends TestCase
{
    private User $admin;

    private Category $category;

    private MockObject&SaveVideoAction $saveVideoActionMock;

    private MockObject&SaveVideoThumbnailAction $saveVideoThumbnailActionMock;

    private VideoService $videoService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = CategoryFactory::new()->createOne();

        $this->admin = UserFactory::new()
            ->setAdmin()
            ->createOne();

        $this->editor = UserFactory::new()
            ->createOne();

        $this->saveVideoActionMock = $this->createMock(SaveVideoAction::class);
        $this->saveVideoThumbnailActionMock = $this->createMock(SaveVideoThumbnailAction::class);

        $this->instance(SaveVideoAction::class, $this->saveVideoActionMock);
        $this->instance(SaveVideoThumbnailAction::class, $this->saveVideoThumbnailActionMock);

        $this->videoService = $this->app->make(VideoService::class);

        $this->freezeTime();
    }

    #[TestDox(
        'Given calling the updateOrCreate method
         AND providing a video file and a thumbnail file,
         Then it should call the SaveVideoAction and SaveVideoThumbnailAction with the correct parameters.'
    )]
    public function testShouldCallSaveActionsWhenVideoAndThumbnailProvided(): void
    {
        $createVIdeoActionMock = $this->createMock(UpdateOrCreateVideoAction::class);
        $this->instance(UpdateOrCreateVideoAction::class, $createVIdeoActionMock);

        $videoDto = new UpdateOrCreateVideoDto(
            title: 'Test Video',
            categoryId: $this->category->id,
            description: 'Test Description',
            thumbnail: UploadedFile::fake()->image('thumbnail.jpg'),
            video: UploadedFile::fake()->create('video.mp4', 1000),
            status: VideoStatus::Draft,
        );

        $createVIdeoActionMock
            ->expects(self::once())
            ->method('__invoke')
            ->with(
                self::callback(static function (UpdateOrCreateVideoDto $dto) use ($videoDto): bool {
                    self::assertSame($dto->title, $videoDto->title);
                    self::assertSame($dto->categoryId, $videoDto->categoryId);
                    self::assertSame($dto->description, $videoDto->description);
                    self::assertSame($dto->status, $videoDto->status);

                    return true;
                }),
                self::callback(function (User $user): bool {
                    self::assertSame($user->id, $this->admin->id);

                    return true;
                }),
                self::callback(static function (?int $videoId): bool {
                    self::assertNull($videoId);

                    return true;
                })
            );

        $this->saveVideoActionMock
            ->expects(self::once())
            ->method('__invoke')
            ->with(
                self::callback(static function (UpdateOrCreateVideoDto $dto) use ($videoDto): bool {
                    self::assertSame($dto->video, $videoDto->video);

                    return true;
                }),
                self::callback(static function (Video $video): bool {
                    self::assertInstanceOf(Video::class, $video);

                    return true;
                })
            );

        $this->saveVideoThumbnailActionMock
            ->expects(self::once())
            ->method('__invoke')
            ->with(
                self::callback(static function (UpdateOrCreateVideoDto $dto) use ($videoDto): bool {
                    self::assertSame($dto->video, $videoDto->video);

                    return true;
                }),
                self::callback(static function (Video $video): bool {
                    self::assertInstanceOf(Video::class, $video);

                    return true;
                })
            );

        $this
            ->videoService
            ->updateOrCreate($videoDto, $this->admin);
    }

    #[TestDox(
        'Given no video nor thumbnail file with a videoDto with the status set to published,
         When calling the updateOrCreate method
         Then it should call the UpdateOrCreateVideoAction with the correct parameters and set the published_at field of the video.'
    )]
    public function testShouldSetPublishedAtWhenStatusIsPublished(): void
    {
        $videoDto = new UpdateOrCreateVideoDto(
            title: 'Test Video',
            categoryId: $this->category->id,
            description: 'Test Description',
            thumbnail: null,
            video: null,
            status: VideoStatus::Published,
        );

        $this
            ->saveVideoActionMock
            ->expects(self::never())
            ->method('__invoke');

        $this
            ->saveVideoThumbnailActionMock
            ->expects(self::never())
            ->method('__invoke');

        $this
            ->videoService
            ->updateOrCreate($videoDto, $this->admin);

        /** @var Video $video */
        $video = Video::query()
            ->where('title', $videoDto->title)
            ->firstOrFail();

        self::assertNotNull($video->published_at);
        self::assertSame(
            $video->published_at->toDateTimeString(),
            Carbon::now()->toDateTimeString()
        );
    }
}
