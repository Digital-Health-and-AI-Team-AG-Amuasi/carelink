@extends('layouts.app')

@section('content')
    <div class="kt-container-fixed">
        <div class="grid gap-5 lg:gap-7.5">
            <div class="kt-card kt-card-grid min-w-full">
                <div class="kt-card-header flex-wrap gap-2">
                    <h3 class="kt-card-title text-sm">
                        Showing {{ min($permissions->total(), $permissions->perPage()) }} of {{ $permissions->total() }} permission(s)
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
                                    {{ $permissions->withQueryString()->onEachSide(3)->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
