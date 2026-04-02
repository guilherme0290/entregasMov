<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\CourierAvailabilityStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterDriverRequest;
use App\Models\Courier;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $field = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $user = Auth::getProvider()->retrieveByCredentials([$field => $credentials['login']]);

        if (! $user || ! Auth::validate([$field => $credentials['login'], 'password' => $credentials['password']])) {
            throw ValidationException::withMessages([
                'login' => ['Credenciais inválidas.'],
            ]);
        }

        abort_unless($user->is_active, 403, 'Usuário inativo.');

        $user->forceFill(['last_login_at' => now()])->save();
        $token = $user->createToken('mobile-app');

        return $this->success([
            'user' => $user->loadMissing(['company', 'partner', 'courier']),
            'token' => $token->plainTextToken,
            'token_type' => 'Bearer',
        ], 'Login realizado com sucesso.');
    }

    public function registerDriver(RegisterDriverRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($request, $data) {
            $user = User::create([
                'company_id' => null,
                'name' => $data['full_name'],
                'email' => null,
                'phone' => $data['phone'],
                'password' => Hash::make(Str::password(12)),
                'role' => UserRole::Courier,
                'is_active' => false,
            ]);

            $driverLicenseFront = $request->file('cnh_front')?->store('couriers/licenses', 'public');
            $driverLicenseBack = $request->file('cnh_back')?->store('couriers/licenses', 'public');
            $proofOfResidence = $request->file('proof_of_residence')?->store('couriers/documents', 'public');

            Courier::create([
                'user_id' => $user->id,
                'company_id' => null,
                'tax_id' => $data['cpf'],
                'birth_date' => $data['birth_date'],
                'address' => 'Aguardando preenchimento',
                'district' => 'Aguardando preenchimento',
                'city' => 'Aguardando preenchimento',
                'state' => 'NA',
                'zip_code' => '00000-000',
                'notes' => $proofOfResidence
                    ? "Cadastro mobile pendente de aprovação. Comprovante: {$proofOfResidence}"
                    : 'Cadastro mobile pendente de aprovação.',
                'vehicle_type' => Str::lower($data['vehicle_type']),
                'vehicle_model' => $data['vehicle_model'],
                'vehicle_plate' => Str::upper($data['vehicle_plate']),
                'document_photo' => $driverLicenseBack,
                'driver_license_photo' => $driverLicenseFront,
                'availability_status' => CourierAvailabilityStatus::Blocked,
                'last_status_at' => now(),
                'is_active' => false,
            ]);
        });

        return $this->success([
            'status' => 'pending_approval',
        ], 'Cadastro de motorista enviado para aprovação.', 201);
    }

    public function logout(Request $request)
    {
        $request->user()?->currentAccessToken()?->delete();

        return $this->success(message: 'Logout realizado com sucesso.');
    }

    public function me(Request $request)
    {
        return $this->success(
            $request->user()?->loadMissing(['company', 'partner', 'courier']),
            'Usuário autenticado.'
        );
    }
}
