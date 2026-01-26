<?php

namespace Tasmir\MediaManager\Http\Controllers;

use App\Http\Controllers\Controller;
use Tasmir\MediaManager\Http\Traits\MediaHelper;
use Tasmir\MediaManager\Models\MediaFile;
use Tasmir\MediaManager\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MediaFileController extends Controller
{
    use MediaHelper;

    protected MediaService $service;

    public function __construct(MediaService $service)
    {
        $this->middleware('auth')->except(['show_file']);
        $this->service = $service;
    }

    public function index()
    {
        $page_date = $this->service->index(request()->exists('trashed'));
        return view('media-manager::index', compact('page_date'));
    }

    public function picker(Request $request)
    {
        $medias = $this->service->picker($request);
        if ($request->ajax() && $request->has('list_only')) {
            return view('media-manager::partials.picker-list', compact('medias'))->render();
        }
        return view('media-manager::picker', compact('medias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp,svg|max:' . ($this->minimumUpSize() / 1024),
        ]);

        if ($request->hasFile('file')) {
            $data = [
                'file' => $request->file('file'),
                'path' => 'media',
                'quality' => 100,
            ];
            if ($request->filled('prefix')) $data['prefix'] = $request->get('prefix');
            
            $media_id = $this->uploadMediaFile($data);
            $media = MediaFile::find($media_id);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'media' => $media,
                    'media_url' => route('file.show', [$media->slug]),
                    'edit_url' => route('admin.files.edit', $media),
                    'delete_url' => route('admin.files.destroy', $media),
                    'message' => 'File uploaded successfully'
                ]);
            }

            return redirect()->route('admin.files.index')->with('success', 'Media File uploaded successfully');
        }

        return $request->ajax() 
            ? response()->json(['success' => false, 'message' => 'No file uploaded'], 400)
            : back()->with('error', 'No file uploaded');
    }

    public function edit(MediaFile $file)
    {
        return view('media-manager::edit', [
            'page_date' => $this->service->formData($file, 'Edit Media File')
        ]);
    }

    public function update(Request $request, MediaFile $file)
    {
        return $this->service->save($file, $request, 'Media updated successfully');
    }

    public function destroy(MediaFile $file)
    {
        return $this->service->delete($file);
    }

    public function ckImageUpload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $data = [
                'file' => $request->file('upload'),
                'path' => 'media/ckeditor',
                'quality' => 100,
            ];
            $media_id = $this->uploadMediaFile($data);
            $media = MediaFile::find($media_id);

            return response()->json([
                'uploaded' => 1,
                'fileName' => $media->name,
                'url' => route('file.show', [$media->slug])
            ]);
        }
        return response()->json(['uploaded' => 0, 'error' => ['message' => 'No file uploaded']]);
    }

    public function show_file($slug): void
    {
        $media = MediaFile::where('slug', $slug)->firstOrFail();
        $this->image_url($media->path);
    }

    private function image_url($path)
    {
        $fullPath = public_path($path);
        if (!File::exists($fullPath)) {
            $fullPath = public_path('uploads/files/notfound.png');
        }
        
        $info = getimagesize($fullPath);
        if ($info) {
            header("Content-type: " . $info['mime']);
            readfile($fullPath);
        }
    }
}
