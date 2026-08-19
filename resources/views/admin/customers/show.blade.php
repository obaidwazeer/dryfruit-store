@extends('admin.layouts.app')

@section('title', 'Customer - ' . $customer->name)

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                {{ $customer->name }}
            </h4>

            <p class="text-muted mb-0">
                Customer details and order history.
            </p>

        </div>


        <a
            href="{{ route('admin.customers.index') }}"
            class="btn btn-outline-secondary"
        >
            Back to Customers
        </a>

    </div>


    {{-- Customer Information --}}

    <div class="row g-4">

        <div class="col-lg-4">

            <div class="card h-100">

                <div class="card-body">

                    <h5 class="mb-4">
                        Customer Information
                    </h5>


                    <p>

                        <strong>
                            Name:
                        </strong>
                        <br>

                        {{ $customer->name }}

                    </p>


                    <p>

                        <strong>
                            Phone:
                        </strong>
                        <br>

                        {{ $customer->phone }}

                    </p>


                    <p>

                        <strong>
                            Email:
                        </strong>
                        <br>

                        {{ $customer->email ?: 'N/A' }}

                    </p>


                    <p class="mb-0">

                        <strong>
                            Customer Since:
                        </strong>
                        <br>

                        {{ $customer->created_at->format('d M Y') }}

                    </p>

                </div>

            </div>

        </div>


        {{-- Statistics --}}

        <div class="col-lg-8">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="card">

                        <div class="card-body">

                            <p class="text-muted mb-1">
                                Total Orders
                            </p>

                            <h3 class="mb-0">
                                {{ $customer->orders_count }}
                            </h3>

                        </div>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="card">

                        <div class="card-body">

                            <p class="text-muted mb-1">
                                Total Spent
                            </p>

                            <h3 class="mb-0">
                                Rs.
                                {{ number_format($totalSpent, 2) }}
                            </h3>

                        </div>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="card">

                        <div class="card-body">

                            <p class="text-muted mb-1">
                                Customer Since
                            </p>

                            <h5 class="mb-0">
                                {{ $customer->created_at->format('M Y') }}
                            </h5>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Order History --}}

    <div class="card mt-4">

        <div class="card-body">

            <h5 class="mb-4">
                Order History
            </h5>


            <div class="table-responsive">

                <table class="table align-middle">

                    <thead>

                        <tr>

                            <th>
                                Order
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Payment
                            </th>

                            <th>
                                Total
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($customer->orders as $order)

                            <tr>

                                <td>

                                    <strong>
                                        {{ $order->order_number }}
                                    </strong>

                                </td>


                                <td>
                                    {{ $order->created_at->format('d M Y') }}
                                </td>


                                <td>

                                    <span class="badge bg-primary">
                                        {{ ucfirst($order->status) }}
                                    </span>

                                </td>


                                <td>

                                    <span class="badge bg-secondary">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>

                                </td>


                                <td>

                                    <strong>
                                        Rs.
                                        {{ number_format($order->total, 2) }}
                                    </strong>

                                </td>


                                <td>

                                    <a
                                        href="{{ route('admin.admin.orders.show', $order) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View Order
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center py-5"
                                >
                                    No orders found.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection
