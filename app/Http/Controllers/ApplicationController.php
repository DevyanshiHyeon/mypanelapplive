<?php

namespace App\Http\Controllers;


use App\Models\Application;
use App\Models\Account;
use Illuminate\Http\Request;
use Nelexa\GPlay\GPlayApps;
use Nelexa\GPlay\Model\AppId;
use Nelexa\GPlay\Exception\GooglePlayException;
use Nelexa\GPlay\GPlayAppsRequest;
use Google_Client;
use Google\Client;
use Google_Service_AndroidPublisher;
use DataTables;
use App\Mail\AppCreateMail;
// use Mail;
use Illuminate\Support\Facades\Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use GuzzleHttp\Exception\RequestException;
use App\Mail\AppSuspendMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        if(Auth::check()){
            if ($request->ajax()) {
                $applications = Application::where('is_trashed',false)->orderBy('id', 'DESC')->get();
                $data = [];
                $i = 1;
                foreach ($applications as $application) {
                    if ($application->logo == null) {
                        $img = '<img src="https://eu.ui-avatars.com/api/?name=' . $application->name . '?background=random" alt="">';
                    } else {
                        $img = '<a href="https://play.google.com/store/apps/details?id=' . $application->package_name . '&hl=en_US&gl=in" target="_blank"><img src="' . $application->logo . '" alt="" class="img-profile rounded-circle" style="height: 70px;"></a>';
                    }
                    $app_name = '<a href="https://play.google.com/store/apps/details?id=' . $application->package_name . '&hl=en_US&gl=in" target="_blank">' . $application->name . '</a></br>' . $application->package_name . '</br>' . $application->created_at->format('d M Y - H:i');
                    $app_status = $application->status;
                    switch ($app_status) {
                        case 'suspended':
                            $status = '<label class="badge badge-danger">Suspended</label>';
                            break;
                        case 'NotPublish':
                            $status = '<label class="badge badge-warning">NotPublish</label>';
                            break;
                        case 'Published':
                            $status = '<label class="badge badge-success">Published</label>';
                            break;
                        default:
                            break;
                    }
                    $account = Account::find($application->account_id);
                    $all_ac = Application::all();
                    if ($account) {
                        $account_name = '<a href="https://play.google.com/store/apps/developer?id=' .$account->name. '&hl=en_US&gl=in" target="_blank">'.$account->name.'</a>';
                    } else {
                        $account_name = "-";
                    }
                    if($app_status == 'suspended'){
                        $action = '<div class="d-flex"><a href="'.url('application/'.$application->id).'" class="btn btn-primary btn-circle"><i class="far fa-edit"></i></a><a href="javascript:app_trash(' . $application->id . ')" title="Move in Trash" class="btn btn-danger btn-circle mx-2"><i class="fas fa-trash"></i></a><a class="btn btn-success mr-2" href="javascript:publish_app('.$application->id.')">Publish App</a></div>';
                    }else{
                        $action ='<a href="'.url('application/'.$application->id).'" class="btn btn-primary btn-circle"><i class="far fa-edit"></i></a><a href="javascript:app_trash(' . $application->id . ')" title="Move in Trash" class="btn btn-danger btn-circle mx-2"><i class="fas fa-trash"></i></a>';
                    }

                    $data[] = [
                        'check_box' => '<input type="checkbox" class="check-box" name="check[]" value="'.$application->id.'">',
                        'sr_no' => $i++,
                        'img' => $img,
                        'app_name' => $app_name,
                        'account_name' => $account_name,
                        'status' => $status,
                        'action' => $action,
                    ];
                }
                return Datatables::of($data)
                    ->rawColumns(['img', 'status', 'app_name', 'action','check_box','account_name'])
                    ->make(true);
            }
            $notPublishedApp = Application::where('status','NotPublish')->count();
            $publishedApp = Application::where('status','Published')->count();
            $suspendedApp = Application::where('status','suspended')->count();
            return view('application.index',compact('notPublishedApp','publishedApp','suspendedApp'));
        }else{
            return redirect('/')->with(['Error'=> 'Login First']);
        }
    }
    public function create()
    {
        return view('application.create');
    }
    public function store(Request $request)
    {
        if (isset($request->app_id)) {
            $request->validate([
                'name' => 'required',
                'package_name' => 'required|unique:applications,package_name,'.$request->app_id,
            ]);
            $app = Application::find($request->app_id);
            $app->update(['name'=>$request->name,'package_name'=>$request->package_name]);
            return redirect('apps')->with('success', 'Application Updated successfully.');
        } else {
            $request->validate([
                'name' => 'required',
                'package_name' => 'required|unique:applications,package_name',
            ]);
            Application::create($request->all());
            return redirect('apps')->with('success', 'Application Create successfully.');
        }
    }
    public function edit($app_id)
    {
        $app = Application::find($app_id);
        return view('application.create',compact('app'));
    }
    public function app_trash($app_id)
    {
        $app = Application::find($app_id);
        if ($app->first()) {
            $app->update(['is_trashed' => true, 'treshed_at' => now()]);
            return response()->json(['success' => 'App is In trash Now.']);
        }
    }
    public function destroy($app_id)
    {
        $app = Application::find($app_id);
        if ($app->first()) {
            $app->delete();
            return response()->json(['success' => 'Application Delete Successfully.']);
        }
    }
public function publish_app($app_id)
{
    $app = Application::find($app_id);
        if ($app->first()) {
            $app->update(['status' => 'Published']);
            return response()->json(['success' => 'App Published Successfully.']);
        }
}
    public function getAppDetails(Request $request)
    {
        // Create a new instance of GPlayApps
        $gplay = new GPlayApps();
        // Specify the package name of the app you want to retrieve information for
        $packageName = 'com.vip.vpnyyhl';
        try {
            // Retrieve the app details using the package name
            $appDetails = $gplay->getAppInfo(new AppId($packageName,'en', 'in'));
            $account_name = $appDetails->getDeveloper()->getName();
            $version = $appDetails->getappVersion();
            return $appDetails;
            // Check the app status
            return view('app-details', compact('appName', 'appRating'));
        } catch (GooglePlayException $e) {
            // Handle any exceptions that occur during the API request
            return $error = $e->getMessage();
        }
    }
    public function checkPublication()
    {
        $packageName = 'com.google.android.youtube'; // Replace with your app's package name
        $credentialsPath = base_path('public/assets/google/google_play_credentials.json');

        // $client = new Google_Client();
        $client = new Client();
        $client->setAuthConfig($credentialsPath);
        $client->setScopes([Google_Service_AndroidPublisher::ANDROIDPUBLISHER]);

        try {
            $service = new Google_Service_AndroidPublisher($client);
            $appDetails = $service->edits->get($packageName, 'current');
            $status = $appDetails->getAppEdit()->getPublishState();

            if ($status === 'published') {
                echo "The application is published on the Play Store.";
            } else {
                echo "The application is not published on the Play Store.";
            }
        } catch (\Exception $e) {
            echo "An error occurred: " . $e->getMessage();
        }
    }
    public function app_check(Request $request)
    {
        foreach ($request->check as $value) {
            $application = Application::find($value);
            $gplay = new GPlayApps();
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
                    'account_name' => $account_name
                ];
                if (isset($appDetails)) {
                    $account = Account::updateOrCreate(['name' => $account_name]);
                    $app = Application::find($application->id);
                    $app->update(['name' => $appName, 'status' => 'published', 'logo' => $icon->getUrl(), 'account_id' => $account->id, 'is_notified' => true]);
                    Mail::to('hyeonsoft46@gmail.com')->send(new AppCreateMail($data));
                }
            } catch (GooglePlayException $e) {
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
                // Handle any exceptions that occur during the API request
                $error = $e->getMessage();
                Log::info('Log From Cron: ' . $error);
            }
        }
        return redirect()->back();
    }
}
