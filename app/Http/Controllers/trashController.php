<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Models\Application;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;

class trashController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check()) {
            if ($request->ajax()) {
                $applications = Application::where('is_trashed', true)->orderBy('id', 'DESC')->get();
                $data = [];
                $i = 1;
                foreach ($applications as $application) {
                    if ($application->logo == null) {
                        $img = '<img src="https://eu.ui-avatars.com/api/?name=' . $application->name . '?background=random" alt="">';
                    } else {
                        $img = '<a href="https://play.google.com/store/apps/details?id=' . $application->package_name . '" target="_blank"><img src="' . $application->logo . '" alt="" class="img-profile rounded-circle" style="height: 70px;"></a>';
                    }
                    $app_name = '<a href="https://play.google.com/store/apps/details?id=' . $application->package_name . '" target="_blank">' . $application->name . '</a></br>' . $application->package_name . '</br>' . $application->created_at->format('d M Y - H:i');
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
                        $account_name = $account->name;
                    } else {
                        $account_name = "-";
                    }
                    $action = '<a class="btn btn-primary btn-circle mr-2" href="javascript:restore_app(' . $application->id . ')" title="Restore Application"><i class="fas fa-arrow-left"></i></a><a href="javascript:delete_app(' . $application->id . ')" title="Delete Application" class="btn btn-danger btn-circle"><i class="fas fa-trash"></i></a>';
                    $data[] = [
                        'sr_no' => $i++,
                        'img' => $img,
                        'app_name' => $app_name,
                        'account_name' => $account_name,
                        'status' => $status,
                        'action' => $action,
                    ];
                }
                return Datatables::of($data)
                    ->rawColumns(['img', 'status', 'app_name', 'action'])
                    ->make(true);
            }
            return view('trash.index');
        } else {
            return redirect('/')->with(['Error' => 'Login First']);
        }
    }
    public function restore($app_id)
    {
        $app = Application::find($app_id);
        if ($app->first()) {
            $app->update(['is_trashed' => false, 'treshed_at' => null]);
            return response()->json(['success' => 'App Restore Successfully.']);
        }
    }
}
