<?php

// app/Providers/SEOServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Services\SEOHelper;

class SEOServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind SEOHelper as singleton
        $this->app->singleton(SEOHelper::class, function ($app) {
            return new SEOHelper();
        });
        
        // Create alias
        $this->app->alias(SEOHelper::class, 'seo');
    }

    public function boot(): void
    {
        // Blade directives cho SEO
        Blade::directive('seo', function ($expression) {
            return "<?php echo app('seo')->renderMeta(); ?>";
        });
        
        Blade::directive('schema', function ($expression) {
            $params = explode(',', $expression);
            $type = trim($params[0], " '\"");
            $data = isset($params[1]) ? $params[1] : '[]';
            return "<?php echo app('seo')->generateSchema('{$type}', {$data}); ?>";
        });
        
        // Lazy loading image directive
        Blade::directive('lazyimage', function ($expression) {
            return "<?php echo '<img src=\"' . {$expression} . '\" loading=\"lazy\" alt=\"\">'; ?>";
        });
        
        // Critical CSS directive
        Blade::directive('criticalcss', function ($file) {
            return "<?php 
                \$path = public_path('css/critical/' . {$file} . '.css');
                if (file_exists(\$path)) {
                    echo '<style>' . file_get_contents(\$path) . '</style>';
                }
            ?>";
        });
        
        // WebP image directive
        Blade::directive('webp', function ($expression) {
            return "<?php 
                \$src = {$expression};
                \$webpSrc = str_replace(['.jpg', '.jpeg', '.png'], '.webp', \$src);
                echo '<picture>';
                echo '<source srcset=\"' . \$webpSrc . '\" type=\"image/webp\">';
                echo '<img src=\"' . \$src . '\" loading=\"lazy\">';
                echo '</picture>';
            ?>";
        });
    }
}

// Đăng ký trong config/app.php
// 'providers' => [
//     // ...
//     App\Providers\SEOServiceProvider::class,
// ],

// Hoặc trong bootstrap/app.php cho Laravel 11
// ->withProviders([
//     App\Providers\SEOServiceProvider::class,
// ])