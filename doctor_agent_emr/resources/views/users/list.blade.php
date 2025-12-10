@extends('layouts.app')

@section('title', 'Users List')

@section('action-links')
    <a class="kt-btn kt-btn-primary" href="{{ route('auth.users.create') }}">
        Add Member
    </a>
@endsection

@section('content')
    <div class="kt-container-fixed">
        <div class="grid gap-5 lg:gap-7.5">
            <div class="kt-card kt-card-grid min-w-full">
                <div class="kt-card-header flex-wrap gap-2">
                    <h3 class="kt-card-title text-sm">
                        Showing {{ min($users->total(), $users->perPage()) }} of {{ $users->total() }} user(s)
                    </h3>
                </div>
                <div class="kt-card-content">
                    <div class="grid" data-kt-datatable="true" data-kt-datatable-page-size="10">
                        <div class="kt-scrollable-x-auto">
                            <table class="kt-table table-auto kt-table-border">
                                <thead>
                                <tr>
                                    <th class="min-w-[200px]">
                                        <span class="kt-table-col asc">
                                            <span class="kt-table-col-label">Name</span>
                                       </span>
                                    </th>
                                    <th class="min-w-[200px]">
                                        <span class="kt-table-col asc">
                                            <span class="kt-table-col-label">Email</span>
                                       </span>
                                    </th>
                                    <th class="min-w-[200px]">
                                        <span class="kt-table-col asc">
                                            <span class="kt-table-col-label">Roles</span>
                                       </span>
                                    </th>
                                    <th class="w-[60px]">
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                    <td>
                                        <div class="flex items-center gap-2.5">
                                            <a class="text-sm font-medium text-mono hover:text-primary" href="#">
                                                {{ $user->first_name }} {{ $user->last_name }}
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-1.5 text-foreground font-normal">
                                            {{ $user->email }}
                                        </div>
                                    </td>
                                    <td class="text-foreground font-normal">
                                        @foreach($user->roles as $role)
                                            <span class="label label-default">{{ $role->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <div class="kt-menu" data-kt-menu="true">
                                            <div class="kt-menu-item" data-kt-menu-item-offset="0, 10px" data-kt-menu-item-placement="bottom-end" data-kt-menu-item-placement-rtl="bottom-start" data-kt-menu-item-toggle="dropdown" data-kt-menu-item-trigger="click">
                                                <button class="kt-menu-toggle kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost">
                                                    <i class="ki-filled ki-dots-vertical text-lg">
                                                    </i>
                                                </button>
                                                <div class="kt-menu-dropdown kt-menu-default w-full max-w-[175px]" data-kt-menu-dismiss="true">
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-pencil"></i>
                                                            </span>
                                                            <span class="kt-menu-title">Edit</span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="{{ route('auth.users.roles', $user) }}">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-pencil"></i>
                                                            </span>
                                                            <span class="kt-menu-title">Assign Roles</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="kt-card-footer justify-center md:justify-between flex-col md:flex-row gap-5 text-secondary-foreground text-sm font-medium">
                            <div class="flex items-center gap-2 order-2 md:order-1">
                            </div>
                            <div class="flex items-center gap-4 order-1 md:order-2">
                                <div class="kt-datatable-pagination" data-kt-datatable-pagination="true">
                                    {{ $users->withQueryString()->onEachSide(3)->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
