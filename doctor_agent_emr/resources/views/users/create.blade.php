@extends('layouts.app')

@section('title', 'Create User')

@section('content')
    <div class="kt-container-fixed">
        <div class="grid gap-5 lg:gap-7.5">

                <form method="post" action="{{route('auth.users.store')}}" class="kt-form">
                    @csrf

                    <div class="kt-form-item">
                        <label class="kt-form-label">First Name</label>
                        <div class="kt-form-control">
                            <input type="text" name="first_name" value="{{ old('first_name') }}" class="kt-input" placeholder="First Name" />
                        </div>
                    </div>

                    <div class="kt-form-item">
                        <label class="kt-form-label">Last Name</label>
                        <div class="kt-form-control">
                            <input type="text" name="last_name" value="{{ old('last_name') }}" class="kt-input" placeholder="Last Name" />
                        </div>
                    </div>

                    <div class="kt-form-item">
                        <label class="kt-form-label">Email</label>
                        <div class="kt-form-control">
                            <input type="email" name="email" value="{{ old('email') }}" class="kt-input" placeholder="Email" />
                        </div>
                    </div>

                    <div class="kt-form-item">
                        <label class="kt-form-label">User Role</label>
                        <select class="kt-select" name="role">
                            <option value="">-- Select --</option>
                            @foreach($roles as $role)
                                <option value="{{$role->id}}" @selected(old('role') == $role->id)>{{ucwords ($role->name)}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="kt-form-item">
                        <label class="kt-form-label">Password</label>
                        <div class="kt-form-control">
                            <input type="password" name="password" value="{{ old('password') }}" class="kt-input" placeholder="Password" />
                        </div>
                    </div>

                    <div class="kt-form-item">
                        <label class="kt-form-label">Confirm Password</label>
                        <div class="kt-form-control">
                            <input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" class="kt-input" placeholder="Confirm Password" />
                        </div>
                    </div>

                    <input type="submit" class="kt-btn" value="Create User" name="save" />
                </form>
        </div>
    </div>
@endsection
