<?php

namespace MargaTampu\LaravelInspector\Console\Commands;

use App\User;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use MargaTampu\LaravelInspector\InsLog;
use MargaTampu\LaravelInspector\InsModel;
use MargaTampu\LaravelInspector\InsRequest;
use Illuminate\Support\Facades\Log;

class InspectorTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inspector:test {--all : Run all modules}
                                            {--model : Run model module}
                                            {--log : Run log module}
                                            {--request : Run request module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Used to run a simple test for all available modules.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line('Test inspector modules');

        if (config('queue.default') != 'sync') {
            $this->error('Please change queue config to sync');

            return;
        }

        // Run test for model module
        if ($this->option('model') || $this->option('all')) {
            $this->modelTest();
        }

        // Run test for log module
        if ($this->option('log') || $this->option('all')) {
            $this->logTest();
        }

        // Run test for request module
        if ($this->option('request') || $this->option('all')) {
            $this->requestTest();
        }
    }

    /**
     * Run model module
     */
    public function modelTest()
    {
        // Add delay for display effect
        sleep(1);

        // Return if inspector model disable
        if (!$config = config('inspector.enableModelInspector')) {
            $this->info('Your model config: false');

            return;
        }

        // Check model existence
        if (!class_exists('App\User')) {
            $this->info('App\User model not found');

            return;
        }

        // Count ins model current record count
        $insModelCountBefore = InsModel::count();

        /*************** Inserting ***************/
        // Check record count before factory
        $userCountBeforeFactory = User::count();

        factory('App\User')->create();

        // Check record count after factory
        $userCountAfterFactory = User::count();

        /*************** Updating ***************/
        // Check record count before update
        $userCountBeforeUpdate = User::count();

        $user       = User::latest()->first();
        $user->name = 'Changed name';
        $user->save();

        // Check record count after update
        $userCountAfterUpdate = User::count();

        // Count ins model record count after 2 actions executed
        $insModelCountAfter = InsModel::count();

        // User Record in database
        if (!(($userCountBeforeFactory + 1 == $userCountAfterFactory)
        && ($userCountBeforeUpdate == $userCountAfterUpdate))) {
            $this->error(json_decode('"\u2717"') . '   User Record Failed');

            return;
        }

        // Ins Model in database
        if ($insModelCountBefore + 2 == $insModelCountAfter) {
            $this->info(json_decode('"\u2713"') . '   Inspector Model Success');
        } else {
            $this->error(json_decode('"\u2717"') . '   Inspector Model Failed');
        }

        // Add delay for display effect
        sleep(1);
    }

    /**
     * Run log module
     */
    public function logTest()
    {
        // Add delay for display effect
        sleep(1);

        // Return if inspector log disable
        if (!$config = config('inspector.enableLogInspector')) {
            $this->info('Your log config: false');

            return;
        }

        // Count ins log current record count
        $insLogCountBefore = InsLog::count();

        // Insert 8 types of log without context
        Log::emergency('This is an emergency');
        Log::alert('This is an alert');
        Log::critical('This is a critical');
        Log::error('This is an error');
        Log::warning('This is a warning');
        Log::notice('This is a notice');
        Log::info('This is an info');
        Log::debug('This is a debug');

        // Insert 8 types of log without exceptions context
        Log::emergency('This is an emergency', ['context' => 'This is just a simple context']);
        Log::alert('This is an alert', ['context' => 'This is just a simple context']);
        Log::critical('This is a critical', ['context' => 'This is just a simple context']);
        Log::error('This is an error', ['context' => 'This is just a simple context']);
        Log::warning('This is a warning', ['context' => 'This is just a simple context']);
        Log::notice('This is a notice', ['context' => 'This is just a simple context']);
        Log::info('This is an info', ['context' => 'This is just a simple context']);
        Log::debug('This is a debug', ['context' => 'This is just a simple context']);

        // Insert 8 types of log with exceptions context
        Log::emergency('This is an emergency', ['exception' => 'This is just a simple exception']);
        Log::alert('This is an alert', ['exception' => 'This is just a simple exception']);
        Log::critical('This is a critical', ['exception' => 'This is just a simple exception']);
        Log::error('This is an error', ['exception' => 'This is just a simple exception']);
        Log::warning('This is a warning', ['exception' => 'This is just a simple exception']);
        Log::notice('This is a notice', ['exception' => 'This is just a simple exception']);
        Log::info('This is an info', ['exception' => 'This is just a simple exception']);
        Log::debug('This is a debug', ['exception' => 'This is just a simple exception']);

        // Count ins log record count after 24 actions executed
        $insLogCountAfter = InsLog::count();

        // Ins Log in database
        if ($insLogCountBefore + 24 == $insLogCountAfter) {
            $this->info(json_decode('"\u2713"') . '   Inspector Log Success');
        } else {
            $this->error(json_decode('"\u2717"') . '   Inspector Log Failed');
        }

        // Add delay for display effect
        sleep(1);
    }

    /**
     * Run request module
     */
    public function requestTest()
    {
        // Add delay for display effect
        sleep(1);

        // Return if inspector request disable
        if (!$config = config('inspector.enableRequestInspector')) {
            $this->info('Your request config: false');

            return;
        }

        // Count ins request current record count
        $insRequestCountBefore = InsRequest::count();

        // Visit main page using guzzle client
        $client = new Client();
        $client->get(url('/'));

        // Add delay to give a time for guzzle to visit web
        sleep(3);

        // Count ins request record count after 1 action executed
        $insRequestCountAfter = InsRequest::count();

        // Ins Request in database
        if ($insRequestCountBefore + 1 == $insRequestCountAfter) {
            $this->info(json_decode('"\u2713"') . '   Inspector Request Success');
        } else {
            $this->error(json_decode('"\u2717"') . '   Inspector Request Failed');
        }

        // Add delay for display effect
        sleep(1);
    }
}
