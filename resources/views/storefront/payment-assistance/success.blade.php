@extends('layouts.storefront.app')

@section('title', 'Request Submitted - ' . config('app.name'))

@section('content')

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-lg-7">

                <div class="text-center border rounded-4 p-4 p-md-5 shadow-sm">

                    {{-- Success Icon --}}
                    <div class="mb-4">

                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>

                    </div>


                    <h1 class="fw-bold">
                        Request Received
                    </h1>


                    <p class="text-muted mt-3">

                        Thank you, {{ $paymentAssistanceRequest->customer_name }}.

                        Our team has received your payment assistance
                        request and will contact you shortly.

                    </p>


                    {{-- Reference --}}
                    <div class="border rounded p-4 my-4">

                        <div class="small text-muted mb-2">
                            Your Assistance Reference
                        </div>

                        <div class="fs-4 fw-bold">

                            {{ $paymentAssistanceRequest->reference }}

                        </div>

                    </div>


                    <p class="small text-muted">

                        Please keep this reference number. You can provide it
                        to our support team when discussing your order.

                    </p>


                    {{-- Actions --}}
                    <div
                        class="d-flex flex-column
                            flex-sm-row
                            justify-content-center
                            gap-3 mt-4">

                        <a href="{{ route('storefront.home') }}" class="btn btn-storefront-primary">

                            Continue Shopping

                        </a>


                        <a href="{{ route('storefront.cart.index') }}" class="btn btn-outline-secondary">

                            View Cart

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
