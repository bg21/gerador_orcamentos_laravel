<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Auth::user()->services()->latest()->paginate(10);
        return view('services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Sanitizar o preço caso venha formatado em BRL (ex: 1.500,00 ou 150,00)
        if ($request->has('default_price')) {
            $price = $request->input('default_price');
            if (is_string($price)) {
                $price = str_replace('.', '', $price);
                $price = str_replace(',', '.', $price);
                $request->merge(['default_price' => $price]);
            }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'default_price' => 'required|numeric|min:0',
        ]);

        Auth::user()->services()->create($validated);

        return redirect()->route('services.index')->with('success', 'Serviço cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        if ($service->user_id !== Auth::id()) {
            abort(403, 'Acesso não autorizado.');
        }

        return redirect()->route('services.edit', $service);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        if ($service->user_id !== Auth::id()) {
            abort(403, 'Acesso não autorizado.');
        }

        return view('services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        if ($service->user_id !== Auth::id()) {
            abort(403, 'Acesso não autorizado.');
        }

        // Sanitizar o preço caso venha formatado em BRL
        if ($request->has('default_price')) {
            $price = $request->input('default_price');
            if (is_string($price)) {
                $price = str_replace('.', '', $price);
                $price = str_replace(',', '.', $price);
                $request->merge(['default_price' => $price]);
            }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'default_price' => 'required|numeric|min:0',
        ]);

        $service->update($validated);

        return redirect()->route('services.index')->with('success', 'Serviço atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        if ($service->user_id !== Auth::id()) {
            abort(403, 'Acesso não autorizado.');
        }

        $service->delete();

        return redirect()->route('services.index')->with('success', 'Serviço excluído com sucesso!');
    }
}
