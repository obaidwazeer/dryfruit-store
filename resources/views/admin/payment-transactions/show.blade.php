@extends('admin.layouts.app')

@section('title', 'Payment Transaction')

@section('content')

    <div class="container-fluid">

        {{-- ================================================================
            Header
        ================================================================= --}}

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h4 class="mb-1">
                    Payment Transaction
                </h4>

                <p class="text-muted mb-0">
                    Transaction information associated with this order.
                </p>

            </div>


            <div class="d-flex gap-2">

                <a href="{{ route('admin.admin.orders.show', $paymentTransaction->order) }}"
                    class="btn btn-outline-secondary">

                    Back to Order

                </a>

            </div>

        </div>


        {{-- ================================================================
            Alerts
        ================================================================= --}}

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


        {{-- ================================================================
            Status Header
        ================================================================= --}}

        <div class="card mb-4">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h3 class="mb-1">
                            Payment Transaction
                        </h3>

                        <p class="text-muted mb-0">
                            {{ $paymentTransaction->transaction_reference }}
                        </p>

                    </div>


                    <div>

                        @if ($paymentTransaction->status === \App\Models\PaymentTransaction::STATUS_PENDING)
                            <span class="badge bg-warning text-dark fs-6">
                                Pending
                            </span>
                        @elseif ($paymentTransaction->status === \App\Models\PaymentTransaction::STATUS_PROCESSING)
                            <span class="badge bg-info text-dark fs-6">
                                Processing
                            </span>
                        @elseif ($paymentTransaction->status === \App\Models\PaymentTransaction::STATUS_PAID)
                            <span class="badge bg-success fs-6">
                                Paid
                            </span>
                        @elseif ($paymentTransaction->status === \App\Models\PaymentTransaction::STATUS_FAILED)
                            <span class="badge bg-danger fs-6">
                                Failed
                            </span>
                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================================
            Transaction Information
        ================================================================= --}}

        <div class="row g-4">


            {{-- Provider --}}

            <div class="col-lg-4">

                <div class="card h-100">

                    <div class="card-body">

                        <h6 class="text-muted">
                            Payment Provider
                        </h6>

                        <h5 class="mb-0">

                            {{ ucwords(str_replace('_', ' ', $paymentTransaction->provider)) }}

                        </h5>

                    </div>

                </div>

            </div>


            {{-- Transaction Reference --}}

            <div class="col-lg-4">

                <div class="card h-100">

                    <div class="card-body">

                        <h6 class="text-muted">
                            Transaction Reference
                        </h6>

                        <h5 class="mb-0">
                            {{ $paymentTransaction->transaction_reference }}
                        </h5>

                    </div>

                </div>

            </div>


            {{-- Gateway Transaction ID --}}

            <div class="col-lg-4">

                <div class="card h-100">

                    <div class="card-body">

                        <h6 class="text-muted">
                            Gateway Transaction ID
                        </h6>

                        <h5 class="mb-0">

                            {{ $paymentTransaction->gateway_transaction_id ?: 'Not available yet' }}

                        </h5>

                    </div>

                </div>

            </div>


            {{-- Amount --}}

            <div class="col-lg-4">

                <div class="card h-100">

                    <div class="card-body">

                        <h6 class="text-muted">
                            Transaction Amount
                        </h6>

                        <h5 class="mb-0">

                            {{ $paymentTransaction->currency }}

                            {{ number_format($paymentTransaction->amount, 2) }}

                        </h5>

                    </div>

                </div>

            </div>


            {{-- Status --}}

            <div class="col-lg-4">

                <div class="card h-100">

                    <div class="card-body">

                        <h6 class="text-muted">
                            Transaction Status
                        </h6>

                        <h5 class="mb-0">

                            {{ ucfirst($paymentTransaction->status) }}

                        </h5>

                    </div>

                </div>

            </div>


            {{-- Created --}}

            <div class="col-lg-4">

                <div class="card h-100">

                    <div class="card-body">

                        <h6 class="text-muted">
                            Created
                        </h6>

                        <h5 class="mb-0">

                            {{ $paymentTransaction->created_at?->format('d M Y, h:i A') }}

                        </h5>

                    </div>

                </div>

            </div>


            {{-- Initiated --}}

            <div class="col-lg-4">

                <div class="card h-100">

                    <div class="card-body">

                        <h6 class="text-muted">
                            Initiated At
                        </h6>

                        <h5 class="mb-0">

                            {{ $paymentTransaction->initiated_at?->format('d M Y, h:i A') ?? 'N/A' }}

                        </h5>

                    </div>

                </div>

            </div>


            {{-- Paid --}}

            <div class="col-lg-4">

                <div class="card h-100">

                    <div class="card-body">

                        <h6 class="text-muted">
                            Paid At
                        </h6>

                        <h5 class="mb-0">

                            {{ $paymentTransaction->paid_at?->format('d M Y, h:i A') ?? 'Not paid yet' }}

                        </h5>

                    </div>

                </div>

            </div>


            {{-- Failed --}}

            <div class="col-lg-4">

                <div class="card h-100">

                    <div class="card-body">

                        <h6 class="text-muted">
                            Failed At
                        </h6>

                        <h5 class="mb-0">

                            {{ $paymentTransaction->failed_at?->format('d M Y, h:i A') ?? 'N/A' }}

                        </h5>

                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================================
            Customer / Order
        ================================================================= --}}

        <div class="card mt-4">

            <div class="card-body">

                <h5 class="mb-4">
                    Order Information
                </h5>


                <div class="row g-4">


                    <div class="col-md-3">

                        <small class="text-muted d-block">
                            Order Number
                        </small>

                        <strong>
                            {{ $paymentTransaction->order->order_number }}
                        </strong>

                    </div>


                    <div class="col-md-3">

                        <small class="text-muted d-block">
                            Customer
                        </small>

                        <strong>
                            {{ $paymentTransaction->order->customer_name }}
                        </strong>

                    </div>


                    <div class="col-md-3">

                        <small class="text-muted d-block">
                            Phone
                        </small>

                        <strong>
                            {{ $paymentTransaction->order->customer_phone }}
                        </strong>

                    </div>


                    <div class="col-md-3">

                        <small class="text-muted d-block">
                            Order Total
                        </small>

                        <strong>
                            Rs.
                            {{ number_format($paymentTransaction->order->total, 2) }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================================
            Gateway Response
        ================================================================= --}}

        <div class="card mt-4">

            <div class="card-body">

                <h5 class="mb-4">
                    Gateway / Verification Information
                </h5>


                <div class="row g-4">


                    <div class="col-md-4">

                        <small class="text-muted d-block">
                            Response Code
                        </small>

                        <strong>
                            {{ $paymentTransaction->response_code ?: 'N/A' }}
                        </strong>

                    </div>


                    <div class="col-md-8">

                        <small class="text-muted d-block">
                            Response Message
                        </small>

                        <strong>
                            {{ $paymentTransaction->response_message ?: 'N/A' }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================================
            Manual Verification
        ================================================================= --}}

        @if (in_array(
                $paymentTransaction->status,
                [\App\Models\PaymentTransaction::STATUS_PENDING, \App\Models\PaymentTransaction::STATUS_PROCESSING],
                true))
            <div class="card mt-4 border-warning">

                <div class="card-body">

                    <h5 class="mb-2">
                        Manual Payment Verification
                    </h5>

                    <p class="text-muted">

                        Verify the customer's bank transfer using your bank
                        statement before approving this transaction.

                    </p>


                    <div class="alert alert-warning">

                        <strong>
                            Important:
                        </strong>

                        Do not approve the payment simply because the customer
                        clicked "I Have Completed the Transfer".

                        Verify the actual amount and transfer reference
                        against your bank account first.

                    </div>


                    <div class="d-flex gap-2">


                        {{-- Approve --}}

                        <form method="POST"
                            action="{{ route('admin.payment-transactions.approve', $paymentTransaction) }}">

                            @csrf

                            @method('PATCH')

                            <button type="submit" class="btn btn-success"
                                onclick="return confirm('Are you sure you want to approve this payment?');">

                                <i class="bi bi-check-circle me-1"></i>

                                Approve Payment

                            </button>

                        </form>


                        {{-- Reject --}}

                        <form method="POST"
                            action="{{ route('admin.payment-transactions.reject', $paymentTransaction) }}">

                            @csrf

                            @method('PATCH')

                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to reject this payment?');">

                                <i class="bi bi-x-circle me-1"></i>

                                Reject Payment

                            </button>

                        </form>

                    </div>

                </div>

            </div>
        @elseif ($paymentTransaction->status === \App\Models\PaymentTransaction::STATUS_PAID)
            <div class="alert alert-success mt-4">

                <strong>
                    Payment Approved
                </strong>

                <div class="mt-1">

                    This payment transaction has been manually verified
                    and approved.

                </div>

            </div>
        @elseif ($paymentTransaction->status === \App\Models\PaymentTransaction::STATUS_FAILED)
            <div class="alert alert-danger mt-4">

                <strong>
                    Payment Rejected
                </strong>

                <div class="mt-1">

                    This payment transaction was rejected.

                </div>

            </div>
        @endif


        {{-- ================================================================
            Request Payload
        ================================================================= --}}

        @if ($paymentTransaction->request_payload)
            <div class="card mt-4">

                <div class="card-body">

                    <h5 class="mb-3">
                        Request Payload
                    </h5>

                    <pre class="bg-light p-3 rounded mb-0">{{ json_encode($paymentTransaction->request_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>

                </div>

            </div>
        @endif


        {{-- ================================================================
            Response Payload
        ================================================================= --}}

        @if ($paymentTransaction->response_payload)
            <div class="card mt-4">

                <div class="card-body">

                    <h5 class="mb-3">
                        Response Payload
                    </h5>

                    <pre class="bg-light p-3 rounded mb-0">{{ json_encode($paymentTransaction->response_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>

                </div>

            </div>
        @endif

    </div>

@endsection
