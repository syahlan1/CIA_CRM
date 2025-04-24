<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KanbanColumn;

class KanbanColumnController extends Controller
{
    public function store(Request $request, $kanbanId)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $column = KanbanColumn::create([
            'kanban_id' => $kanbanId,
            'name' => $request->name,
            'position' => KanbanColumn::where('kanban_id', $kanbanId)->count() + 1
        ]);

        return response()->json(['success' => true, 'column' => $column]);
    }

    public function destroy($id)
    {
        // Cari column
        $column = KanbanColumn::findOrFail($id);

        // Hapus column
        $column->delete();

        // Balikkan respons JSON
        return response()->json(['success' => true]);
    }


    public function update(Request $request, $id)
    {
        $column = KanbanColumn::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $column->update(['name' => $request->name]);

        return response()->json(['success' => true, 'column' => $column]);
    }


}
