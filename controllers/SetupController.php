<?php

namespace App\Controllers;

use App\Lib\ImgShop;
use App\Lib\SetupService;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Routing\Controller;
use Redirect;
use Request;
use View;

/**
 * Class InstallController
 * @author Laju Morrison <morrelinko@gmail.com>
 * @package App\Controllers
 */
class SetupController extends Controller
{
    protected $setupService;

    public function __construct(SetupService $setupService)
    {
        $this->setupService = $setupService;
    }

    public function install()
    {
        if (Request::isMethod('post')) {
            $input = Request::only(['admin_username', 'admin_password']);

            $validator = \Validator::make(Request::input(), [
                'admin_username' => 'required',
                'admin_password' => 'required'
            ]);

            if ($validator->fails()) {
                return Redirect::refresh()
                    ->withErrors($validator);
            }

            // Run migrations
            \Artisan::call('migrate', ['--force' => true]);

            if (\App::environment('local')) {
                \Artisan::call('db:seed', ['--force' => true]);
            }

            // Create User
            $user = new User();
            $user->username = $input['admin_username'];
            $user->password = Hash::make($input['admin_password']);
            $user->save();

            // Save installation information
            $this->setupService->setInfo([
                'version' => ImgShop::VERSION,
                'created_at' => (new \DateTime())->format('Y-m-d H:i:s'),
                'updated_at' => (new \DateTime())->format('Y-m-d H:i:s'),
            ]);

            // Login User
            Auth::login($user);

            // Allow user to see message... durrr!! :)
            sleep(1);

            // Redirect User to finish page
            return Redirect::route('app.dashboard');
        }

        return View::make('setup.install');
    }

    public function upgrade()
    {
        $info = $this->setupService->getInfo();

        $currentVersion = $info['version'];
        $newVersion = ImgShop::VERSION;

        if (Request::isMethod('post')) {
            if ($this->setupService->executeUpgrade($currentVersion, $newVersion)) {
                return Redirect::route('app.dashboard');
            }
        }

        return View::make('setup.upgrade', [
            'currentVersion' => $currentVersion,
            'newVersion' => $newVersion
        ]);
    }
}