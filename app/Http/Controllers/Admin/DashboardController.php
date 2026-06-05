<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Booking;
use App\Models\ContactInquiry;
use App\Models\Destination;
use App\Models\Package;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'packages' => Package::count(),
            'bookings' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'blogs' => Blog::count(),
            'destinations' => Destination::count(),
            'inquiries' => ContactInquiry::where('is_read', false)->count(),
        ];

        $recentBookings = Booking::with('package')->latest()->take(8)->get();

        $recentInquiries = ContactInquiry::latest()->take(5)->get();

        // Bookings per status for a simple bar chart
        $statusChart = [
            'Pending' => $stats['pending'],
            'Confirmed' => $stats['confirmed'],
            'Completed' => $stats['completed'],
            'Cancelled' => $stats['cancelled'],
        ];

        return view('admin.dashboard', compact('stats', 'recentBookings', 'recentInquiries', 'statusChart'));
    }
}
