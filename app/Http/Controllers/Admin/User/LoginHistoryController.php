<?php

namespace App\Http\Controllers\Admin\User;

use App\Models\LoginHistory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class LoginHistoryController extends Controller
{
	public function index(Request $request)
	{
		$totalData = LoginHistory::count();
		$loginSuccess = LoginHistory::where('status', 1)->count();
		$loginFailed = LoginHistory::where('status', 0)->count();

		if ($request->ajax()) {
			$data = LoginHistory::latest();

			return DataTables::of($data)
				->addColumn('status', function ($data) use ($request) {
					return $data->status === 0 ? '<span class="badge bg-danger">Failed</span>' : '<span class="badge bg-success">Success</span>';
				})
				->addColumn('created_at', function ($data) use ($request) {
					return \Carbon\Carbon::parse($data->created_at)->format('d-m-Y H:i:s');
				})
				// ->addColumn('action', function ($data) use ($request) {
				// 	$id = $data->id;
				// 	$link = $request->url() . '/' . $id;
				// 	return '
				// 		<a href="" data-delete-url="' . $link . '" class="btn btn-danger btn-sm delete-data" data-bs-toggle="modal" data-bs-target="#deleteModal" title="Delete"><span class="bi bi-trash-fill"></span></a>
				// 	';
				// })
				->rawColumns(['status', 'action'])
				->make(true);
		}

		return view('admin.users.login-history', compact('totalData', 'loginSuccess', 'loginFailed'));
	}

	public function destroy()
	{
		$dataCount = LoginHistory::count();

		if ($dataCount <= 10) {
			LoginHistory::truncate();

			return to_route('login-history.index')->with('danger', 'The all login history data has been deleted!');
		} else {
			$loginHistory = LoginHistory::orderBy('id', 'desc')->limit(10)->get();

			foreach ($loginHistory as $history) {
				$history->delete();
			}
		}

		return to_route('login-history.index')->with('danger', 'The last 10 login history data has been deleted!');
	}
}
