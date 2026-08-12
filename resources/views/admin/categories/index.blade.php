@extends('admin.layouts.app')

@section('title', 'Categories')

@section('content')

    <div class="container-fluid">

        {{-- Page Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">

            <div>

                <h6 class="mb-1 text-uppercase">
                    Catalog
                </h6>

                <h4 class="mb-0">
                    Categories
                </h4>

            </div>

            @can('categories.create')
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">

                    <i class="bx bx-plus me-1"></i>

                    Add Category

                </a>
            @endcan

        </div>


        {{-- Categories Card --}}
        <div class="card">

            <div class="card-body">

                {{-- Card Header --}}
                <div class="d-flex align-items-center justify-content-between mb-3">

                    <div>

                        <h5 class="mb-0">
                            All Categories
                        </h5>

                        <small class="text-muted">
                            Manage your dry fruit product categories.
                        </small>

                    </div>

                    <span class="badge bg-light text-dark">

                        {{ $categories->total() }}

                        {{ Str::plural('Category', $categories->total()) }}

                    </span>

                </div>


                {{-- Search --}}
                <form method="GET" action="{{ route('admin.categories.index') }}" class="row g-2 mb-4">

                    <div class="col-md-6">

                        <div class="input-group">

                            <span class="input-group-text bg-transparent">
                                <i class="bx bx-search"></i>
                            </span>

                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Search categories...">

                        </div>

                    </div>


                    <div class="col-md-auto">

                        <button type="submit" class="btn btn-primary">

                            Search

                        </button>

                    </div>


                    @if (request('search'))
                        <div class="col-md-auto">

                            <a href="{{ route('admin.categories.index') }}" class="btn btn-light">

                                Clear

                            </a>

                        </div>
                    @endif

                </form>


                {{-- Table --}}
                <div class="table-responsive">

                    <table class="table align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Category
                                </th>

                                <th>
                                    Slug
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Sort Order
                                </th>

                                <th class="text-end">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($categories as $category)
                                <tr>

                                    {{-- ID --}}
                                    <td>

                                        <span class="fw-semibold">
                                            {{ $category->id }}
                                        </span>

                                    </td>


                                    {{-- Category --}}
                                    <td>

                                        <div class="d-flex align-items-center">

                                            {{-- Image --}}
                                            <div class="me-3">

                                                @if ($category->image_path)
                                                    <img src="{{ asset('storage/' . $category->image_path) }}"
                                                        alt="{{ $category->name }}" width="50" height="50"
                                                        class="rounded object-fit-cover">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                        style="width:50px;height:50px;">

                                                        <i class="bx bx-image text-muted fs-4"></i>

                                                    </div>
                                                @endif

                                            </div>


                                            <div>

                                                <h6 class="mb-1">

                                                    {{ $category->name }}

                                                </h6>

                                                @if ($category->description)
                                                    <small class="text-muted">

                                                        {{ Str::limit($category->description, 50) }}

                                                    </small>
                                                @endif

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Slug --}}
                                    <td>

                                        <code>
                                            {{ $category->slug }}
                                        </code>

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @if ($category->is_active)
                                            <span class="badge bg-success">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                Inactive
                                            </span>
                                        @endif

                                    </td>


                                    {{-- Sort --}}
                                    <td>

                                        {{ $category->sort_order }}

                                    </td>


                                    {{-- Actions --}}
                                    <td class="text-end">

                                        <div class="d-flex justify-content-end gap-2">

                                            @can('categories.update')
                                                <a href="{{ route('admin.categories.edit', $category) }}"
                                                    class="btn btn-sm btn-light" title="Edit">

                                                    <i class="bx bx-edit"></i>

                                                </a>
                                            @endcan


                                            @can('categories.delete')
                                                <form method="POST"
                                                    action="{{ route('admin.categories.destroy', $category) }}"
                                                    onsubmit="return confirm('Are you sure you want to delete this category?');">

                                                    @csrf

                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-sm btn-light text-danger"
                                                        title="Delete">

                                                        <i class="bx bx-trash"></i>

                                                    </button>

                                                </form>
                                            @endcan

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6" class="text-center py-5">

                                        <div class="mb-3">

                                            <i class="bx bx-category fs-1 text-muted">
                                            </i>

                                        </div>

                                        <h6>
                                            No categories found
                                        </h6>

                                        <p class="text-muted mb-3">

                                            Start by creating your first product category.

                                        </p>

                                        @can('categories.create')
                                            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">

                                                <i class="bx bx-plus me-1"></i>

                                                Create Category

                                            </a>
                                        @endcan

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                @if ($categories->hasPages())
                    <div class="mt-4">

                        {{ $categories->links() }}

                    </div>
                @endif

            </div>

        </div>

    </div>

@endsection
