<?php

namespace Konigbach\ChangelogManager\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AddChangelogCommand extends Command
{
    protected $signature = 'changelog:add';

    protected $description = 'Adds a changelog task.';

    public function handle(): int
    {
        /** @var array<string> $changelogTypes */
        $changelogTypes = config('changelog-manager.allowed_types');
        $changelogType = $this->askWithCompletion('What type of changelog do you want to add?', $changelogTypes);

        $taskNumber = $this->ask("What's the task number?");
        /** @var string $taskDescription */
        $taskDescription = $this->ask("What's the description of the task");
        if (!Str::endsWith($taskDescription, '.')) {
            $taskDescription .= '.';
        }

        $view = view('changelog-manager::changelog-manager.changelog', compact('changelogType', 'taskNumber', 'taskDescription'));
        $path = config('changelog-manager.directory') . '/' . $taskNumber . '.yaml';
        File::put($path, $view->render());

        return 0;
    }
}
