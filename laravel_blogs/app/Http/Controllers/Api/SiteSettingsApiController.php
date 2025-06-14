<?php

namespace App\Http\Controllers\Api;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class SiteSettingsApiController extends Controller
{
    /**
     * Lấy tất cả thiết lập website (Site Settings)
     *
     * - Các giá trị như: quick_links, social_facebook, social_x, social_instagram
     *   sẽ được tự động decode từ JSON về dạng mảng.
     * - Các giá trị còn lại giữ nguyên (string, number,...).
     */
    public function getSettings()
    {
        $settings = SiteSetting::all()->pluck('value', 'key')->map(function ($value, $key) {
            if (in_array($key, ['quick_links', 'social_facebook', 'social_x', 'social_instagram'])) {
                return json_decode($value, true);
            }
            return $value;
        });
        return response()->json(['data' => $settings], 200);
    }

}
