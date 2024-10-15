<?php

namespace App\Console\Commands;

use App\Models\TodoModel;
use App\Models\TodoUserModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

use function PHPUnit\Framework\isEmpty;
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
        $User = [];

        while (!$exit) {
            if (!$loggedIn) {
                $this->info('Select an operation to perform');
            }
            $this->info('0. Exit');

            if (!$loggedIn) {
                $this->info('1. Register');
                $this->info('2. Login');
                $choice = $this->ask('Enter your choice (0-2)');

                switch ($choice) {
                    case 1:
                        $this->Register();
                        break;
                    case 2:
                        $this->Login($loggedIn, $User);
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
                        $this->Create($User);
                        // Implement Create logic here
                        break;
                    case 5:
                        $this->Read();
                        // Implement Read logic here
                        break;
                    case 6:
                        $this->Update();
                        // Implement Update logic here
                        break;
                    case 7:
                        $this->Delete();
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
    protected function Login(&$loggedIn, &$User)
    {
        $email = $this->ask('Enter email:');
        $password = $this->ask('Enter password:');

        $user = TodoUserModel::where('email', $email)->first();

        if ($user) {
            if (Hash::check($password, $user->password)) {
                $this->info('Login Successful!');
                $loggedIn = true;
                $User = $user;
            } else {
                $this->error('Incorrect Password!');
            }
        } else {
            $this->error('Incorrect Email!');
        }
    }

    protected function Create(&$User)
    {
        $title = $this->ask('Enter title');
        $description = $this->ask('Enter description');
        $id = $User->id;

        $this->info($id);


        TodoModel::create([
            'title' => $title,
            'description' => $description,
            'todo_users_id' => $id
        ]);

        $this->info('Item created successfully!');
    }

    protected function Read()
    {
        $todos = TodoModel::all();

        if ($todos->isEmpty()) {
            $this->info('There are no Items');
            return;
        }

        $this->table(['ID', 'Title', 'Description', 'UserID', 'CreatedAt', 'UpdatedAt'], $todos->toArray());
    }

    protected function Update()
    {
        $this->Read();

        $id = $this->ask('Enter the ID of the Item to Update');
        $item = TodoModel::where('id', $id)->first();

        $this->info($item);

        if (!$item) {
            $this->info('Item not found');
            return;
        }

        $title = $this->ask('Enter title (Leave blank to keep current title) ', $item->title);
        $description = $this->ask('Enter description (Leave blank to keep current description) ', $item->description);

        $item->update([
            'title' => $title ?: $item->title,
            'description' => $description ?: $item->description,
        ]);

        $this->info('Item updated successfully');
    }

    protected function Delete()
    {
        $this->Read();

        $id = $this->ask('Enter the ID of the item you want to delete');
        $item = TodoModel::where('id', $id)->first();

        if (!$item) {
            $this->info('Item not found.');
            return;
        }

        $confirm = $this->ask('Do you really want to delete? (yes/no)');

        if (strtolower($confirm) !== 'yes') {
            return;
        } else {
            $item->delete();

            $this->info('Item deleted Successfully!');
        }
    }
}
