<?php

namespace Botble\Base\Commands;

use Botble\ACL\Models\User;
use Botble\ACL\Repositories\Interfaces\UserInterface;
use Carbon\Carbon;
use EmailHandler;
use Illuminate\Console\Command;

class SendUserBirthdayEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:send_email_user_birthday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild all the user permissions from the users defined roles and the roles defined flags';

    /**
     * @var UserInterface
     */
    protected $userRepository;

    /**
     * RebuildPermissions constructor.
     * @author Sang Nguyen
     * @param UserInterface $userRepository
     */
    public function __construct(UserInterface $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    /**
     * Execute the console command.
     * @author Sang Nguyen
     */
    public function handle()
    {
        $users = $this->userRepository->all();
        foreach ($users as $user) {
            /**
             * @var User $user
             */
            if (acl_is_user_activated($user)) {
                if (!empty($user->dob) && Carbon::parse($user->dob)->diffInDays(Carbon::now()) == 0) {
                    EmailHandler::send(view('bases::emails.birthday', compact('user'))->render(), trans('bases::mail.happy_birthday'), ['to' => $user->email, 'name' => $user->getFullName()]);
                    $this->info('Sent birthday email to user ' . $user->getFullName());
                }
            }
        }
    }
}
