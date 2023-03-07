<?php

namespace Konigbach\ChangelogManager;

use Illuminate\Support\Facades\File;
use Konigbach\ChangelogManager\Exceptions\NotFilesFoundException;
use Konigbach\ChangelogManager\Exceptions\ParsedFileContentIsNotArrayException;
use SplFileInfo;
use Symfony\Component\Yaml\Yaml;
use TypeError;

class ChangelogGenerator
{
    public function make(?string $version): void
    {
        $lines = [];
        $files = $this->files();

        if (\count($files) === 0) {
            throw new NotFilesFoundException();
        }

        foreach ($files as $file) {
            $parsedFile = Yaml::parseFile($file);
            if (!\is_array($parsedFile)) {
                throw new ParsedFileContentIsNotArrayException($file);
            }
            $fileContent = collect($parsedFile);

            foreach ($this->allowedTypes() as $allowedType) {
                if ($fileContent->keys()->contains($allowedType)) {
                    $lines[$allowedType][] = $fileContent->get($allowedType)[0];
                }
            }
        }

        $view = view('changelog-manager::changelog-manager.changelog_template', compact('lines', 'version'));

        File::put('changelog_.md', $view->render());
    }

    /**
     * @return array<SplFileInfo>
     */
    protected function files(): array
    {
        $directory = $this->directory();

        return File::allFiles($directory);
    }

    protected function directory(): string
    {
        $directory = config('changelog-manager.directory');

        if (!\is_string($directory)) {
            throw new TypeError();
        }

        return $directory;
    }

    /**
     * @return array<string>
     */
    protected function allowedTypes(): array
    {
        $allowedTypes = config('changelog-manager.allowed_types');

        if (!\is_array($allowedTypes)) {
            throw new TypeError();
        }

        return $allowedTypes;
    }
}
