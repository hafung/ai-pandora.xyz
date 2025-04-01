<?php

namespace App\Providers;

use App\Services\AI\AiService;
use EasyWeChat\Factory;
use EasyWeChat\OfficialAccount\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {

        $this->app->singleton(AiService::class);


        $this->app->singleton(Application::class, function ($app) {
            $config = [
                'app_id' => 'wx45fdgd98', // todo 改成自己的, 这里写死自己用方便 或使用env config
                'secret' => 'ee2ef5810621dfg116bedc796e297c8',// todo 改成自己的, 这里写死自己用方便 或使用env config
                'token' => 'aipandora',// todo 改成自己的, 这里写死自己用方便 或使用env config
                'aes_key' => 'roi7RdjhStdfdgsOsC70VmpZaNCctcNyg',// todo 改成自己的, 这里写死自己用方便 或使用env config
                'http' => [
                    'max_retries' => 1,
                    'retry_delay' => 500,
                    'timeout' => 60.0
                ],
            ];

            return Factory::officialAccount($config);
        });

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        //
    }
}
