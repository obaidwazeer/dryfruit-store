@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="container-fluid">

        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-body">

                        <h4 class="mb-1">
                            Welcome, {{ auth()->user()->name }}
                        </h4>

                        <p class="mb-0 text-muted">
                            Welcome to your dry fruit store administration panel.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
