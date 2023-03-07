<?php

namespace Konigbach\ChangelogManager\Tests;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class AddChangelogCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        View::addLocation(base_path('../../../../resources/views/'));
    }

    /**
     * @test
     * @dataProvider validDataProvider
     */
    public function it_generates_a_file_with_version(
        string $type,
        int $taskNumber,
        string $description
    ): void {
        $directory = base_path('../../../../tests/Stubs/AddChangelogDirectory');
        Config::set('changelog-manager.directory', $directory);
        Config::set('changelog-manager.allowed_types', [
            'added',
            'changed',
            'deprecated',
            'fixed',
            'removed',
        ]);

        $this->artisan('changelog:add')
            ->expectsQuestion('What type of changelog do you want to add?', $type)
            ->expectsQuestion("What's the task number?", $taskNumber)
            ->expectsQuestion("What's the description of the task", $description)
            ->assertExitCode(0);

        $generatedChangelog = File::get("{$directory}/{$taskNumber}.yaml");

        self::assertTrue(Str::contains($generatedChangelog, ['fixed']));
        self::assertTrue(Str::contains($generatedChangelog, ['#123: A task description.']));
    }

    public function validDataProvider(): array
    {
        return [
            'with_final_dot' => ['fixed', 123, 'A task description.'],
            'without_final_dot' => ['fixed', 123, 'A task description'],
        ];
    }
}
