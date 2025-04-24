{{-- resources/views/partials/modals.blade.php --}}
<!-- Modal Buat Kanban -->
<div class="modal fade" id="createKanbanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="createKanbanForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Buat Kanban Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="title" class="form-control mb-2" placeholder="Nama Kanban" required>
                    <select name="type" class="form-control mb-2">
                        <option value="sales">Sales</option>
                        <option value="project">Project</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Buat Kanban Column -->
<div class="modal fade" id="createColumnModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Column</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="createColumnForm">
                    @csrf
                    {{-- Ambil segmen kedua dari URL (yaitu {id}) --}}
                    @php
                    $modalKanbanId = request()->segment(2) ?? '';
                    @endphp
                    <input type="hidden" name="kanban_id" id="kanban_id" value="{{ $modalKanbanId }}">
                    <input type="text" name="name" class="form-control mb-3" placeholder="Nama Column" required>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Buat Kanban Card -->
<div class="modal fade" id="createCardModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Card</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="createCardForm">
                    @csrf
                    <input type="hidden" name="column_id" id="column_id">
                    <input type="text" name="title" class="form-control mb-3" placeholder="Nama Card" required>
                    <textarea name="description" class="form-control mb-3" placeholder="Deskripsi"></textarea>
                    <div id="cardValues"></div>
                    <button type="button" class="btn btn-secondary" id="addCardValue">+ Tambah Field</button>
                    <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Kanban -->
<div class="modal fade" id="editKanbanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editKanbanForm">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="kanban_id" id="edit_kanban_id">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kanban</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label>Judul Kanban</label>
                    <input type="text" name="title" id="edit_kanban_title" class="form-control mb-2" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Column -->
<div class="modal fade" id="editColumnModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editColumnForm">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="column_id" id="edit_column_id">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Column</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label>Nama Column</label>
                    <input type="text" name="name" id="edit_column_name" class="form-control mb-2" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Card -->
<div class="modal fade" id="editCardModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editCardForm">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="card_id" id="edit_card_id">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Card</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label>Title</label>
                    <input type="text" name="title" id="edit_card_title" class="form-control mb-2" required>
                    <label>Description</label>
                    <textarea name="description" id="edit_card_description" class="form-control mb-2"></textarea>
                    <div id="edit_card_values_container"></div>
                    <button type="button" class="btn btn-secondary" id="addEditCardValue">+ Tambah Field</button>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal History Card -->
<div class="modal fade" id="cardHistoryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title">History Card</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="cardHistoryContent">
               <!-- History content akan dimuat di sini -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Share Project -->
<div class="modal fade" id="shareProjectModal" tabindex="-1" aria-labelledby="shareProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="inviteForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="shareProjectModalLabel">Share Project: {{ $activeKanban->title ?? '' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Invitation Method</label>
                        <div>
                            <input type="radio" name="invite_type" id="invite_by_id" value="id" checked>
                            <label for="invite_by_id">By User ID</label>
                            &nbsp;&nbsp;
                            <input type="radio" name="invite_type" id="invite_by_email" value="email">
                            <label for="invite_by_email">By Email</label>
                        </div>
                    </div>
                    <div class="mb-3" id="inviteUserIdDiv">
                        <label for="invite_user_id" class="form-label">User ID</label>
                        <input type="text" name="user_id" id="invite_user_id" class="form-control" placeholder="Enter User ID">
                    </div>
                    <div class="mb-3 d-none" id="inviteEmailDiv">
                        <label for="invite_email" class="form-label">Email Address</label>
                        <input type="email" name="email" id="invite_email" class="form-control" placeholder="Enter user's email">
                    </div>
                    <div class="mb-3">
                        <label for="invite_role" class="form-label">Permission Role</label>
                        <select name="role" id="invite_role" class="form-select" required>
                            <option value="editor">Editor</option>
                            <option value="viewer">Viewer</option>
                        </select>
                    </div>
                    <input type="hidden" name="kanban_id" id="share_project_id" value="{{ $activeKanban->id ?? '' }}">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Send Invite</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- End Modal Share Project --}}
