@extends('layouts.dashboard')
    <style>
        .image-preview { max-width: 100px; margin-top: 10px; }
        .form-section { margin-bottom: 30px; }
        .form-section h2 { border-bottom: 2px solid #ddd; padding-bottom: 10px; }
    </style>
@section('content')
<link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('admin/css/style1.css') }}">
<div class="main-content">
    <div class="container">
        <h1>Quản lý Cài đặt Website</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Ảnh -->
            <div class="form-section">
                <h2>Ảnh</h2>
                <div class="row">
                    @foreach (['logo', 'banner1', 'banner2', 'banner3'] as $field)
                        <div class="col-md-3 mb-3">
                            <label for="{{ $field }}" class="form-label">{{ ucfirst($field) }}</label>
                            <input type="file" name="{{ $field }}" id="{{ $field }}" class="form-control">
                            @if (isset($settings[$field]))
                                <img src="{{ asset($settings[$field]->value) }}" class="image-preview" alt="{{ $field }}">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Footer -->
            <div class="form-section">
                <h2>Footer</h2>
                <div class="mb-3">
                    <label for="footer_myblog" class="form-label">MyBlog</label>
                    <input type="text" name="footer_myblog" id="footer_myblog" class="form-control" value="{{ $settings['footer_myblog']->value ?? '' }}" required>
                </div>
                <div class="mb-3">
                    <label for="footer_contact" class="form-label">Liên hệ</label>
                    <textarea name="footer_contact" id="footer_contact" class="form-control" rows="4" required>{{ $settings['footer_contact']->value ?? '' }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Liên kết nhanh</label>
                    @php $quickLinks = isset($settings['quick_links']) ? json_decode($settings['quick_links']->value, true) : [['name'=>'','url'=>''],['name'=>'','url'=>''],['name'=>'','url'=>''],['name'=>'','url'=>'']] @endphp
                    @for ($i = 0; $i < 4; $i++)
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <input type="text" name="quick_links[{{ $i }}][name]" class="form-control" placeholder="Tên liên kết" value="{{ $quickLinks[$i]['name'] ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="quick_links[{{ $i }}][url]" class="form-control" placeholder="URL" value="{{ $quickLinks[$i]['url'] ?? '' }}" required>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Theo dõi tôi -->
            <div class="form-section">
                <h2>Theo dõi tôi</h2>
                @foreach (['facebook', 'x', 'instagram'] as $social)
                    <div class="mb-3">
                        <label class="form-label">{{ ucfirst($social) }}</label>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="url" name="social_{{ $social }}[url]" class="form-control" placeholder="URL" value="{{ isset($settings['social_' . $social]) ? json_decode($settings['social_' . $social]->value, true)['url'] ?? '' : '' }}">
                            </div>
                            <div class="col-md-6">
                                <input type="file" name="social_{{ $social }}[image]" class="form-control">
                                @if (isset($settings['social_' . $social]) && json_decode($settings['social_' . $social]->value, true)['image'])
                                    <img src="{{ asset(json_decode($settings['social_' . $social]->value, true)['image']) }}" class="image-preview" alt="{{ $social }}">
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary">Lưu</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function(e) {
            const preview = this.nextElementSibling;
            if (e.target.files[0]) {
                preview.src = URL.createObjectURL(e.target.files[0]);
                preview.style.display = 'block';
            }
        });
    });
</script>
@endsection
