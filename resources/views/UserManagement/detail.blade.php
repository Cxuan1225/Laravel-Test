<div class="modal fade" id="viewUserModal{{ $user->id }}" tabindex="-1" role="dialog"
    aria-labelledby="viewUserLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewUserLabel{{ $user->id }}"><i class="fas fa-user"></i> User Details
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-left">
                <p><strong>User ID:</strong> {{ $user->id }}</p>
                <p><strong>Name:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Phone Number:</strong> {{ $user->phone_number }}
                </p>
                <p><strong>Status:</strong>
                    <span
                        class="badge
                        @if ($user->status == 'active') badge-success
                        @elseif($user->status == 'inactive') badge-secondary
                        @else badge-warning @endif">
                        {{ ucfirst($user->status) }}
                    </span>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
