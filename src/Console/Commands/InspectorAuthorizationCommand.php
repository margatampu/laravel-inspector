<?php

namespace MargaTampu\LaravelInspector\Console\Commands;

use Illuminate\Console\Command;
use MargaTampu\LaravelInspector\Models\InsAuth;

class InspectorAuthorizationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inspector:auth {--new : Create new authorization} 
                                            {--name= : Include id to update name of inserted id}
                                            {--refresh= : Include id to refresh token of inserted id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage inspector authorization data using console command.';

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
        if ($this->option('new')) {
            // Add new inspector authorization
            $this->createNew();
        } elseif ($id = $this->option('name')) {
            // Edit name of inspector authorization record
            $this->editName($id);
        } elseif ($id = $this->option('refresh')) {
            // Refresh a new token of inspector authorization record
            $this->refreshToken($id);
        } else {
            // Generate default token value
            $this->createDefault();
        }
    }

    /**
     * Create new inspector authorization
     */
    protected function createNew()
    {
        $this->line('Creating new inspector authorization');

        // Generate token
        $token = str_random(32);

        // Instantiate ins auth model
        $insAuth = new InsAuth();

        // Get name from user
        $insAuth->name  = $this->ask('What is the new inspector autorization name?');
        $insAuth->token = $token;

        $this->info('You will create a new inspector authorization using name: ' . $insAuth->name);

        if ($this->confirm('Do you wish to continue?')) {
            $insAuth->save();

            $this->info('Your authorization code is: ' . $token);
        } else {
            $this->error('Create new inspector authorization canceled');
        }
    }

    /**
     * Edit name of inpesctor authorization
     */
    protected function editName($id)
    {
        if (!$insAuth = InsAuth::where('id', $id)->first()) {
            $this->error('No inspector authorization found!');

            return;
        }

        $this->line('Updating name of inspector authorization');
        $this->info('Name: ' . $insAuth->name);
        if ($this->confirm('Do you wish to continue?')) {
            $insAuth->name  = $this->ask('What is the new name?');
            $insAuth->save();

            $this->info('Your inspector authorization data updated');
        } else {
            $this->error('Update name inspector authorization canceled');
        }
    }

    /**
     * Refresh token of inspector authorization
     */
    protected function refreshToken($id)
    {
        if (!$insAuth = InsAuth::where('id', $id)->first()) {
            $this->error('No inspector authorization found!');

            return;
        }
        $this->line('Refresh token of inspector authorization');
        $this->info('Name: ' . $insAuth->name);
        if ($this->confirm('Do you wish to continue?')) {
            // Generate token
            $token = str_random(32);

            $insAuth->token = $token;
            $insAuth->save();

            $this->info('Your new authorization code is: ' . $token);
        } else {
            $this->error('Refresh token of inspector authorization canceled');
        }
    }

    /**
     * Create default inspector authorization
     */
    protected function createDefault()
    {
        $this->line('Creating default inspector authorization');

        // Generate token
        $token = str_random(32);

        // Instantiate ins auth model
        $insAuth = new InsAuth();

        // Define default value
        $insAuth->name  = 'Default Inspector Authorization';
        $insAuth->token = $token;

        $insAuth->save();
        $this->info('Your authorization code is: ' . $token);
    }
}
