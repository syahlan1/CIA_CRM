@if ($columns->isEmpty())
    <p class="text-muted text-center">Belum ada kolom. Tambahkan kolom pertama!</p>
@endif

<div class="row">
    @foreach ($columns as $column)
    <div class="col-md-3">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0">{{ $column->name }}</h5>
                <!-- Tombol Edit Column -->
                <button class="btn btn-warning btn-sm edit-column"
                        data-id="{{ $column->id }}"
                        data-name="{{ $column->name }}">
                    Edit
                </button>
                <button class="btn btn-danger btn-sm delete-column" 
                        data-id="{{ $column->id }}">
                    Delete
                </button>
            </div>
            <div class="card-body">
                <button class="btn btn-success btn-sm create-card" 
                        data-id="{{ $column->id }}"
                        data-bs-toggle="modal" 
                        data-bs-target="#createCardModal">
                    + Tambah Card
                </button>

                <!-- LOOPING Cards di kolom ini -->
                @foreach ($column->cards as $card)
                    <div class="kanban-card p-2 border rounded mb-2">
                        <h6 class="fw-bold">{{ $card->title }}</h6>
                        <p class="text-muted">{{ $card->description }}</p>
                        <ul class="list-unstyled">
                            @foreach ($card->values as $value)
                                <li>
                                    <strong>{{ $value->key }}:</strong> {{ $value->value }}
                                </li>
                            @endforeach
                        </ul>
                        <small>
                            Dibuat pada: {{ $card->created_at->format('d/m/Y H:i') }} 
                            @if($card->created_by)
                               oleh: {{ optional($card->createdBy)->name }}
                            @endif
                        </small>
                        
                        <!-- Tombol Edit & Delete Card -->
                        <button class="btn btn-warning btn-sm edit-card" data-id="{{ $card->id }}">
                            Edit
                        </button>
                        <button class="btn btn-danger btn-sm delete-card" data-id="{{ $card->id }}">
                            Delete
                        </button>

                        <!-- Tombol History -->
                        <button class="btn btn-info btn-sm history-card" data-card-id="{{ $card->id }}">
                            Lihat History
                        </button>

                        <!-- Dropdown "Pindahkan" -->
                        <div class="dropdown d-inline-block ms-1">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" 
                                    type="button" 
                                    data-bs-toggle="dropdown">
                                Pindahkan
                            </button>
                            <ul class="dropdown-menu">
                                @foreach ($allColumns as $col)
                                    <li>
                                        <a class="dropdown-item move-card"
                                           href="#"
                                           data-card-id="{{ $card->id }}"
                                           data-column-id="{{ $col->id }}">
                                           {{ $col->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <small>
                            Diubah pada: {{ $card->updated_at->format('d/m/Y H:i') }}
                            @if($card->updated_by)
                               oleh: {{ optional($card->updatedBy)->name }}
                            @endif
                        </small>
                    </div>
                @endforeach
                <!-- END LOOPING Cards -->
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Tombol Tambah Column -->
<button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#createColumnModal">
    + Tambah Kolom
</button>

<button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#shareProjectModal">
    Share Project
</button>
