<?php
/**
 * MYMO CMS - Free Laravel CMS
 *
 * @package    mymocms/mymocms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://github.com/mymocms/mymocms
 * @license    MIT
 *
 * Created by The Anh.
 * Date: 5/25/2021
 * Time: 10:05 PM
 */

//require (__DIR__ . '/../../Updater/helpers.php');

if (!defined('MYMO_BASE_PATH')) {
    define('MYMO_BASE_PATH', __DIR__ . '/../..');
}

use Illuminate\Support\Facades\Auth;
use Mymo\Core\Helpers\Breadcrumb;
use Mymo\Core\Models\Config;
use Mymo\Core\Models\Menu;
use Mymo\Core\Models\User;
use Mymo\Core\Models\ThemeConfig;
use Illuminate\Support\Str;

function json_message($message, $status = 'success') {
    header('Content-Type: application/json');
    echo json_encode(['status' => $status, 'message' => $message]);
    exit();
}

/**
 * Get client ip
 *
 * @return string
 * */
function get_client_ip()
{
    // Check Cloudflare support
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        return $_SERVER["HTTP_CF_CONNECTING_IP"];
    }

    // Get ip from server
    return request()->ip();
}

/**
 * Get DB config
 *
 * @param string $key
 * @param mixed $default
 * @return string|array
 * */
function get_config($key, $default = null)
{
    return Config::getConfig($key, $default);
}

function set_config($key, $value)
{
    return Config::setConfig($key, $value);
}

function generate_token($string) {
    $month = date('Y-m');
    $ip = get_client_ip();
    $key = 'ADAsd$#5vSD342354BCVByt&%^23vx';
    return md5($key . $month . $key) . md5($key . $ip . $string);
}

function check_token($token, $string) {
    if (generate_token($string) == $token) {
        return true;
    }
    return false;
}

function sub_words($string, int $words = 20) {
    return Str::words($string, $words);
}

function image_path($url) {
    $img = explode('uploads/', $url);
    if (isset($img[1])) {
        return $img[1];
    }
    
    return $img[0];
}

function is_url($url) {
    if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
        return false;
    }

    return true;
}

function image_url($path) {
    if (is_url($path)) {
        return $path;
    }
    
    if ($path) {
        $storage = Storage::disk('public');
        $file_url = $storage->url($path);
        
        if (file_exists($storage->path($path))) {
            return $file_url;
        }
    }
    
    return asset('mymo/styles/images/thumb-default.png');
}

function logo_url($path) {
    if (empty($path)) {
        return asset('images/logo.png');
    }
    
    return image_url($path);
}

function copyfile_chunked($infile, $outfile) {
    $chunksize = 10 * (1024 * 1024); // 10 Megs
    
    /**
     * parse_url breaks a part a URL into it's parts, i.e. host, path,
     * query string, etc.
     */
    $parts = parse_url($infile);
    $i_handle = fsockopen($parts['host'], 80, $errstr, $errcode, 5);
    $o_handle = fopen($outfile, 'wb');
    
    if ($i_handle == false || $o_handle == false) {
        return false;
    }
    
    if (!empty($parts['query'])) {
        $parts['path'] .= '?' . $parts['query'];
    }
    
    /**
     * Send the request to the server for the file
     */
    $request = "GET {$parts['path']} HTTP/1.1\r\n";
    $request .= "Host: {$parts['host']}\r\n";
    $request .= "User-Agent: Mozilla/5.0\r\n";
    $request .= "Keep-Alive: 115\r\n";
    $request .= "Connection: keep-alive\r\n\r\n";
    fwrite($i_handle, $request);
    
    /**
     * Now read the headers from the remote server. We'll need
     * to get the content length.
     */
    $headers = array();
    while(!feof($i_handle)) {
        $line = fgets($i_handle);
        if ($line == "\r\n") break;
        $headers[] = $line;
    }
    
    /**
     * Look for the Content-Length header, and get the size
     * of the remote file.
     */
    $length = 0;
    foreach($headers as $header) {
        if (stripos($header, 'Content-Length:') === 0) {
            $length = (int)str_replace('Content-Length: ', '', $header);
            break;
        }
    }
    
    /**
     * Start reading in the remote file, and writing it to the
     * local file one chunk at a time.
     */
    $cnt = 0;
    while(!feof($i_handle)) {
        $buf = fread($i_handle, $chunksize);
        $bytes = fwrite($o_handle, $buf);
        if ($bytes == false) {
            return false;
        }
        $cnt += $bytes;
        
        /**
         * We're done reading when we've reached the conent length
         */
        if ($cnt >= $length) break;
    }
    
    fclose($i_handle);
    fclose($o_handle);
    return $cnt;
}

function full_text_wildcards($term) {
    $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
    $term = str_replace($reservedSymbols, '', $term);
    $words = explode(' ', $term);
    
    foreach ($words as $key => $word) {
        if (strlen($word) >= 1) {
            $words[$key] = '+' . $word  . '*';
        }
    }
    
    $searchTerm = implode(' ', $words);
    
    return $searchTerm;
}

function theme_config($code) {
    $config = ThemeConfig::where('code', '=', $code)
        ->first(['content']);
    if ($config) {
        return json_decode($config->content, true);
    }
    
    return false;
}

function menu_info($id) {
    return Menu::where('id', '=', $id)
        ->first(['id', 'name']);
}

function theme_setting($code) {
    $config = ThemeConfig::where('code', '=', $code)
        ->first(['content']);
    
    if ($config) {
        return json_decode($config->content);
    }
    
    return false;
}

function slider_setting($slider_id) {
    $slider = Sliders::find($slider_id);
    if ($slider) {
        return json_decode($slider->content);
    }
    
    return false;
}

function menu_setting($menu_id) {
    try {
        $menu = Menu::find($menu_id);
        if ($menu) {
            return json_decode($menu->content);
        }
    }
    catch (Exception $exception) {
        \Log::error($exception->getMessage());
    }
    return [];
}

function count_unread_notifications() {
    return Auth::user()->unreadNotifications()->count(['id']);
}

function core_path($path = null) {
    if ($path) {
        return __DIR__ . '/../' . $path;
    }

    return __DIR__ . '/../';
}

function user_avatar($user = null) {
    if ($user) {
        if (!is_a($user, User::class)) {
            $user = User::find($user);
        }

        return $user->getAvatar();
    }

    if (Auth::check()) {
        $user = User::find(Auth::user()->id);
        return $user->getAvatar();
    }

    return asset('mymo/styles/images/thumb-default.png');
}

function breadcrumb($name, $add_items = [])
{
    $items = apply_filters($name . '_breadcrumb', []);

    if ($add_items) {
        foreach ($add_items as $add_item) {
            $items[] = $add_item;
        }
    }

    return Breadcrumb::render($name, $items);
}

function combine_pivot($entities, $pivots = [])
{
    // Set array
    $pivotArray = [];
    // Loop through all pivot attributes
    foreach ($pivots as $pivot => $value) {
        // Combine them to pivot array
        $pivotArray += [$pivot => $value];
    }
    // Get the total of arrays we need to fill
    $total = count($entities);
    // Make filler array
    $filler = array_fill(0, $total, $pivotArray);
    // Combine and return filler pivot array with data
    return array_combine($entities, $filler);
}

function path_url(string $url)
{
    if (!is_url($url)) {
        return $url;
    }

    return parse_url($url)['path'];
}

function upload_url($path, $default = null)
{
    if (is_url($path)) {
        return $path;
    }

    $storage = Storage::disk('public');
    if ($storage->exists($path)) {
        return $storage->url($path);
    }

    if ($default) {
        return $default;
    }

    return asset('mymo/styles/images/thumb-default.png');
}

function random_string(int $length = 16)
{
    return Str::random($length);
}

function is_json($string) {
    json_decode($string);
    return json_last_error() === JSON_ERROR_NONE;
}
