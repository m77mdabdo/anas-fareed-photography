<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Models\Media;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VerifyMediaCommandTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('media_originals');
        Storage::fake('media_public');
    }

    private function media(bool $original, bool $rendition): Media
    {
        $media = Media::factory()->create();

        if ($original) {
            Storage::disk('media_originals')->put($media->path, 'x');
        }

        $path = sprintf('2026/09/%s/1920.jpg', $media->uuid);

        if ($rendition) {
            Storage::disk('media_public')->put($path, 'x');
        }

        $media->variants()->create([
            'format' => 'jpeg', 'width' => 1920, 'height' => 1280,
            'disk' => 'media_public', 'path' => $path, 'size_bytes' => 1,
        ]);

        return $media;
    }

    public function test_it_passes_when_every_file_is_present(): void
    {
        $this->media(original: true, rendition: true);

        $this->artisan('media:verify')
            ->expectsOutput('Originals: 1 present, 0 missing (of 1). Renditions: 1 present, 0 missing (of 1).')
            ->assertExitCode(0);
    }

    public function test_it_counts_and_lists_missing_files_and_changes_nothing(): void
    {
        $this->media(original: true, rendition: true);
        $broken = $this->media(original: false, rendition: false);

        $this->artisan('media:verify', ['--list' => true])
            ->expectsOutput('Originals: 1 present, 1 missing (of 2). Renditions: 1 present, 1 missing (of 2).')
            ->expectsOutputToContain("missing media_originals:{$broken->path}")
            ->assertExitCode(1);

        // Read-only: the records are all still there.
        $this->assertSame(2, Media::query()->count());
        $this->assertSame(1, $broken->variants()->count());
    }
}
