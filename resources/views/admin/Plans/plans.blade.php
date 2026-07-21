<?php
if (Auth('admin')->User()->dashboard_style == "light") {
    $text = "dark";
    $bg = 'light';
} else {
    $text = "light";
    $bg = 'dark';
}
?>

@extends('layouts.app')

@section('content')
    @include('admin.topmenu')
    @include('admin.sidebar')

    <div class="main-panel">
        <div class="content bg-{{ $bg }}">
            <div class="page-inner">
                <div class="mt-2 mb-4">
                    <h1 class="title1 text-{{ $text }}">Trading Pairs Management</h1>
                    <p class="text-{{ $text }} opacity-75">Manage cryptocurrency trading pairs available for investment</p>
                </div>

                <x-danger-alert/>
                <x-success-alert/>

                <!-- Add New Pair Button -->
                <div class="mb-4 row">
                    <div class="col-lg-12">
                        <a href="{{ route('admin.trading-pairs.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Add New Trading Pair
                        </a>
                    </div>
                </div>

                <!-- Trading Pairs Grid -->
                <div class="row">
                    @forelse ($tradingPairs as $pair)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card bg-{{ $bg }} shadow border-0">
                                <div class="card-body p-4">
                                    <!-- Pair Header -->
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="pair-icon me-3">
                                            <img src="{{ $pair->base_icon_url }}" alt="{{ $pair->base_symbol }}" class="rounded-circle" width="40" height="40">
                                        </div>
                                        <div>
                                            <h4 class="text-{{ $text }} mb-0">{{ $pair->base_symbol }}/{{ $pair->quote_symbol }}</h4>
                                            <small class="text-{{ $text }} opacity-75">{{ $pair->base_name }}</small>
                                        </div>
                                    </div>

                                    <!-- Current Price -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-{{ $text }} opacity-75">Current Price</span>
                                            <span class="text-{{ $text }} fw-bold" id="price-{{ $pair->id }}">
                                                {{ $settings->currency }}{{ number_format($pair->current_price, 4) }}
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-{{ $text }} opacity-75">24h Change</span>
                                            <span class="fw-bold" id="change-{{ $pair->id }}"
                                                  style="color: {{ $pair->price_change_24h >= 0 ? '#28a745' : '#dc3545' }}">
                                                {{ $pair->price_change_24h >= 0 ? '+' : '' }}{{ number_format($pair->price_change_24h, 2) }}%
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Investment Parameters -->
                                    <div class="investment-params mb-4">
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <div class="param-box p-2 rounded bg-opacity-10" style="background-color: rgba(0,123,255,0.1)">
                                                    <small class="text-{{ $text }} opacity-75 d-block">Min Investment</small>
                                                    <span class="text-{{ $text }} fw-bold">{{ $settings->currency }}{{ number_format($pair->min_investment) }}</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="param-box p-2 rounded bg-opacity-10" style="background-color: rgba(0,123,255,0.1)">
                                                    <small class="text-{{ $text }} opacity-75 d-block">Max Investment</small>
                                                    <span class="text-{{ $text }} fw-bold">{{ $settings->currency }}{{ number_format($pair->max_investment) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row text-center mt-2">
                                            <div class="col-6">
                                                <div class="param-box p-2 rounded bg-opacity-10" style="background-color: rgba(40,167,69,0.1)">
                                                    <small class="text-{{ $text }} opacity-75 d-block">Min Return</small>
                                                    <span class="text-success fw-bold">{{ number_format($pair->min_return_percentage, 1) }}%</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="param-box p-2 rounded bg-opacity-10" style="background-color: rgba(40,167,69,0.1)">
                                                    <small class="text-{{ $text }} opacity-75 d-block">Max Return</small>
                                                    <span class="text-success fw-bold">{{ number_format($pair->max_return_percentage, 1) }}%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Status Badge -->
                                    <div class="mb-3">
                                        <span class="badge {{ $pair->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $pair->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        <span class="badge bg-info ms-2">
                                            {{ $pair->investment_duration }} days
                                        </span> -
                                        <span class="badge bg-info ms-2">
                                            {{ $pair->max_investment_duration }} days
                                        </span>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="text-center">
                                        <button class="btn btn-sm btn-primary me-2" onclick="editPair({{ $pair->id }}, 'editPairModal')">
                                            <i class="fa fa-edit"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-warning me-2" onclick="togglePairStatus({{ $pair->id }})">
                                            <i class="fa fa-power-off"></i> {{ $pair->is_active ? 'Disable' : 'Enable' }}
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deletePair({{ $pair->id }})">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="card bg-{{ $bg }} shadow border-0">
                                <div class="card-body text-center p-5">
                                    <i class="fa fa-chart-line fa-3x text-{{ $text }} opacity-50 mb-3"></i>
                                    <h4 class="text-{{ $text }}">No Trading Pairs Available</h4>
                                    <p class="text-{{ $text }} opacity-75">Click the "Add New Trading Pair" button above to add your first trading pair.</p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Edit Pair Modal -->
        <div class="custom-modal" id="editPairModal" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content bg-{{ $bg }}">
                    <div class="modal-header border-0">
                        <h5 class="modal-title text-{{ $text }}">Edit Trading Pair</h5>
                        <button type="button" class="btn-close" onclick="closeModal('editPairModal')">&times;</button>
                    </div>
                    <form id="editPairForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div id="editFormContent"></div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary" onclick="closeModal('editPairModal')">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Trading Pair</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <style>
            .custom-modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1050;
                display: none;
                justify-content: center;
                align-items: center;
            }
            .modal-dialog {
                max-width: 800px;
                width: 90%;
            }
            .modal-content {
                border-radius: 5px;
                overflow: hidden;
            }
            .modal-header, .modal-footer {
                padding: 1rem;
            }
            .btn-close {
                background: none;
                border: none;
                font-size: 1.5rem;
                cursor: pointer;
            }
            .custom-modal.show {
                display: flex;
            }
        </style>

        <script>
            // Modal control functions
            function openModal(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.add('show');
                    modal.style.display = 'flex';
                    document.body.style.overflow = 'hidden'; // Prevent scrolling
                }
            }

            function closeModal(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.remove('show');
                    modal.style.display = 'none';
                    document.body.style.overflow = ''; // Restore scrolling
                }
            }

            // Close modal when clicking outside
            document.addEventListener('click', function(event) {
                if (event.target.classList.contains('custom-modal')) {
                    closeModal(event.target.id);
                }
            });

            // Auto-refresh prices (disabled until server-side route is fixed)
            // setInterval(refreshPrices, 30000);

            function refreshPrices() {
                fetch('{{ route("admin.trading-pairs.refresh-prices") }}')
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        data.forEach(pair => {
                            const priceElement = document.getElementById(`price-${pair.id}`);
                            const changeElement = document.getElementById(`change-${pair.id}`);
                            if (priceElement) {
                                priceElement.textContent = `{{ $settings->currency }}${parseFloat(pair.current_price).toFixed(4)}`;
                            }
                            if (changeElement) {
                                changeElement.textContent = `${pair.price_change_24h >= 0 ? '+' : ''}${parseFloat(pair.price_change_24h).toFixed(2)}%`;
                                changeElement.style.color = pair.price_change_24h >= 0 ? '#28a745' : '#dc3545';
                            }
                        });
                    })
                    .catch(error => console.error('Error refreshing prices:', error));
            }

            function editPair(pairId, modalId) {
                fetch(`{{ url('admin/admin/trading-pairs') }}/${pairId}/edit`)
                    .then(response => {
                        if (!response.ok) throw new Error('Failed to fetch pair data');
                        return response.json();
                    })
                    .then(pair => {
                        document.getElementById('editPairForm').action = `{{ url('admin/admin/trading-pairs') }}/${pairId}`;
                        const formContent = document.createElement('div');

                        // Define form fields
                        const fields = [
                            { label: 'CoinGecko ID *', name: 'coingecko_id', type: 'text', value: pair.coingecko_id || '', required: true },
                            { label: 'Quote Currency *', name: 'quote_symbol', type: 'select', options: [
                                { value: 'USDT', text: 'USDT', selected: pair.quote_symbol === 'USDT' },
                                { value: 'USD', text: 'USD', selected: pair.quote_symbol === 'USD' },
                                { value: 'BTC', text: 'BTC', selected: pair.quote_symbol === 'BTC' },
                                { value: 'ETH', text: 'ETH', selected: pair.quote_symbol === 'ETH' }
                            ], required: true },
                            { label: 'Minimum Investment *', name: 'min_investment', type: 'number', value: pair.min_investment || 0, step: '0.01', required: true },
                            { label: 'Maximum Investment *', name: 'max_investment', type: 'number', value: pair.max_investment || 0, step: '0.01', required: true },
                            { label: 'Minimum Return (%) *', name: 'min_return_percentage', type: 'number', value: pair.min_return_percentage || 0, step: '0.1', required: true },
                            { label: 'Maximum Return (%) *', name: 'max_return_percentage', type: 'number', value: pair.max_return_percentage || 0, step: '0.1', required: true },
                            { label: 'Trade Duration (Days) *', name: 'investment_duration', type: 'number', value: pair.investment_duration || 0, required: true },
                            { label: 'Max Trade Duration (Days) *', name: 'max_investment_duration', type: 'number', value: pair.max_investment_duration || 0, required: true },

                            { label: 'Status', name: 'is_active', type: 'select', options: [
                                { value: '1', text: 'Active', selected: pair.is_active },
                                { value: '0', text: 'Inactive', selected: !pair.is_active }
                            ] }
                        ];

                        // Generate form fields dynamically
                        fields.forEach((field, index) => {
                            const col = document.createElement('div');
                            col.className = 'col-md-6';
                            const mb3 = document.createElement('div');
                            mb3.className = 'mb-3';
                            const label = document.createElement('label');
                            label.className = `form-label text-{{ $text }}`;
                            label.textContent = field.label;
                            mb3.appendChild(label);

                            if (field.type === 'select') {
                                const select = document.createElement('select');
                                select.className = 'form-control';
                                select.name = field.name;
                                if (field.required) select.required = true;
                                field.options.forEach(opt => {
                                    const option = document.createElement('option');
                                    option.value = opt.value;
                                    option.textContent = opt.text;
                                    if (opt.selected) option.selected = true;
                                    select.appendChild(option);
                                });
                                mb3.appendChild(select);
                            } else {
                                const input = document.createElement('input');
                                input.className = 'form-control';
                                input.type = field.type;
                                input.name = field.name;
                                input.value = field.value;
                                if (field.step) input.step = field.step;
                                if (field.required) input.required = true;
                                mb3.appendChild(input);
                            }

                            col.appendChild(mb3);
                            if (index % 2 === 0) {
                                const row = document.createElement('div');
                                row.className = 'row';
                                row.appendChild(col);
                                formContent.appendChild(row);
                            } else {
                                formContent.lastChild.appendChild(col);
                            }
                        });

                        document.getElementById('editFormContent').innerHTML = '';
                        document.getElementById('editFormContent').appendChild(formContent);
                        openModal(modalId);
                    })
                    .catch(error => console.error('Error fetching pair:', error));
            }

            function togglePairStatus(pairId) {
                if (confirm('Are you sure you want to toggle this trading pair status?')) {
                    fetch(`{{ url('admin/admin/trading-pairs') }}/${pairId}/toggle-status`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert('Error updating status');
                            }
                        })
                        .catch(error => console.error('Error toggling status:', error));
                }
            }

            function deletePair(pairId) {
                if (confirm('Are you sure you want to delete this trading pair? This action cannot be undone.')) {
                    fetch(`{{ url('admin/admin/trading-pairs') }}/${pairId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert('Error deleting trading pair');
                            }
                        })
                        .catch(error => console.error('Error deleting pair:', error));
                }
            }
        </script>
    </div>
@endsection
