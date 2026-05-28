<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cryptocurrency;
use App\Services\BinanceService;
use Illuminate\Http\Request;

class CryptocurrencyController extends Controller
{
    protected $binanceService;

    public function __construct(BinanceService $binanceService)
    {
        $this->binanceService = $binanceService;
    }

    public function index()
    {
        $pageTitle = 'Manage Cryptocurrencies';
        $cryptocurrencies = Cryptocurrency::paginate(getPaginate());
        $emptyMessage = 'No cryptocurrencies found';

        return view('admin.cryptocurrencies.index', compact('pageTitle', 'cryptocurrencies', 'emptyMessage'));
    }

    public function create()
    {
        $pageTitle = 'Add Cryptocurrency';
        return view('admin.cryptocurrencies.create', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:cryptocurrencies|max:100',
            'symbol' => 'required|unique:cryptocurrencies|max:20',
            'binance_symbol' => 'required|max:20',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'min_bet' => 'required|numeric|gt:0',
            'max_bet' => 'required|numeric|gt:min_bet',
        ]);

        // Validate Binance symbol
        if (!$this->binanceService->validateSymbol($request->binance_symbol)) {
            $notify[] = ['error', 'Invalid Binance symbol'];
            return back()->withNotify($notify)->withInput();
        }

        $currentPrice = $this->binanceService->getPrice($request->binance_symbol);

        $data = $request->only(['name', 'symbol', 'binance_symbol', 'description', 'min_bet', 'max_bet']);
        $data['current_price'] = $currentPrice;
        $data['last_updated'] = now();
        $data['status'] = $request->status ? 1 : 0;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/images/cryptocurrencies'), $filename);
            $data['image'] = $filename;
        }

        Cryptocurrency::create($data);

        $notify[] = ['success', 'Cryptocurrency added successfully'];
        return redirect()->route('admin.cryptocurrencies.index')->withNotify($notify);
    }

    public function edit($id)
    {
        $crypto = Cryptocurrency::findOrFail($id);
        $pageTitle = 'Edit - ' . $crypto->name;

        return view('admin.cryptocurrencies.edit', compact('pageTitle', 'crypto'));
    }

    public function update(Request $request, $id)
    {
        $crypto = Cryptocurrency::findOrFail($id);

        $request->validate([
            'name' => 'required|max:100|unique:cryptocurrencies,name,' . $crypto->id,
            'symbol' => 'required|max:20|unique:cryptocurrencies,symbol,' . $crypto->id,
            'binance_symbol' => 'required|max:20',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'min_bet' => 'required|numeric|gt:0',
            'max_bet' => 'required|numeric|gt:min_bet',
        ]);

        $data = $request->only(['name', 'symbol', 'binance_symbol', 'description', 'min_bet', 'max_bet']);
        $data['status'] = $request->status ? 1 : 0;

        if ($request->hasFile('image')) {
            if ($crypto->image && file_exists(public_path('assets/images/cryptocurrencies/' . $crypto->image))) {
                unlink(public_path('assets/images/cryptocurrencies/' . $crypto->image));
            }
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/images/cryptocurrencies'), $filename);
            $data['image'] = $filename;
        }

        $crypto->update($data);

        $notify[] = ['success', 'Cryptocurrency updated successfully'];
        return redirect()->route('admin.cryptocurrencies.index')->withNotify($notify);
    }

    public function updatePrices()
    {
        $cryptocurrencies = Cryptocurrency::active()->get();
        $symbols = $cryptocurrencies->pluck('binance_symbol')->toArray();

        $prices = $this->binanceService->getPrices($symbols);

        foreach ($cryptocurrencies as $crypto) {
            if (isset($prices[$crypto->binance_symbol])) {
                $crypto->current_price = $prices[$crypto->binance_symbol];
                $crypto->last_updated = now();
                $crypto->save();
            }
        }

        $notify[] = ['success', 'Prices updated successfully'];
        return back()->withNotify($notify);
    }

    public function destroy($id)
    {
        $crypto = Cryptocurrency::findOrFail($id);

        if ($crypto->image && file_exists(public_path('assets/images/cryptocurrencies/' . $crypto->image))) {
            unlink(public_path('assets/images/cryptocurrencies/' . $crypto->image));
        }

        $crypto->delete();

        $notify[] = ['success', 'Cryptocurrency deleted successfully'];
        return back()->withNotify($notify);
    }
}
