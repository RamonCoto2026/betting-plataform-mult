<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CasinoGame;
use Illuminate\Http\Request;

class CasinoGameController extends Controller
{
    public function index()
    {
        $pageTitle = 'Casino Games Management';
        $games = CasinoGame::paginate(getPaginate());
        $emptyMessage = 'No games found';
        $categories = [
            CasinoGame::SLOTS => 'Slots',
            CasinoGame::TABLE_GAMES => 'Table Games',
            CasinoGame::LIVE_CASINO => 'Live Casino',
            CasinoGame::CARD_GAMES => 'Card Games',
            CasinoGame::VIDEO_POKER => 'Video Poker',
            CasinoGame::SPECIALTY => 'Specialty Games',
        ];

        return view('admin.casino.games.index', compact('pageTitle', 'games', 'emptyMessage', 'categories'));
    }

    public function create()
    {
        $pageTitle = 'Add Casino Game';
        $categories = [
            CasinoGame::SLOTS => 'Slots',
            CasinoGame::TABLE_GAMES => 'Table Games',
            CasinoGame::LIVE_CASINO => 'Live Casino',
            CasinoGame::CARD_GAMES => 'Card Games',
            CasinoGame::VIDEO_POKER => 'Video Poker',
            CasinoGame::SPECIALTY => 'Specialty Games',
        ];

        return view('admin.casino.games.create', compact('pageTitle', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:casino_games|max:100',
            'slug' => 'required|unique:casino_games|max:100',
            'category' => 'required|in:slots,table_games,live_casino,card_games,video_poker,specialty',
            'provider' => 'required|max:100',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'rtp' => 'required|numeric|between:50,99.99',
            'min_bet' => 'required|numeric|gt:0',
            'max_bet' => 'required|numeric|gt:min_bet',
            'order' => 'required|integer',
        ]);

        $data = $request->only(['name', 'slug', 'category', 'provider', 'rtp', 'min_bet', 'max_bet', 'order', 'description']);
        $data['status'] = $request->status ? 1 : 0;
        $data['featured'] = $request->featured ? 1 : 0;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/images/casino-games'), $filename);
            $data['image'] = $filename;
        }

        CasinoGame::create($data);

        $notify[] = ['success', 'Casino game added successfully'];
        return redirect()->route('admin.casino.games.index')->withNotify($notify);
    }

    public function edit($id)
    {
        $game = CasinoGame::findOrFail($id);
        $pageTitle = 'Edit - ' . $game->name;
        $categories = [
            CasinoGame::SLOTS => 'Slots',
            CasinoGame::TABLE_GAMES => 'Table Games',
            CasinoGame::LIVE_CASINO => 'Live Casino',
            CasinoGame::CARD_GAMES => 'Card Games',
            CasinoGame::VIDEO_POKER => 'Video Poker',
            CasinoGame::SPECIALTY => 'Specialty Games',
        ];

        return view('admin.casino.games.edit', compact('pageTitle', 'game', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $game = CasinoGame::findOrFail($id);

        $request->validate([
            'name' => 'required|max:100|unique:casino_games,name,' . $game->id,
            'slug' => 'required|max:100|unique:casino_games,slug,' . $game->id,
            'category' => 'required|in:slots,table_games,live_casino,card_games,video_poker,specialty',
            'provider' => 'required|max:100',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'rtp' => 'required|numeric|between:50,99.99',
            'min_bet' => 'required|numeric|gt:0',
            'max_bet' => 'required|numeric|gt:min_bet',
            'order' => 'required|integer',
        ]);

        $data = $request->only(['name', 'slug', 'category', 'provider', 'rtp', 'min_bet', 'max_bet', 'order', 'description']);
        $data['status'] = $request->status ? 1 : 0;
        $data['featured'] = $request->featured ? 1 : 0;

        if ($request->hasFile('image')) {
            if ($game->image && file_exists(public_path('assets/images/casino-games/' . $game->image))) {
                unlink(public_path('assets/images/casino-games/' . $game->image));
            }
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/images/casino-games'), $filename);
            $data['image'] = $filename;
        }

        $game->update($data);

        $notify[] = ['success', 'Casino game updated successfully'];
        return redirect()->route('admin.casino.games.index')->withNotify($notify);
    }

    public function destroy($id)
    {
        $game = CasinoGame::findOrFail($id);

        if ($game->image && file_exists(public_path('assets/images/casino-games/' . $game->image))) {
            unlink(public_path('assets/images/casino-games/' . $game->image));
        }

        $game->delete();

        $notify[] = ['success', 'Casino game deleted successfully'];
        return back()->withNotify($notify);
    }
}
