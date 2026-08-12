@extends('admin.layouts.app')

@section('title', 'Create Product')

@section('content')

    <div class="container-fluid">

        {{-- Page Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">

            <div>
                <h4 class="mb-1">
                    Create Product
                </h4>

                <p class="text-muted mb-0">
                    Add a new dry fruit product to your catalog.
                </p>
            </div>

            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">

                <i class="bx bx-arrow-back me-1"></i>

                Back to Products

            </a>

        </div>


        {{-- Validation Errors --}}
        @if ($errors->any())

            <div class="alert alert-danger">

                <div class="fw-semibold mb-2">
                    Please correct the following errors:
                </div>

                <ul class="mb-0">

                    @foreach ($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach

                </ul>

            </div>

        @endif


        <form method="POST" action="{{ route('admin.products.store') }}">

            @csrf


            <div class="row g-4">


                {{-- Main Product Information --}}
                <div class="col-lg-8">

                    <div class="card">

                        <div class="card-header">

                            <h5 class="mb-0">
                                Product Information
                            </h5>

                        </div>


                        <div class="card-body">


                            {{-- Product Name --}}
                            <div class="mb-4">

                                <label for="name" class="form-label fw-semibold">
                                    Product Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text" id="name" name="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    placeholder="e.g. Premium Almonds" required>

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Slug --}}
                            <div class="mb-4">

                                <label for="slug" class="form-label fw-semibold">
                                    Slug
                                </label>

                                <input type="text" id="slug" name="slug"
                                    class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}"
                                    placeholder="premium-almonds">

                                <div class="form-text">
                                    Leave blank to automatically generate the slug.
                                </div>

                                @error('slug')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Short Description --}}
                            <div class="mb-4">

                                <label for="short_description" class="form-label fw-semibold">
                                    Short Description
                                </label>

                                <textarea id="short_description" name="short_description" rows="3" maxlength="500"
                                    class="form-control @error('short_description') is-invalid @enderror"
                                    placeholder="A short description of this product...">{{ old('short_description') }}</textarea>

                                <div class="form-text">
                                    Maximum 500 characters.
                                </div>

                                @error('short_description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Description --}}
                            <div class="mb-2">

                                <label for="description" class="form-label fw-semibold">
                                    Product Description
                                </label>

                                <textarea id="description" name="description" rows="7" maxlength="10000"
                                    class="form-control @error('description') is-invalid @enderror"
                                    placeholder="Write the detailed product description...">{{ old('description') }}</textarea>

                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Product Settings --}}
                <div class="col-lg-4">

                    {{-- Categories --}}
                    <div class="card mb-4">

                        <div class="card-header">

                            <h5 class="mb-0">
                                Categories
                            </h5>

                        </div>


                        <div class="card-body">

                            <label for="categories" class="form-label fw-semibold">
                                Product Categories
                                <span class="text-danger">*</span>
                            </label>


                            @if ($categories->isEmpty())

                                <div class="alert alert-warning mb-0">

                                    No active categories are available.

                                    <a href="{{ route('admin.categories.create') }}" class="fw-semibold">
                                        Create a category
                                    </a>

                                    first.

                                </div>
                            @else
                                <select id="categories" name="categories[]"
                                    class="form-select @error('categories') is-invalid @enderror" multiple size="6"
                                    required>

                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected(in_array($category->id, old('categories', [])))>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach

                                </select>

                                <div class="form-text">
                                    Hold Ctrl while clicking to select multiple categories.
                                </div>

                                @error('categories')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror


                                @error('categories.*')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror

                            @endif

                        </div>

                    </div>


                    {{-- Product Status --}}
                    <div class="card mb-4">

                        <div class="card-header">

                            <h5 class="mb-0">
                                Product Settings
                            </h5>

                        </div>


                        <div class="card-body">


                            {{-- Status --}}
                            <div class="mb-4">

                                <label for="status" class="form-label fw-semibold">
                                    Status
                                    <span class="text-danger">*</span>
                                </label>

                                <select id="status" name="status"
                                    class="form-select @error('status') is-invalid @enderror" required>

                                    <option value="draft" @selected(old('status', 'draft') === 'draft')>
                                        Draft
                                    </option>

                                    <option value="active" @selected(old('status') === 'active')>
                                        Active
                                    </option>

                                    <option value="archived" @selected(old('status') === 'archived')>
                                        Archived
                                    </option>

                                </select>

                                @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Featured --}}
                            <div class="mb-4">

                                <div class="form-check form-switch">

                                    <input class="form-check-input" type="checkbox" role="switch" id="is_featured"
                                        name="is_featured" value="1" @checked(old('is_featured'))>

                                    <label class="form-check-label fw-semibold" for="is_featured">
                                        Featured Product
                                    </label>

                                </div>

                                <div class="form-text">
                                    Featured products can appear in promotional sections.
                                </div>

                            </div>


                            {{-- Sort Order --}}
                            <div>

                                <label for="sort_order" class="form-label fw-semibold">
                                    Sort Order
                                </label>

                                <input type="number" id="sort_order" name="sort_order"
                                    class="form-control @error('sort_order') is-invalid @enderror"
                                    value="{{ old('sort_order', 0) }}" min="0">

                                <div class="form-text">
                                    Lower numbers appear first.
                                </div>

                                @error('sort_order')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>


                    {{-- Action Card --}}
                    <div class="card">

                        <div class="card-body">

                            <div class="d-grid gap-2">

                                <button type="submit" class="btn btn-primary">

                                    <i class="bx bx-check me-1"></i>

                                    Create Product

                                </button>


                                <a href="{{ route('admin.products.index') }}" class="btn btn-light">

                                    Cancel

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>

@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');

            if (!nameInput || !slugInput) {
                return;
            }

            let slugManuallyEdited = slugInput.value.length > 0;

            slugInput.addEventListener('input', function() {
                slugManuallyEdited = this.value.length > 0;
            });

            nameInput.addEventListener('input', function() {

                if (slugManuallyEdited) {
                    return;
                }

                let slug = this.value
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');

                slugInput.value = slug;
            });

        });
    </script>
@endpush
