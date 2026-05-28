<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sport;
use Illuminate\Http\Request;

class SportController extends Controller
{
    public function index()
    {
        $pageTitle = 'Manage Sports';
        $sports = Sport::orderBy('order', 'asc')->paginate(getPaginate());
        $emptyMessage = 'No sports found';

        return view('admin.sports.index', compact('pageTitle', 'sports', 'emptyMessage'));
    }

    public function create()
    {
        $pageTitle = 'Add New Sport';
        return view('admin.sports.create', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:sports|max:100',
            'slug' => 'required|unique:sports|max:100',
            'icon' => 'nullable|max:50',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|max:500',
            'order' => 'required|integer',
        ]);

        $data = $request->only(['name', 'slug', 'icon', 'description', 'order']);
        $data['status'] = $request->status ? 1 : 0;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/images/sports'), $filename);
            $data['image'] = $filename;
        }

        Sport::create($data);

        $notify[] = ['success', 'Sport added successfully'];
        return redirect()->route('admin.sports.index')->withNotify($notify);
    }

    public function edit($id)
    {
        $sport = Sport::findOrFail($id);
        $pageTitle = 'Edit Sport - ' . $sport->name;

        return view('admin.sports.edit', compact('pageTitle', 'sport'));
    }

    public function update(Request $request, $id)
    {
        $sport = Sport::findOrFail($id);

        $request->validate([
            'name' => 'required|max:100|unique:sports,name,' . $sport->id,
            'slug' => 'required|max:100|unique:sports,slug,' . $sport->id,
            'icon' => 'nullable|max:50',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|max:500',
            'order' => 'required|integer',
        ]);

        $data = $request->only(['name', 'slug', 'icon', 'description', 'order']);
        $data['status'] = $request->status ? 1 : 0;

        if ($request->hasFile('image')) {
            if ($sport->image && file_exists(public_path('assets/images/sports/' . $sport->image))) {
                unlink(public_path('assets/images/sports/' . $sport->image));
            }
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/images/sports'), $filename);
            $data['image'] = $filename;
        }

        $sport->update($data);

        $notify[] = ['success', 'Sport updated successfully'];
        return redirect()->route('admin.sports.index')->withNotify($notify);
    }

    public function destroy($id)
    {
        $sport = Sport::findOrFail($id);

        if ($sport->image && file_exists(public_path('assets/images/sports/' . $sport->image))) {
            unlink(public_path('assets/images/sports/' . $sport->image));
        }

        $sport->delete();

        $notify[] = ['success', 'Sport deleted successfully'];
        return back()->withNotify($notify);
    }
}
