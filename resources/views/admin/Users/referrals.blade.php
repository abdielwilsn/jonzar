<?php
if (Auth('admin')->user()->dashboard_style == "light") {
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
                <x-danger-alert />
                <x-success-alert />

                <!-- Beginning of User Referrals -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="p-3 card bg-{{ $bg }}">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h1 class="d-inline text-primary">{{ $user->name }}'s Referrals</h1>
                                        <div class="d-inline">
                                            <div class="float-right btn-group">
                                                <a class="btn btn-primary btn-sm" href="{{ route('manageusers') }}">
                                                    <i class="fa fa-arrow-left"></i> Back to Users
                                                </a>
                                            </div>
                                            <div class="float-right mr-1 btn-group">
                                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editReferralSettingsModal">
                                                    Edit Referral Settings
                                                </button>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="mt-4">
                                        @if ($referred_users->isEmpty())
                                            <div class="alert alert-info text-{{ $text }}">
                                                No referrals found for this user.
                                            </div>
                                        @else
                                            <div class="table-responsive">
                                                <table class="table text-{{ $text }}">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Joined</th>
                                                        <th>Status</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach ($referred_users as $index => $ref)
                                                        <tr>
                                                            <td>{{ $loop->iteration + ($referred_users->currentPage() - 1) * $referred_users->perPage() }}</td>
                                                      <td>
    <a href="{{ route('viewuser', $ref->id) }}" class="text-primary">
        {{ $ref->name }}
    </a>
</td>
                                                            <td>{{ $ref->email }}</td>
                                                            <td>{{ $ref->created_at->toDayDateTimeString() }}</td>
                                                            <td>
                                                                @if ($ref->status == 'active')
                                                                    <span class="badge badge-success">Active</span>
                                                                @else
                                                                    <span class="badge badge-secondary">{{ ucfirst($ref->status ?? 'inactive') }}</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="mt-3">
                                                {{ $referred_users->links() }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of User Referrals -->

                    <!-- Edit Referral Settings Modal -->
                    <div class="modal fade" id="editReferralSettingsModal" tabindex="-1" role="dialog" aria-labelledby="editReferralSettingsModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content bg-{{ $bg }}">
                                <div class="modal-header">
                                    <h5 class="modal-title text-{{ $text }}" id="editReferralSettingsModalLabel">Edit Referral Settings for {{ $user->name }}</h5>
                                    <button type="button" class="close text-{{ $text }}" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="referralSettingsForm" action="{{ route('user.referral.update') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $userReferralSetting->id ?? '' }}">
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <div class="form-group">
                                            <label for="ref_commission" class="text-{{ $text }}">Referral Commission (%)</label>
                                            <input type="number" class="form-control bg-{{ $bg }} text-{{ $text }}" name="ref_commission" value="{{ $userReferralSetting->referral_commission ?? 0.00 }}" step="0.01" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="ref_commission1" class="text-{{ $text }}">Level 1 Commission (%)</label>
                                            <input type="number" class="form-control bg-{{ $bg }} text-{{ $text }}" name="ref_commission1" value="{{ $userReferralSetting->referral_commission1 ?? 0.00 }}" step="0.01" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="ref_commission2" class="text-{{ $text }}">Level 2 Commission (%)</label>
                                            <input type="number" class="form-control bg-{{ $bg }} text-{{ $text }}" name="ref_commission2" value="{{ $userReferralSetting->referral_commission2 ?? 0.00 }}" step="0.01" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="ref_commission3" class="text-{{ $text }}">Level 3 Commission (%)</label>
                                            <input type="number" class="form-control bg-{{ $bg }} text-{{ $text }}" name="ref_commission3" value="{{ $userReferralSetting->referral_commission3 ?? 0.00 }}" step="0.01" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="ref_commission4" class="text-{{ $text }}">Level 4 Commission (%)</label>
                                            <input type="number" class="form-control bg-{{ $bg }} text-{{ $text }}" name="ref_commission4" value="{{ $userReferralSetting->referral_commission4 ?? 0.00 }}" step="0.01" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="ref_commission5" class="text-{{ $text }}">Level 5 Commission (%)</label>
                                            <input type="number" class="form-control bg-{{ $bg }} text-{{ $text }}" name="ref_commission5" value="{{ $userReferralSetting->referral_commission5 ?? 0.00 }}" step="0.01" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="signup_bonus" class="text-{{ $text }}">Signup Bonus ($)</label>
                                            <input type="number" class="form-control bg-{{ $bg }} text-{{ $text }}" name="signup_bonus" value="{{ $userReferralSetting->signup_bonus ?? 0.00 }}" step="0.01" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Save Settings</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of Modal -->

                </div>
            </div>
        </div>

        @include('admin.Users.users_actions')

        <script>
            // AJAX form submission for referral settings
            document.getElementById('referralSettingsForm').addEventListener('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 200) {
                            alert(data.success);
                            $('#editReferralSettingsModal').modal('hide');
                        } else {
                            alert('Error saving settings');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error saving settings');
                    });
            });
        </script>
        @endsection
