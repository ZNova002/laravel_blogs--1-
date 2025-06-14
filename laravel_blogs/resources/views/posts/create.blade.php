<!DOCTYPE html>
<html>
<head>
    <title>QLBLOGS</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('summernote/summernote-bs5.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
    <style>
        .note-editor, .note-frame {
            font-family: Arial, sans-serif !important;
            background: #fff !important;
            border: 1px solid #ccc !important;
        }
        .note-editable {
            min-height: 400px;
        }
    </style>

    <div class="main-content">
        <div class="container mt-5">
            <h1>Tạo bài viết mới</h1>

            <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Tiêu đề -->
                <div class="mb-3">
                    <label for="title" class="form-label">Tiêu đề</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Danh mục -->
                <div class="mb-3">
                    <label for="category_id" class="form-label">Danh mục</label>
                    <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Nội dung -->
                <div class="mb-3">
                    <label for="content" class="form-label">Nội dung</label>
                    <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content">{{ old('content') }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Ảnh đại diện -->
                <div class="mb-3">
                    <label for="thumbnail" class="form-label">Ảnh đại diện</label>
                    <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" id="thumbnail" name="thumbnail">
                    @error('thumbnail')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div id="thumbnail-preview"></div>
                </div>

                <!-- Tags -->
                <div class="mb-3">
                    <label for="tags" class="form-label">Tags</label>
                    <select name="tags[]" id="tags" class="form-select" multiple>
                        @foreach($tags as $tag)
                            <option value="{{ $tag->name }}" {{ (collect(old('tags'))->contains($tag->name)) ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Ảnh bổ sung -->
                <div class="mb-3">
                    <label for="images" class="form-label">Ảnh bổ sung</label>
                    <input type="file" class="form-control @error('images.*') is-invalid @enderror" id="images" name="images[]" multiple>
                    @error('images.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div id="images-preview" class="d-flex flex-wrap"></div>
                </div>

                <!-- Nút -->
                <button type="submit" class="btn btn-primary">Đăng bài</button>
                <a href="{{ route('posts.index') }}" class="btn btn-secondary">Hủy</a>
            </form>

        </div>
    </div>

    <!-- jQuery Summernote -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('summernote/summernote-bs5.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tags').select2({
                tags: true,
                tokenSeparators: [','],
                placeholder: "Chọn hoặc nhập tag mới",
            });
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-php.min.js"></script>
    <script>
        jQuery(document).ready(function($) {
            if (typeof $.fn.summernote === 'undefined') {
                console.error('Summernote không được tải. Kiểm tra đường dẫn summernote/summernote-bs5.min.js');
                alert('Lỗi: Summernote không được tải. Vui lòng kiểm tra console.');
                return;
            }

            $('#content').summernote({
                height: 400,
                useIframe: true,
                toolbar: [
                    ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video', 'hr']],
                    ['view', ['fullscreen', 'codeview', 'help']],
                ],
                codeviewFilter: true,
                codeviewIframeFilter: true,
                callbacks: {
                    onImageUpload: function(files) {
                        let data = new FormData();
                        data.append('file', files[0]);
                        $.ajax({
                            url: '{{ route("posts.upload-image") }}',
                            method: 'POST',
                            data: data,
                            processData: false,
                            contentType: false,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                $('#content').summernote('insertImage', response.url);
                            },
                            error: function(xhr) {
                                alert('Lỗi khi tải ảnh: ' + (xhr.responseJSON?.error || 'Không thể tải ảnh'));
                            }
                        });
                    }
                }
            });
        });

        document.getElementById('thumbnail').addEventListener('change', function(e) {
            const preview = document.getElementById('thumbnail-preview');
            preview.innerHTML = '';

            const file = e.target.files[0];
            if (file) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.style.maxWidth = '200px';
                img.style.height = 'auto';
                img.style.marginTop = '10px';
                preview.appendChild(img);
            }
        });

        document.getElementById('images').addEventListener('change', function(e) {
            const preview = document.getElementById('images-preview');
            preview.innerHTML = '';

            const files = e.target.files;
            for (let i = 0; i < files.length; i++) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(files[i]);
                img.style.maxWidth = '150px';
                img.style.height = 'auto';
                img.style.margin = '5px';
                preview.appendChild(img);
            }
        });
    </script>
</body>
</html>
