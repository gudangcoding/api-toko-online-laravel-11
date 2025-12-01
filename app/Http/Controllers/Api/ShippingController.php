<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RajaOngkirService;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    protected $rajaOngkirService;

    public function __construct(RajaOngkirService $rajaOngkirService)
    {
        $this->rajaOngkirService = $rajaOngkirService;
    }

    /**
     * Track shipment status using RajaOngkir API
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function trackShipment(Request $request)
    {
        $request->validate([
            'waybill' => 'required|string',
            'courier' => 'required|string|in:jne,pos,tiki,rpx,pcp,esl,ncs,sicepat,jet,sap,first,ninja,lion,idl,rex,ide,sentral',
        ]);

        try {
            $tracking = $this->rajaOngkirService->trackShipment(
                $request->waybill,
                $request->courier
            );

            return response()->json([
                'status' => 'success',
                'data' => $tracking
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get list of available couriers
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCouriers()
    {
        $package = config('rajaongkir.package', 'starter');
        $couriers = config("rajaongkir.couriers.$package", config('rajaongkir.couriers.starter'));

        return response()->json([
            'status' => 'success',
            'data' => $couriers
        ]);
    }

    /**
     * Get provinces from RajaOngkir
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProvinces()
    {
        try {
            $provinces = $this->rajaOngkirService->getProvinces();
            return response()->json([
                'status' => 'success',
                'data' => $provinces,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get cities by province_id from RajaOngkir
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCities(Request $request)
    {
        $request->validate([
            'province_id' => 'required|integer',
        ]);

        try {
            $cities = $this->rajaOngkirService->getCities($request->input('province_id'));
            return response()->json([
                'status' => 'success',
                'data' => $cities,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get districts by city_id from RajaOngkir
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDistricts(Request $request)
    {
        $request->validate([
            'city_id' => 'required|integer',
        ]);

        try {
            $districts = $this->rajaOngkirService->getDistricts($request->input('city_id'));
            return response()->json([
                'status' => 'success',
                'data' => $districts,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check shipping cost (ongkir)
     * destination expects district_id per current integration
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ongkir(Request $request)
    {
        // Dynamic allowed couriers from config based on package
        $package = config('rajaongkir.package', 'starter');
        $couriersConfig = config("rajaongkir.couriers.$package", config('rajaongkir.couriers.starter'));
        $allowedCouriers = array_map(fn($c) => $c['code'], $couriersConfig);

        $request->validate([
            'origin' => 'required|integer', // city_id
            'destination' => 'required|integer', // district_id
            'weight' => 'required|integer|min:1',
            'courier' => 'required|string|in:' . implode(',', $allowedCouriers),
        ]);

        try {
            $result = $this->rajaOngkirService->checkOngkir(
                $request->input('origin'),
                $request->input('destination'),
                $request->input('weight'),
                $request->input('courier'),
            );

            return response()->json([
                'status' => 'success',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
