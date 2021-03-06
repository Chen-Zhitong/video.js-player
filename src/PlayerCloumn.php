<?php
namespace Encore\Admin\MediaPlayer;
use Encore\Admin\Admin;
class PlayerColumn
{
    public function setupScript($options = [])
    {
        $options = array_merge([
            'language' => config('app.locale'),
            'autoplay' => 'false',
            'videoWidth' => '1280',
            'videoHeight' => '720',
        ], $options);
        $options['style'] = 'padding-left:auto;padding-right:auto;';
        $config = json_encode($options);
        $locale = config('app.locale');
        $script = <<<SCRIPT

mejs.i18n.language('$locale');
var config = $config;
config.success = function (player, node) {
    $(player).closest('.mejs__container').attr('lang', mejs.i18n.language());
};
$('video, audio').mediaelementplayer(config);
$('.modal').on('hidden.bs.modal', function () {

    var playerId = $(this).find('.mejs__container').attr('id');
    var player = mejs.players[playerId];
    if (!player.paused) {
        player.pause();
    }
});
$('.mejs__container').css({'margin-left':'auto', 'margin-right':'auto'});
SCRIPT;
        Admin::script($script);
    }
    public static function video()
    {
        $macro = new static();
        return function ($value, $options = []) use ($macro) {
            $macro->setupScript($options);
            $url = MediaPlayer::getValidUrl($value, array_get($options, 'server'));
            $width = array_get($options, 'videoWidth');
            $height = array_get($options, 'videoHeight');
            return <<<HTML
<a class="btn btn-app grid-open-map" data-toggle="modal" data-target="#video-modal-{$this->getKey()}">
    <i class="fa fa-play"></i> Play
</a>

<div class="modal" id="video-modal-{$this->getKey()}" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
          <span>×</span>
          </button>
          <h4 class="modal-title">Play</h4>
      </div>
      <div class="modal-body">
        <video src="$url" width="{$width}" height="{$height}"></video>
      </div>
    </div>
  </div>
</div>
HTML;
        };
    }
}
