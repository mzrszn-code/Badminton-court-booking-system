<div wire:poll.10s>
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-circle"
                    style="color: var(--success); font-size: 0.5rem; animation: pulse 2s infinite;"></i>
                Live Court Status
            </h2>
            <span style="font-size: 0.75rem; color: var(--text-muted);">
                <i class="fas fa-sync-alt"></i> Auto-updates every 10s
            </span>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1rem;">
            @foreach($this->courts as $court)
                <div
                    style="background: var(--bg-card-hover); border-radius: var(--radius-md); padding: 1rem; text-align: center; border-left: 3px solid {{ $court->status === 'available' ? 'var(--success)' : ($court->status === 'maintenance' ? 'var(--danger)' : 'var(--warning)') }};">
                    <div style="font-weight: 700; margin-bottom: 0.5rem;">{{ $court->court_name }}</div>
                    <span class="badge badge-{{ $court->status }}">{{ ucfirst($court->status) }}</span>
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">
                        {{ $court->bookings_count }} booking(s) today
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>