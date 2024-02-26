<?php

namespace App\Providers;

use App\Models\Notifications;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void
	{
		//
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		view()->composer('includes.header', function ($view) {
			$notifications = Notifications::with(['commenter', 'posts'])
				->where('user_id', auth()->id())
				->orderBy('created_at', 'desc')
				->get();

			$view->with('notifications', $notifications);
		});
	}
}
