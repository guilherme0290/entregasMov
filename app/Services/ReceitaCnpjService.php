<?php

namespace App\Services;

use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\RequestException;

class ReceitaCnpjService
{
    public function __construct(private readonly HttpFactory $http) {}

    public function lookup(string $taxId): array
    {
        $document = preg_replace('/\D/', '', $taxId ?? '');

        abort_unless(strlen($document) === 14, 422, 'Informe um CNPJ valido.');

        $baseUrl = rtrim((string) config('services.receita_cnpj.base_url'), '/');
        abort_if($baseUrl === '', 500, 'Servico de consulta de CNPJ nao configurado.');

        $request = $this->http
            ->acceptJson()
            ->timeout((int) config('services.receita_cnpj.timeout', 10));

        $token = (string) config('services.receita_cnpj.token');

        if ($token !== '') {
            $request = $request->withToken($token);
        }

        try {
            $response = $request->get($baseUrl.'/'.$document)->throw()->json();
        } catch (RequestException $exception) {
            abort(422, 'Nao foi possivel consultar o CNPJ informado.');
        }

        abort_if(! is_array($response), 422, 'Resposta invalida ao consultar o CNPJ.');
        abort_if(($response['status'] ?? null) === 'ERROR', 422, (string) ($response['message'] ?? 'Nao foi possivel consultar o CNPJ informado.'));

        return [
            'trade_name' => $this->stringValue($response, ['fantasia', 'nome_fantasia']),
            'company_name' => $this->stringValue($response, ['nome', 'razao_social']),
            'contact_name' => $this->shareholderName($response),
            'contact_phone' => $this->stringValue($response, ['telefone', 'ddd_telefone_1', 'phone']),
            'billing_email' => $this->stringValue($response, ['email']),
            'pickup_address' => $this->stringValue($response, ['logradouro', 'street']),
            'pickup_number' => $this->stringValue($response, ['numero', 'number']),
            'pickup_district' => $this->stringValue($response, ['bairro', 'district']),
            'pickup_city' => $this->stringValue($response, ['municipio', 'cidade', 'city']),
            'pickup_state' => $this->stringValue($response, ['uf', 'state']),
            'pickup_zip_code' => $this->stringValue($response, ['cep', 'zip_code']),
            'pickup_complement' => $this->stringValue($response, ['complemento', 'complement']),
        ];
    }

    private function stringValue(array $data, array $keys): ?string
    {
        foreach ($keys as $key) {
            $value = $data[$key] ?? null;

            if (is_string($value) && trim($value) !== '') {
                return trim($value);
            }
        }

        return null;
    }

    private function shareholderName(array $data): ?string
    {
        $shareholders = $data['qsa'] ?? $data['socios'] ?? null;

        if (! is_array($shareholders) || $shareholders === []) {
            return null;
        }

        $firstShareholder = $shareholders[0] ?? null;

        if (! is_array($firstShareholder)) {
            return null;
        }

        return $this->stringValue($firstShareholder, ['nome_socio', 'nome']);
    }
}
