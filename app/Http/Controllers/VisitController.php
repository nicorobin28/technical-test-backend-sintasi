<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Visit;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;


class VisitController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $query = Visit::query();

        // Jika request memiliki parameter medical_record_number
        if ($request->has('medical_record_number')) {
            $rm = strtoupper($request->medical_record_number);
            $patient = Patient::where('medical_record_number', $rm)->first();

            if (!$patient) {
                return response()->json(['message' => 'No Rekam Medik tidak ditemukan.'], 404);
            }

            $query->where('patient_id', $patient->id);
        }

        $visits = $query->orderBy('visit_date', 'desc')->get();

        $visits->transform(function ($visit) {
            $visit->visit_date = \Carbon\Carbon::parse($visit->visit_date)->format('Y-m-d');
            return $visit;
        });

        return response()->json([
            'message' => 'Data kunjungan berhasil diambil.',
            'data' => $visits
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'medical_record_number' => ['required', 'regex:/^RM\\d{4,}$/'],
            'visit_date' => ['required', 'date'],
            'complaint' => ['required', 'string', 'min:3', 'max:1000'],
            'doctor' => ['required', 'string', 'min:2', 'max:150'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        if ($validator->fails())
            return response()->json(['message' => 'Validasi gagal.', 'errors' => $validator->errors()], 422);

        $patient = Patient::where('medical_record_number', strtoupper($request->medical_record_number))->first();
        if (!$patient)
            return response()->json(['message' => 'No Rekam Medik tidak ditemukan.'], 404);

        $visit = Visit::create([
            'patient_id' => $patient->id,
            'visit_date' => $request->visit_date,
            'complaint' => $request->complaint,
            'doctor' => $request->doctor,
            'notes' => $request->notes,
        ]);
        return response()->json([
            'message' => 'Kunjungan berhasil dibuat.',
            'data' => [
                'id' => $visit->id,
                'medical_record_number' => $patient->medical_record_number,
                'patient_name' => $patient->name,
                'visit_date' => $visit->visit_date->format('Y-m-d'),
                'complaint' => $visit->complaint,
                'doctor' => $visit->doctor,
                'notes' => $visit->notes,
                'visit_count' => $patient->visits()->count(),
            ]
        ], 201);
    }
}
