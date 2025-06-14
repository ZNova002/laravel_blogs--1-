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
    <div class="main-content">
        <div class="container mt-5">
            <h1>Chỉnh sửa bài viết</h1>
            <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="title" class="form-label">Tiêu đề</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $post->title) }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label">Danh mục</label>
                    <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ $post->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="tags" class="form-label">Thẻ</label>
                    <select class="form-select @error('tags') is-invalid @enderror" name="tags[]" id="tags" multiple>
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->name }}"
                                {{ $post->tags->pluck('name')->contains($tag->name) ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('tags')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Nội dung</label>
                    <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content">{{ old('content', $post->content) }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="thumbnail" class="form-label">Ảnh đại diện</label>
                    <div id="thumbnail-preview">
                        @if ($post->thumbnail)
                            <img src="{{ asset($post->thumbnail) }}" alt="Thumbnail" style="max-width: 200px; max-height: 200px; object-fit: cover;">
                        @endif
                    </div>
                    <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" id="thumbnail" name="thumbnail" accept="image/*">
                    @error('thumbnail')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="images" class="form-label">Ảnh bổ sung</label>
                    <div id="images-preview" class="row">
                        @if ($post->images)
                            @foreach ($post->images as $image)
                                <div class="col-md-3 mb-2">
                                    <img src="{{ asset($image) }}" alt="Image" style="max-width: 100px; max-height: 100px; object-fit: cover;">
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <input type="file" class="form-control @error('images.*') is-invalid @enderror" id="images" name="images[]" multiple accept="image/*">
                    @error('images.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="{{ route('posts.index') }}" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>

    <!-- jQuery trước Summernote -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('summernote/summernote-bs5.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#tags').select2({
                tags: true,
                tokenSeparators: [',']
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

        document.getElementById('thumbnail').addEventListener('change', function (event) {
            let previewDiv = document.getElementById('thumbnail-preview');
            previewDiv.innerHTML = '';

            const file = event.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    let img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '200px';
                    img.style.maxHeight = '200px';
                    img.style.objectFit = 'cover';
                    previewDiv.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('images').addEventListener('change', function (event) {
            let previewDiv = document.getElementById('images-preview');
            previewDiv.innerHTML = '';

            const files = event.target.files;
            if (files.length > 0) {
                Array.from(files).forEach(file => {
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        let colDiv = document.createElement('div');
                        colDiv.className = 'col-md-3 mb-2';

                        let img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.maxWidth = '100px';
                        img.style.maxHeight = '100px';
                        img.style.objectFit = 'cover';

                        colDiv.appendChild(img);
                        previewDiv.appendChild(colDiv);
                    };
                    reader.readAsDataURL(file);
                });
            }
        });
    </script>
</body>
</html>
