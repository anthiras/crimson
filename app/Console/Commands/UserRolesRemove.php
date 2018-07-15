<?php

namespace App\Console\Commands;

use App\Domain\RoleId;
use App\Domain\UserRepository;
use Illuminate\Console\Command;

class UserRolesRemove extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'userroles:remove {email : Email address of user} {role : admin/instructor}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove a role from a user';

    protected $userRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $email = $this->argument('email');
        $role = $this->argument('role');
        $roleId = RoleId::fromName($role);
        $user = $this->userRepository
            ->userByEmail($email)
            ->removeRole($roleId);
        $this->userRepository->save($user);
        $this->info("Removed the ".$role." role from user ".$email);
    }
}
