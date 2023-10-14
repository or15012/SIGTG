<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

        return view('auth.register', [
            'schools'   => $schools,
            'userTypes' => $userTypes
        ]);
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
            'email'             => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'school'            => ['required', 'exists:schools,id'], // Asegúrate de que exista una escuela con ese ID
            'password'          => ['required', 'string', 'min:8', 'confirmed'],
            'type'              => ['required'],
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
        User::create([
            'first_name'        => $data['first_name'],
            'middle_name'       => $data['middle_name'],
            'last_name'         => $data['last_name'],
            'second_last_name'  => $data['second_last_name'],
            'carnet'            => $data['carnet'],
            'email'             => $data['email'],
            'school_id'         => $data['school'], // Asumiendo que el campo se llama 'school_id' en tu modelo User
            'password'          => Hash::make($data['password']),
        ]);
    }
}
