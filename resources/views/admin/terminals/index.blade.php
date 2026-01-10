@extends('layouts.master')
@section('main-content')
@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/styles/vendor/datatables.min.css') }}">
@endsection

<div class="breadcrumb">
    <h1>POS Terminal Management</h1>
</div>

<div class="separator-breadcrumb border-top"></div>

<div class="row">
    <!-- License Status Card -->
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="card-title mb-2">License Usage</h4>
                        <p class="mb-0">
                            You are using <strong>{{ $activeCount }}</strong> out of
                            <strong>{{ $maxClients }}</strong> allowed POS Terminals.
                        </p>
                        @if ($remaining <= 0)
                            <span class="badge badge-danger mt-2">License Limit Reached</span>
                            <small class="text-danger d-block mt-1">Please upgrade your license to add more
                                terminals.</small>
                        @else
                            <span class="badge badge-success mt-2">{{ $remaining }} Slots Available</span>
                        @endif
                    </div>
                    <div class="col-md-4 text-right">
                        <!-- Add Terminal Button (Trigger Modal) -->
                        @if ($remaining > 0)
                            <button type="button" class="btn btn-primary btn-rounded" data-toggle="modal"
                                data-target="#addTerminalModal">
                                <i class="i-Add"></i> Authorize New Terminal
                            </button>
                        @else
                            <button type="button" class="btn btn-secondary btn-rounded" disabled>
                                <i class="i-Lock-2"></i> Limit Reached
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Terminal List -->
    <div class="col-md-12">
        <div class="card text-left">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="terminal_table" class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Hardware ID (HWID)</th>
                                <th>Status</th>
                                <th>Last Seen</th>
                                <th>IP Address</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($terminals as $terminal)
                                <tr>
                                    <td>{{ $terminal->name }}</td>
                                    <td><code>{{ $terminal->hwid }}</code></td>
                                    <td>
                                        @if ($terminal->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Banned</span>
                                        @endif
                                    </td>
                                    <td>{{ $terminal->last_seen_at ?? 'Never' }}</td>
                                    <td>{{ $terminal->last_ip ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('admin.terminals.delete', $terminal->id) }}"
                                            class="text-danger mr-2"
                                            onclick="return confirm('Are you sure you want to remove this terminal?');">
                                            <i class="nav-icon i-Close-Window font-weight-bold"></i> Remove
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Terminal Modal -->
<div class="modal fade" id="addTerminalModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Authorize New POS Terminal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.terminals.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="i-Information"></i> Open the Client App on the new machine. It will show you an HWID
                        if rejected. Copy it here.
                    </div>
                    <div class="form-group">
                        <label for="name">Terminal Name</label>
                        <input type="text" class="form-control" name="name" placeholder="e.g. Cashier 1" required>
                    </div>
                    <div class="form-group">
                        <label for="hwid">Hardware ID</label>
                        <input type="text" class="form-control" name="hwid"
                            placeholder="Paste HWID from Client App" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Authorize</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
