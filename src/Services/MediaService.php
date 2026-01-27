<?php

namespace Tasmir\MediaManager\Services;

use Tasmir\MediaManager\Models\MediaFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MediaService
{
    public function getAll()
    {
        return MediaFile::latest()->paginate(30);
    }

    public function picker(Request $request)
    {
        $query = MediaFile::latest();

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('slug', 'like', "%$search%");
            });
        }

        return $query->paginate(24);
    }

    public function index($isTrash = false)
    {
        return [
            'model_data' => $isTrash ? MediaFile::onlyTrashed()->latest()->paginate(27) : $this->getAll(),
            'empty_message' => 'No media found.',
            'page_title' => $isTrash ? 'Trashed Media' : 'Media',
            'rootURL' => !$isTrash ? route('admin.files.index', ['trashed']) : route('admin.files.index'),
            'loop' => (object)[
                'edit' => 'admin.files.edit',
                'view' => 'admin.files.show',
                'delete' => 'admin.files.destroy',
                'force' => 'admin.files.force-delete',
                'restore' => 'admin.files.restore',
            ],
        ];
    }

    public function formData(MediaFile $file, string $title = 'Create Media File'): array
    {
        return [
            'page_title' => $title,
            'form' => (object)[
                'type' => $file->exists ? 'PUT' : 'POST',
                'action' => $file->exists ? route('admin.files.update', $file) : route('admin.files.store'),
            ],
            'model_data' => $file,
            'modelName' => "Media",
            'back_button' => route('admin.files.index'),
        ];
    }

    public function save(MediaFile $file, Request $request, string $message)
    {
        try {
            DB::transaction(function () use ($file, $request) {
                $file->fill($request->only(['slug', 'alt', 'caption']))->save();
            });
            return redirect()->route('admin.files.index')->with('success', $message);
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function delete(MediaFile $file)
    {
        try {
            DB::transaction(fn() => $file->delete());
            return redirect()->route('admin.files.index')->with('success', 'Media File deleted successfully');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
