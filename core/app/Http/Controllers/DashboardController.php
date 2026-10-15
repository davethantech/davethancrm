<?php
namespace App\Http\Controllers;
use App\Models\Lead;
use Illuminate\Support\Facades\Auth;
class DashboardController extends Controller {
    public function __invoke() {
        return view('dashboard', [
            'leadCount'=>Lead::count(),
            'openLeads'=>Lead::whereNotIn('status',['won','lost'])->count(),
            'recentLeads'=>Lead::latest()->limit(8)->get(),
            'user'=>Auth::user()
        ]);
    }
}