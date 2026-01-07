@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
    <div class="page-header">
        <h1 class="page-title">My Profile</h1>
        <p class="page-subtitle">Manage your account settings and personal information.</p>
    </div>

    <div class="grid grid-cols-2" style="grid-template-columns: 1fr 400px;">
        <!-- Profile Form -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-user-edit" style="color: var(--primary); margin-right: 0.5rem;"></i>
                    Account Settings</h2>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="tel" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}"
                        placeholder="e.g., 0123456789">
                </div>

                <hr style="border-color: var(--border-color); margin: 2rem 0;">

                <h3 style="font-weight: 600; margin-bottom: 1rem;">Change Password</h3>
                <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 1.5rem;">Leave blank if you
                    don't want to change your password.</p>

                <div class="form-group">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-control"
                        placeholder="Enter current password">
                </div>

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-control" placeholder="Enter new password">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" class="form-control"
                            placeholder="Confirm new password">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </form>
        </div>

        <!-- Profile Summary & Activity -->
        <div>
            <div class="card" style="text-align: center; margin-bottom: 1.5rem;">
                <!-- Profile Picture Section -->
                <div style="position: relative; width: 120px; height: 120px; margin: 0 auto 1.5rem;">
                    @if($user->getProfilePictureUrl())
                        <img src="{{ $user->getProfilePictureUrl() }}" alt="{{ $user->name }}" id="profilePreview"
                            style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid var(--primary);">
                    @else
                        <div id="profileInitial"
                            style="width: 120px; height: 120px; background: var(--gradient-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 3rem; font-weight: 800; color: white; border: 4px solid var(--primary);">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <img src="" alt="" id="profilePreview"
                            style="display: none; width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid var(--primary);">
                    @endif

                    <!-- Camera upload button -->
                    <label for="profilePictureInput"
                        style="position: absolute; bottom: 0; right: 0; width: 36px; height: 36px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 3px solid var(--bg-card); transition: var(--transition-fast);"
                        class="upload-btn">
                        <i class="fas fa-camera" style="color: white; font-size: 0.875rem;"></i>
                    </label>
                </div>

                <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.25rem;">{{ $user->name }}</h2>
                <p style="color: var(--text-secondary);">{{ $user->email }}</p>
                <span class="badge badge-{{ $user->role === 'admin' ? 'approved' : 'available' }}"
                    style="margin-top: 0.5rem;">
                    {{ ucfirst($user->role) }}
                </span>

                <!-- Profile Picture Upload Form -->
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="pictureForm"
                    style="margin-top: 1rem;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="name" value="{{ $user->name }}">
                    <input type="hidden" name="email" value="{{ $user->email }}">
                    <input type="hidden" name="phone" value="{{ $user->phone }}">
                    <input type="file" name="profile_picture" id="profilePictureInput" accept="image/*"
                        style="display: none;">

                    <div id="pictureActions" style="display: none; gap: 0.5rem; justify-content: center; margin-top: 1rem;">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-check"></i> Save Photo
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="cancelPictureChange()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                    </div>
                </form>

                @if($user->profile_picture)
                    <form method="POST" action="{{ route('profile.update') }}" style="margin-top: 0.75rem;"
                        id="removePictureForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="name" value="{{ $user->name }}">
                        <input type="hidden" name="email" value="{{ $user->email }}">
                        <input type="hidden" name="phone" value="{{ $user->phone }}">
                        <input type="hidden" name="remove_picture" value="1">
                        <button type="submit" class="btn btn-danger btn-sm" id="removeBtn">
                            <i class="fas fa-trash"></i> Remove Photo
                        </button>
                    </form>
                @endif

                <div
                    style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
                    <div>
                        <div style="font-size: 1.5rem; font-weight: 800; color: var(--primary);">
                            {{ $user->bookings()->count() }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary);">Total Bookings</div>
                    </div>
                    <div>
                        <div style="font-size: 1.5rem; font-weight: 800; color: var(--success);">
                            {{ $user->bookings()->where('status', 'approved')->get()->filter(fn($b) => $b->hasSessionEnded())->count() }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary);">Completed</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2 class="card-title"><i class="fas fa-history"
                            style="color: var(--secondary); margin-right: 0.5rem;"></i> Recent Activity</h2>
                    <a href="{{ route('profile.activity') }}" class="btn btn-sm btn-secondary">View All</a>
                </div>

                @forelse($recentActivity as $activity)
                    <div
                        style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 0; border-bottom: 1px solid var(--border-color);">
                        <div
                            style="width: 36px; height: 36px; background: var(--bg-card-hover); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-circle-dot" style="color: var(--primary); font-size: 0.75rem;"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-size: 0.875rem; font-weight: 500;">
                                {{ ucfirst(str_replace('_', ' ', $activity->activity_type)) }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">
                                {{ $activity->activity_time->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <p style="color: var(--text-secondary); text-align: center; padding: 1rem;">No recent activity</p>
                @endforelse
            </div>

            <!-- Delete Account Section -->
            <div class="card" style="border: 1px solid var(--danger); margin-top: 1.5rem;">
                <div class="card-header">
                    <h2 class="card-title" style="color: var(--danger);"><i class="fas fa-exclamation-triangle"
                            style="margin-right: 0.5rem;"></i> Danger Zone</h2>
                </div>
                <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 1rem;">
                    Once you delete your account, there is no going back. Please be certain.
                </p>
                <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteModal').style.display='flex'">
                    <i class="fas fa-trash-alt"></i> Delete Account
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); align-items: center; justify-content: center; z-index: 1000;">
        <div class="card" style="max-width: 400px; width: 90%; margin: 0; animation: slideUp 0.3s ease;">
            <div class="card-header">
                <h2 class="card-title" style="color: var(--danger);"><i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem;"></i> Delete Account</h2>
            </div>
            <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
                This action is <strong>permanent</strong> and cannot be undone. All your data, bookings, and activity history will be deleted.
            </p>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')
                <div class="form-group">
                    <label class="form-label">Enter your password to confirm</label>
                    <input type="password" name="password" class="form-control" placeholder="Your current password" required>
                    @error('password')
                        <span style="color: var(--danger); font-size: 0.75rem;">{{ $message }}</span>
                    @enderror
                </div>
                <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                    <button type="button" class="btn btn-secondary" style="flex: 1;" onclick="document.getElementById('deleteModal').style.display='none'">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger" style="flex: 1;">
                        <i class="fas fa-trash-alt"></i> Delete Forever
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .upload-btn:hover {
            transform: scale(1.1);
            background: var(--primary-dark) !important;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <script>
        document.getElementById('profilePictureInput').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const preview = document.getElementById('profilePreview');
                    const initial = document.getElementById('profileInitial');
                    const actions = document.getElementById('pictureActions');
                    const removeBtn = document.getElementById('removeBtn');

                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    if (initial) initial.style.display = 'none';
                    actions.style.display = 'flex';
                    if (removeBtn) removeBtn.style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });

        function cancelPictureChange() {
            const preview = document.getElementById('profilePreview');
            const initial = document.getElementById('profileInitial');
            const actions = document.getElementById('pictureActions');
            const removeBtn = document.getElementById('removeBtn');
            const input = document.getElementById('profilePictureInput');

            input.value = '';
            actions.style.display = 'none';

            @if($user->getProfilePictureUrl())
                preview.src = "{{ $user->getProfilePictureUrl() }}";
                preview.style.display = 'block';
                if (removeBtn) removeBtn.style.display = 'inline-flex';
            @else
                preview.style.display = 'none';
                if (initial) initial.style.display = 'flex';
            @endif
        }
    </script>
@endsection