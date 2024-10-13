<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Rental;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RentalController extends Controller
{
    public function index()
    {
        $cars = Car::all();

        return Inertia::render('Car', [
            'cars' => $cars,
        ]);
    }

    public function carForm()
    {
        $brands = [
            'Toyota' => ['Avanza', 'Fortuner', 'Yaris'],
            'Honda' => ['Civic', 'CR-V', 'Jazz'],
            'Suzuki' => ['Ertiga', 'Swift', 'XL7'],
            'Mitsubishi' => ['Pajero', 'Xpander', 'Outlander'],
        ];
        return Inertia::render('AddCar', [
            'brands' => array_keys($brands),
            'models' => $brands,
        ]);
    }

    public function addCar(Request $request)
    {
        // Validasi input kendaraan baru
        $request->validate([
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'license_plate' => 'required|string|max:20|unique:cars',
            'rental_rate' => 'required|numeric|min:0',
        ]);

        // Tambahkan mobil baru ke database
        Car::create([
            'brand' => $request->brand,
            'model' => $request->model,
            'license_plate' => $request->license_plate,
            'rental_rate' => $request->rental_rate,
            'status' => true,
        ]);

        return back()->with('message', 'Kendaraan berhasil ditambahkan!');
    }

    public function rentForm()
    {
        // Ambil semua mobil yang tersedia untuk disewa
        $cars = Car::where('status', 'available')->get();

        // Kirim data mobil ke halaman Vue.js menggunakan Inertia
        return Inertia::render('RentCar', [
            'cars' => $cars,
        ]);
    }
    //
    public function rentCar(Request $request)
    {
        // Validasi input yang dimasukkan pengguna
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Ambil data mobil berdasarkan car_id
        $car = Car::find($request->car_id);

        // Jika mobil tidak tersedia untuk disewa
        if (!$car->is_available) {
            return back()->withErrors(['message' => 'Mobil tidak tersedia untuk disewa']);
        }

        $isCarBooked = Rental::where('car_id', $request->car_id)
            ->where(function ($query) use ($request) {
                // Cek apakah ada peminjaman lain yang berlangsung selama rentang waktu yang diminta
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                    });
            })
            ->exists();

        if ($isCarBooked) {
            return back()->withErrors(['message' => 'Mobil sudah disewa dalam rentang tanggal tersebut']);
        }

        // Buat peminjaman baru
        Rental::create([
            'user_id' => $request->user()->id,
            'car_id' => $request->car_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'ongoing',
        ]);

        // Tandai mobil sebagai tidak tersedia
        $car->is_available = false;
        $car->save();

        return back()->with('message', 'Peminjaman berhasil!');
    }

    // Fungsi untuk mengembalikan mobil
    public function returnCar(Request $request)
    {
        // Validasi input pengembalian
        $request->validate([
            'rental_id' => 'required|exists:rentals,id',
        ]);

        // Ambil data peminjaman berdasarkan rental_id
        $rental = Rental::find($request->rental_id);

        // Jika peminjaman sudah dikembalikan
        if ($rental->status == 'returned') {
            return back()->withErrors(['message' => 'Mobil sudah dikembalikan']);
        }

        // Ambil data mobil
        $car = $rental->car;

        // Hitung total hari peminjaman
        $startDate = new \DateTime($rental->start_date);
        $endDate = new \DateTime($rental->end_date);
        $totalDays = $startDate->diff($endDate)->days;

        // Hitung biaya total
        $totalCost = $totalDays * $car->rental_rate;

        // Update status peminjaman
        $rental->status = 'returned';
        $rental->save();

        // Tandai mobil sebagai tersedia kembali
        $car->is_available = true;
        $car->save();

        return back()->with('message', 'Mobil berhasil dikembalikan!');
    }
}
