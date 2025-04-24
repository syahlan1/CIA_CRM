{{-- resources/views/layouts/main.blade.php --}}
@extends('layouts.app')  {{-- assume this has your HTML <head> with CSRF meta, Bootstrap, etc. --}}

    @section('content')
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <h4>Daftar Kanban</h4>
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createKanbanModal">
                + Buat Kanban
            </button>
    
            <ul class="list-group" id="kanban-list">
                @foreach($kanbans as $k)
                    <li class="list-group-item kanban-item" data-id="{{ $k->id }}" data-type="{{ $k->type }}">
                        <a href="{{ route('kanban.show', $k->id) }}" class="flex-grow-1 text-decoration-none">
                            {{ $k->title }}
                        </a>
                        <button class="btn btn-warning btn-sm float-end ms-1 edit-kanban"
                                data-id="{{ $k->id }}"
                                data-title="{{ $k->title }}">
                            Edit
                        </button>
                        <button class="btn btn-danger btn-sm float-end delete-kanban" data-id="{{ $k->id }}">
                            Hapus
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
    
        <!-- Main content (detail) -->
        <div class="col-md-9">
            @yield('main_content')
        </div>
    </div>
    
    @include('partials.modals')
    
    @endsection
    