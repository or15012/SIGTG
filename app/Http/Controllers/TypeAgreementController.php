<?php

namespace App\Http\Controllers;

use App\Models\TypeAgreement;
use Illuminate\Http\Request;

class TypeAgreementController extends Controller
{
    const PERMISSIONS = [
        'index'    => 'TypeAgreements',
    ];

    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('permission:' . self::PERMISSIONS['index'])->only(['index']);
    }

    public function index()
    {
        $TypeAgreements = TypeAgreement::paginate(10);
        return view('type_agreements.index', compact('TypeAgreements'));
    }

    public function show(TypeAgreement $TypeAgreement)
    {
        return view('type_agreements.show', compact('TypeAgreement'));
    }

    public function create()
    {
        return view('type_agreements.create', []);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|unique:type_agreements',
            'selectedAffect'    => 'required|integer',
        ]);

        $TypeAgreement = TypeAgreement::create([
            'name'      => $request->name,
            'affect'    => $request->selectedAffect,
        ]);

        return redirect()->route('type_agreements.index')->with('success', 'Tipo de acuerdo creado correctamente.');
    }

    public function edit(TypeAgreement $TypeAgreement)
    {
        return view('type_agreements.edit', compact('TypeAgreement'));
    }

    public function update(Request $request, TypeAgreement $TypeAgreement)
    {
        $request->validate([
            'name'              => 'required|unique:type_agreements,name,' . $TypeAgreement->id,
            'selectedAffect'    => 'required|integer',
        ]);

        $TypeAgreement->name    = $request->name;
        $TypeAgreement->affect  = $request->selectedAffect;
        $TypeAgreement->update();

        return redirect()->route('type_agreements.index')->with('success', 'Tipo de acuerdo actualizado correctamente.');
    }

    public function destroy(TypeAgreement $TypeAgreement)
    {
        $TypeAgreement->delete();
        return redirect()->route('type_agreements.index')->with('success', 'Tipo de acuerdo eliminado correctamente.');
    }
}
