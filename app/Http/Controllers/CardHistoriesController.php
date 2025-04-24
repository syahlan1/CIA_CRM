<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CardHistories;

class CardHistoriesController extends Controller
{
    public function index()
    {
        // Mengambil seluruh history, eager load relasi card, column, dan createdBy
        $histories = CardHistories::with(['card', 'column', 'createdBy'])
            ->orderBy('created_date', 'desc')
            ->get();

        return view('history.index', compact('histories'));
    }

    public function getByCard($cardId)
    {
        // Ambil history untuk card tertentu, eager load relasi untuk menampilkan nama
        $histories = CardHistories::with(['createdBy', 'column'])
            ->where('card_id', $cardId)
            ->orderBy('created_date', 'desc')
            ->get();

        // Return view partial untuk history card (tanpa layout penuh)
        return view('history.card_history', compact('histories'));
    }
}
