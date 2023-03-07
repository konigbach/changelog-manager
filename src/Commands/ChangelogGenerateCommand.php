<?php

namespace Konigbach\ChangelogManager\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Konigbach\ChangelogManager\ChangelogGenerator;
use Symfony\Component\Finder\SplFileInfo;
use TypeError;

class ChangelogGenerateCommand extends Command
{
    public $signature = 'changelog:generate
            {--D|delete-files : Delete files}
            {--c|changelog-version= : Version of the changelog}';

    public $description = 'It generates a changelog.';

    public function __construct(protected ChangelogGenerator $changelogGenerator)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $version = $this->option('changelog-version');

        if (\is_array($version)) {
            $version = $version[0];
        }

        $this->changelogGenerator->make($version);

        if ($this->option('delete-files')) {
            $directory = $this->directory();
            $files = array_filter(File::allFiles($directory), function (SplFileInfo $file): bool {
                return $file->getExtension() === 'yaml';
            });
            foreach ($files as $file) {
                if (\is_string($file->getRealPath())) {
                    File::delete($file->getRealPath());
                }
            }
        }

        $this->comment('All done!');
    }

    protected function directory(): string
    {
        $directory = config('changelog-manager.directory');

        if (!\is_string($directory)) {
            throw new TypeError();
        }

        return $directory;
    }
}
