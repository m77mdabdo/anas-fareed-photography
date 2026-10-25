<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Media;
use App\Models\MediaVariant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Does every photograph the database knows about have its files on disk?
 *
 * Read-only. The database and the files travel separately — the database by
 * import or migration, the files through shared/storage, never through Git —
 * so a site can be complete in the dashboard and show a broken image on every
 * page. That happened once in production (182 records, no files) and nothing
 * said so. The deploy runs this after the health check and prints the result,
 * without failing the release over it.
 */
class VerifyMedia extends Command
{
    protected $signature = 'media:verify
        {--list : Print the path of every missing file}';

    protected $description = 'Count media originals and renditions that are missing from disk (read-only)';

    public function handle(): int
    {
        $missingOriginals = [];
        $originals = 0;

        Media::query()->select(['id', 'disk', 'path'])->chunkById(200, function ($chunk) use (&$missingOriginals, &$originals): void {
            foreach ($chunk as $media) {
                $originals++;

                if ($media->path === null || ! Storage::disk($media->disk)->exists($media->path)) {
                    $missingOriginals[] = "{$media->disk}:{$media->path}";
                }
            }
        });

        $missingVariants = [];
        $variants = 0;

        MediaVariant::query()->whereHas('media')->select(['id', 'disk', 'path'])->chunkById(500, function ($chunk) use (&$missingVariants, &$variants): void {
            foreach ($chunk as $variant) {
                $variants++;

                if (! Storage::disk($variant->disk)->exists($variant->path)) {
                    $missingVariants[] = "{$variant->disk}:{$variant->path}";
                }
            }
        });

        $this->line(sprintf(
            'Originals: %d present, %d missing (of %d). Renditions: %d present, %d missing (of %d).',
            $originals - count($missingOriginals), count($missingOriginals), $originals,
            $variants - count($missingVariants), count($missingVariants), $variants,
        ));

        if ($this->option('list')) {
            foreach ([...$missingOriginals, ...$missingVariants] as $path) {
                $this->line("  missing {$path}");
            }
        }

        return $missingOriginals === [] && $missingVariants === [] ? self::SUCCESS : self::FAILURE;
    }
}
