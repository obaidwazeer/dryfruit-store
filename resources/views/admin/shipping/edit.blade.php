@extends('admin.layouts.app')

@section('title', 'Edit Shipping Rate')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h4 class="mb-1">
                    Edit Shipping Rate
                </h4>

                <p class="text-muted mb-0">
                    Update the shipping charge for {{ $shippingRate->city }}.
                </p>

            </div>

            <a href="{{ route('admin.shipping.index') }}" class="btn btn-outline-secondary">

                Back to Shipping Rates

            </a>

        </div>


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

                <form method="POST" action="{{ route('admin.shipping.update', $shippingRate) }}">

                    @csrf

                    @method('PUT')


                    <div class="mb-3">

                        <label for="city" class="form-label">

                            City

                        </label>

                        <input type="text" id="city" name="city" value="{{ old('city', $shippingRate->city) }}"
                            class="form-control @error('city') is-invalid @enderror" required>

                        @error('city')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="mb-3">

                        <label for="rate" class="form-label">

                            Shipping Fee

                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                Rs.
                            </span>

                            <input type="number" id="rate" name="rate"
                                value="{{ old('rate', $shippingRate->rate) }}"
                                class="form-control @error('rate') is-invalid @enderror" min="0" step="0.01"
                                required>

                        </div>

                        @error('rate')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="form-check mb-4">

                        <input type="checkbox" id="is_active" name="is_active" value="1" class="form-check-input"
                            @checked(old('is_active', $shippingRate->is_active))>

                        <label for="is_active" class="form-check-label">

                            Active

                        </label>

                    </div>


                    <button type="submit" class="btn btn-primary">

                        Update Shipping Rate

                    </button>


                    <a href="{{ route('admin.shipping.index') }}" class="btn btn-outline-secondary ms-2">

                        Cancel

                    </a>

                </form>

            </div>

        </div>

    </div>

@endsection
