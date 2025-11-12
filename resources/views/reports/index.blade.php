@extends('layouts.app')
@section('title', 'Reports | Mifty')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-between align-items-center" style="margin-bottom:8px;">
                <h4 class="page-title mb-0" style="font-size:20px; font-weight:600;">Reports Dashboard</h4>
                <ol class="breadcrumb mb-0" style="background:none;">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Reports</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row align-items-end mb-3">
        <div class="col-md-3">
            <label for="filter_from" class="form-label" style="font-size:12px;">Date From</label>
            <input type="date" id="filter_from" class="form-control form-control-sm" value="{{ date('Y-m-d', strtotime('-30 days')) }}">
        </div>
        <div class="col-md-3">
            <label for="filter_to" class="form-label" style="font-size:12px;">Date To</label>
            <input type="date" id="filter_to" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
        </div>
        <div class="col-md-3">
            <label for="filter_period" class="form-label" style="font-size:12px;">Period</label>
            <select id="filter_period" class="form-select form-select-sm">
                <option value="day" selected>Daily</option>
                <option value="month">Monthly</option>
                <option value="year">Yearly</option>
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary btn-sm w-100" id="applyFiltersBtn" style="font-size:14px;">
                <i class="las la-filter me-2"></i>Filter
            </button>
        </div>
    </div>

    <!-- Chart Panels -->
    <div class="row g-3">
        <div class="col-lg-4 col-md-6">
            <div class="card shadow-none border rounded-3 mb-0" style="background:#fff;">
                <div class="card-header bg-transparent border-bottom-0 p-2">
                    <span style="font-size:15px;font-weight:500;">Technician Performance</span>
                </div>
                <div class="card-body p-2">
                    <canvas id="technicianChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card shadow-none border rounded-3 mb-0" style="background:#fff;">
                <div class="card-header bg-transparent border-bottom-0 p-2">
                    <span style="font-size:15px;font-weight:500;">Top Devices</span>
                </div>
                <div class="card-body p-2">
                    <canvas id="deviceChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card shadow-none border rounded-3 mb-0" style="background:#fff;">
                <div class="card-header bg-transparent border-bottom-0 p-2">
                    <span style="font-size:15px;font-weight:500;">Water & Physical Damage</span>
                </div>
                <div class="card-body p-2">
                    <canvas id="waterPhysicalDamageChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card shadow-none border rounded-3 mb-0" style="background:#fff;">
                <div class="card-header bg-transparent border-bottom-0 p-2">
                    <span style="font-size:15px;font-weight:500;">Problem Breakdown</span>
                </div>
                <div class="card-body p-2">
                    <canvas id="problemChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card shadow-none border rounded-3 mb-0" style="background:#fff;">
                <div class="card-header bg-transparent border-bottom-0 p-2">
                    <span style="font-size:15px;font-weight:500;">Device Condition</span>
                </div>
                <div class="card-body p-2">
                    <canvas id="conditionChart" height="120"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8 col-md-12">
            <div class="card shadow-none border rounded-3 mb-0" style="background:#fff;">
                <div class="card-header bg-transparent border-bottom-0 p-2">
                    <span style="font-size:15px;font-weight:500;">Customer Flow </span>
                </div>
                <div class="card-body p-2">
                    <canvas id="customerFlowChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-12">
            <div class="card shadow-none border rounded-3 mb-0" style="background:#fff;">
                <div class="card-header bg-transparent border-bottom-0 p-2">
                    <span style="font-size:15px;font-weight:500;">Revenue</span>
                </div>
                <div class="card-body p-2">
                    <canvas id="revenueChart" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@include('reports.scripts.reports_script')
@endsection
