<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Coupon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $usedCoupons = Coupon::where('is_used', true)->count();
        $unusedCoupons = Coupon::where('is_used', false)->count();

        // For chart data: e.g., daily user signups
        // Example: group by date
        $dailySignups = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->get();

        return view('admin.dashboard', compact('totalUsers', 'usedCoupons', 'unusedCoupons', 'dailySignups'));
    }
    /**
     * Summary of exportCoupons
     * @return mixed|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportCoupons()
    {
        $coupons = Coupon::all();
        $filename = 'coupons_export_' . now()->format('YmdHis') . '.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=' . $filename,
        ];

        $callback = function () use ($coupons) {
            $output = fopen('php://output', 'w');
            // CSV header
            fputcsv($output, ['ID', 'Code', 'Is Used', 'Used By', 'Created At']);

            foreach ($coupons as $coupon) {
                fputcsv($output, [
                    $coupon->id,
                    $coupon->code,
                    $coupon->is_used ? 'Yes' : 'No',
                    $coupon->used_by,
                    $coupon->created_at
                ]);
            }
            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }
    /**
     * Summary of importCoupons
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function importCoupons(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('csv_file');

        if (($handle = fopen($file, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                // Assuming the code is in the first column
                $code = $data[0] ?? null;
                if ($code) {
                    // Create or skip duplicates
                    Coupon::firstOrCreate(['code' => $code]);
                }
            }
            fclose($handle);
        }

        return redirect()->back()->with('success', 'Coupons imported successfully!');
    }

    public function successfulCoupons()
    {
        // Grab all used coupons, eager-load the user relationship
        // (You could also paginate if you expect many records)
        $coupons = Coupon::where('is_used', true)
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
    
        return view('admin.successful_coupons', compact('coupons'));
    }
}
