<?php

namespace App\Http\Controllers;

use App\Models\MailingList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ListController extends Controller
{
    public function index()
    {
        $lists = MailingList::where('user_id', Auth::id())
            ->withCount('subscribers')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('lists.index', compact('lists'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $name = $request->string('name')->trim()->toString();
        $slugBase = Str::slug($name) ?: 'list';
        $slug = $slugBase . '-' . Str::lower(Str::random(8));

        $list = MailingList::create([
            'user_id' => Auth::id(),
            'name' => $name,
            'slug' => $slug,
            'description' => $request->input('description'),
            'is_public' => $request->boolean('is_public'),
            'is_default' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'List created successfully!',
            'list' => $list,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $list = MailingList::where('user_id', Auth::id())->findOrFail($id);
        $name = $request->string('name')->trim()->toString();

        $list->update([
            'name' => $name,
            'slug' => (Str::slug($name) ?: 'list') . '-' . Str::lower(Str::random(8)),
            'description' => $request->input('description'),
            'is_public' => $request->boolean('is_public'),
        ]);

        return response()->json(['success' => true, 'message' => 'List updated successfully!', 'list' => $list]);
    }

    public function destroy($id)
    {
        $list = MailingList::where('user_id', Auth::id())->findOrFail($id);

        if ($list->subscribers()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete a list that has subscribers. Remove subscribers first.',
            ], 400);
        }

        $list->delete();

        return response()->json(['success' => true, 'message' => 'List deleted successfully!']);
    }
}
