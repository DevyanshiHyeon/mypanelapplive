<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Nelexa\GPlay\GPlayApps;
use Nelexa\GPlay\Model\AppId;
use Nelexa\GPlay\Exception\GooglePlayException;
use App\Models\Application;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppCreateMail;
use App\Models\Account;
use App\Mail\AppSuspendMail;

class CheckAppStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:live';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To check app published or not.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $applications = Application::where('status', 'NotPublish')->select('id', 'name', 'package_name')->orderBy('created_at', 'desc')->get();
        foreach ($applications as $application) {
            // Create a new instance of GPlayApps
            $gplay = new GPlayApps();
            // Specify the package name of the app you want to retrieve information for
            $packageName = $application->package_name;
            try {
                // Retrieve the app details using the package name
                $appDetails = $gplay->getAppInfo(new AppId($packageName,'en', 'in'));
                $appName = $appDetails->getName();
                $account_name = $appDetails->getDeveloper()->getName();
                $icon = $appDetails->getIcon();
                $data = [
                    'logo' => $icon->getUrl(),
                    'app_name' => $appName,
                    'status' => 'Live',
                    'package' => $packageName,
                    'account_name' => $account_name,
                ];
                if (isset($appDetails)) {
                    $account = Account::create(['name' => $account_name]);
                    $app = Application::find($application->id);
                    $app->update(['name' => $appName, 'status' => 'published', 'logo' => $icon->getUrl(), 'account_id' => $account->id, 'is_notified' => true]);
                    Mail::to('hyeonsoft46@gmail.com')->send(new AppCreateMail($data));
                }
            } catch (GooglePlayException $e) {
                // $app = Application::find($application->id);
                // $data = [
                //     'logo' => $application->logo,
                //     'app_name' => $application->name,
                //     'status' => 'Removed/Suspended',
                //     'package' => $packageName,
                // ];
                // $app->update(['status' => 'suspended']);
                // Mail::to('hyeonsoft46@gmail.com')->send(new AppSuspendMail($data));
                $error = $e->getMessage();
                Log::info('Log From Cron: ' . $error);
                // Handle any exceptions that occur during the API request
                $error = $e->getMessage();
                Log::info('Log From Cron: ' . $error);
            }
        }
    }
}
