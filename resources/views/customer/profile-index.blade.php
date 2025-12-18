@extends('customer.index')

@section('content')
<div class="container py-4">

    <div class="row">

        <!-- Sidebar -->
        <div class="col-md-4 mb-4">
            @include('customer.profile.sidebar', [
                'active' => $section
            ])
        </div>

        <!-- Content -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">

                    @if($section === 'profile')
                        @include('customer.profile.profile')

                    @elseif($section === 'update-profile')
                        @include('customer.profile.update-profile')

                    @elseif($section === 'update-password')
                        @include('customer.profile.update-password')

                    @elseif($section === 'delete-account')
                        @include('customer.profile.delete-account')

                    @else
                        @include('customer.profile.profile')
                    @endif

                </div>
            </div>
        </div>

    </div>

</div>
@endsection
