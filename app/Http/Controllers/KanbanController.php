<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kanban;
use App\Models\KanbanColumn;

class KanbanController extends Controller
{
    public function index(Request $request)
    {
        $kanbans = Kanban::where('user_id', auth()->id())->get();
        // Untuk homepage, tidak ada detail aktif
        return view('home', compact('kanbans'));
    }

    public function show($id)
    {
        $kanban = Kanban::findOrFail($id);
        $columns = KanbanColumn::where('kanban_id', $id)
                    ->with(['cards' => function ($query) {
                        $query->orderBy('updated_at', 'desc');
                    }, 'cards.values', 'cards.createdBy', 'cards.updatedBy'])
                    ->get();

        // Jika diperlukan untuk dropdown "move card", gunakan:
        $allColumns = $columns;

        // Sidebar data juga agar layout utama dapat menampilkan daftar kanban
        $kanbans = Kanban::where('user_id', auth()->id())->get();

        return view('kanban.detail', compact('kanban', 'columns', 'allColumns', 'kanbans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type'  => 'required|string|max:50',
        ]);
        
        $kanban = Kanban::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'type' => $request->type
        ]);

        return response()->json(['success' => true, 'kanban' => $kanban]);
    }

    public function destroy($id)
    {
        Kanban::where('id', $id)->where('user_id', auth()->id())->delete();
        return response()->json(['success' => true]);
    }

    public function showColumns($kanbanId)
    {
        try {
            $kanban = Kanban::findOrFail($kanbanId);
            // Mengurutkan card berdasarkan updated_at secara menurun (terbaru di atas)
            $columns = $kanban->columns()->with([
                'cards' => function($query) {
                    $query->orderBy('updated_at', 'desc');
                },
                'cards.values'
            ])->get();

            return view('partials.kanban_columns', [
                'columns'    => $columns,
                'allColumns' => $columns // Jika diperlukan untuk dropdown "move card"
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function getColumns($id)
    {
        $columns = KanbanColumn::where('kanban_id', $id)->with('cards.values')->get();

        return response()->json($columns);
    }

    public function update(Request $request, $id)
    {
        $kanban = Kanban::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
        ]);
    
        $kanban->update([
            'title' => $request->title,
            // HAPUS baris 'type' => $request->type
        ]);
    
        return response()->json(['success' => true, 'kanban' => $kanban]);
    }

}
