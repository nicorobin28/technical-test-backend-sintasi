<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StorePatientRequest;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;


class PatientController extends Controller
{

    public function index(): JsonResponse
    {
        $patients = Patient::query()->withCount('visits')->latest('id')->get()->map(fn(Patient $patient) => $patient->toSafeArray());
        return response()->json(['data' => $patients]);
    }

    public function store(StorePatientRequest $request): JsonResponse
    {
        $data = $request->validated();
        if (Patient::where('nik_hash', hash('sha256', $data['nik']))->exists()) {
            return response()->json(['message' => 'NIK sudah terdaftar.'], 422);
        }

        $patient = DB::transaction(function () use ($data) {
            $patient = Patient::create([
                'nik' => Crypt::encryptString($data['nik']),
                'nik_hash' => hash('sha256', $data['nik']),
                'name' => $data['name'],
                'email' => Crypt::encryptString($data['email']),
                'email_hash' => hash('sha256', strtolower(trim($data['email']))),
                'phone' => $data['phone'],
                'gender' => $data['gender'],
                'birth_date' => $data['birth_date'],
                'address' => $data['address'],
            ]);
            $patient->update(['medical_record_number' => 'RM' . str_pad((string) $patient->id, 4, '0', STR_PAD_LEFT)]);
            return $patient->loadCount('visits');
        });

        return response()->json(['message' => 'Pasien berhasil didaftarkan.', 'data' => $patient->toSafeArray()], 201);
    }

    public function show(string $medicalRecordNumber): JsonResponse
    {
        $patient = Patient::where('medical_record_number', strtoupper($medicalRecordNumber))->withCount('visits')->first();
        if (!$patient)
            return response()->json(['message' => 'Pasien dengan No Rekam Medik tersebut tidak ditemukan.'], 404);
        return response()->json(['data' => $patient->toSafeArray()]);
    }
}
