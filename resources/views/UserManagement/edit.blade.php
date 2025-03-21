<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editUserForm{{ $user->id }}" action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body text-left">
                    <div class="form-group">
                        <label for="name{{ $user->id }}">Username</label>
                        <input type="text" class="form-control" id="name{{ $user->id }}" name="name"
                            value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="email{{ $user->id }}">Email</label>
                        <input type="email" class="form-control" id="email{{ $user->id }}" name="email"
                            value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="phone_number{{ $user->id }}">Phone Number</label>
                        <input type="text" class="form-control" id="phone_number{{ $user->id }}"
                            name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="status{{ $user->id }}">Status</label>
                        <select class="form-control" id="status{{ $user->id }}" name="status" required>
                            <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>
                                Active</option>
                            <option value="inactive"
                                {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
