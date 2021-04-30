<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class UserRefreshApiToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:refresh-api-token {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerates a user\'s API token';

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
        /** @var User|null $user */
        $user = User::where(function ($query)
        {
            /** @var \Illuminate\Database\Query\Builder $query */
            $query->where('name', $this->argument('user'))
                ->orWhere('email', $this->argument('user'));
        })->first();
        if (!$user)
        {
            $this->error('Could not find user record.');
            return 1;
        }

        $user->update([
            'api_token' => Str::random(60)
        ]);

        $this->info('API Token: ' . $user->api_token);
    }
}
