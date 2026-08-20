<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator; // <--- ဒီနေရာကို ထည့်ပါ
use App\Models\User;

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
        // Bootstrap 5 Pagination ကို အသုံးပြုရန်
        Paginator::useBootstrapFive(); // <--- ဒီလိုင်းကို ထည့်ပါ

        // ၁။ Admin နှင့် Manager နှစ်ဦးစလုံး Backend သို့ ဝင်ရောက်ခွင့်ပြုမည်
        Gate::define('access-backend', function (User $user) {
            return in_array($user->role, ['Admin', 'Manager']);
        });

        // ၂။ Admin သာလျှင် သီးသန့် လုပ်ဆောင်နိုင်သော နေရာများအတွက်
        Gate::define('admin-only', function (User $user) {
            return $user->role === 'Admin';
        });

        // ၃။ Manager သို့မဟုတ် Admin တာဝန်ကျ Warehouse ဖြစ်မဖြစ် စစ်ဆေးရန်
        Gate::define('manage-warehouse', function (User $user, $warehouse) {
            if ($user->role === 'Admin') {
                return true;
            }

            return $user->role === 'Manager' && $user->warehouse_id === $warehouse->id;
        });
    }
}
