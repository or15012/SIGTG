<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Group;
use App\Models\TypeAgreement;
use App\Models\User;
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
            $agreement->description         = $request->description;
            $agreement->group_id            = $request->group_id;
            $agreement->user_load_id        = auth()->user()->id;
            $agreement->type_agreement_id   = $request->type;
            $agreement->save();

            return redirect()->route('groups.edit', $request->group_id)->with('success', 'Acuerdo registrado exitosamente.');
        } catch (Exception $th) {
            Log::info($th->getMessage());
            return redirect()->action([GroupController::class, 'index'])->with('error', 'Algo salió mal. Intente nuevamente.');
        }
    }

    public function createAgreementStudent(User $student)
    {
        $agreementTypes = TypeAgreement::where('affect', 1)->get();

        return view('agreements.create_student', compact('student', 'agreementTypes'));
    }


    public function storeAgreementStudent(Request $request)
    {
        try {

            $validatedData = $request->validate([
                'number_agreement'  => 'required|max:255',
                'date_agreement'    => 'required|date',
                'type'              => 'required',
                'user_id'            => 'required',
            ]);

            //Insertare el acuerdo del grupo
            $agreement                      = new Agreement();
            $agreement->number              = $request->number_agreement;
            $agreement->approval_date       = $request->date_agreement;
            $agreement->description         = $request->description;
            $agreement->user_id             = $request->user_id;
            $agreement->user_load_id        = auth()->user()->id;
            $agreement->type_agreement_id   = $request->type;
            $agreement->save();

            return redirect()->route('users.agreements', $request->user_id)->with('success', 'Acuerdo registrado exitosamente.');
        } catch (Exception $th) {
            Log::info($th->getMessage());
            return redirect()->action([GroupController::class, 'index'])->with('error', 'Algo salió mal. Intente nuevamente.');
        }
    }


    public function createAgreementProtocol()
    {
        $agreementTypes = TypeAgreement::where('affect', 3)->get();

        return view('agreements.create_protocol', compact('agreementTypes'));
    }


    public function storeAgreementProtocol(Request $request)
    {
        try {

            $validatedData = $request->validate([
                'number_agreement'  => 'required|max:255',
                'date_agreement'    => 'required|date',
                'type'              => 'required',
            ]);

            //Insertare el acuerdo del grupo
            $agreement                      = new Agreement();
            $agreement->number              = $request->number_agreement;
            $agreement->approval_date       = $request->date_agreement;
            $agreement->description         = $request->description;
            $agreement->protocol_id         = session('protocol')['id'];
            $agreement->school_id           = session('school')['id'];
            $agreement->user_load_id        = auth()->user()->id;
            $agreement->type_agreement_id   = $request->type;
            $agreement->save();

            return redirect()->route('agreements.protocol.school')->with('success', 'Acuerdo registrado exitosamente.');
        } catch (Exception $th) {
            dd($th);
            Log::info($th->getMessage());
            return redirect()->route('agreements.protocol.school')->with('error', 'Algo salió mal. Intente nuevamente.');
        }
    }


    public function agreementsProtocolSchool()
    {

        $agreements = Agreement::join('type_agreements as ta', 'ta.id', 'agreements.type_agreement_id')
            ->join('users as u', 'u.id', 'agreements.user_load_id')
            ->where('agreements.protocol_id', session('protocol')['id'])
            ->where('agreements.school_id', session('school', ['id']))
            ->select(
                'agreements.id',
                'ta.name',
                'agreements.number',
                'agreements.description',
                'agreements.approval_date',
                'agreements.created_at',
                'u.first_name',
                'u.last_name'
            )
            ->get();

        return view('agreements.protocol_school', [
            "agreements"    => $agreements
        ]);
    }


    public function destroy(Agreement $agreement)
    {
        $agreement->delete();

        return redirect()->back()->with('success', 'Acuerdo eliminada exitosamente.');
    }
}