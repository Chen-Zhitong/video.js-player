<?php
namespace Sikoay\Admin\VideoJsPlayer;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Column;
use Encore\Admin\Show\Field;
use Illuminate\Support\ServiceProvider;

class VideoJsPlayerServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(VideoJsPlayer $extension)
    {
        if (! VideoJsPlayer::boot()) {
            return ;
        }
        if ($this->app->runningInConsole() && $assets = $extension->assets()) {

            $this->publishes(
                [$assets => public_path('vendor/laravel-admin-ext/video-js-player')],
                'laravel-admin-video-js-player'
            );

        }
        Admin::booting(function () {
            Admin::js('vendor/laravel-admin-ext/video-js-player/node_modules/video.js/dist/video.js');
            Admin::css('vendor/laravel-admin-ext/video-js-player/node_modules/video.js/dist/video-js.css');
            // Field::macro('video', PlayerField::video());
            // Field::macro('audio', PlayerField::audio());
            Column::extend('video', PlayerColumn::video());
            Column::extend('audio', PlayerColumn::audio());
        });
    }
}
