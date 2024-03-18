<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Group;
use App\Models\TypeAgreement;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AgreementController extends Controller
{
    public function createAgreementGroup(Group $group)
    {
        $agreementTypes = TypeAgreement::where('affect', 2)->get();

        return view('agreements.create_group', compact('group', 'agreementTypes'));
    }


    public function storeAgreementGroup(Request $request)
    {
        try {

            $validatedData = $request->validate([
                'number_agreement'  => 'required|max:255',
                'date_agreement'    => 'required|date',
                'type'              => 'required',
                'group_id'          => 'required',
            ]);

            //Insertare el acuerdo del grupo
            $agreement                      = new Agreement();
            $agreement->number              = $request->number_agreement;
            $agreement->approval_date       = $request->date_agreement;
            $agreement->group_id            = $request->group_id;
            $agreement->user_load_id        = auth()->user()->id;
            $agreement->type_agreement_id   = $request->type;
            $agreement->save();

            return redirect()->route('groups.edit', $request->group_id)->with('success', 'Carta de acuerdo subida exitosamente.');
        } catch (Exception $th) {
            Log::info($th->getMessage());
            return redirect()->action([GroupController::class, 'index'])->with('error', 'Algo salió mal. Intente nuevamente.');
        }
    }
}
