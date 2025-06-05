<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use App\Models\Announcement;
use App\Models\StoreAnnouncement;
use App\Models\PharmacyStore;
use App\Models\Role;
use Illuminate\Pagination\Paginator;
use Spatie\Permission\Models\Permission;

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
        view()->composer('*', function ($view) {
            $user = Auth::user() ?? null;
            $authEmployee = null;

            // $numberOfStorePermissions = 0;
            // $menuStores = PharmacyStore::get()->keyBy('id');

            if(!empty($user)){
                // $permissionsToCheck = [];
                // foreach($menuStores as $ms) {
                //     $name = 'menu_store.'.$ms->id;
                //     $permissionsToCheck[$ms->id] = $name;
                // }
                
                // $authEmployee = Employee::select('id','firstname', 'lastname','image','initials_random_color')->where('user_id',$user->id)->first();
                // $storeIDs = [];
                // foreach($permissionsToCheck as $pid => $pname) {
                //     if(auth()->user()->can($pname)) {
                //         $numberOfStorePermissions += 1;
                //         $storeIDs[] = $pid;
                //     }
                // }
                // dd($permissionsToCheck);
                // $numberOfStorePermissions = $user->getAllPermissions()
                //     ->whereIn('name', $permissionsToCheck)
                //     ->count();
                // if($user->hasRole('super-admin')) {
                //     $numberOfStorePermissions = count($menuStores);
                // }
            }
            // $userNotifications = $user->unreadNotifications ?? null;
            // $notifications = [
            //     'App\Notifications\AnnouncementNotification' => [],
            //     'App\Notifications\Store\AnnouncementNotification' => []
            // ];
            // if(!empty($userNotifications)) {
            //     foreach($userNotifications as $notification) {
            //         $notifications[$notification->type][$notification->data['announcement']] = $notification->created_at;
            //     }
            // }
            // dd($notifications);
            // $announcements = [
            //     'App\Notifications\AnnouncementNotification' => [],
            //     'App\Notifications\Store\AnnouncementNotification' => []
            // ];
            // $announcements['App\Notifications\AnnouncementNotification'] = Announcement::with('user.employee')->orderBy('id','desc')->get()->keyBy('id')->toArray();
            // if(!empty($storeIDs)) {
            //     $announcements['App\Notifications\Store\AnnouncementNotification'] = StoreAnnouncement::with('user.employee')->whereIn('pharmacy_store_id', $storeIDs)->orderBy('id','desc')->get()->keyBy('id')->toArray();
            // }

            // $menuStoreGroupPermissions = Permission::where('division_name', 'menu_store')->pluck('group_name','name')->all();
            $menuGeneralGroupPermissions = Permission::where('division_name','general')->pluck('group_name','name')->all();
            $menuSettingsGroupPermissions = Permission::where('division_name','system_settings')->pluck('group_name','name')->all();
            // dd($menuSettingsGroupPermissions);
            $view->with('user', $user);
            // dd($menuGeneralGroupPermissions);
            // $view->with('authEmployee', $authEmployee);
            // $view->with('notifications', $notifications);
            // $view->with('announcements', $announcements);
            // $view->with('menuStores', $menuStores);
            // $view->with('menuStoreGroupPermissions', $menuStoreGroupPermissions);
            $view->with('menuGeneralGroupPermissions', $menuGeneralGroupPermissions);
            $view->with('menuSettingsGroupPermissions', $menuSettingsGroupPermissions);
            // $view->with('numberOfStorePermissions', $numberOfStorePermissions);
            Paginator::useBootstrap();
        });
    }
}
