<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KanbanController;
use App\Http\Controllers\KanbanColumnController;
use App\Http\Controllers\KanbanCardController;
use App\Http\Controllers\KanbanCardValueController;
use App\Http\Controllers\CardHistoriesController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    // Kanban
    Route::get('/', [KanbanController::class, 'index'])->name('home');
    Route::post('/kanban', [KanbanController::class, 'store'])->name('kanban.store');
    Route::get('/kanban/{id}', [KanbanController::class, 'show']);
    Route::get('/kanban/{id}', [KanbanController::class, 'show'])->name('kanban.show');
    Route::delete('/kanban/{id}', [KanbanController::class, 'destroy']);

    // >>> Tambahkan route update Kanban <<<
    Route::put('/kanban/{id}', [KanbanController::class, 'update'])->name('kanban.update');

    // Kanban Columns
    Route::get('/kanban/{kanban}/columns', [KanbanController::class, 'showColumns']);
    Route::post('/kanban/{kanban}/columns', [KanbanColumnController::class, 'store'])->name('kanban.columns.store');
    Route::delete('/kanban-column/{id}', [KanbanColumnController::class, 'destroy']);
    Route::get('/kanban/{id}/columns', [KanbanColumnController::class, 'getKanbanById']);
    Route::put('/kanban-column/{id}', [KanbanColumnController::class, 'update'])->name('kanban.column.update');

    // Kanban Cards
    Route::post('/kanban-column/{column}/cards', [KanbanCardController::class, 'store'])->name('kanban.cards.store');
    Route::get('/kanban-card/{id}', [KanbanCardController::class, 'get'])->name('kanban.cards.show');
    Route::delete('/kanban-card/{id}', [KanbanCardController::class, 'destroy']);
    Route::post('/kanban-card/{id}/move', [KanbanCardController::class, 'move'])->name('kanban.card.move');
    Route::put('/kanban-card/{id}', [KanbanCardController::class, 'update'])->name('kanban.card.update');

    // Kanban Card Values
    Route::post('/kanban-card/{card}/values', [KanbanCardValueController::class, 'store'])->name('kanban.card.values.store');
    Route::delete('/kanban-card-value/{id}', [KanbanCardValueController::class, 'destroy']);

    //histories
    Route::get('/history', [CardHistoriesController::class, 'index'])->name('history.index');
    Route::get('/kanban-card/{id}/history', [CardHistoriesController::class, 'getByCard'])->name('kanban.card.history');
    
    // Halaman Share Proyek
    Route::get('/kanban/{kanban}/share', [\App\Http\Controllers\KanbanShareController::class, 'index'])->name('kanban.share');

    // Update permission anggota (misalnya, dari dropdown)
    Route::post('/kanban/{kanban}/permission/{user}', [\App\Http\Controllers\KanbanShareController::class, 'updatePermission'])->name('kanban.permission.update');

    // Delete permission anggota
    Route::delete('/kanban/{kanban}/permission/{user}', [\App\Http\Controllers\KanbanShareController::class, 'deletePermission'])->name('kanban.permission.delete');

    // Buat undangan (invite)
    Route::post('/kanban/{kanban}/invite', [\App\Http\Controllers\KanbanShareController::class, 'createInvite'])->name('kanban.invite.create');

    // Hapus undangan
    Route::delete('/kanban/{kanban}/invite/{invite}', [\App\Http\Controllers\KanbanShareController::class, 'deleteInvite'])->name('kanban.invite.delete');

});
