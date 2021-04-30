<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserUpdatePassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:update-password {user} {--password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates a user\'s password';

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
        if (is_null($this->option('password')))
        {
            $password = $this->secret('Password');
            if (!$password)
            {
                $this->error('Password is required.');
                return 1;
            }

        }
        else if (empty($this->option('password')))
        {
            // Generate a random password if we didn't specify one
            $password = Str::random(60);
        }
        else
        {
            // Use passed pa
            $password = $this->option('password');
        }

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
            'password' => Hash::make($password)
        ]);
    }
}
