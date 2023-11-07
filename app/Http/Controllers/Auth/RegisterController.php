<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Modality;
use App\Models\Protocol;
use App\Models\School;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $users      = User::all(); // Obtén todos los registros de usuarios
        $userTypes  = User::TYPES;
        return view('auth.index', [
            'users'     => $users,
            'userTypes'  => $userTypes,
        ]);
    }

    public function showRegistrationForm()
    {
        $schools    = School::all(); // Obtener la lista de escuelas
        $userTypes  = User::TYPES;
        $protocols  = Protocol::all();
        $modalities  = Modality::all();

        return view('auth.register', [
            'schools'   => $schools,
            'userTypes' => $userTypes,
            'protocols' => $protocols,
            'modalities' => $modalities,
        ]);
    }

    protected function registered(Request $request, $user)
    {
        // En lugar de iniciar sesión automáticamente, redirige al usuario a la ruta que desees
        return redirect()->route('users.index')->with('success', 'Usuario creado con éxito');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name'        => ['required', 'string', 'max:255'],
            'middle_name'       => ['required', 'string', 'max:255'],
            'last_name'         => ['required', 'string', 'max:255'],
            'second_last_name'  => ['required', 'string', 'max:255'],
            'carnet'            => ['required', 'string', 'max:7', 'unique:users'], // Asegúrate de ajustar la validación según tus necesidades
            'email'             => ['required', 'string', 'max:255', 'unique:users', 'not_regex:/@/'],
            'school'            => ['required', 'exists:schools,id'], // Asegúrate de que exista una escuela con ese ID
            'password'          => ['required', 'string', 'min:8', 'confirmed'],
            'type'              => ['required'],
            'modality_id'              => ['required'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'first_name'        => $data['first_name'],
            'middle_name'       => $data['middle_name'],
            'last_name'         => $data['last_name'],
            'second_last_name'  => $data['second_last_name'],
            'carnet'            => $data['carnet'],
            'email'             => $data['email'].'@ues.edu.sv',
            'school_id'         => $data['school'], // Asumiendo que el campo se llama 'school_id' en tu modelo User
            'type'              => $data['type'],
            'password'          => Hash::make($data['password']),
            'modality_id'       => $data['modality_id']
        ]);


        // Agregar un protocolo con status 1 y establecer status 0 para otros protocolos
        if (!empty($data['protocol_id'])) {
            $user->protocols()->attach([
                $data['protocol_id'] => ['status' => 1]
            ]);
            // Establecer status 0 para otros protocolos
            $user->protocols()->where('user_id', '!=', $user->id)->update(['status' => 0]);
        }

        return $user;
    }


    public function downloadTemplate() {
        return response()->download(public_path('uploads/users/formato-importacion-usuarios.xlsx'));
    }

    public function import_users(Request $request)
    {
        $request = $request;

        $validator = Validator::make($request->all(),
        [
            'excelFile.required' => 'Selecciona un archivo de tipo .xslx',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('users.index')
                    ->withErrors($validator)
                    ->withInput();
            }
        }
        try {
            if(!is_dir(Storage::path('public/uploads/users'))) {

                mkdir(Storage::path('public/uploads/users'), 0755, true);
            }

            $request = $request;


            $extension = $request->file('excelFile')->getClientOriginalExtension();
            $request->file('excelFile')->storeAs('uploads/users', 'file.' . $extension, 'public');

            $reader = new Xlsx();
            $spreadsheet = $reader->load(Storage::path('public/uploads/users/file.' . $extension));
            $worksheet = $spreadsheet->getActiveSheet();
            $listado = $worksheet->toArray(null, true);

            DB::beginTransaction();
            $existen = false;

            for ($i=1; $i < count($listado); $i++) {
                if ($listado[$i][0] == null) {
                    continue;
                }
                if (User::where('carnet', $listado[$i][2])->exists()) {
                    $existen = true;
                    continue;
                }
                $user = User::create([
                    'first_name'        => $listado[$i][0],
                    'middle_name'       => '',
                    'last_name'         => $listado[$i][1],
                    'second_last_name'  => '',
                    'carnet'            => $listado[$i][2],
                    'email'             => $listado[$i][2].'@ues.edu.sv',
                    'school_id'         => School::where('name', trim(strval($listado[$i][6])))->first()->id??null, // Asumiendo que el campo se llama 'school_id' en tu modelo User
                    'type'              => 1,
                    'password'          => Hash::make(self::randomPassword()),
                    'modality_id'       => Modality::where('name', trim(strval($listado[$i][4])))->first()->id??null,
                ]);
                if (!empty($listado[$i][5])) {
                    $user->protocols()->attach([
                        Protocol::where('name', trim(strval($listado[$i][5])))->first()->id??null => ['status' => 1]
                    ]);
                    // Establecer status 0 para otros protocolos
                    $user->protocols()->where('user_id', '!=', $user->id)->update(['status' => 0]);
                }
            }

            DB::commit();

            if ($existen == true) {
                return redirect()->route('users.index')
                    ->withErrors(['Algunos usuarios ya existen'])
                    ->withInput();
            }

            return redirect()->route('users.index')
                    ->with(['success'=>'Importación correcta.'])
                    ->withInput();

        Storage::disk('public')->delete('users/file.' . $extension);

        return redirect()->route('users.index')->with('success', 'Quotation Created Sucessfully');
        // return response()->download(public_path('quotations/uploads/file.xlsx'));
        } catch (Exception $e) {
            return redirect()->route('users.index')
                    ->withErrors(['Sorry, Error Occured !', 'Asegúrese que el archivo tenga el formato correcto.'])
                    ->withInput();
        }
    }



    static function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
}
