<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // GET /api/v1/services (Lihat semua layanan)
    public function index()
    {
        $services = Service::latest()->paginate(10);
        return ServiceResource::collection($services);
    }

    // POST /api/v1/services (Tambah layanan baru)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:services,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'estimated_duration' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $service = Service::create($validated);

        return (new ServiceResource($service))
            ->response()
            ->setStatusCode(201)
            ->header('Location', url("/api/v1/services/{$service->id}"));
    }

    // GET /api/v1/services/{id} (Lihat detail 1 layanan)
    public function show($id)
    {
        $service = Service::findOrFail($id);
        return new ServiceResource($service);
    }

    // PATCH/PUT /api/v1/services/{id} (Ubah data layanan)
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'code' => 'sometimes|required|string|max:10|unique:services,code,' . $service->id,
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'estimated_duration' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $service->update($validated);
        return new ServiceResource($service);
    }

    // DELETE /api/v1/services/{id} (Hapus layanan)
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return response()->noContent(); // Status 204
    }
}