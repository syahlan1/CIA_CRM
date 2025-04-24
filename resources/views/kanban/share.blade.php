@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Pengaturan Share untuk Kanban: {{ $kanban->title }}</h2>

    <!-- Bagian Anggota -->
    <h4>Anggota Proyek</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Peran</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permissions as $perm)
            <tr>
                <td>{{ $perm->user->name }}</td>
                <td>
                    <select class="form-select permission-select" data-kanban="{{ $kanban->id }}" data-user="{{ $perm->user->id }}">
                        <option value="editor" {{ $perm->role=='editor' ? 'selected' : '' }}>Editor</option>
                        <option value="viewer" {{ $perm->role=='viewer' ? 'selected' : '' }}>Viewer</option>
                        <option value="blocked" {{ $perm->role=='blocked' ? 'selected' : '' }}>Blocked</option>
                    </select>
                </td>
                <td>
                    <button class="btn btn-danger btn-sm delete-permission" data-kanban="{{ $kanban->id }}" data-user="{{ $perm->user->id }}">Hapus</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Bagian Invite -->
    <h4>Buat Link Undangan</h4>
    <form id="inviteForm">
        @csrf
        <div class="row g-2 align-items-center">
            <div class="col-auto">
                <select name="role" class="form-select">
                    <option value="editor">Editor</option>
                    <option value="viewer">Viewer</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Buat Invite</button>
            </div>
        </div>
    </form>

    <h5 class="mt-4">Undangan yang telah dibuat</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Invite Token</th>
                <th>Peran</th>
                <th>Dibuat pada</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invites as $invite)
            <tr>
                <td>{{ $invite->invite_token }}</td>
                <td>{{ ucfirst($invite->role) }}</td>
                <td>{{ $invite->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <button class="btn btn-danger btn-sm delete-invite" data-kanban="{{ $kanban->id }}" data-invite="{{ $invite->id }}">Hapus</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
    // Update permission via dropdown
    document.querySelectorAll('.permission-select').forEach(select => {
        select.addEventListener('change', function() {
            let kanbanId = this.getAttribute('data-kanban');
            let userId   = this.getAttribute('data-user');
            let role     = this.value;

            fetch(`/kanban/${kanbanId}/permission/${userId}`, {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ role: role })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    alert('Permission berhasil diubah.');
                }
            });
        });
    });

    // Delete permission
    document.querySelectorAll('.delete-permission').forEach(btn => {
        btn.addEventListener('click', function() {
            let kanbanId = this.getAttribute('data-kanban');
            let userId   = this.getAttribute('data-user');

            if(confirm('Hapus permission anggota ini?')){
                fetch(`/kanban/${kanbanId}/permission/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success){
                        location.reload();
                    }
                });
            }
        });
    });

    // Create invite
    let inviteForm = document.getElementById('inviteForm');
    inviteForm.addEventListener('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(inviteForm);
        let kanbanId = "{{ $kanban->id }}";

        fetch(`/kanban/${kanbanId}/invite`, {
            method: 'POST',
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                location.reload();
            }
        });
    });

    // Delete invite
    document.querySelectorAll('.delete-invite').forEach(btn => {
        btn.addEventListener('click', function() {
            let kanbanId = this.getAttribute('data-kanban');
            let inviteId = this.getAttribute('data-invite');

            if(confirm('Hapus invite ini?')){
                fetch(`/kanban/${kanbanId}/invite/${inviteId}`, {
                    method: 'DELETE',
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success){
                        location.reload();
                    }
                });
            }
        });
    });
</script>
@endpush
