<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class RajaOngkirService
{
    protected $client;
    protected $apiKey;
    protected $baseUrl;

    public function __construct(Client $client)
    {
        $this->client = $client;
        // Baca API key mengikuti artikel (config) dan tetap kompatibel dengan env
        $this->apiKey = Config::get('rajaongkir.api_key')
            ?: env('RAJAONGKIR_API_KEY')
            ?: env('RAJAONGKIR_KEY');
        // Bersihkan base URL dari backticks/quotes dan trailing slash
        $rawBase = env('RAJAONGKIR_BASE_URL', 'https://rajaongkir.komerce.id/api/v1');
        $this->baseUrl = rtrim(trim($rawBase, " \t\n\r\0\x0B`\"'"), '/');
    }

    public function getProvinces()
    {
        try {
            // Mengikuti artikel: endpoint province
            $response = $this->client->request('GET', $this->baseUrl . '/destination/province', [
                'headers' => [
                    'Accept' => 'application/json',
                    'key' => $this->apiKey,
                ],
            ]);

            $result = json_decode($response->getBody()->getContents());
            // Jika sudah meta+data, langsung kembalikan
            if (isset($result->meta) && isset($result->data)) {
                return $result;
            }

            // Transform jika yang diterima format klasik (untuk kompatibilitas)
            $items = $result->rajaongkir->results ?? [];
            $data = array_map(function ($item) {
                return [
                    'id' => isset($item->province_id) ? (int) $item->province_id : null,
                    'name' => $item->province ?? null,
                ];
            }, $items);

            return json_decode(json_encode([
                'meta' => [
                    'message' => 'Success Get Province',
                    'code' => 200,
                    'status' => 'success',
                ],
                'data' => $data,
            ]));
        } catch (ClientException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getCities($provinceId)
    {
        try {
            // Mengikuti artikel: endpoint city/{provinceId}
            $response = $this->client->request('GET', $this->baseUrl . '/destination/city/' . $provinceId, [
                'headers' => [
                    'Accept' => 'application/json',
                    'key' => $this->apiKey,
                ],
            ]);

            $result = json_decode($response->getBody()->getContents());
            if (isset($result->meta) && isset($result->data)) {
                return $result;
            }

            // Transform jika yang diterima format klasik (untuk kompatibilitas)
            $items = $result->rajaongkir->results ?? [];
            $data = array_map(function ($item) {
                return [
                    'id' => isset($item->city_id) ? (int) $item->city_id : null,
                    'name' => $item->city_name ?? null,
                    'province_id' => isset($item->province_id) ? (int) $item->province_id : null,
                ];
            }, $items);

            return json_decode(json_encode([
                'meta' => [
                    'message' => 'Success Get City',
                    'code' => 200,
                    'status' => 'success',
                ],
                'data' => $data,
            ]));
        } catch (ClientException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get districts by city ID (sesuai artikel)
     * @param int|string $cityId
     * @return object
     */
    public function getDistricts($cityId)
    {
        try {
            // Mengikuti artikel: endpoint district/{cityId}
            $response = $this->client->request('GET', $this->baseUrl . '/destination/district/' . $cityId, [
                'headers' => [
                    'Accept' => 'application/json',
                    'key' => $this->apiKey,
                ],
            ]);

            $result = json_decode($response->getBody()->getContents());
            if (isset($result->meta) && isset($result->data)) {
                return $result;
            }

            // Transform bila format klasik
            $items = $result->rajaongkir->results ?? [];
            $data = array_map(function ($item) {
                return [
                    'id' => isset($item->subdistrict_id) ? (int) $item->subdistrict_id : null,
                    'name' => $item->subdistrict_name ?? null,
                    'city_id' => isset($item->city_id) ? (int) $item->city_id : null,
                ];
            }, $items);

            return json_decode(json_encode([
                'meta' => [
                    'message' => 'Success Get District',
                    'code' => 200,
                    'status' => 'success',
                ],
                'data' => $data,
            ]));
        } catch (ClientException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function checkOngkir($origin, $destination, $weight, $courier)
    {
        try {
            // Mengikuti artikel: Calculate Domestic Cost
            $response = $this->client->request('POST', $this->baseUrl . '/calculate/domestic-cost', [
                'headers' => [
                    'Accept' => 'application/json',
                    'key' => $this->apiKey,
                ],
                'form_params' => [
                    'origin' => $origin,
                    // destination adalah district_id sesuai artikel
                    'destination' => $destination,
                    'weight' => $weight,
                    'courier' => $courier,
                ]
            ]);

            $result = json_decode($response->getBody()->getContents());
            if (isset($result->meta) && isset($result->data)) {
                return $result;
            }

            $first = $result->rajaongkir->results[0] ?? null;
            $costs = $first->costs ?? [];

            $data = array_map(function ($c) use ($first) {
                $cost = $c->cost[0] ?? null;
                return [
                    'courier' => $first->code ?? $first->name ?? null,
                    'service' => $c->service ?? null,
                    'description' => $c->description ?? null,
                    'value' => isset($cost->value) ? (int) $cost->value : null,
                    'etd' => $cost->etd ?? null,
                    'note' => $cost->note ?? null,
                ];
            }, $costs);

            return json_decode(json_encode([
                'meta' => [
                    'message' => 'Success Get Shipping Cost',
                    'code' => 200,
                    'status' => 'success',
                ],
                'data' => $data,
            ]));
        } catch (ClientException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Track shipment status
     * @param string $waybill Nomor resi
     * @param string $courier Kode kurir
     * @return object
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function trackShipment($waybill, $courier)
    {
        try {
            // Mengikuti dokumen Komerce: Tracking AWB
            $response = $this->client->request('POST', $this->baseUrl . '/track/waybill', [
                'headers' => [
                    'key' => $this->apiKey,
                ],
                'form_params' => [
                    'waybill' => $waybill,
                    'courier' => $courier,
                ]
            ]);

            $result = json_decode($response->getBody()->getContents());
            // Jika sudah meta+data, langsung kembalikan
            if (isset($result->meta) && isset($result->data)) {
                return $result;
            }

            if (!isset($result->rajaongkir->result)) {
                throw new \Exception($result->rajaongkir->status->description ?? 'Failed to track shipment');
            }

            // Normalisasi ke meta + data (respon klasik)
            return json_decode(json_encode([
                'meta' => [
                    'message' => 'Success Track Waybill',
                    'code' => 200,
                    'status' => 'success',
                ],
                'data' => $result->rajaongkir->result,
            ]));
        } catch (ClientException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
