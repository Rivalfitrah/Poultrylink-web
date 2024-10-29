<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Verifsupplier;

class verifController extends Controller
{
    public function postverif(Request $request)
{
        // Validasi input
        $validate = $request->validate([
            'imageKtp' => 'required|file',
            'imageNPWP' => 'required|file',
            'nik' => 'required',
            'nama' => 'required',
            'ttl' => 'required',
            'alamat' => 'required',
            'kewarganegaraan' => 'required',
        ]);
        $imageKtpPath = null;
        $imageNPWPPath = null;

        if ($request->hasFile('imageKtp')) {
            $imageKtpPath = $request->file('imageKtp')->store('verifSupplier', 'public');
        }
        if ($request->hasFile('imageNPWP')) {
            $imageNPWPPath = $request->file('imageNPWP')->store('verifSupplier', 'public');
        }

        $request['user_id'] = Auth::user()->id;
        $verifData = $request->all();
        $verifData['imageKtp'] = $imageKtpPath;
        $verifData['imageNPWP'] = $imageNPWPPath;

        $verif = Verifsupplier::create($verifData);
        return response()->json([
            'message' => 'Data verifikasi berhasil disimpan',
            'data' => $verif,
    ]);
}
   
}
