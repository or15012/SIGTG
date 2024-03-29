<?php

namespace App\Http\Controllers;

use App\Models\Protocol;
use Illuminate\Http\Request;

class ProtocolController extends Controller
{
    const PERMISSIONS = [
        'index'    => 'Protocols',
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:' . self::PERMISSIONS['index'])->only(['index']);
    }


    public function index()
    {
        $protocols = Protocol::all();
        return view('protocols.index', compact('protocols'));
    }

    public function show(Protocol $protocol)
    {

        // Devuelve la vista 'schools.show' pasando la asesoría como una variable compacta
        return view('protocols.show', compact('protocol'));
    }

    public function edit(Protocol $protocol)
    {
        return view('protocols.edit', compact('protocol'));
    }

    public function update(Request $request, Protocol $protocol)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $protocol->update($data);

        return redirect()->route('protocols.index')->with('success', 'Escuela actualizada correctamente.');
    }

    public function destroy(Protocol $protocol)
    {
        $protocol->delete();

        return redirect()->route('protocols.index')->with('success', 'Escuela eliminada correctamente.');
    }
}
