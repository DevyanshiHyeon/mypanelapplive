<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Nelexa\GPlay\GPlayApps;
use Nelexa\GPlay\Model\AppId;
use Nelexa\GPlay\Exception\GooglePlayException;
use App\Models\Application;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppSuspendMail;
use Nelexa\GPlay\Exception\NotFoundException;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use App\Mail\UpdateMail;
use GuzzleHttp\Client;
use App\Models\Account;

class checkSuspended extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:suspended';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To check app is suspended';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $applications = Application::where('status', 'Published')->select('id', 'name','logo', 'package_name','version')->orderBy('created_at', 'desc')->get();
        foreach ($applications as $application) {
            $gplay = new GPlayApps();
            $packageName = $application->package_name;
            try {
                $appDetails = $gplay->setDefaultLocale('ru')->getAppInfo(new AppId($packageName,'en', 'in'));
                $icon = $appDetails->getIcon();
                $version = $appDetails->getappVersion();
                $old_version = $application->version;
                $app = Application::find($application->id);
                $account_name = Account::find($app->account_id)->name;
                if($version !== $old_version)
                {
                    $data = [
                        'logo' => $application->logo,
                        'app_name' => $application->name,
                        'status' => 'Updated',
                        'package' => $packageName,
                        'account_name' => $account_name
                    ];
                    $application->update(['version' => $version]);
                    Mail::to('hyeonsoft46@gmail.com')->send(new UpdateMail($data));
                }
            } catch (GooglePlayException $e) {
                if ($e instanceof RequestException && $e->getCode() === 404) {
                    echo ($e->getMessage());
                } else {
                    $app = Application::find($application->id);
                    $account_name = Account::find($app->account_id)->name;
                    $data = [
                        'logo' => $application->logo,
                        'app_name' => $application->name,
                        'status' => 'Removed/Suspended',
                        'package' => $packageName,
                        'account_name' => $account_name
                    ];
                    $app->update(['status' => 'suspended']);
                    Mail::to('hyeonsoft46@gmail.com')->send(new AppSuspendMail($data));
                    $error = $e->getMessage();
                    Log::info('Log From Cron: ' . $error);
                }
            }
        }
    }

}
