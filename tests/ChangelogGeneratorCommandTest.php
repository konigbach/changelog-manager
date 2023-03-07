<?php

namespace Konigbach\ChangelogManager\Tests;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Konigbach\ChangelogManager\Exceptions\NotFilesFoundException;
use Konigbach\ChangelogManager\Exceptions\ParsedFileContentIsNotArrayException;

use function PHPUnit\Framework\assertEquals;

use TypeError;

class ChangelogGeneratorCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        View::addLocation(base_path('../../../../resources/views/'));
    }

    /** @test */
    public function it_generates_a_file_with_version(): void
    {
        Config::set('changelog-manager.directory', base_path('../../../../tests/Stubs/Changelog'));
        Config::set('changelog-manager.allowed_types', [
            'added',
            'changed',
            'deprecated',
            'fixed',
            'removed',
        ]);

        $this->artisan('changelog:generate --changelog-version=2.0.0')
            ->expectsOutput('All done!')
            ->assertExitCode(0);

        $generatedChangelog = File::get('changelog_.md');

        self::assertTrue(Str::contains($generatedChangelog, ['[2.0.0]']));

        File::delete('changelog_.md');
    }

    /** @test */
    public function it_deletes_the_files_when_option_is_active(): void
    {
        $directory = base_path('../../../../tests/Stubs/ChangelogToBeRemoved');
        File::copyDirectory(base_path('../../../../tests/Stubs/Changelog'), $directory);

        Config::set('changelog-manager.directory', $directory);
        Config::set('changelog-manager.allowed_types', [
            'added',
            'changed',
            'deprecated',
            'fixed',
            'removed',
        ]);

        assertEquals(3, \count(File::allFiles($directory)));

        $this->artisan('changelog:generate --delete-files')
            ->expectsOutput('All done!')
            ->assertExitCode(0);

        File::delete('changelog_.md');
    }

    /** @test */
    public function it_throws_an_exception_if_files_not_found(): void
    {
        $this->expectException(NotFilesFoundException::class);

        $directory = base_path('../../../../tests/Stubs/EmptyDirectory');

        Config::set('changelog-manager.directory', $directory);
        Config::set('changelog-manager.allowed_types', [
            'added',
            'changed',
            'deprecated',
            'fixed',
            'removed',
        ]);

        $this->artisan('changelog:generate --delete-files')
            ->assertExitCode(1);
    }

    /** @test */
    public function it_throws_an_exception_if_there_is_no_directory(): void
    {
        $this->expectException(TypeError::class);

        $this->artisan('changelog:generate --delete-files');
    }

    /** @test */
    public function it_throws_an_exception_if_parsed_file_is_not_array(): void
    {
        $this->expectException(ParsedFileContentIsNotArrayException::class);
        $this->expectExceptionCode(422);

        $directory = base_path('../../../../tests/Stubs/WrongFiles');

        Config::set('changelog-manager.directory', $directory);
        Config::set('changelog-manager.allowed_types', [
            'added',
            'changed',
            'deprecated',
            'fixed',
            'removed',
        ]);

        $this->artisan('changelog:generate')
            ->assertExitCode(1);
    }
}
