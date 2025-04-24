<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KanbanCardValue;

class KanbanCardValueController extends Controller
{
    public function store(Request $request, $cardId)
    {
        $request->validate([
            'key' => 'required|string|max:255',
            'value' => 'required|string'
        ]);

        $value = KanbanCardValue::create([
            'card_id' => $cardId,
            'key' => $request->key,
            'value' => $request->value
        ]);

        return response()->json(['success' => true, 'value' => $value]);
    }
}
