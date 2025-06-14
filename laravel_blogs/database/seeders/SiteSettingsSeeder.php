<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'logo', 'value' => '/storage/settings/logo.png', 'type' => 'url'],
            ['key' => 'banner1', 'value' => '/storage/settings/banner1.jpg', 'type' => 'url'],
            ['key' => 'banner2', 'value' => '/storage/settings/banner2.jpg', 'type' => 'url'],
            ['key' => 'banner3', 'value' => '/storage/settings/banner3.jpg', 'type' => 'url'],
            ['key' => 'footer_myblog', 'value' => 'MyBlog', 'type' => 'text'],
            ['key' => 'footer_contact', 'value' => 'Email: contact@myblog.com | ĐT: 0123 456 789 | Địa chỉ: TP.HCM, Việt Nam', 'type' => 'text'],
            ['key' => 'quick_links', 'value' => json_encode([
                ['name' => 'HOME', 'url' => '/'],
                ['name' => 'BLOG', 'url' => '/blog'],
                ['name' => 'ABOUT', 'url' => '/about'],
                ['name' => 'CONTACT', 'url' => '/contact'],
            ]), 'type' => 'json'],
            ['key' => 'social_facebook', 'value' => json_encode(['url' => 'https://facebook.com/myblog', 'image' => '/storage/settings/facebook.png']), 'type' => 'json'],
            ['key' => 'social_x', 'value' => json_encode(['url' => 'https://x.com/myblog', 'image' => '/storage/settings/x.png']), 'type' => 'json'],
            ['key' => 'social_instagram', 'value' => json_encode(['url' => 'https://instagram.com/myblog', 'image' => '/storage/settings/instagram.png']), 'type' => 'json'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
