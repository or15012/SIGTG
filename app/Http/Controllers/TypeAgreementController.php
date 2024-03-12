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

            'description'   => 'nullable|string|max:255',
            'permissions'   => 'array',
        ]);

        if (!in_array($TypeAgreement->id, [1, 2, 3, 4, 5])) {
            $TypeAgreement->name = $request->input('name');
        }

        $TypeAgreement->description = $request->input('description');
        $TypeAgreement->save();

        if ($request->has('permissions')) {
            $TypeAgreement->syncPermissions($request->input('permissions'));
        } else {
            $TypeAgreement->syncPermissions([]);
        }

        return redirect()->route('type_agreements.index')->with('success', 'Rol actualizado correctamente.');
    }

    public function destroy(TypeAgreement $TypeAgreement)
    {
        $TypeAgreement->delete();
        return redirect()->route('type_agreements.index')->with('success', 'Rol eliminado correctamente.');
    }
}
