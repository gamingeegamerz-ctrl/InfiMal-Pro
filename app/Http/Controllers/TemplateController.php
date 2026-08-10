<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('templates.index', compact('templates'));
    }

    public function create()
    {
        return view('templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:email,campaign,notification',
            'is_active' => 'boolean',
        ]);

        try {
            Template::create([
                'user_id' => Auth::id(),
                'name' => $validated['name'],
                'subject' => $validated['subject'],
                'content' => $validated['content'],
                'type' => $validated['type'],
                'is_active' => $validated['is_active'] ?? true,
                'slug' => Str::slug($validated['name']) . '-' . Str::random(6),
            ]);

            return redirect()->route('templates.index')
                ->with('success', 'Template created successfully!');
        } catch (\Throwable $e) {
            report($e);

            return redirect()->back()
                ->with('error', 'Error creating template.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        $template = Template::where('user_id', Auth::id())->findOrFail($id);

        return view('templates.edit', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:email,campaign,notification',
            'is_active' => 'boolean',
        ]);

        try {
            $template = Template::where('user_id', Auth::id())->findOrFail($id);

            $template->update([
                'name' => $validated['name'],
                'subject' => $validated['subject'],
                'content' => $validated['content'],
                'type' => $validated['type'],
                'is_active' => $validated['is_active'] ?? $template->is_active,
            ]);

            return redirect()->route('templates.index')
                ->with('success', 'Template updated successfully!');
        } catch (\Throwable $e) {
            report($e);

            return redirect()->back()
                ->with('error', 'Error updating template.')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $template = Template::where('user_id', Auth::id())->findOrFail($id);
            $template->delete();

            return redirect()->route('templates.index')
                ->with('success', 'Template deleted successfully!');
        } catch (\Throwable $e) {
            report($e);

            return redirect()->back()
                ->with('error', 'Error deleting template.');
        }
    }

    public function duplicate($id)
    {
        try {
            $template = Template::where('user_id', Auth::id())->findOrFail($id);

            $newTemplate = $template->replicate();
            $newTemplate->user_id = Auth::id();
            $newTemplate->name = $template->name . ' (Copy)';
            $newTemplate->slug = Str::slug($newTemplate->name) . '-' . Str::random(6);
            $newTemplate->save();

            return redirect()->route('templates.index')
                ->with('success', 'Template duplicated successfully!');
        } catch (\Throwable $e) {
            report($e);

            return redirect()->back()
                ->with('error', 'Error duplicating template.');
        }
    }

    public function preview($id)
    {
        $template = Template::where('user_id', Auth::id())->findOrFail($id);

        return view('templates.preview', compact('template'));
    }
}
