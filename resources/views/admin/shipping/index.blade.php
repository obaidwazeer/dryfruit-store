@extends('admin.layouts.app')

@section('title', 'Shipping Rates')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h4 class="mb-1">
                    Shipping Rates
                </h4>

                <p class="text-muted mb-0">
                    Manage shipping charges for each city.
                </p>
            </div>

            @can('shipping.create')
                <a href="{{ route('admin.shipping.create') }}" class="btn btn-primary">

                    Add Shipping Rate

                </a>
            @endcan

        </div>


        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif


        @if ($errors->any())

            <div class="alert alert-danger">

                @foreach ($errors->all() as $error)
                    <div>
                        {{ $error }}
                    </div>
                @endforeach

            </div>

        @endif


        <div class="card">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead>

                            <tr>

                                <th>
                                    City
                                </th>

                                <th>
                                    Shipping Fee
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Updated
                                </th>

                                <th>
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse ($shippingRates as $shippingRate)
                                <tr>

                                    <td>
                                        <strong>
                                            {{ $shippingRate->city }}
                                        </strong>
                                    </td>


                                    <td>
                                        Rs.
                                        {{ number_format($shippingRate->rate, 2) }}
                                    </td>


                                    <td>

                                        @if ($shippingRate->is_active)
                                            <span class="badge bg-success">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                Inactive
                                            </span>
                                        @endif

                                    </td>


                                    <td>
                                        {{ $shippingRate->updated_at->format('d M Y') }}
                                    </td>


                                    <td>

                                        @can('shipping.update')
                                            <a href="{{ route('admin.shipping.edit', $shippingRate) }}"
                                                class="btn btn-sm btn-outline-primary">

                                                Edit

                                            </a>
                                        @endcan


                                        @can('shipping.delete')
                                            <form method="POST" action="{{ route('admin.shipping.destroy', $shippingRate) }}"
                                                class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this shipping rate?');">

                                                @csrf

                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-outline-danger">

                                                    Delete

                                                </button>

                                            </form>
                                        @endcan

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5" class="text-center py-5">

                                        No shipping rates found.

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>


                <div class="mt-3">

                    {{ $shippingRates->links() }}

                </div>

            </div>

        </div>

    </div>

@endsection
