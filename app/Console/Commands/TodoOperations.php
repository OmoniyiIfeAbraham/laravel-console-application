<?php

namespace App\Console\Commands;

use App\Models\TodoUserModel;
use Illuminate\Console\Command;

use function Termwind\ask;

class TodoOperations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:todo-operations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Select an operation to perform');
        $this->info('1. Register');
        $this->info('2. Login');

        $choice = $this->ask('Enter your choice (1-2)');

        switch($choice) {
            case 1:
                $this->Register();
                break;
            case 2:
                $this->Login();
                break;
            default:
            $this->error('Invalid Option!');
        }
    }
    // register function
    protected function Register()
    {
        $username = $this->ask('Enter username: ');
        $email = $this->ask('Enter email: ');
        $password = $this->ask('Enter password: ');

        TodoUserModel::create([
            'username' => $username,
            'email' => $email,
            'password' => $password
        ]);

        $this->info('Registration Successful');
    }

    protected function Login() {
        $email = $this->ask('Enter email:');
        $password = $this->ask('Enter password:');

        $user = TodoUserModel::where('email', $email)->first();
        if($user) {
            if($user->password === $password) {
                $this->info('Login Successful!');
            } else {
                $this->error('Incorrect Password!');
            }
        } else {
            $this->error('Incorrect Email!');
        }
    }
}
