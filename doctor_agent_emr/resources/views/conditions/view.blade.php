@extends('layouts.app')

@section('title', sprintf('View visit #%s', $visit->id))

@section('action-links')
    <a class="kt-btn kt-btn-primary" href="{{ route('visits.vital.create', $visit) }}">
        Record Vitals
    </a>
    <a class="kt-btn kt-btn-primary" href="{{ route('visits.medications.create', $visit) }}">
        Record Medications
    </a>
    <a class="kt-btn kt-btn-primary" href="">
        Back to Patient
    </a>
@endsection

@section('content')
    <div class="kt-container-fixed">
        <!-- begin: grid -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-5 lg:gap-7.5">
            <div class="col-span-1">
                <div class="grid gap-5 lg:gap-7.5">
                    <div class="kt-card min-w-full">
                        <div class="kt-card-header">
                            <h3 class="kt-card-title">
                                Vitals
                            </h3>
                            <div class="kt-menu" data-kt-menu="true">
                                <div class="kt-menu-item kt-menu-item-dropdown" data-kt-menu-item-offset="0, 10px" data-kt-menu-item-placement="bottom-end" data-kt-menu-item-placement-rtl="bottom-start" data-kt-menu-item-toggle="dropdown" data-kt-menu-item-trigger="click">
                                    <button class="kt-menu-toggle kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost">
                                        <i class="ki-filled ki-dots-vertical text-lg">
                                        </i>
                                    </button>
                                    <div class="kt-menu-dropdown kt-menu-default w-full max-w-[175px]" data-kt-menu-dismiss="true">
                                        <div class="kt-menu-item">
                                            <a class="kt-menu-link" href="">
                                                <span class="kt-menu-icon">
                                                    <i class="ki-filled ki-add-files"></i>
                                                </span>
                                                <span class="kt-menu-title">Add</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="kt-card-table kt-scrollable-x-auto">
                            <div class="kt-scrollable-auto">
                                <table class="kt-table align-middle text-sm text-secondary-foreground">
                                    <tbody><tr class="bg-accent/50">
                                        <th class="text-start font-normal min-w-48 py-2.5">
                                            Measurement
                                        </th>
                                        <th class="text-end font-normal min-w-20 py-2.5">
                                            Value
                                        </th>
                                        <th class="text-end font-normal min-w-20 py-2.5">
                                            Related Visit
                                        </th>
                                        <th class="text-end font-normal min-w-20 py-2.5">Related Pregnancy</th>
                                        <th class="min-w-16">
                                        </th>
                                    </tr>
                                    @foreach($vitals as $vital)
                                        <tr>
                                            <td>
                                                {{ $vital->vitalType->name }}
                                            </td>
                                            <td class="py-2 text-end">
                                                {{ $vital->value }} {{ $vital->unit_of_measurement }}
                                            </td>
                                            <td class="py-2 text-end">
                                                Visit #{{ $vital->visit_id }} with Date: {{ $vital->visit->created_at->format('d M Y') }}
                                            </td>
                                            <td class="py-2 text-end">
                                                Pregnancy #{{ $vital->visit->pregnancy->id }} with EDD: {{ $vital->visit->pregnancy->edd }}
                                            </td>
                                            <td class="text-end">
                                                <div class="kt-menu inline-flex" data-kt-menu="true">
                                                    <div class="kt-menu-item kt-menu-item-dropdown" data-kt-menu-item-offset="0, 10px" data-kt-menu-item-placement="bottom-end" data-kt-menu-item-placement-rtl="bottom-start" data-kt-menu-item-toggle="dropdown" data-kt-menu-item-trigger="click">
                                                        <button class="kt-menu-toggle kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost">
                                                            <i class="ki-filled ki-dots-vertical text-lg">
                                                            </i>
                                                        </button>
                                                        <div class="kt-menu-dropdown kt-menu-default w-full max-w-[175px]" data-kt-menu-dismiss="true">
                                                            <div class="kt-menu-item">
                                                                <a class="kt-menu-link" href="#">
                                                                    <span class="kt-menu-icon">
                                                                        <i class="ki-filled ki-search-list"></i>
                                                                    </span>
                                                                    <span class="kt-menu-title">View</span>
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
                        </div>
                        <div class="kt-card-footer justify-center">
                            <a class="kt-link kt-link-underlined kt-link-dashed" href="">
                                View All
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-1">
                <div class="grid gap-5 lg:gap-7.5">
                    <div class="kt-card min-w-full">
                        <div class="kt-card-header">
                            <h3 class="kt-card-title">
                                Medications
                            </h3>
                            <div class="kt-menu" data-kt-menu="true">
                                <div class="kt-menu-item kt-menu-item-dropdown" data-kt-menu-item-offset="0, 10px" data-kt-menu-item-placement="bottom-end" data-kt-menu-item-placement-rtl="bottom-start" data-kt-menu-item-toggle="dropdown" data-kt-menu-item-trigger="click">
                                    <button class="kt-menu-toggle kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost">
                                        <i class="ki-filled ki-dots-vertical text-lg">
                                        </i>
                                    </button>
                                    <div class="kt-menu-dropdown kt-menu-default w-full max-w-[175px]" data-kt-menu-dismiss="true">
                                        <div class="kt-menu-item">
                                            <a class="kt-menu-link" href="">
                                                <span class="kt-menu-icon">
                                                    <i class="ki-filled ki-add-files"></i>
                                                </span>
                                                <span class="kt-menu-title">Add</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="kt-card-table kt-scrollable-x-auto">
                            <div class="kt-scrollable-auto">
                                <table class="kt-table align-middle text-sm text-secondary-foreground">
                                    <tbody><tr class="bg-accent/50">
                                        <th class="text-start font-normal min-w-48 py-2.5">
                                            Drug
                                        </th>
                                        <th class="text-end font-normal min-w-20 py-2.5">
                                            Frequency
                                        </th>
                                        <th class="text-end font-normal min-w-20 py-2.5">
                                            Duration
                                        </th>
                                        <th class="min-w-16">
                                        </th>
                                    </tr>
                                    @foreach($medications as $medication)
                                        <tr>
                                            <td>
                                                {{ $medication->drug->name }}
                                            </td>
                                            <td class="py-2 text-end">
                                                {{ $medication->frequency }}
                                            </td>
                                            <td class="py-2 text-end">
                                                {{ $medication->duration }}
                                            </td>
                                            <td class="text-end">
                                                <div class="kt-menu inline-flex" data-kt-menu="true">
                                                    <div class="kt-menu-item kt-menu-item-dropdown" data-kt-menu-item-offset="0, 10px" data-kt-menu-item-placement="bottom-end" data-kt-menu-item-placement-rtl="bottom-start" data-kt-menu-item-toggle="dropdown" data-kt-menu-item-trigger="click">
                                                        <button class="kt-menu-toggle kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost">
                                                            <i class="ki-filled ki-dots-vertical text-lg">
                                                            </i>
                                                        </button>
                                                        <div class="kt-menu-dropdown kt-menu-default w-full max-w-[175px]" data-kt-menu-dismiss="true">
                                                            <div class="kt-menu-item">
                                                                <a class="kt-menu-link" href="#">
                                                                    <span class="kt-menu-icon">
                                                                        <i class="ki-filled ki-search-list"></i>
                                                                    </span>
                                                                    <span class="kt-menu-title">View</span>
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
                        </div>
                        <div class="kt-card-footer justify-center">
                            <a class="kt-link kt-link-underlined kt-link-dashed" href="">
                                View All
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end: grid -->
    </div>
@endsection
