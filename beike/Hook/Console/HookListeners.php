<?php

namespace Beike\Hook\Console;

use Beike\Hook\Facades\Hook;
use Illuminate\Console\Command;

class HookListeners extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hook:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all hook listeners';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $list  = Hook::getListeners();
        $array = [];

        foreach ($list as $hook => $lister) {
            foreach ($lister as $key => $element) {
                $array[] = [
                    $key,
                    $hook,
                    $element['caller']['class'],
                ];
            }
        }

        $headers = ['Sort', 'Hook name', 'Listener class'];

        $this->table($headers, $array);
    }
}
