<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\User;
use Illuminate\Validation\ValidationException;

class UserCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create  {--username=} {--email=} {--password=} {--is-admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

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
        $userName = $this->hasOption('username') ? $this->option('username') : $this->ask('User name');
        if (!$userName)
        {
            $this->error('User name is required.');
            return 1;
        }
    
        $email = $this->hasOption('email') ? $this->option('email') : $this->ask('Email address');
        if (!$email)
        {
            $this->error('Email address is required.');
            return 1;
        }
    
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
    
        $data = [
            'name' => $userName,
            'email' => $email,
            'password' => $password,
            'is_admin' => (bool)($this->hasOption('is-admin') ? $this->option('is-admin') : $this->confirm('Is admin'))
        ];
        try
        {
            Validator::validate($data, [
                'name'     => ['required', 'string', 'max:255', 'unique:users'],
                'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string'],
            ]);
        }
        catch (ValidationException $e)
        {
            $this->error('Validation failed: ');
            foreach ($e->errors() as $error)
            {
                $this->error($error[0]);
            }
            return 1;
        }
    
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'api_token' => Str::random(60),
            'is_admin' => $data['is_admin']
        ]);
        
        if ($user->is_admin)
        {
            $user->admin()->create();
        }
    
        $this->info('API Token: ' . $user->api_token);
    }
}
