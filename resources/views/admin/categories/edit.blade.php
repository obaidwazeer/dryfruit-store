<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Edit Category - {{ config('app.name') }}
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --admin-primary: #3B2416;
            --admin-primary-light: #6B4226;
            --admin-accent: #C89B3C;
            --admin-background: #F8F5EF;
            --admin-card: #FFFFFF;
            --admin-border: #E8E0D4;
            --admin-text: #2F241D;
            --admin-muted: #7A7068;
            --admin-danger: #B42318;
            --admin-success: #2E7D32;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--admin-background);
            color: var(--admin-text);
            font-family:
                Inter,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
        }

        .category-page {
            min-height: 100vh;
            padding: 40px 20px;
        }

        .category-container {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
        }

        .category-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 30px;
        }

        .category-header-content h1 {
            margin: 0 0 7px;
            color: var(--admin-primary);
            font-size: 30px;
            font-weight: 800;
        }

        .category-header-content p {
            margin: 0;
            color: var(--admin-muted);
            font-size: 14px;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 18px;
            border: 1px solid var(--admin-border);
            border-radius: 8px;
            background: #fff;
            color: var(--admin-primary);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
        }

        .back-button:hover {
            border-color: var(--admin-accent);
        }

        .category-card {
            overflow: hidden;
            border: 1px solid var(--admin-border);
            border-radius: 14px;
            background: var(--admin-card);
            box-shadow: 0 10px 30px rgba(59, 36, 22, 0.06);
        }

        .category-card-header {
            padding: 22px 28px;
            border-bottom: 1px solid var(--admin-border);
            background: #FCFAF7;
        }

        .category-card-header h2 {
            margin: 0;
            color: var(--admin-primary);
            font-size: 18px;
            font-weight: 750;
        }

        .category-card-header p {
            margin: 5px 0 0;
            color: var(--admin-muted);
            font-size: 13px;
        }

        .category-form {
            padding: 30px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            margin-bottom: 8px;
            color: var(--admin-text);
            font-size: 14px;
            font-weight: 650;
        }

        .required {
            color: var(--admin-danger);
        }

        .form-control,
        .form-textarea {
            width: 100%;
            border: 1px solid var(--admin-border);
            border-radius: 8px;
            background: #fff;
            color: var(--admin-text);
            font-size: 14px;
            outline: none;
        }

        .form-control {
            height: 46px;
            padding: 0 14px;
        }

        .form-textarea {
            min-height: 130px;
            padding: 13px 14px;
            resize: vertical;
            line-height: 1.6;
        }

        .form-control:focus,
        .form-textarea:focus {
            border-color: var(--admin-accent);
            box-shadow: 0 0 0 3px rgba(200, 155, 60, 0.12);
        }

        .form-help {
            margin-top: 7px;
            color: var(--admin-muted);
            font-size: 12px;
        }

        .error-message {
            margin-top: 6px;
            color: var(--admin-danger);
            font-size: 12px;
        }

        .validation-alert {
            margin-bottom: 25px;
            padding: 15px 18px;
            border: 1px solid #F1B5B0;
            border-radius: 8px;
            background: #FFF5F4;
            color: var(--admin-danger);
        }

        .validation-alert strong {
            display: block;
            margin-bottom: 7px;
        }

        .validation-alert ul {
            margin: 0;
            padding-left: 18px;
        }

        .image-section {
            display: grid;
            grid-template-columns: 220px 1fr;
            gap: 25px;
            align-items: start;
            padding: 20px;
            border: 1px solid var(--admin-border);
            border-radius: 10px;
            background: #FCFAF7;
        }

        .current-image {
            width: 220px;
            height: 170px;
            overflow: hidden;
            border-radius: 9px;
            background: #F0EBE3;
        }

        .current-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .no-image {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            color: var(--admin-muted);
            font-size: 13px;
        }

        .image-upload-content {
            display: flex;
            flex-direction: column;
        }

        .image-input {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--admin-border);
            border-radius: 8px;
            background: #fff;
            font-size: 13px;
        }

        .new-image-preview {
            display: none;
            width: 180px;
            height: 135px;
            margin-top: 15px;
            overflow: hidden;
            border: 1px solid var(--admin-border);
            border-radius: 9px;
        }

        .new-image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .status-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 18px;
            border: 1px solid var(--admin-border);
            border-radius: 9px;
            background: #FCFAF7;
        }

        .status-content strong {
            display: block;
            margin-bottom: 4px;
            font-size: 14px;
        }

        .status-content span {
            color: var(--admin-muted);
            font-size: 12px;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 48px;
            height: 26px;
            flex-shrink: 0;
        }

        .switch input {
            width: 0;
            height: 0;
            opacity: 0;
        }

        .slider {
            position: absolute;
            inset: 0;
            cursor: pointer;
            border-radius: 30px;
            background: #CFC8C0;
        }

        .slider::before {
            position: absolute;
            content: "";
            width: 20px;
            height: 20px;
            left: 3px;
            top: 3px;
            border-radius: 50%;
            background: #fff;
            transition: 0.2s;
        }

        .switch input:checked + .slider {
            background: var(--admin-success);
        }

        .switch input:checked + .slider::before {
            transform: translateX(22px);
        }

        .form-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid var(--admin-border);
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 0 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 650;
            text-decoration: none;
            cursor: pointer;
        }

        .button-secondary {
            border: 1px solid var(--admin-border);
            background: #fff;
            color: var(--admin-primary);
        }

        .button-primary {
            border: 1px solid var(--admin-primary);
            background: var(--admin-primary);
            color: #fff;
        }

        .button-primary:hover {
            background: var(--admin-primary-light);
        }

        @media (max-width: 767px) {

            .category-page {
                padding: 25px 15px;
            }

            .category-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .category-form {
                padding: 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .full-width {
                grid-column: auto;
            }

            .image-section {
                grid-template-columns: 1fr;
            }

            .current-image {
                width: 100%;
                max-width: 300px;
            }

            .form-actions {
                flex-direction: column-reverse;
            }

            .button {
                width: 100%;
            }
        }
    </style>

</head>

<body>

<div class="category-page">

    <div class="category-container">

        {{-- Page Header --}}
        <div class="category-header">

            <div class="category-header-content">

                <h1>Edit Category</h1>

                <p>
                    Update the category information and storefront image.
                </p>

            </div>

            <a
                href="{{ route('admin.categories.index') }}"
                class="back-button"
            >
                ← Back to Categories
            </a>

        </div>


        <div class="category-card">

            <div class="category-card-header">

                <h2>
                    {{ $category->name }}
                </h2>

                <p>
                    Update the details below and save your changes.
                </p>

            </div>


            <form
                method="POST"
                action="{{ route('admin.categories.update', $category) }}"
                enctype="multipart/form-data"
                class="category-form"
            >

                @csrf

                @method('PUT')


                {{-- Validation --}}
                @if ($errors->any())

                    <div class="validation-alert">

                        <strong>
                            Please correct the following errors:
                        </strong>

                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                    </div>

                @endif


                <div class="form-grid">


                    {{-- Name --}}
                    <div class="form-group">

                        <label
                            for="name"
                            class="form-label"
                        >
                            Category Name
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-control"
                            value="{{ old('name', $category->name) }}"
                            required
                        >

                        @error('name')
                            <div class="error-message">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Slug --}}
                    <div class="form-group">

                        <label
                            for="slug"
                            class="form-label"
                        >
                            Slug
                        </label>

                        <input
                            type="text"
                            id="slug"
                            name="slug"
                            class="form-control"
                            value="{{ old('slug', $category->slug) }}"
                        >

                        @error('slug')
                            <div class="error-message">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Description --}}
                    <div class="form-group full-width">

                        <label
                            for="description"
                            class="form-label"
                        >
                            Description
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            class="form-textarea"
                            rows="5"
                        >{{ old('description', $category->description) }}</textarea>

                        @error('description')
                            <div class="error-message">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Image --}}
                    <div class="form-group full-width">

                        <label class="form-label">
                            Category Image
                        </label>

                        <div class="image-section">


                            {{-- Existing Image --}}
                            <div class="current-image">

                                @if ($category->image_path)

                                    <img
                                        src="{{ Storage::url($category->image_path) }}"
                                        alt="{{ $category->name }}"
                                    >

                                @else

                                    <div class="no-image">
                                        No image uploaded
                                    </div>

                                @endif

                            </div>


                            {{-- Upload New Image --}}
                            <div class="image-upload-content">

                                <input
                                    type="file"
                                    id="image"
                                    name="image"
                                    class="image-input"
                                    accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                >

                                <div class="form-help">
                                    Select a new image only if you want to
                                    replace the current image.
                                    Maximum size: 5 MB.
                                </div>

                                @error('image')
                                    <div class="error-message">
                                        {{ $message }}
                                    </div>
                                @enderror


                                <div
                                    id="newImagePreview"
                                    class="new-image-preview"
                                >

                                    <img
                                        id="newImagePreviewImage"
                                        src=""
                                        alt="New category image preview"
                                    >

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Sort Order --}}
                    <div class="form-group">

                        <label
                            for="sort_order"
                            class="form-label"
                        >
                            Sort Order
                        </label>

                        <input
                            type="number"
                            id="sort_order"
                            name="sort_order"
                            class="form-control"
                            value="{{ old('sort_order', $category->sort_order) }}"
                            min="0"
                        >

                        @error('sort_order')
                            <div class="error-message">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="form-group">

                        <label class="form-label">
                            Category Status
                        </label>

                        <div class="status-row">

                            <div class="status-content">

                                <strong>
                                    Active Category
                                </strong>

                                <span>
                                    Customers can see active categories.
                                </span>

                            </div>

                            <label class="switch">

                                <input
                                    type="checkbox"
                                    name="is_active"
                                    value="1"
                                    @checked(old('is_active', $category->is_active))
                                >

                                <span class="slider"></span>

                            </label>

                        </div>

                    </div>

                </div>


                {{-- Actions --}}
                <div class="form-actions">

                    <a
                        href="{{ route('admin.categories.index') }}"
                        class="button button-secondary"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="button button-primary"
                    >
                        Update Category
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<script>

    const imageInput = document.getElementById('image');

    const previewContainer =
        document.getElementById('newImagePreview');

    const previewImage =
        document.getElementById('newImagePreviewImage');


    imageInput.addEventListener('change', function () {

        const file = this.files[0];


        if (!file) {

            previewContainer.style.display = 'none';

            previewImage.src = '';

            return;
        }


        const allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];


        if (!allowedTypes.includes(file.type)) {

            previewContainer.style.display = 'none';

            previewImage.src = '';

            return;
        }


        const objectUrl =
            URL.createObjectURL(file);


        previewImage.src = objectUrl;

        previewContainer.style.display = 'block';

    });

</script>

</body>
</html>
