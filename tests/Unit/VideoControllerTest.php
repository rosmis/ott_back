<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Enums\VideoStatus;
use App\Http\Controllers\VideoController;
use App\Models\Category;
use App\Models\User;
use Database\Factories\CategoryFactory;
use Database\Factories\UserFactory;
use Database\Factories\VideoFactory;
use Feature\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;

/**
 * @internal
 */
#[CoversClass(VideoController::class)]
final class VideoControllerTest extends TestCase
{
    private User $admin;

    private User $editor;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = CategoryFactory::new()->createOne();

        $this->admin = UserFactory::new()
            ->setAdmin()
            ->createOne();

        $this->editor = UserFactory::new()
            ->createOne();
    }

    #[TestDox(
        'Given an unauthenticated request,
         When accessing the videos index endpoint,
         Then it should return a 401 unauthorized response.'
    )]
    public function testUnauthenticatedRequestIsRejected(): void
    {
        $this
            ->get(route('videos.index'))
            ->assertUnauthorized();
    }

    #[TestDox(
        'Given an authenticated editor user,
         When trying to delete a video,
         Then it should return a 403 forbidden response because only admins can delete.'
    )]
    public function testEditorCannotDeleteVideo(): void
    {
        $video = VideoFactory::new()
            ->setCategory($this->category->id)
            ->setUser($this->editor->id)
            ->createOne();

        $this
            ->actingAs($this->editor)
            ->delete(route('videos.destroy', ['video_id' => $video->id]))
            ->assertForbidden();
    }

    #[TestDox(
        'Given an authenticated admin user,
         When trying to get the videos index,
         Then it should return a 200 ok response with a list of videos.'
    )]
    public function testShouldGetVideosAsAdmin(): void
    {
        VideoFactory::new()
            ->setCategory($this->category->id)
            ->setUser($this->editor->id)
            ->count(3)
            ->create();

        $response = $this
            ->actingAs($this->admin)
            ->get(route('videos.index'));

        $response
            ->assertOk()
            ->assertJson(
                static fn (AssertableJson $json) => $json
                    ->has(
                        'data',
                        static fn (AssertableJson $json) => $json
                            ->count(3)
                            ->each(
                                static fn (AssertableJson $json) => $json
                                    ->whereType('id', 'integer')
                                    ->whereType('title', 'string')
                                    ->whereType('duration', 'integer')
                                    ->whereType('status', 'string')
                                    ->whereType('thumbnail_url', 'string')
                                    ->whereType('category', 'array')
                                    ->whereType('user', 'array')
                                    ->whereType('published_at', 'string')
                            )
                    )
                    ->etc()
            );
    }

    #[TestDox(
        'Given an authenticated admin user,
         When trying to delete a video,
         Then it should return a 204 no content response indicating successful deletion.'
    )]
    public function testShouldDeleteVideoAsAdmin(): void
    {
        $video = VideoFactory::new()
            ->setCategory($this->category->id)
            ->setUser($this->editor->id)
            ->createOne();

        $this
            ->actingAs($this->admin)
            ->delete(route('videos.destroy', ['video_id' => $video->id]))
            ->assertNoContent();
    }

    #[TestDox(
        'Given an authenticated editor user,
         When trying to update another user\'s video,
         Then it should return a 403 forbidden response.'
    )]
    public function testEditorCannotUpdateAnotherUsersVideo(): void
    {
        $otherEditor = UserFactory::new()->createOne();

        $video = VideoFactory::new()
            ->setCategory($this->category->id)
            ->setUser($otherEditor->id)
            ->createOne();

        $response = $this
            ->actingAs($this->editor)
            ->put(
                route(
                    'videos.update',
                    ['video_id' => $video->id]
                ),
                [
                    'title' => 'Updated Title',
                    'slug' => 'updated-title',
                    'category_id' => $this->category->id,
                    'status' => VideoStatus::Draft->value,
                ]
            );

        $response->assertForbidden();
    }

    #[TestDox(
        'Given videos with different statuses,
         When fetching videos filtered by a specific status,
         Then it should return only the videos matching that status.'
    )]
    public function testFilterByStatusReturnsMatchingVideos(): void
    {
        VideoFactory::new()
            ->setCategory($this->category->id)
            ->setUser($this->editor->id)
            ->count(3)
            ->create(['status' => VideoStatus::Published]);

        VideoFactory::new()
            ->setCategory($this->category->id)
            ->setUser($this->editor->id)
            ->count(2)
            ->create(['status' => VideoStatus::Draft]);

        VideoFactory::new()
            ->setCategory($this->category->id)
            ->setUser($this->editor->id)
            ->count(1)
            ->create(['status' => VideoStatus::Archived]);

        $response = $this
            ->actingAs($this->editor)
            ->get(
                route(
                    'videos.index',
                    ['status[value]' => VideoStatus::Published->value]
                )
            );

        $response->assertOk();

        $data = $response->json('data');

        self::assertCount(3, $data);

        foreach ($data as $video) {
            self::assertSame(VideoStatus::Published->value, $video['status']);
        }
    }
}
