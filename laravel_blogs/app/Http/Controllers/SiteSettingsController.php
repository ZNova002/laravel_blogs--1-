<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class SiteSettingsController extends Controller
{
    // Hiển thị form chỉnh sửa cài đặt
    public function edit()
    {
        $settings = SiteSetting::all()->keyBy('key');
        return view('settings.edit', compact('settings'));
    }

    // Cập nhật tất cả cài đặt
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'logo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'banner1' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'banner2' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'banner3' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'footer_myblog' => 'required|string|max:255',
            'footer_contact' => 'required|string',
            'quick_links.*.name' => 'required|string|max:50',
            'quick_links.*.url' => 'required|string|max:255',
            'social_facebook.url' => 'nullable|url',
            'social_facebook.image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'social_x.url' => 'nullable|url',
            'social_x.image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'social_instagram.url' => 'nullable|url',
            'social_instagram.image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ], [
            'logo.image' => 'Logo phải là ảnh (jpg, png, jpeg).',
            'banner*.image' => 'Banner phải là ảnh (jpg, png, jpeg).',
            'footer_myblog.required' => 'Tên MyBlog là bắt buộc.',
            'quick_links.*.name.required' => 'Tên liên kết là bắt buộc.',
            'social_*.url.url' => 'URL mạng xã hội không hợp lệ.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Xử lý ảnh
        $imageFields = ['logo', 'banner1', 'banner2', 'banner3'];
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('storage/settings'), $filename);
                SiteSetting::updateOrCreate(
                    ['key' => $field],
                    ['value' => 'storage/settings/' . $filename, 'type' => 'url']
                );
            }
        }

        // Footer
        SiteSetting::updateOrCreate(
            ['key' => 'footer_myblog'],
            ['value' => $request->footer_myblog, 'type' => 'text']
        );
        SiteSetting::updateOrCreate(
            ['key' => 'footer_contact'],
            ['value' => $request->footer_contact, 'type' => 'text']
        );

        // Quick Links
        SiteSetting::updateOrCreate(
            ['key' => 'quick_links'],
            ['value' => json_encode($request->quick_links), 'type' => 'json']
        );

        // Social
        $socialFields = ['social_facebook', 'social_x', 'social_instagram'];
        foreach ($socialFields as $field) {
            $socialData = ['url' => $request->input($field . '.url', '')];
            if ($request->hasFile($field . '.image')) {
                $file = $request->file($field . '.image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('storage/settings'), $filename);
                $socialData['image'] = 'storage/settings/' . $filename;
            } else {
                $existing = SiteSetting::where('key', $field)->first();
                $socialData['image'] = $existing ? json_decode($existing->value, true)['image'] ?? '' : '';
            }
            SiteSetting::updateOrCreate(
                ['key' => $field],
                ['value' => json_encode($socialData), 'type' => 'json']
            );
        }

        return redirect()->back()->with('success', 'Cài đặt đã được cập nhật');
    }
}
