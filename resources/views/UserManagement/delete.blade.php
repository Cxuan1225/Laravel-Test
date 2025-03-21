<div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1" role="dialog"
    aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="deleteUserModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this user?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteUserForm{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}"
                    method="POST" style="display: none;">
                    @csrf
                    @method('POST')
                    <button type="submit" class="btn btn-danger btn"> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
