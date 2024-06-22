<?php

namespace Plugin\Clarity;

use Beike\Admin\Http\Resources\PluginResource;
use Beike\Plugin\Plugin;

class Bootstrap
{
    /**
     * @return void
     */
    public function boot(): void
    {
        $this->addScript();
    }

    public function addScript(): void
    {
//        $pluginResource = (new PluginResource($plugin))->jsonSerialize();
//        $version = $plugin->getVersion();
        add_hook_blade('layout.header.code', function ($callback, $output, $data) {
            $script = plugin_setting('clarity.value') ?? '';

            // $script = <<<EOF
            // <script type="text/javascript">
            //     (function(c,l,a,r,i,t,y){
            //         c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
            //         t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
            //         y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
            //     })(window, document, "clarity", "script", "m12gb2u3dy");
            // </script>
            // EOF;
            $logs = <<<EOF
             <script type="text/javascript">
                 console.log(`clarity plugin enabled`)
            </script>
            EOF;
            return $output . $script . $logs;
        });
    }
}
