<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KanbanCard;
use App\Models\KanbanCardValue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; 

class KanbanCardController extends Controller
{
    public function store(Request $request, $columnId)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'key'         => 'array',
            'value'       => 'array',
            'key.*'       => 'string|max:255',
            'value.*'     => 'string|max:255',
        ]);

        $card = KanbanCard::create([
            'column_id'   => $columnId,
            'title'       => $request->title,
            'description' => $request->description,
            'position'    => KanbanCard::where('column_id', $columnId)->count() + 1,
            'created_by'  => auth()->id(),
            'updated_by'  => auth()->id(),
        ]);

        if ($request->has('key') && $request->has('value')) {
            $keys   = $request->input('key', []);
            $values = $request->input('value', []);

            foreach ($keys as $i => $k) {
                if (!empty($k)) {
                    KanbanCardValue::create([
                        'card_id' => $card->id,
                        'key'     => $k,
                        'value'   => $values[$i] ?? ''
                    ]);
                }
            }
        }

        return response()->json(['success' => true, 'card' => $card]);
    }

    public function update(Request $request, $id)
    {
        $card = KanbanCard::findOrFail($id);

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'key'         => 'array',
            'value'       => 'array',
        ]);

        // Update data card dan simpan user yang mengupdate
        $card->update([
            'title'       => $request->title,
            'description' => $request->description,
            'updated_by'  => auth()->id(),
        ]);

        // Hapus card values lama dan simpan yang baru
        $card->values()->delete();
        if ($request->has('key') && $request->has('value')) {
            $keys   = $request->input('key', []);
            $values = $request->input('value', []);
            foreach ($keys as $i => $k) {
                if (!empty($k)) {
                    $card->values()->create([
                        'key'   => $k,
                        'value' => $values[$i] ?? ''
                    ]);
                }
            }
        }

        // Refresh data card dengan relationship values
        $card->load('values');

        // Buat data updated_value dalam format JSON
        $updatedValue = json_encode([
            'title'       => $card->title,
            'description' => $card->description,
            'values'      => $card->values,
        ]);

        // Simpan history, lengkap dengan field `id`
        DB::table('card_histories')->insert([
            'id'            => Str::uuid()->toString(),  // ← UUID baru
            'card_id'       => $card->id,
            'column_id'     => $card->column_id,
            'position'      => $card->position,
            'updated_value' => $updatedValue,
            'created_date'  => now(),
            'created_by'    => auth()->id(),
        ]);

        return response()->json(['success' => true, 'card' => $card]);
    }

    public function get($id)
    {
        $card = KanbanCard::with('values')->findOrFail($id);
        return response()->json($card);
    }

    public function destroy($id)
    {
        $card = KanbanCard::findOrFail($id);
        $card->delete();
        return response()->json(['success' => true]);
    }

    public function move(Request $request, $id)
    {
        $request->validate([
            'column_id' => 'required|integer|exists:kanban_columns,id',
        ]);

        $card = KanbanCard::findOrFail($id);
        $card->column_id = $request->column_id;
        $card->updated_by = auth()->id();
        $card->save();

        // Optionally, simpan juga history dari perpindahan card
        $updatedValue = json_encode([
            'title'       => $card->title,
            'description' => $card->description,
            'values'      => $card->values,
        ]);
        DB::table('card_histories')->insert([
            'id'            => Str::uuid()->toString(),  // ← UUID baru
            'card_id'       => $card->id,
            'column_id'     => $card->column_id,
            'position'      => $card->position,
            'updated_value' => $updatedValue,
            'created_date'  => now(),
            'created_by'    => auth()->id(),
        ]);

        return response()->json(['success' => true]);
    }
}
