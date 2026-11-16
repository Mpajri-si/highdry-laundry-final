<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::latest()->paginate(10);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:kiloan,satuan',
            'unit' => 'required|string|max:10',
            'price' => 'required|numeric|min:0',
            'estimated_days' => 'required|integer|min:1|max:30',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }
        $data['is_active'] = $request->boolean('is_active');

        Service::create($data);
        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:kiloan,satuan',
            'unit' => 'required|string|max:10',
            'price' => 'required|numeric|min:0',
            'estimated_days' => 'required|integer|min:1|max:30',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($service->image) Storage::disk('public')->delete($service->image);
            $data['image'] = $request->file('image')->store('services', 'public');
        }
        $data['is_active'] = $request->boolean('is_active');

        $service->update($data);
        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Service $service)
    {
        if ($service->orderItems()->exists()) {
            return back()->with('error', 'Layanan sudah dipakai pesanan, nonaktifkan saja.');
        }
        if ($service->image) Storage::disk('public')->delete($service->image);
        $service->delete();
        return back()->with('success', 'Layanan dihapus.');
    }
}
