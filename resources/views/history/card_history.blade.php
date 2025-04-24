@if($histories->isEmpty())
    <p class="text-center">Tidak ada history untuk card ini.</p>
@else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID History</th>
                <th>Column</th>
                <th>Position</th>
                <th>Updated Value (JSON)</th>
                <th>Created Date</th>
                <th>Updated By</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($histories as $history)
            <tr>
                <td>{{ $history->id }}</td>
                <td>{{ optional($history->column)->name }}</td>
                <td>{{ $history->position }}</td>
                <td>
                    <pre>{{ json_encode(json_decode($history->updated_value), JSON_PRETTY_PRINT) }}</pre>
                </td>
                <td>{{ \Carbon\Carbon::parse($history->created_date)->format('d/m/Y H:i') }}</td>
                <td>{{ optional($history->createdBy)->name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif
