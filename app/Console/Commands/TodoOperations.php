<?php

namespace App\Console\Commands;

use App\Models\TodoUserModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

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
        $exit = false;
        $loggedIn = false;

        while (!$exit) {
            $this->info('0. Exit');

            if (!$loggedIn) {
                $this->info('Select an operation to perform');
                $this->info('1. Register');
                $this->info('2. Login');
                $choice = $this->ask('Enter your choice (0-2)');

                switch ($choice) {
                    case 1:
                        $this->Register();
                        break;
                    case 2:
                        $this->Login($loggedIn);
                        break;
                    case 0:
                        $this->info('Exiting...');
                        $exit = true;
                        break;
                    default:
                        $this->error('Invalid Option!');
                        break;
                }
            } else {
                $this->info('Select a Todo Operation to perform');
                $this->info('4. Create');
                $this->info('5. Read');
                $this->info('6. Update');
                $this->info('7. Delete');
                $choice = $this->ask('Enter your choice (0, 4-7)');

                switch ($choice) {
                    case 4:
                        $this->info('Create operation');
                        // Implement Create logic here
                        break;
                    case 5:
                        $this->info('Read operation');
                        // Implement Read logic here
                        break;
                    case 6:
                        $this->info('Update operation');
                        // Implement Update logic here
                        break;
                    case 7:
                        $this->info('Delete operation');
                        // Implement Delete logic here
                        break;
                    case 0:
                        $this->info('Exiting...');
                        $exit = true;
                        break;
                    default:
                        $this->error('Invalid Option!');
                        break;
                }
            }

            if (!$exit && !$loggedIn) {
                $continue = $this->ask('Do you want to continue (yes/no)?');
                if (strtolower($continue) !== 'yes') {
                    $exit = true;
                }
            }
        }

        return 0;
    }

    // Register function
    protected function Register()
    {
        $username = $this->ask('Enter username: ');
        $email = $this->ask('Enter email: ');
        $password = $this->ask('Enter password: ');

        TodoUserModel::create([
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password)
        ]);

        $this->info('Registration Successful');
    }

    // Login function
    protected function Login(&$loggedIn)
    {
        $email = $this->ask('Enter email:');
        $password = $this->ask('Enter password:');

        $user = TodoUserModel::where('email', $email)->first();

        if ($user) {
            if (Hash::check($password, $user->password)) {
                $this->info('Login Successful!');
                $loggedIn = true;
            } else {
                $this->error('Incorrect Password!');
            }
        } else {
            $this->error('Incorrect Email!');
        }
    }

    protected function Create() {
        
    }
}
