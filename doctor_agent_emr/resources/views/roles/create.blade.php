@extends('layouts.app')

@section('title', 'Create Role')

@section('content')
    <div class="kt-container-fixed">
        <div class="grid gap-5 lg:gap-7.5">

            <form method="post" action="{{route('auth.roles.store')}}" class="kt-form">
                @csrf

                <div class="kt-form-item">
                    <label class="kt-form-label">Name</label>
                    <div class="kt-form-control">
                        <input type="text" name="name" value="{{ old('name') }}" class="kt-input" placeholder="Role Name" />
                    </div>
                </div>

                <input type="submit" class="kt-btn" value="Create Role" name="save">
            </form>
        </div>
    </div>
@endsection
