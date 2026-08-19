@extends('admin.layouts.app')

@section('title', 'Order ' . $order->order_number)

@section('content')

    @php
        /*
        |--------------------------------------------------------------------------
        | Order Statuses
        |--------------------------------------------------------------------------
        */

        $orderStatuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];

        /*
        |--------------------------------------------------------------------------
        | Payment Statuses
        |--------------------------------------------------------------------------
        */

        $paymentStatuses = ['pending', 'paid', 'failed'];

        /*
        |--------------------------------------------------------------------------
        | Shipment Statuses
        |--------------------------------------------------------------------------
        */

        $shipmentStatuses = \App\Models\Shipment::STATUSES;

        /*
        |--------------------------------------------------------------------------
        | Latest Payment Transaction
        |--------------------------------------------------------------------------
        |
        | Online payments can have one or more transactions.
        | We display the latest transaction on the order page.
        |
        */

        $paymentTransaction = $order->paymentTransactions->sortByDesc('created_at')->first();
    @endphp


    <div class="container-fluid">

        {{-- ================================================================ --}}
        {{-- Header --}}
        {{-- ================================================================ --}}

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h4 class="mb-1">
                    Order {{ $order->order_number }}
                </h4>

                <p class="text-muted mb-0">
                    {{ $order->created_at->format('d M Y, h:i A') }}
                </p>

            </div>


            <a href="{{ route('admin.admin.orders.index') }}" class="btn btn-outline-secondary">

                Back to Orders

            </a>

        </div>


        {{-- ================================================================ --}}
        {{-- Alerts --}}
        {{-- ================================================================ --}}

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


        {{-- ================================================================ --}}
        {{-- Customer / Shipping / Order Status --}}
        {{-- ================================================================ --}}

        <div class="row g-4">


            {{-- ============================================================ --}}
            {{-- Customer Information --}}
            {{-- ============================================================ --}}

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

                            {{ $order->customer_name }}

                        </p>


                        <p>

                            <strong>
                                Phone:
                            </strong>

                            <br>

                            {{ $order->customer_phone }}

                        </p>


                        <p>

                            <strong>
                                Email:
                            </strong>

                            <br>

                            {{ $order->customer_email ?: 'N/A' }}

                        </p>


                        @if ($order->customer_id)
                            <p class="mb-0">

                                <strong>
                                    Customer ID:
                                </strong>

                                <br>

                                #{{ $order->customer_id }}

                            </p>
                        @endif

                    </div>

                </div>

            </div>


            {{-- ============================================================ --}}
            {{-- Shipping Information --}}
            {{-- ============================================================ --}}

            <div class="col-lg-4">

                <div class="card h-100">

                    <div class="card-body">

                        <h5 class="mb-4">
                            Shipping Information
                        </h5>


                        <p>

                            <strong>
                                City:
                            </strong>

                            <br>

                            {{ $order->shipping_city }}

                        </p>


                        <p>

                            <strong>
                                Address:
                            </strong>

                            <br>

                            {{ $order->shipping_address }}

                        </p>


                        <p>

                            <strong>
                                Postal Code:
                            </strong>

                            <br>

                            {{ $order->shipping_postal_code ?: 'N/A' }}

                        </p>

                    </div>

                </div>

            </div>


            {{-- ============================================================ --}}
            {{-- Order & Payment Status --}}
            {{-- ============================================================ --}}

            <div class="col-lg-4">

                <div class="card h-100">

                    <div class="card-body">

                        <h5 class="mb-4">
                            Order & Payment Status
                        </h5>


                        {{-- ================================================= --}}
                        {{-- Order Status --}}
                        {{-- ================================================= --}}

                        <form method="POST" action="{{ route('admin.admin.orders.status', $order) }}" class="mb-4">

                            @csrf

                            @method('PATCH')


                            <label class="form-label">
                                Order Status
                            </label>


                            <select name="status" class="form-select mb-2" required>

                                @foreach ($orderStatuses as $status)
                                    <option value="{{ $status }}" @selected($order->status === $status)>

                                        {{ ucfirst($status) }}

                                    </option>
                                @endforeach

                            </select>


                            <button type="submit" class="btn btn-primary w-100">

                                Update Order Status

                            </button>

                        </form>


                        {{-- ================================================= --}}
                        {{-- Payment Method --}}
                        {{-- ================================================= --}}

                        <div class="border rounded p-3 mb-4">

                            <h6 class="mb-3">
                                Payment Method
                            </h6>


                            <div class="d-flex justify-content-between mb-2">

                                <span class="text-muted">
                                    Method
                                </span>

                                <strong>

                                    @if ($order->payment_method === 'cod')
                                        Cash on Delivery
                                    @elseif ($order->payment_method === 'online')
                                        Online Payment
                                    @else
                                        {{ ucfirst($order->payment_method ?? 'N/A') }}
                                    @endif

                                </strong>

                            </div>


                            <div class="d-flex justify-content-between">

                                <span class="text-muted">
                                    Provider
                                </span>

                                <strong>

                                    @if ($order->payment_provider)
                                        {{ ucfirst(str_replace('_', ' ', $order->payment_provider)) }}
                                    @else
                                        N/A
                                    @endif

                                </strong>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- Payment Status --}}
                        {{-- ================================================= --}}

                        <form method="POST" action="{{ route('admin.admin.orders.payment-status', $order) }}">

                            @csrf

                            @method('PATCH')


                            <label class="form-label">
                                Payment Status
                            </label>


                            <select name="payment_status" class="form-select mb-2" required>

                                @foreach ($paymentStatuses as $status)
                                    <option value="{{ $status }}" @selected($order->payment_status === $status)>

                                        {{ ucfirst($status) }}

                                    </option>
                                @endforeach

                            </select>


                            <button type="submit" class="btn btn-outline-primary w-100">

                                Update Payment Status

                            </button>

                        </form>

                    </div>

                </div>

            </div>
            {{-- =========================================================
    Payment Transaction
========================================================== --}}

            @if ($order->paymentTransaction)
                <div class="card mt-4">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <h5 class="mb-1">
                                    Payment Transaction
                                </h5>

                                <p class="text-muted mb-0">

                                    {{ $order->paymentTransaction->transaction_reference }}

                                </p>

                            </div>


                            <a href="{{ route('admin.payment-transactions.show', $order->paymentTransaction) }}"
                                class="btn btn-outline-primary">

                                View Transaction

                            </a>

                        </div>

                    </div>

                </div>
            @endif

        </div>


        {{-- ================================================================= --}}
        {{-- Payment Transaction --}}
        {{-- ================================================================= --}}

        <div class="card mt-4">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>

                        <h5 class="mb-1">
                            Payment Transaction
                        </h5>

                        <p class="text-muted mb-0">
                            Transaction information associated with this order.
                        </p>

                    </div>


                    @if ($paymentTransaction)
                        @php

                            $transactionBadgeClass = match ($paymentTransaction->status) {
                                \App\Models\PaymentTransaction::STATUS_PAID => 'bg-success',

                                \App\Models\PaymentTransaction::STATUS_FAILED => 'bg-danger',

                                \App\Models\PaymentTransaction::STATUS_PROCESSING => 'bg-info',

                                default => 'bg-warning text-dark',
                            };

                        @endphp


                        <span class="badge {{ $transactionBadgeClass }}">

                            {{ ucfirst($paymentTransaction->status) }}

                        </span>
                    @else
                        <span class="badge bg-secondary">

                            No Transaction

                        </span>
                    @endif

                </div>


                @if ($paymentTransaction)

                    <div class="row g-4">


                        {{-- ================================================= --}}
                        {{-- Provider --}}
                        {{-- ================================================= --}}

                        <div class="col-md-4">

                            <div class="border rounded p-3 h-100">

                                <small class="text-muted d-block mb-1">
                                    Payment Provider
                                </small>

                                <strong>
                                    {{ ucfirst(str_replace('_', ' ', $paymentTransaction->provider)) }}
                                </strong>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- Transaction Reference --}}
                        {{-- ================================================= --}}

                        <div class="col-md-4">

                            <div class="border rounded p-3 h-100">

                                <small class="text-muted d-block mb-1">
                                    Transaction Reference
                                </small>

                                <strong class="text-break">
                                    {{ $paymentTransaction->transaction_reference }}
                                </strong>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- Gateway Transaction ID --}}
                        {{-- ================================================= --}}

                        <div class="col-md-4">

                            <div class="border rounded p-3 h-100">

                                <small class="text-muted d-block mb-1">
                                    Gateway Transaction ID
                                </small>

                                <strong class="text-break">

                                    {{ $paymentTransaction->gateway_transaction_id ?: 'Not available yet' }}

                                </strong>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- Amount --}}
                        {{-- ================================================= --}}

                        <div class="col-md-4">

                            <div class="border rounded p-3 h-100">

                                <small class="text-muted d-block mb-1">
                                    Transaction Amount
                                </small>

                                <strong>

                                    {{ $paymentTransaction->currency }}

                                    {{ number_format($paymentTransaction->amount, 2) }}

                                </strong>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- Transaction Status --}}
                        {{-- ================================================= --}}

                        <div class="col-md-4">

                            <div class="border rounded p-3 h-100">

                                <small class="text-muted d-block mb-1">
                                    Transaction Status
                                </small>

                                <strong>

                                    {{ ucfirst($paymentTransaction->status) }}

                                </strong>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- Created --}}
                        {{-- ================================================= --}}

                        <div class="col-md-4">

                            <div class="border rounded p-3 h-100">

                                <small class="text-muted d-block mb-1">
                                    Created
                                </small>

                                <strong>

                                    {{ $paymentTransaction->created_at?->format('d M Y, h:i A') ?? 'N/A' }}

                                </strong>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- Initiated At --}}
                        {{-- ================================================= --}}

                        <div class="col-md-4">

                            <div class="border rounded p-3 h-100">

                                <small class="text-muted d-block mb-1">
                                    Initiated At
                                </small>

                                <strong>

                                    {{ $paymentTransaction->initiated_at?->format('d M Y, h:i A') ?? 'Not available' }}

                                </strong>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- Paid At --}}
                        {{-- ================================================= --}}

                        <div class="col-md-4">

                            <div class="border rounded p-3 h-100">

                                <small class="text-muted d-block mb-1">
                                    Paid At
                                </small>

                                <strong>

                                    {{ $paymentTransaction->paid_at?->format('d M Y, h:i A') ?? 'Not paid yet' }}

                                </strong>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- Failed At --}}
                        {{-- ================================================= --}}

                        <div class="col-md-4">

                            <div class="border rounded p-3 h-100">

                                <small class="text-muted d-block mb-1">
                                    Failed At
                                </small>

                                <strong>

                                    {{ $paymentTransaction->failed_at?->format('d M Y, h:i A') ?? 'N/A' }}

                                </strong>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- Response Code --}}
                        {{-- ================================================= --}}

                        @if ($paymentTransaction->response_code)
                            <div class="col-md-4">

                                <div class="border rounded p-3 h-100">

                                    <small class="text-muted d-block mb-1">
                                        Response Code
                                    </small>

                                    <strong class="text-break">

                                        {{ $paymentTransaction->response_code }}

                                    </strong>

                                </div>

                            </div>
                        @endif


                        {{-- ================================================= --}}
                        {{-- Response Message --}}
                        {{-- ================================================= --}}

                        @if ($paymentTransaction->response_message)
                            <div class="col-md-8">

                                <div class="border rounded p-3 h-100">

                                    <small class="text-muted d-block mb-1">
                                        Response Message
                                    </small>

                                    <strong>

                                        {{ $paymentTransaction->response_message }}

                                    </strong>

                                </div>

                            </div>
                        @endif


                        {{-- ================================================= --}}
                        {{-- Bank Transfer Information --}}
                        {{-- ================================================= --}}

                        @if ($paymentTransaction->provider === 'bank_transfer')
                            <div class="col-12">

                                <div class="alert alert-info mb-0">

                                    <strong>
                                        Bank Transfer Payment
                                    </strong>

                                    <br>

                                    The customer has selected bank transfer
                                    as the payment method.

                                    The transaction should remain pending
                                    until the payment is manually verified.

                                    <div class="mt-2">

                                        <strong>
                                            Reference:
                                        </strong>

                                        {{ $paymentTransaction->transaction_reference }}

                                    </div>

                                </div>

                            </div>
                        @endif

                    </div>
                @else
                    {{-- ===================================================== --}}
                    {{-- No Transaction --}}
                    {{-- ===================================================== --}}

                    @if ($order->payment_method === 'cod')
                        <div class="alert alert-secondary mb-0">

                            <strong>
                                Cash on Delivery
                            </strong>

                            <br>

                            This order does not require an online payment
                            transaction.

                        </div>
                    @else
                        <div class="alert alert-warning mb-0">

                            <strong>
                                No payment transaction found.
                            </strong>

                            <br>

                            This order uses online payment, but no
                            payment transaction is currently associated
                            with the order.

                        </div>
                    @endif

                @endif

            </div>

        </div>


        {{-- ================================================================= --}}
        {{-- Shipment --}}
        {{-- ================================================================= --}}

        <div class="card mt-4">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>

                        <h5 class="mb-1">
                            Shipment
                        </h5>

                        <p class="text-muted mb-0">
                            Manage the manual fulfillment and shipment information.
                        </p>

                    </div>


                    @if ($order->shipment)
                        <span class="badge bg-primary">

                            {{ ucfirst(str_replace('_', ' ', $order->shipment->status)) }}

                        </span>
                    @else
                        <span class="badge bg-secondary">
                            Not Created
                        </span>
                    @endif

                </div>


                {{-- ========================================================== --}}
                {{-- Manual Fulfillment Information --}}
                {{-- ========================================================== --}}

                <div class="alert alert-info">

                    <strong>
                        Manual fulfillment
                    </strong>

                    <br>

                    The order will currently be fulfilled manually.
                    Prepare the package, take it to the selected courier office,
                    and enter the tracking information after the shipment is
                    accepted by the courier.

                </div>


                {{-- ========================================================== --}}
                {{-- Create Shipment --}}
                {{-- ========================================================== --}}

                @if (!$order->shipment)

                    <form method="POST" action="{{ route('admin.orders.shipment.store', $order) }}">

                        @csrf


                        <div class="row g-3">


                            {{-- ================================================= --}}
                            {{-- Courier --}}
                            {{-- ================================================= --}}

                            <div class="col-md-6">

                                <label class="form-label">
                                    Courier
                                </label>


                                <select name="courier" class="form-select" required>

                                    <option value="">
                                        Select Courier
                                    </option>


                                    @foreach (config('couriers', []) as $key => $courier)
                                        @if ($courier['enabled'] ?? false)
                                            <option value="{{ $key }}" @selected(old('courier') === $key)>

                                                {{ $courier['name'] }}

                                            </option>
                                        @endif
                                    @endforeach

                                </select>


                                <small class="text-muted">

                                    Select the courier office where the package
                                    will be submitted manually.

                                </small>

                            </div>


                            {{-- ================================================= --}}
                            {{-- Tracking Number --}}
                            {{-- ================================================= --}}

                            <div class="col-md-6">

                                <label class="form-label">
                                    Tracking Number
                                </label>


                                <input type="text" name="tracking_number" class="form-control"
                                    value="{{ old('tracking_number') }}"
                                    placeholder="Enter after courier accepts the package">


                                <small class="text-muted">

                                    Optional while the shipment is pending or ready.

                                </small>

                            </div>


                            {{-- ================================================= --}}
                            {{-- Shipment Status --}}
                            {{-- ================================================= --}}

                            <div class="col-md-6">

                                <label class="form-label">
                                    Shipment Status
                                </label>


                                <select name="status" class="form-select" required>

                                    @foreach ($shipmentStatuses as $status)
                                        <option value="{{ $status }}" @selected(old('status', \App\Models\Shipment::STATUS_PENDING) === $status)>

                                            {{ ucfirst(str_replace('_', ' ', $status)) }}

                                        </option>
                                    @endforeach

                                </select>


                                <small class="text-muted">

                                    Start with Pending. Change this as the
                                    package moves through the fulfillment process.

                                </small>

                            </div>


                            {{-- ================================================= --}}
                            {{-- Fulfillment Method --}}
                            {{-- ================================================= --}}

                            <div class="col-md-6">

                                <label class="form-label">
                                    Fulfillment Method
                                </label>


                                <input type="text" class="form-control" value="Manual" readonly>


                                <small class="text-muted">

                                    Courier API integration will be added later.

                                </small>

                            </div>


                            {{-- ================================================= --}}
                            {{-- Tracking URL --}}
                            {{-- ================================================= --}}

                            <div class="col-md-6">

                                <label class="form-label">
                                    Tracking URL
                                </label>


                                <input type="url" name="tracking_url" class="form-control"
                                    value="{{ old('tracking_url') }}" placeholder="https://...">


                                <small class="text-muted">

                                    Optional. Add the courier tracking page if available.

                                </small>

                            </div>


                            {{-- ================================================= --}}
                            {{-- Notes --}}
                            {{-- ================================================= --}}

                            <div class="col-md-6">

                                <label class="form-label">
                                    Notes
                                </label>


                                <textarea name="notes" class="form-control" rows="3" placeholder="Internal shipment notes...">{{ old('notes') }}</textarea>

                            </div>


                            {{-- ================================================= --}}
                            {{-- Submit --}}
                            {{-- ================================================= --}}

                            <div class="col-12">

                                <button type="submit" class="btn btn-primary">

                                    Create Shipment

                                </button>

                            </div>

                        </div>

                    </form>
                @else
                    {{-- ====================================================== --}}
                    {{-- Existing Shipment --}}
                    {{-- ====================================================== --}}

                    <form method="POST"
                        action="{{ route('admin.orders.shipment.update', [
                            'order' => $order,
                            'shipment' => $order->shipment,
                        ]) }}">

                        @csrf

                        @method('PUT')


                        <div class="row g-3">


                            {{-- ================================================= --}}
                            {{-- Courier --}}
                            {{-- ================================================= --}}

                            <div class="col-md-6">

                                <label class="form-label">
                                    Courier
                                </label>


                                <select name="courier" class="form-select" required>

                                    <option value="">
                                        Select Courier
                                    </option>


                                    @foreach (config('couriers', []) as $key => $courier)
                                        @if ($courier['enabled'] ?? false)
                                            <option value="{{ $key }}" @selected($order->shipment->courier === $key)>

                                                {{ $courier['name'] }}

                                            </option>
                                        @endif
                                    @endforeach

                                </select>

                            </div>


                            {{-- ================================================= --}}
                            {{-- Tracking Number --}}
                            {{-- ================================================= --}}

                            <div class="col-md-6">

                                <label class="form-label">
                                    Tracking Number
                                </label>


                                <input type="text" name="tracking_number" class="form-control"
                                    value="{{ old('tracking_number', $order->shipment->tracking_number) }}"
                                    placeholder="Enter courier tracking number">


                                <small class="text-muted">

                                    Enter the tracking number provided by the courier.

                                </small>

                            </div>


                            {{-- ================================================= --}}
                            {{-- Shipment Status --}}
                            {{-- ================================================= --}}

                            <div class="col-md-6">

                                <label class="form-label">
                                    Shipment Status
                                </label>


                                <select name="status" class="form-select" required>

                                    @foreach ($shipmentStatuses as $status)
                                        <option value="{{ $status }}" @selected(old('status', $order->shipment->status) === $status)>

                                            {{ ucfirst(str_replace('_', ' ', $status)) }}

                                        </option>
                                    @endforeach

                                </select>


                                <small class="text-muted">

                                    Update this when the physical shipment
                                    progresses.

                                </small>

                            </div>


                            {{-- ================================================= --}}
                            {{-- Fulfillment Method --}}
                            {{-- ================================================= --}}

                            <div class="col-md-6">

                                <label class="form-label">
                                    Fulfillment Method
                                </label>


                                <input type="text" class="form-control" value="Manual" readonly>


                                <small class="text-muted">

                                    Courier API integration will be added later.

                                </small>

                            </div>


                            {{-- ================================================= --}}
                            {{-- Tracking URL --}}
                            {{-- ================================================= --}}

                            <div class="col-md-6">

                                <label class="form-label">
                                    Tracking URL
                                </label>


                                <input type="url" name="tracking_url" class="form-control"
                                    value="{{ old('tracking_url', $order->shipment->tracking_url) }}"
                                    placeholder="https://...">


                                @if ($order->shipment->tracking_url)
                                    <div class="mt-2">

                                        <a href="{{ $order->shipment->tracking_url }}" target="_blank"
                                            rel="noopener noreferrer" class="btn btn-sm btn-outline-secondary">

                                            Track Shipment

                                        </a>

                                    </div>
                                @endif

                            </div>


                            {{-- ================================================= --}}
                            {{-- Shipped At --}}
                            {{-- ================================================= --}}

                            <div class="col-md-6">

                                <label class="form-label">
                                    Shipped At
                                </label>


                                <input type="text" class="form-control"
                                    value="{{ $order->shipment->shipped_at?->format('d M Y, h:i A') ?? 'Not shipped yet' }}"
                                    readonly>

                            </div>


                            {{-- ================================================= --}}
                            {{-- Delivered At --}}
                            {{-- ================================================= --}}

                            <div class="col-md-6">

                                <label class="form-label">
                                    Delivered At
                                </label>


                                <input type="text" class="form-control"
                                    value="{{ $order->shipment->delivered_at?->format('d M Y, h:i A') ?? 'Not delivered yet' }}"
                                    readonly>

                            </div>


                            {{-- ================================================= --}}
                            {{-- Notes --}}
                            {{-- ================================================= --}}

                            <div class="col-12">

                                <label class="form-label">
                                    Notes
                                </label>


                                <textarea name="notes" class="form-control" rows="3" placeholder="Internal shipment notes...">{{ old('notes', $order->shipment->notes) }}</textarea>

                            </div>


                            {{-- ================================================= --}}
                            {{-- Submit --}}
                            {{-- ================================================= --}}

                            <div class="col-12">

                                <button type="submit" class="btn btn-primary">

                                    Update Shipment

                                </button>

                            </div>

                        </div>

                    </form>

                @endif

            </div>

        </div>


        {{-- ================================================================= --}}
        {{-- Order Items --}}
        {{-- ================================================================= --}}

        <div class="card mt-4">

            <div class="card-body">

                <h5 class="mb-4">
                    Order Items
                </h5>


                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead>

                            <tr>

                                <th>
                                    Product
                                </th>

                                <th>
                                    Variant
                                </th>

                                <th>
                                    SKU
                                </th>

                                <th>
                                    Weight
                                </th>

                                <th>
                                    Price
                                </th>

                                <th>
                                    Quantity
                                </th>

                                <th>
                                    Total
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse ($order->items as $item)
                                <tr>

                                    <td>
                                        {{ $item->product_name }}
                                    </td>


                                    <td>
                                        {{ $item->variant_name ?: 'N/A' }}
                                    </td>


                                    <td>
                                        {{ $item->sku ?: 'N/A' }}
                                    </td>


                                    <td>
                                        {{ $item->weight_grams }}g
                                    </td>


                                    <td>
                                        Rs. {{ number_format($item->unit_price, 2) }}
                                    </td>


                                    <td>
                                        {{ $item->quantity }}
                                    </td>


                                    <td>

                                        <strong>
                                            Rs. {{ number_format($item->total, 2) }}
                                        </strong>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7" class="text-center py-4 text-muted">

                                        No order items found.

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- ================================================================= --}}
        {{-- Totals --}}
        {{-- ================================================================= --}}

        <div class="row justify-content-end mt-4">

            <div class="col-lg-4">

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-2">

                            <span>
                                Subtotal
                            </span>

                            <strong>
                                Rs. {{ number_format($order->subtotal, 2) }}
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-2">

                            <span>
                                Shipping
                            </span>

                            <strong>
                                Rs. {{ number_format($order->shipping_fee, 2) }}
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-2">

                            <span>
                                Discount
                            </span>

                            <strong>
                                Rs. {{ number_format($order->discount, 2) }}
                            </strong>

                        </div>


                        <hr>


                        <div class="d-flex justify-content-between">

                            <strong>
                                Total
                            </strong>

                            <strong class="fs-5">
                                Rs. {{ number_format($order->total, 2) }}
                            </strong>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================================= --}}
        {{-- Customer Notes --}}
        {{-- ================================================================= --}}

        @if ($order->customer_notes)
            <div class="card mt-4">

                <div class="card-body">

                    <h5 class="mb-3">
                        Customer Notes
                    </h5>

                    <p class="mb-0">
                        {{ $order->customer_notes }}
                    </p>

                </div>

            </div>
        @endif

    </div>

@endsection
