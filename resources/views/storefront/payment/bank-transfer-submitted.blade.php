@extends('layouts.storefront.app')

@section('content')
    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-lg-7">

                <div class="card shadow-sm border-0">

                    <div class="card-body p-5 text-center">

                        <div class="mb-4" style="font-size: 60px;">
                            ✓
                        </div>

                        <h2 class="mb-3">
                            Bank Transfer Submitted
                        </h2>

                        <p class="text-muted mb-4">

                            Your bank transfer details have been submitted
                            successfully.

                            Our team will verify your payment and update
                            your order shortly.

                        </p>

                        {{-- Payment Reference --}}
                        <div class="alert alert-light border text-start">

                            <div class="mb-2">

                                <strong>
                                    Payment Reference:
                                </strong>

                                <br>

                                <span>
                                    {{ $transaction->transaction_reference }}
                                </span>

                            </div>

                            <div class="mb-2">

                                <strong>
                                    Amount:
                                </strong>

                                <br>

                                <span>
                                    PKR
                                    {{ number_format($transaction->amount, 2) }}
                                </span>

                            </div>

                            <div>

                                <strong>
                                    Payment Status:
                                </strong>

                                <br>

                                <span class="badge bg-warning text-dark">
                                    Pending Verification
                                </span>

                            </div>

                        </div>

                        <div class="alert alert-info text-start">

                            <strong>
                                Please note:
                            </strong>

                            <p class="mb-0 mt-2">

                                Your payment has not been marked as paid yet.
                                It will remain pending until our team verifies
                                your bank transfer.

                            </p>

                        </div>

                        <a href="{{ route('storefront.home') }}" class="btn btn-primary">
                            Continue Shopping
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection
