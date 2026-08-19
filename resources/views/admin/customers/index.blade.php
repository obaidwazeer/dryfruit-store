@extends('admin.layouts.app')

@section('title', 'Customers')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h4 class="mb-1">
                    Customers
                </h4>

                <p class="text-muted mb-0">
                    Manage customers and view their order history.
                </p>
            </div>

        </div>


        {{-- Filters --}}

        <div class="card mb-4">

            <div class="card-body">

                <form method="GET" action="{{ route('admin.customers.index') }}">

                    <div class="row g-3">

                        <div class="col-md-10">

                            <label class="form-label">
                                Search
                            </label>

                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Name, email or phone">

                        </div>


                        <div class="col-md-2 d-flex align-items-end">

                            <button class="btn btn-primary w-100">
                                Search
                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- Customers --}}

        <div class="card">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead>

                            <tr>

                                <th>
                                    Customer
                                </th>

                                <th>
                                    Phone
                                </th>

                                <th>
                                    Email
                                </th>

                                <th>
                                    Orders
                                </th>

                                <th>
                                    Total Spent
                                </th>

                                <th>
                                    Joined
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse ($customers as $customer)
                                <tr>

                                    <td>

                                        <strong>
                                            {{ $customer->name }}
                                        </strong>

                                    </td>


                                    <td>
                                        {{ $customer->phone }}
                                    </td>


                                    <td>
                                        {{ $customer->email ?: 'N/A' }}
                                    </td>


                                    <td>

                                        <span class="badge bg-primary">
                                            {{ $customer->orders_count }}
                                        </span>

                                    </td>


                                    <td>

                                        <strong>
                                            Rs.
                                            {{ number_format($customer->orders_sum_total ?? 0, 2) }}
                                        </strong>

                                    </td>


                                    <td>
                                        {{ $customer->created_at->format('d M Y') }}
                                    </td>


                                    <td>

                                        <a href="{{ route('admin.customers.show', $customer) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7" class="text-center py-5">
                                        No customers found.
                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>


                <div class="mt-3">

                    {{ $customers->links() }}

                </div>

            </div>

        </div>

    </div>

@endsection
