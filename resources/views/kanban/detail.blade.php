{{-- resources/views/kanban/detail.blade.php --}}
@extends('layouts/main')

@section('main_content')
<div class="container">
    <div id="kanban-detail" data-id="{{ $kanban->id }}" data-type="{{ $kanban->type }}">
    <!-- Header Detail Kanban -->
        <div class="mb-4">
            <h2>Detail Kanban: {{ $kanban->title }}</h2>
            <p><strong>Type:</strong> {{ $kanban->type }}</p>
        </div>

        {{-- Include partial untuk menampilkan kolom dan card (kanban_columns) --}}
        @include('partials.kanban_columns', [
            'columns' => $columns,
            'allColumns' => $allColumns
        ])
    </div>
</div>
@endsection
