@extends('layouts.app')

@section('content')
<div class="container">
    <h2>History Kanban Card</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID History</th>
                <th>Card Title</th>
                <th>Column</th>
                <th>Position</th>
                <th>Updated Value</th>
                <th>Created Date</th>
                <th>Updated By</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($histories as $history)
            <tr>
                <td>{{ $history->id }}</td>
                <td>{{ optional($history->card)->title }}</td>
                <td>{{ optional($history->column)->name }}</td>
                <td>{{ $history->position }}</td>
                <td>
                    <!-- Tampilkan updated_value sebagai JSON terformat -->
                    <pre>{{ json_encode(json_decode($history->updated_value), JSON_PRETTY_PRINT) }}</pre>
                </td>
                <td>{{ \Carbon\Carbon::parse($history->created_date)->format('d/m/Y H:i') }}</td>
                <td>{{ optional($history->createdBy)->name }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data history.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
