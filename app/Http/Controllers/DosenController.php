<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DosenController extends Controller
{
    /**
     * Tampilkan daftar seluruh data dosen dengan fitur pencarian dan filter prodi.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('q', ''));
        $filter_prodi = trim($request->input('prodi', ''));

        $query = Dosen::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nidn', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        if ($filter_prodi !== '') {
            $query->where('prodi', $filter_prodi);
        }

        $daftar_dosen = $query->orderBy('id', 'asc')->get();
        $total_dosen = Dosen::count();

        $list_prodi = [
            'Rekayasa Perangkat Lunak',
            'Sains Data',
            'Sistem Informasi',
            'Teknik Informatika',
            'Teknologi Informasi',
            'Manajemen Informatika'
        ];

        return view('dosen.index', compact('daftar_dosen', 'total_dosen', 'list_prodi', 'search', 'filter_prodi'));
    }

    /**
     * Simpan data dosen baru ke database (Create).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nidn'   => ['required', 'string', 'max:30', 'unique:dosens,nidn'],
            'nama'   => ['required', 'string', 'max:150'],
            'prodi'  => ['required', 'string', 'max:100'],
            'alamat' => ['required', 'string'],
        ], [
            'nidn.required'   => 'NIDN wajib diisi!',
            'nidn.unique'     => 'NIDN tersebut sudah terdaftar di sistem!',
            'nama.required'   => 'Nama dosen lengkap wajib diisi!',
            'prodi.required'  => 'Program studi wajib dipilih!',
            'alamat.required' => 'Alamat dosen wajib diisi!',
        ]);

        Dosen::create($validated);

        return redirect()->route('dosen.index')->with('success', 'Data dosen baru berhasil ditambahkan!');
    }

    /**
     * Perbarui data dosen yang sudah ada (Update).
     */
    public function update(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);

        $validated = $request->validate([
            'nidn'   => ['required', 'string', 'max:30', Rule::unique('dosens')->ignore($dosen->id)],
            'nama'   => ['required', 'string', 'max:150'],
            'prodi'  => ['required', 'string', 'max:100'],
            'alamat' => ['required', 'string'],
        ], [
            'nidn.required'   => 'NIDN wajib diisi!',
            'nidn.unique'     => 'NIDN tersebut sudah digunakan oleh dosen lain!',
            'nama.required'   => 'Nama dosen lengkap wajib diisi!',
            'prodi.required'  => 'Program studi wajib dipilih!',
            'alamat.required' => 'Alamat dosen wajib diisi!',
        ]);

        $dosen->update($validated);

        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil diperbarui!');
    }

    /**
     * Hapus data dosen dari database (Delete).
     */
    public function destroy($id)
    {
        $dosen = Dosen::findOrFail($id);
        $dosen->delete();

        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil dihapus dari sistem!');
    }

    /**
     * Reset data ke sampel data standar (Utility untuk demo presentasi).
     */
    public function resetDefault()
    {
        Dosen::truncate();

        $samples = [
            ['nidn' => '0005078504', 'nama' => 'Keonho, S.T., M.Kom', 'prodi' => 'Rekayasa Perangkat Lunak', 'alamat' => 'Jl. jalan kita berdua'],
            ['nidn' => '0011129002', 'nama' => 'Mingyu Kim, M.Kom', 'prodi' => 'Sains Data', 'alamat' => 'Jl. ke ujung dunia'],
            ['nidn' => '0023088703', 'nama' => 'Dr. Jenoya, S.T., M.Kom', 'prodi' => 'Sistem Informasi', 'alamat' => 'Jl. Seoul No. 21'],
            ['nidn' => '0015048201', 'nama' => 'Amrisyah Pasaribu, S.Kom., M.M', 'prodi' => 'Teknik Informatika', 'alamat' => 'Jl. Sibolga No. 100'],
        ];

        foreach ($samples as $sample) {
            Dosen::create($sample);
        }

        return redirect()->route('dosen.index')->with('info', 'Data dosen berhasil di-reset ke data bawaan!');
    }
}
