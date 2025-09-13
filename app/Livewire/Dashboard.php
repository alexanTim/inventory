<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SupplyProfile;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use App\Models\Finance;
use App\Models\RequestSlip;
use App\Models\Shipment;

class Dashboard extends Component
{
    public function render()
    {
        // System Overview Metrics
        $totalProducts = SupplyProfile::count();
        $lowStockProducts = SupplyProfile::where('supply_qty', '<=', 10)->count();
        $outOfStockProducts = SupplyProfile::where('supply_qty', '<=', 0)->count();
        
        // Purchase Orders
        $pendingPOs = PurchaseOrder::where('status', 'pending')->count();
        $approvedPOs = PurchaseOrder::where('status', 'approved')->count();
        $totalPOs = PurchaseOrder::count();
        
        // Sales Orders
        $pendingSalesOrders = SalesOrder::where('status', 'pending')->count();
        $approvedSalesOrders = SalesOrder::where('status', 'approved')->count();
        $totalSalesOrders = SalesOrder::count();
        
        // Financial Overview
        $totalPayables = Finance::where('type', 'payable')->sum('balance');
        $totalReceivables = Finance::where('type', 'receivable')->sum('amount');
        $totalExpenses = Finance::where('type', 'expense')->sum('amount');
        
        // Recent Activities
        $recentPurchaseOrders = PurchaseOrder::with('supplier')
            ->latest()
            ->take(5)
            ->get();
            
        $recentSalesOrders = SalesOrder::with('customer')
            ->latest()
            ->take(5)
            ->get();
            
        $recentRequestSlips = RequestSlip::with(['sentFrom', 'requestedBy'])
            ->latest()
            ->take(5)
            ->get();
            
        $recentShipments = Shipment::with('customer')
            ->latest()
            ->take(5)
            ->get();
            
        // Low Stock Alerts
        $lowStockAlerts = SupplyProfile::where('supply_qty', '<=', 10)
            ->with(['itemType', 'itemClass'])
            ->orderBy('supply_qty', 'asc')
            ->take(5)
            ->get();

        return view('livewire.dashboard', [
            'totalProducts' => $totalProducts,
            'lowStockProducts' => $lowStockProducts,
            'outOfStockProducts' => $outOfStockProducts,
            'pendingPOs' => $pendingPOs,
            'approvedPOs' => $approvedPOs,
            'totalPOs' => $totalPOs,
            'pendingSalesOrders' => $pendingSalesOrders,
            'approvedSalesOrders' => $approvedSalesOrders,
            'totalSalesOrders' => $totalSalesOrders,
            'totalPayables' => $totalPayables,
            'totalReceivables' => $totalReceivables,
            'totalExpenses' => $totalExpenses,
            'recentPurchaseOrders' => $recentPurchaseOrders,
            'recentSalesOrders' => $recentSalesOrders,
            'recentRequestSlips' => $recentRequestSlips,
            'recentShipments' => $recentShipments,
            'lowStockAlerts' => $lowStockAlerts,
        ]);
    }
}
