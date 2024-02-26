<?php

namespace App\Http\Controllers;

use App\Models\Notifications;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
	public function markAsRead(string $id)
	{
		$notification = Notifications::with(['posts'])->findOrFail($id);
		$notification->is_read = true;
		$notification->save();

		return redirect()->route('post', $notification->posts->slug);
	}

	public function readAll()
	{
		$userId = Auth::user()->id;

		Notifications::where('user_id', $userId)->update([
			'is_read' => true
		]);

		return redirect()->back();
	}

	public function destroyAllNotifications()
	{
		$userId = Auth::user()->id;

		Notifications::where('user_id', $userId)->delete();

		return redirect()->back();
	}

	public function destroy(string $id)
	{
		$notification = Notifications::findOrFail($id);

		$notification->delete();

		return redirect()->back();
	}
}
