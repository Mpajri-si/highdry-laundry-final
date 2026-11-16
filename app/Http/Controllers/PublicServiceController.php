<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicServiceController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::active()
            ->when($request->q, fn ($q, $v) => $q->where('name', 'like', "%{$v}%"))
            ->when($request->type, fn ($q, $v) => $q->where('type', $v))
            ->orderBy('name')
            ->paginate(6)
            ->withQueryString();

        return view('public.services', compact('services'));
    }

    public function choose(Service $service)
    {
        abort_unless($service->is_active, 404);
        session(['selected_service' => $service->id]);
        return Auth::check()
            ? redirect()->route('orders.create')
            : redirect()->route('login')->with('error', 'Silakan login untuk memesan.');
    }
}
