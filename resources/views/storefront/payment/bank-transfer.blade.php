@extends('layouts.storefront.app')

@section('content')

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="card shadow-sm border-0">

                    <div class="card-body p-4">

                        <h2 class="mb-4">
                            Bank Transfer Payment
                        </h2>

                        {{-- Success Message --}}
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        {{-- Validation Errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">

                                <ul class="mb-0">

                                    @foreach ($errors->all() as $error)
                                        <li>
                                            {{ $error }}
                                        </li>
                                    @endforeach

                                </ul>

                            </div>
                        @endif

                        {{-- Payment Information --}}
                        <div class="mb-4">

                            <h5 class="mb-3">
                                Payment Information
                            </h5>

                            <div class="table-responsive">

                                <table class="table table-bordered">

                                    <tr>

                                        <th width="40%">
                                            Payment Reference
                                        </th>

                                        <td>
                                            {{ $transaction->transaction_reference }}
                                        </td>

                                    </tr>

                                    <tr>

                                        <th>
                                            Amount
                                        </th>

                                        <td>
                                            PKR
                                            {{ number_format($transaction->amount, 2) }}
                                        </td>

                                    </tr>

                                    <tr>

                                        <th>
                                            Payment Method
                                        </th>

                                        <td>
                                            Bank Transfer
                                        </td>

                                    </tr>

                                    <tr>

                                        <th>
                                            Payment Status
                                        </th>

                                        <td>

                                            @if ($transaction->status === 'pending')
                                                <span class="badge bg-warning text-dark">
                                                    Pending Verification
                                                </span>
                                            @elseif ($transaction->status === 'processing')
                                                <span class="badge bg-info text-dark">
                                                    Processing
                                                </span>
                                            @elseif ($transaction->status === 'paid')
                                                <span class="badge bg-success">
                                                    Paid
                                                </span>
                                            @elseif ($transaction->status === 'failed')
                                                <span class="badge bg-danger">
                                                    Failed
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    {{ ucfirst($transaction->status) }}
                                                </span>
                                            @endif

                                        </td>

                                    </tr>

                                </table>

                            </div>

                        </div>

                        {{-- Bank Details --}}
                        <div class="alert alert-info">

                            <h5>
                                Bank Transfer Instructions
                            </h5>

                            <p class="mb-2">
                                Please transfer the required amount to the following bank account.
                            </p>

                            <strong>Bank Name:</strong>
                            Your Bank Name

                            <br>

                            <strong>Account Title:</strong>
                            Your Account Title

                            <br>

                            <strong>Account Number:</strong>
                            Your Account Number

                            <br>

                            <strong>IBAN:</strong>
                            Your IBAN

                        </div>

                        {{-- Bank Transfer Form --}}
                        <form
                            action="{{ route('storefront.payment.bank-transfer.submit', [
                                'transaction' => $transaction->transaction_reference,
                            ]) }}"
                            method="POST" enctype="multipart/form-data">

                            @csrf

                            {{-- Transaction Reference --}}
                            <div class="mb-3">

                                <label for="transaction_reference" class="form-label">
                                    Transaction Reference
                                </label>

                                <input type="text" name="transaction_reference" id="transaction_reference"
                                    class="form-control @error('transaction_reference') is-invalid @enderror"
                                    value="{{ old('transaction_reference') }}"
                                    placeholder="Enter your bank transaction/reference number" required>

                                @error('transaction_reference')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- Payment Proof --}}
                            <div class="mb-3">

                                <label for="payment_proof" class="form-label">
                                    Payment Proof
                                </label>

                                <input type="file" name="payment_proof" id="payment_proof"
                                    class="form-control @error('payment_proof') is-invalid @enderror"
                                    accept=".jpg,.jpeg,.png,.webp,.pdf">

                                <small class="text-muted">
                                    JPG, JPEG, PNG, WEBP or PDF.
                                    Maximum size: 5MB.
                                </small>

                                @error('payment_proof')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- Notes --}}
                            <div class="mb-4">

                                <label for="notes" class="form-label">
                                    Additional Notes
                                </label>

                                <textarea name="notes" id="notes" rows="4" class="form-control @error('notes') is-invalid @enderror"
                                    placeholder="Optional notes">{{ old('notes') }}</textarea>

                                @error('notes')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- Submit --}}
                            <div class="d-flex justify-content-end">

                                <button type="submit" class="btn btn-primary">
                                    Submit Bank Transfer
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
