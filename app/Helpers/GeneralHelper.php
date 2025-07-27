<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

if (!function_exists('routeController')) {
    /**
     * Automatically register routes for controller methods
     *
     * @param string $prefix Route prefix
     * @param string $controller Controller class name
     * @return void
     */
    function routeController($prefix, $controller)
    {
        $name = str_replace("/",".",$prefix);
        $prefix = trim($prefix, '/').'/';

        if(substr($controller,0,1) != "\\") {
            $controller = "\App\Http\Controllers\\".$controller;
        }

        $exp = explode("\\", $controller);
        $controller_name = end($exp);

        try {
            Route::get($prefix, ['uses' => $controller.'@getIndex', 'as' => $name]);

            $controller_class = new \ReflectionClass($controller);
            $controller_methods = $controller_class->getMethods(\ReflectionMethod::IS_PUBLIC);
            $wildcards = '/{one?}/{two?}/{three?}/{four?}/{five?}';
            
            foreach ($controller_methods as $method) {
                if ($method->class != 'Illuminate\Routing\Controller' && $method->name != 'getIndex') {
                    // GET method
                    if (substr($method->name, 0, 3) == 'get') {
                        $method_name = substr($method->name, 3);
                        $slug = array_filter(preg_split('/(?=[A-Z])/', $method_name));
                        $as = $name.'.'.strtolower(implode('.', $slug));
                        $as = ltrim($as, '.');
                        $slug = strtolower(implode('-', $slug));
                        $slug = ($slug == 'index') ? '' : $slug;
                        Route::get($prefix.$slug.$wildcards, ['uses' => $controller.'@'.$method->name, 'as' => $as]);
                    } 
                    // POST method
                    elseif (substr($method->name, 0, 4) == 'post') {
                        $method_name = substr($method->name, 4);
                        $slug = array_filter(preg_split('/(?=[A-Z])/', $method_name));
                        $as = $name.'.'.strtolower(implode('.', $slug));
                        $as = ltrim($as, '.');
                        $slug = strtolower(implode('-', $slug));
                        Route::post($prefix.$slug.$wildcards, [
                            'uses' => $controller.'@'.$method->name,
                            'as' => $as,
                        ]);
                    }
                    // PUT method
                    elseif (substr($method->name, 0, 3) == 'put') {
                        $method_name = substr($method->name, 3);
                        $slug = array_filter(preg_split('/(?=[A-Z])/', $method_name));
                        $as = $name.'.'.strtolower(implode('.', $slug));
                        $as = ltrim($as, '.');
                        $slug = strtolower(implode('-', $slug));
                        Route::put($prefix.$slug.$wildcards, [
                            'uses' => $controller.'@'.$method->name,
                            'as' => $as,
                        ]);
                    }
                    elseif (substr($method->name, 0, 3) == 'patch') {
                        $method_name = substr($method->name, 3);
                        $slug = array_filter(preg_split('/(?=[A-Z])/', $method_name));
                        $as = $name.'.'.strtolower(implode('.', $slug));
                        $as = ltrim($as, '.');
                        $slug = strtolower(implode('-', $slug));
                        Route::patch($prefix.$slug.$wildcards, [
                            'uses' => $controller.'@'.$method->name,
                            'as' => $as,
                        ]);
                    }
                    // DELETE method
                    elseif (substr($method->name, 0, 6) == 'delete') {
                        $method_name = substr($method->name, 6);
                        $slug = array_filter(preg_split('/(?=[A-Z])/', $method_name));
                        $as = $name.'.'.strtolower(implode('.', $slug));
                        $as = ltrim($as, '.');
                        $slug = strtolower(implode('-', $slug));
                        Route::delete($prefix.$slug.$wildcards, [
                            'uses' => $controller.'@'.$method->name,
                            'as' => $as,
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            // Log the error or handle it appropriately
            Log::error('Route registration error: ' . $e->getMessage());
        }
    }
}

if (! function_exists('format_idr')) {
    function format_idr($val){
        return number_format($val , 0, ',', '.');
    }
}

if (!function_exists('encrypt_custom')) {
    function encrypt_custom($string) {
        $key = config('app.key');
        $iv = substr(hash('sha256', $key), 0, 16);
        $encrypted = openssl_encrypt($string, 'AES-256-CBC', $key, 0, $iv);
        return base64_encode($encrypted);
    }
}

if (!function_exists('decrypt_custom')) {
    function decrypt_custom($string) {
        $key = config('app.key');
        $iv = substr(hash('sha256', $key), 0, 16);
        $decrypted = openssl_decrypt(base64_decode($string), 'AES-256-CBC', $key, 0, $iv);
        return $decrypted;
    }
}