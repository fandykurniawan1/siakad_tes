<?php

use App\Models\Client;
use App\Models\Preference;
use App\Models\Customer\Customer;
use Illuminate\Support\Facades\Config;
use App\Exceptions\ForbiddenPermissionAccessException;

function prefix($str, $length)
{
    return str_pad($str, $length, '0', STR_PAD_LEFT);
}

function excerpt($str, $maxLength = 155, $startPos = 0)
{
    $str = strip_tags($str);
    if (strlen($str) > $maxLength) {
        $excerpt   = substr($str, $startPos, $maxLength);
        $lastSpace = strrpos($excerpt, ' ');
        $excerpt   = substr($excerpt, 0, $lastSpace);
        $excerpt  .= '...';
    } else {
        $excerpt = $str;
    }

    return $excerpt;
}

function isClosure($t)
{
    return is_object($t) && ($t instanceof Closure);
}

function isEmpty($string)
{
    return empty(trim($string));
}

function dmyToYmd($dmyDate)
{
    $date = explode('-', $dmyDate);

    return $date[2] . '-' . $date[1] . '-' . $date[0];
}

function ymdToDmy($dmyDate)
{
    $date = explode('-', $dmyDate);

    return $date[2] . '-' . $date[1] . '-' . $date[0];
}

function thousandSeparator($number, $commas = 0)
{
    return number_format($number, $commas, '.', ',');
}

function noDuplicatesInArr(Array $array)
{
    return count($array) === count(array_flip($array));
}

function space_case($str)
{
    $str = snake_case($str);
    $str = str_replace('_', ' ', $str);
    $str = str_replace('-', ' ', $str);

    return title_case($str);
}

function validateDate($string, $dateFormat = 'Y-m-d')
{
    try {
        return Carbon\Carbon::createFromFormat($dateFormat, $string) !== false;
    } catch(\Exception $e) {
        return false;
    }
}

function validateMonth($string)
{
    $month = intval($string);

    return $month >= 1 && $month <= 12;
}

function checkPermissionTo($permissionCode, $requireAll = false)
{
    if (! userCan($permissionCode, $requireAll)) throw new ForbiddenPermissionAccessException ($permissionCode);

    return true;
}

function userCan($permissionCode, $requireAll = false)
{
    return user()->can($permissionCode, $requireAll);
}

function validationError($message = 'Validation Error!')
{
    while (DB::transactionLevel() > 0) {
        DB::rollBack();
    }

    return redirect()->back()->withInput(request()->except('_token'))->withErrors($message);
}

function swalError($message = 'Validation Error!')
{
    while (DB::transactionLevel() > 0) {
        DB::rollBack();
    }

    return redirect()->back()->withInput(request()->except('_token'))->with('swal_error', $message);
}

function user()
{
    return Auth::user();
}

function lookupExists($table)
{
    return 'exists:' . $table . ',id,client_id,' . clientId() . ',deleted_at,NULL';
}

function headerLogo()
{
    if (!Session::has('header_logo')) {
        Session::put('header_logo', Preference::valueOf('logo') ?: asset('assets/images/logo.png'));
    }

    return Session::get('header_logo');
}

function uploadFile($file, $path, $originalName = false)
{
    if ($originalName === true) {
        $filename = preg_replace('@[^0-9a-z\.\s]+@i', '', $file->getClientOriginalName());
        $filename = str_replace(' ', '-', $filename);
    } elseif ($originalName) {
        $filename = $originalName . '.' . $file->getClientOriginalExtension();
    } else {
        $filename = strtoupper(str_random(10)) . '-' . time() . '.' . $file->getClientOriginalExtension();
    }
    $filePath = Storage::putFileAs($path, $file, $filename, 'public');
    if (in_array($file->getClientOriginalExtension(), ['jpg', 'png', 'gif', 'webp'])) compressImage($filePath);

    return Storage::url($filePath);
}

function deleteFile($fileUrl)
{
    $currentImagePath = explode(url('/') . '/', $fileUrl);
    $currentImagePath = isset($currentImagePath[1]) ? storage_path('app/public/' . $currentImagePath[1]) : null;
    if ($currentImagePath && File::isFile($currentImagePath)) {
        File::delete($currentImagePath);

        return true;
    } else {
        return false;
    }
}

function compressImage($pathToFile, $quality = 60, $savePath = null)
{
    Image::make(Storage::path($pathToFile))->save($savePath, $quality);

    return true;
}

function resizeImage($pathToFile, $width = 500, $height = null, $quality = 60, $savePath = null)
{
    Image::make($pathToFile)->resize($width, $height, function ($constraint) {
        $constraint->aspectRatio();
    })->save($savePath, $quality);

    return true;
}

function getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP)) {
        $ip = $client;
    } elseif(filter_var($forward, FILTER_VALIDATE_IP)) {
        $ip = $forward;
    } else {
        $ip = $remote;
    }

    return $ip;
}

function generateUniqueSlug($model, $title, $excludeId = null)
{
    $slug = $originalSlug = str_slug($title, '-');
    $modelCount = $model::withoutGlobalScopes()->where('slug', $slug)->where('id', '!=', $excludeId)->count();

    $counter = 2;
    while ($modelCount > 0) {
        $slug = $originalSlug . '-' . $counter;
        $modelCount = $model::withoutGlobalScopes()->where('slug', $slug)->where('id', '!=', $excludeId)->count();
        $counter++;
    }

    return $slug;
}

function shortNumberFormat($amount, $precision = 0)
{
    if ($amount < 999) {
        $amountFormat = number_format($amount, $precision, ',', '.');
        $suffix = '';
    } else if ($amount < 999999) {
        $amountFormat = number_format($amount / 1000, $precision, ',', '.');
        $suffix = 'K';
    } else if ($amount < 999999999) {
        $amountFormat = number_format($amount / 1000000, $precision, ',', '.');
        $suffix = 'M';
    } else if ($amount < 999999999999) {
        $amountFormat = number_format($amount / 1000000000, $precision, ',', '.');
        $suffix = 'B';
    } else {
        $amountFormat = number_format($amount / 1000000000000, $precision, ',', '.');
        $suffix = 'T';
    }
    if ($precision > 0) {
        $dotzero = '.' . str_repeat('0', $precision);
        $amountFormat = str_replace($dotzero, '', $amountFormat);
    }

    return $amountFormat . $suffix;
}