<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Coupon;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Subscriber;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = Subscriber::count();
        $usedCoupons = Coupon::where('is_used', true)->count();
        $unusedCoupons = Coupon::where('is_used', false)->count();

        // For chart data: e.g., daily user signups
        // Example: group by date
        $dailySignups = Subscriber::selectRaw('DATE(created_at) as date, COUNT(*) as count')
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

        $response = new StreamedResponse(function () use ($coupons) {
            $handle = fopen('php://output', 'w');
            // CSV header
            fputcsv($handle, ['ID', 'Code', 'Is Used', 'Used By', 'Used At', 'Created At']);

            foreach ($coupons as $coupon) {
                fputcsv($handle, [
                    $coupon->id,
                    $coupon->code,
                    $coupon->is_used ? 'Yes' : 'No',
                    $coupon->used_by,
                    $coupon->used_at,
                    $coupon->created_at
                ]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
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
                // we only expect the coupon code in the first cell
                $code = $data[0] ?? null;
                if ($code) {
                    Coupon::firstOrCreate([
                        'code' => trim($code),
                    ]);
                }
            }
            fclose($handle);
        }

        return redirect()->back()->with('success', 'Coupons imported successfully!');
    }


    public function successfulCoupons()
    {
        $coupons = Coupon::where('is_used', true)
            ->with('subscriber')
            ->orderByDesc('used_at')
            ->paginate(20);

        return view('admin.successful_coupons', compact('coupons'));
    }

    public function stats()
    {
        // Total number of subscribers
        $totalSubscribers = Subscriber::count();

        // Total coupons, with breakdown
        $totalCoupons = Coupon::count();
        $usedCoupons    = Coupon::where('is_used', true)->count();
        $unusedCoupons  = Coupon::where('is_used', false)->count();

        // OTP stats:
        // For this example, we assume that every subscriber record represents a successful OTP request.
        // If you decide to track failed OTP requests (e.g., via a log or additional column), update accordingly.
        $totalOtpSuccess = $totalSubscribers;
        $totalOtpFailed  = 0; // Not tracked yet

        // Daily OTP requests – count how many subscribers were created per day
        $dailyOtp = Subscriber::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $dailyLabels = $dailyOtp->pluck('date');
        $dailyCounts = $dailyOtp->pluck('total');

        return view('admin.stats', compact(
            'totalSubscribers',
            'totalCoupons',
            'usedCoupons',
            'unusedCoupons',
            'totalOtpSuccess',
            'totalOtpFailed',
            'dailyLabels',
            'dailyCounts'
        ));
    }
    public function downloadDemoCsv()
    {
        $filename = 'demo_coupons.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () {
            $output = fopen('php://output', 'w');
            // Optional: add a header row if you want
            // fputcsv($output, ['coupon_code']);

            // Write sample coupon codes
            fputcsv($output, ['ABC123']);
            fputcsv($output, ['DEF456']);
            fputcsv($output, ['GHI789']);
            fputcsv($output, ['JKL012']);
            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display a paginated list of subscribers.
     */
    public function subscribersList(Request $request)
    {
        // Fetch subscribers with pagination (e.g. 20 per page)
        $subscribers = Subscriber::orderBy('created_at', 'desc')->paginate(20);

        return view('admin.subscribers', compact('subscribers'));
    }

    /**
     * Export all subscribers as a CSV file.
     */
    public function exportSubscribers()
    {
        $filename = 'subscribers_export_' . now()->format('YmdHis') . '.csv';

        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');

            // Optional: write a header row
            fputcsv($handle, ['ID', 'Name', 'Email', 'Phone Number', 'Created At']);

            // Retrieve all subscribers
            $subscribers = Subscriber::orderBy('created_at', 'desc')->get();
            foreach ($subscribers as $subscriber) {
                fputcsv($handle, [
                    $subscriber->id,
                    $subscriber->name,
                    $subscriber->email,
                    $subscriber->phone_number,
                    $subscriber->created_at,
                ]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', "attachment; filename=\"$filename\"");

        return $response;
    }
}
