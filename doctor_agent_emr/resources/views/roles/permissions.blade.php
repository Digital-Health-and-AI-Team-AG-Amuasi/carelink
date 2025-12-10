@extends('layouts.app')

@section('title', sprintf('Manage "%s" Role Permissions', $role->name))

@section('content')
    <div class="kt-container-fixed">
        <div class="grid gap-5 lg:gap-7.5">
            <div class="kt-card kt-card-grid min-w-full">
                <div class="kt-card-header flex-wrap gap-2">
                    <h3 class="kt-card-title text-sm">
                        Showing all permissions
                    </h3>
                </div>

                <div class="kt-card-content">
                    <form class="kt-form" method="post" action="{{ route('auth.roles.permissions.update', $role->id) }}">
                        <div class="grid" data-kt-datatable="true" data-kt-datatable-page-size="10">
                            <div class="kt-scrollable-x-auto">
                                @csrf
                                @method('PATCH')

                                <table class="kt-table table-auto kt-table-border">
                                    <thead>
                                    <tr>
                                        <th class="min-w-[200px]">
                                        <span class="kt-table-col asc">
                                            <span class="kt-table-col-label">Name</span>
                                       </span>
                                        </th>
                                        <th class="w-[60px]">
                                            <input
                                                type="checkbox"
                                                class="kt-checkbox"
                                                id="check-all"
                                                value="1"
                                            />
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($permissions as $permission)
                                        <tr>
                                            <td>
                                                <div class="flex items-center gap-1.5 text-foreground font-normal">
                                                    {{ $permission->name }}
                                                </div>
                                            </td>
                                            <td>
                                                <input
                                                    type="checkbox"
                                                    class="kt-checkbox js-permission-check-box"
                                                    name="permissions[]"
                                                    value="{{ $permission->id }}"
                                                    @if ($rolePermissions->contains($permission->id))
                                                        checked
                                                    @endif
                                                />
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="kt-card-footer justify-center md:justify-between flex-col md:flex-row gap-5 text-secondary-foreground text-sm font-medium">
                                <div class="flex items-center gap-2 order-2 md:order-1">
                                    <input type="submit" class="kt-btn" value="Update">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('check-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.js-permission-check-box');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    </script>
@endpush
