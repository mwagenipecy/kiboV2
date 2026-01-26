<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

// Language switching route
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, config('app.available_locales', ['en', 'sw']))) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('language.switch');

// Redirect root to cars section (default landing page)
Route::get('/', function () {
    return redirect()->route('cars.index');
});

// ============================================
// CARS SECTION ROUTES
// ============================================
Route::prefix('cars')->name('cars.')->group(function () {
    Route::get('/', function () {
        return view('cars.index', ['vehicleType' => 'cars']);
    })->name('index');
    
    Route::get('/search', \App\Livewire\Customer\VehicleSearch::class)->name('search');
    
    // Specific routes MUST come before dynamic routes
    Route::get('/used', function () {
        return view('cars.used', ['vehicleType' => 'cars']);
    })->name('used');
    
    Route::get('/new', function () {
        return view('cars.new', ['vehicleType' => 'cars']);
    })->name('new');
    
    Route::get('/sell', function () {
        return view('cars.sell', ['vehicleType' => 'cars']);
    })->name('sell');
    
    Route::get('/pricing', function () {
        return view('pricing.index', ['category' => 'cars', 'vehicleType' => 'cars']);
    })->name('pricing');
    
    Route::get('/list-vehicle', function () {
        return view('cars.list-vehicle', ['vehicleType' => 'cars']);
    })->middleware('auth')->name('list-vehicle');
    
    Route::get('/sell-to-dealer', \App\Livewire\Customer\AuctionVehicleForm::class)
        ->middleware('auth')->name('sell-to-dealer');
    
    Route::get('/find-me-a-car', \App\Livewire\Customer\FindMeACar::class)
        ->name('find');

    Route::get('/value', function () {
        return view('cars.value', ['vehicleType' => 'cars']);
    })->name('value');
    
    Route::get('/reviews', function () {
        return view('cars.reviews', ['vehicleType' => 'cars']);
    })->name('reviews');
    
    Route::get('/leasing', function () {
        return view('cars.leasing', ['vehicleType' => 'cars']);
    })->name('leasing');
    
    // Leasing Cars Routes
    Route::prefix('lease')->name('lease.')->group(function () {
        Route::get('/', \App\Livewire\Customer\LeasingCarList::class)->name('index');
        Route::get('/{id}', \App\Livewire\Customer\LeasingCarDetail::class)->name('detail');
    });
    
    Route::get('/electric', function () {
        return view('cars.electric', ['vehicleType' => 'cars']);
    })->name('electric');
    
    Route::get('/insurance', function () {
        return view('cars.insurance', ['vehicleType' => 'cars']);
    })->name('insurance');
    
    Route::get('/buy-online', function () {
        return view('cars.buy-online', ['vehicleType' => 'cars']);
    })->name('buy-online');
    
    // Dynamic route comes last
    Route::get('/{id}', \App\Livewire\Customer\VehicleDetail::class)->name('detail');
});

// ============================================
// VANS SECTION ROUTES
// ============================================
Route::prefix('vans')->name('vans.')->group(function () {
    Route::get('/', function () {
        return view('vans.index', ['vehicleType' => 'vans']);
    })->name('index');
    
    Route::get('/used', function () {
        return view('vans.used', ['vehicleType' => 'vans']);
    })->name('used');
    
    Route::get('/new', function () {
        return view('vans.new', ['vehicleType' => 'vans']);
    })->name('new');
    
    Route::get('/sell', function () {
        return view('vans.index', ['vehicleType' => 'vans']);
    })->name('sell');
    
    Route::get('/value', function () {
        return view('vans.index', ['vehicleType' => 'vans']);
    })->name('value');
    
    Route::get('/reviews', function () {
        return view('vans.index', ['vehicleType' => 'vans']);
    })->name('reviews');
    
    Route::get('/leasing', function () {
        return view('vans.index', ['vehicleType' => 'vans']);
    })->name('leasing');
    
    Route::get('/electric', function () {
        return view('vans.index', ['vehicleType' => 'vans']);
    })->name('electric');
    
    Route::get('/buy-online', function () {
        return view('vans.index', ['vehicleType' => 'vans']);
    })->name('buy-online');
    
    Route::get('/search', function () {
        return view('vans.index', ['vehicleType' => 'vans']);
    })->name('search');
});

// ============================================
// BIKES SECTION ROUTES
// ============================================
Route::prefix('bikes')->name('bikes.')->group(function () {
    Route::get('/', function () {
        return view('bikes.index', ['vehicleType' => 'bikes']);
    })->name('index');
    
    Route::get('/used', function () {
        return view('bikes.index', ['vehicleType' => 'bikes']);
    })->name('used');
    
    Route::get('/new', function () {
        return view('bikes.index', ['vehicleType' => 'bikes']);
    })->name('new');
    
    Route::get('/sell', function () {
        return view('bikes.index', ['vehicleType' => 'bikes']);
    })->name('sell');
    
    Route::get('/value', function () {
        return view('bikes.index', ['vehicleType' => 'bikes']);
    })->name('value');
    
    Route::get('/reviews', function () {
        return view('bikes.index', ['vehicleType' => 'bikes']);
    })->name('reviews');
    
    Route::get('/insurance', function () {
        return view('bikes.index', ['vehicleType' => 'bikes']);
    })->name('insurance');
    
    Route::get('/guides', function () {
        return view('bikes.index', ['vehicleType' => 'bikes']);
    })->name('guides');
    
    Route::get('/electric', function () {
        return view('bikes.index', ['vehicleType' => 'bikes']);
    })->name('electric');
});

// ============================================
// MOTORHOMES SECTION ROUTES
// ============================================
Route::prefix('motorhomes')->name('motorhomes.')->group(function () {
    Route::get('/', function () {
        return view('motorhomes.index', ['vehicleType' => 'motorhomes']);
    })->name('index');
    
    Route::get('/used', function () {
        return view('motorhomes.index', ['vehicleType' => 'motorhomes']);
    })->name('used');
    
    Route::get('/new', function () {
        return view('motorhomes.index', ['vehicleType' => 'motorhomes']);
    })->name('new');
    
    Route::get('/sell', function () {
        return view('motorhomes.index', ['vehicleType' => 'motorhomes']);
    })->name('sell');
    
    Route::get('/value', function () {
        return view('motorhomes.index', ['vehicleType' => 'motorhomes']);
    })->name('value');
    
    Route::get('/reviews', function () {
        return view('motorhomes.index', ['vehicleType' => 'motorhomes']);
    })->name('reviews');
    
    Route::get('/insurance', function () {
        return view('motorhomes.index', ['vehicleType' => 'motorhomes']);
    })->name('insurance');
    
    Route::get('/finance', function () {
        return view('motorhomes.index', ['vehicleType' => 'motorhomes']);
    })->name('finance');
    
    Route::get('/guides', function () {
        return view('motorhomes.index', ['vehicleType' => 'motorhomes']);
    })->name('guides');
});

// ============================================
// CARAVANS SECTION ROUTES
// ============================================
Route::prefix('caravans')->name('caravans.')->group(function () {
    Route::get('/', function () {
        return view('caravans.index', ['vehicleType' => 'caravans']);
    })->name('index');
    
    Route::get('/used', function () {
        return view('caravans.index', ['vehicleType' => 'caravans']);
    })->name('used');
    
    Route::get('/new', function () {
        return view('caravans.index', ['vehicleType' => 'caravans']);
    })->name('new');
    
    Route::get('/sell', function () {
        return view('caravans.index', ['vehicleType' => 'caravans']);
    })->name('sell');
    
    Route::get('/value', function () {
        return view('caravans.index', ['vehicleType' => 'caravans']);
    })->name('value');
    
    Route::get('/reviews', function () {
        return view('caravans.index', ['vehicleType' => 'caravans']);
    })->name('reviews');
    
    Route::get('/insurance', function () {
        return view('caravans.index', ['vehicleType' => 'caravans']);
    })->name('insurance');
    
    Route::get('/finance', function () {
        return view('caravans.index', ['vehicleType' => 'caravans']);
    })->name('finance');
    
    Route::get('/guides', function () {
        return view('caravans.index', ['vehicleType' => 'caravans']);
    })->name('guides');
});

// ============================================
// TRUCKS SECTION ROUTES
// ============================================
Route::prefix('trucks')->name('trucks.')->group(function () {
    Route::get('/', function () {
        return view('trucks.index', ['vehicleType' => 'trucks']);
    })->name('index');
    
    Route::get('/search', \App\Livewire\Customer\TruckSearch::class)->name('search');
    
    Route::get('/used', function () {
        return view('trucks.index', ['vehicleType' => 'trucks']);
    })->name('used');
    
    Route::get('/new', function () {
        return view('trucks.index', ['vehicleType' => 'trucks']);
    })->name('new');
    
    Route::get('/sell', function () {
        return view('trucks.index', ['vehicleType' => 'trucks']);
    })->name('sell');
    
    Route::get('/pricing', function () {
        return view('pricing.index', ['category' => 'trucks', 'vehicleType' => 'trucks']);
    })->name('pricing');
    
    Route::get('/value', function () {
        return view('trucks.index', ['vehicleType' => 'trucks']);
    })->name('value');
    
    Route::get('/reviews', function () {
        return view('trucks.index', ['vehicleType' => 'trucks']);
    })->name('reviews');
    
    Route::get('/leasing', function () {
        return view('trucks.index', ['vehicleType' => 'trucks']);
    })->name('leasing');
    
    Route::get('/finance', function () {
        return view('trucks.index', ['vehicleType' => 'trucks']);
    })->name('finance');
    
    Route::get('/parts', function () {
        return view('trucks.index', ['vehicleType' => 'trucks']);
    })->name('parts');
    
    Route::get('/insurance', function () {
        return view('trucks.insurance', ['vehicleType' => 'trucks']);
    })->name('insurance');
    
    // Dynamic route for truck detail must come AFTER all static routes
    Route::get('/{id}', \App\Livewire\Customer\TruckDetail::class)->name('detail');
});

// ============================================
// FARM EQUIPMENT SECTION ROUTES
// ============================================
Route::prefix('farm')->name('farm.')->group(function () {
    Route::get('/', function () {
        return view('farm.index', ['vehicleType' => 'farm']);
    })->name('index');
    
    Route::get('/tractors', function () {
        return view('farm.index', ['vehicleType' => 'farm']);
    })->name('tractors');
    
    Route::get('/equipment', function () {
        return view('farm.index', ['vehicleType' => 'farm']);
    })->name('equipment');
    
    Route::get('/sell', function () {
        return view('farm.index', ['vehicleType' => 'farm']);
    })->name('sell');
    
    Route::get('/value', function () {
        return view('farm.index', ['vehicleType' => 'farm']);
    })->name('value');
    
    Route::get('/reviews', function () {
        return view('farm.index', ['vehicleType' => 'farm']);
    })->name('reviews');
    
    Route::get('/finance', function () {
        return view('farm.index', ['vehicleType' => 'farm']);
    })->name('finance');
    
    Route::get('/parts', function () {
        return view('farm.index', ['vehicleType' => 'farm']);
    })->name('parts');
    
    Route::get('/guides', function () {
        return view('farm.index', ['vehicleType' => 'farm']);
    })->name('guides');
});

// ============================================
// PLANT MACHINERY SECTION ROUTES
// ============================================
Route::prefix('plant')->name('plant.')->group(function () {
    Route::get('/', function () {
        return view('plant.index', ['vehicleType' => 'plant']);
    })->name('index');
    
    Route::get('/excavators', function () {
        return view('plant.index', ['vehicleType' => 'plant']);
    })->name('excavators');
    
    Route::get('/machinery', function () {
        return view('plant.index', ['vehicleType' => 'plant']);
    })->name('machinery');
    
    Route::get('/sell', function () {
        return view('plant.index', ['vehicleType' => 'plant']);
    })->name('sell');
    
    Route::get('/value', function () {
        return view('plant.index', ['vehicleType' => 'plant']);
    })->name('value');
    
    Route::get('/reviews', function () {
        return view('plant.index', ['vehicleType' => 'plant']);
    })->name('reviews');
    
    Route::get('/hire', function () {
        return view('plant.index', ['vehicleType' => 'plant']);
    })->name('hire');
    
    Route::get('/finance', function () {
        return view('plant.index', ['vehicleType' => 'plant']);
    })->name('finance');
    
    Route::get('/parts', function () {
        return view('plant.index', ['vehicleType' => 'plant']);
    })->name('parts');
});

// ============================================
// ELECTRIC BIKES SECTION ROUTES
// ============================================
Route::prefix('electric-bikes')->name('electric-bikes.')->group(function () {
    Route::get('/', function () {
        return view('electric-bikes.index', ['vehicleType' => 'electric-bikes']);
    })->name('index');
    
    Route::get('/shop', function () {
        return view('electric-bikes.index', ['vehicleType' => 'electric-bikes']);
    })->name('shop');
    
    Route::get('/new', function () {
        return view('electric-bikes.index', ['vehicleType' => 'electric-bikes']);
    })->name('new');
    
    Route::get('/sell', function () {
        return view('electric-bikes.index', ['vehicleType' => 'electric-bikes']);
    })->name('sell');
    
    Route::get('/reviews', function () {
        return view('electric-bikes.index', ['vehicleType' => 'electric-bikes']);
    })->name('reviews');
    
    Route::get('/comparison', function () {
        return view('electric-bikes.index', ['vehicleType' => 'electric-bikes']);
    })->name('comparison');
    
    Route::get('/accessories', function () {
        return view('electric-bikes.index', ['vehicleType' => 'electric-bikes']);
    })->name('accessories');
    
    Route::get('/guides', function () {
        return view('electric-bikes.index', ['vehicleType' => 'electric-bikes']);
    })->name('guides');
    
    Route::get('/charging', function () {
        return view('electric-bikes.index', ['vehicleType' => 'electric-bikes']);
    })->name('charging');
});

// ============================================
// SPARE PARTS SECTION ROUTES
// ============================================
Route::prefix('spare-parts')->name('spare-parts.')->group(function () {
    Route::get('/', function () {
        return view('spare-parts.index', ['vehicleType' => 'spare-parts']);
    })->name('index');
    
    Route::get('/by-make', function () {
        return view('spare-parts.by-make', ['vehicleType' => 'spare-parts']);
    })->name('by-make');
    
    Route::get('/categories', function () {
        return view('spare-parts.categories', ['vehicleType' => 'spare-parts']);
    })->name('categories');
    
    Route::get('/search', function () {
        return view('spare-parts.search', ['vehicleType' => 'spare-parts']);
    })->name('search');
    
    Route::get('/sourcing', function () {
        return view('spare-parts.sourcing', ['vehicleType' => 'spare-parts']);
    })->name('sourcing');
    
    Route::middleware('auth')->group(function () {
        Route::get('/orders', function () {
            return view('spare-parts.orders', ['vehicleType' => 'spare-parts']);
        })->name('orders');
        
        Route::get('/order/{id}', function ($id) {
            return view('spare-parts.order-detail', ['id' => $id, 'vehicleType' => 'spare-parts']);
        })->name('order-detail');
    });
    
    Route::get('/supplier/{id}', \App\Livewire\Customer\SparePartSupplierDetail::class)->name('supplier');
});

// ============================================
// GARAGE SECTION ROUTES
// ============================================
Route::prefix('garage')->name('garage.')->group(function () {
    Route::get('/', function () {
        return view('garage.index', ['vehicleType' => 'garage']);
    })->name('index');
    
    Route::get('/services', function () {
        return view('garage.services', ['vehicleType' => 'garage']);
    })->name('services');
    
    Route::get('/by-location', function () {
        return view('garage.by-location', ['vehicleType' => 'garage']);
    })->name('by-location');
    
    Route::get('/book-service', function () {
        return view('garage.book-service', ['vehicleType' => 'garage']);
    })->name('book-service');
    
    Route::get('/pricing', function () {
        return view('pricing.index', ['category' => 'garage', 'vehicleType' => 'garage']);
    })->name('pricing');
});

// ============================================
// LOAN CALCULATOR ROUTES
// ============================================
Route::prefix('loan-calculator')->name('loan-calculator.')->group(function () {
    Route::get('/', \App\Livewire\Customer\LoanCalculator::class)->name('index');
});

// ============================================
// IMPORT FINANCING ROUTES
// ============================================
Route::prefix('import-financing')->name('import-financing.')->group(function () {
    Route::get('/', \App\Livewire\Customer\ImportFinancing::class)->name('index');
    
    Route::middleware('auth')->group(function () {
        Route::get('/requests', \App\Livewire\Customer\ImportFinancingRequests::class)->name('requests');
        Route::get('/requests/{id}', \App\Livewire\Customer\ImportFinancingRequestDetail::class)->name('request-detail');
    });
});

// ============================================
// UNIFIED PRICING ROUTE (for use in components)
// ============================================
Route::get('/pricing/{category}', function ($category) {
    $vehicleType = in_array($category, ['cars', 'trucks', 'garage']) ? $category : 'cars';
    return view('pricing.index', ['category' => $category, 'vehicleType' => $vehicleType]);
})->where('category', 'cars|trucks|garage')->name('pricing.show');

// ============================================
// DEALER PANEL ROUTES
// ============================================
Route::prefix('dealer')->name('dealer.')->middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dealer.dashboard');
    })->name('dashboard');
    
    // Vehicles
    Route::prefix('vehicles')->name('vehicles.')->group(function () {
        Route::get('/', function () {
            return view('dealer.vehicles.index');
        })->name('index');
        Route::get('/create', function () {
            return view('dealer.vehicles.create');
        })->name('create');
        Route::get('/active', function () {
            return view('dealer.vehicles.active');
        })->name('active');
        Route::get('/sold', function () {
            return view('dealer.vehicles.sold');
        })->name('sold');
    });
    
    // Offers
    Route::get('/offers', function () {
        return view('dealer.offers');
    })->name('offers');

    // Find-me-a-car requests (dealers submit offers)
    Route::get('/car-requests', \App\Livewire\Dealer\CarRequests::class)->name('car-requests');
    
    // Auctions - Buy from private sellers
    Route::get('/auctions', \App\Livewire\Dealer\AuctionList::class)->name('auctions');
    
    // Orders
    Route::get('/orders', function () {
        return view('dealer.orders');
    })->name('orders');
    
    // Analytics
    Route::get('/analytics', function () {
        return view('dealer.analytics');
    })->name('analytics');
    
    // Profile
    Route::get('/profile', function () {
        return view('dealer.profile');
    })->name('profile');
    
    // Settings
    Route::get('/settings', function () {
        return view('dealer.settings');
    })->name('settings');
});

// ============================================
// LENDER PANEL ROUTES
// ============================================
Route::prefix('lender')->name('lender.')->middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('lender.dashboard');
    })->name('dashboard');
    
    // Loan Requests
    Route::prefix('requests')->name('requests.')->group(function () {
        Route::get('/', function () {
            return view('lender.requests.index');
        })->name('index');
        Route::get('/pending', function () {
            return view('lender.requests.pending');
        })->name('pending');
        Route::get('/approved', function () {
            return view('lender.requests.approved');
        })->name('approved');
        Route::get('/rejected', function () {
            return view('lender.requests.rejected');
        })->name('rejected');
    });
    
    // Active Loans
    Route::get('/loans', function () {
        return view('lender.loans');
    })->name('loans');
    
    // Portfolio
    Route::get('/portfolio', function () {
        return view('lender.portfolio');
    })->name('portfolio');
    
    // Reports
    Route::get('/reports', function () {
        return view('lender.reports');
    })->name('reports');
    
    // Customers
    Route::get('/customers', function () {
        return view('lender.customers');
    })->name('customers');
    
    // Profile
    Route::get('/profile', function () {
        return view('lender.profile');
    })->name('profile');
    
    // Settings
    Route::get('/settings', function () {
        return view('lender.settings');
    })->name('settings');
});

// ============================================
// ADMIN PANEL ROUTES
// ============================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    // Dashboard - Shows different components based on user role (switch case)
    Route::get('/dashboard', function () {
        $user = auth()->user();
        $role = $user->role ?? 'customer';
        
        return view('admin.dashboard', ['userRole' => $role]);
    })->name('dashboard');
    
    // Profile & Settings (Common to all roles)
    Route::get('/profile', function () {
        return view('admin.profile');
    })->name('profile');
    
    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('settings');
    
    // Analytics
    Route::get('/analytics', function () {
        return view('admin.analytics');
    })->name('analytics');
    
    // Vehicles
    Route::prefix('vehicles')->name('vehicles.')->group(function () {
        Route::get('/', function () {
            return view('admin.vehicles.index');
        })->name('index');
        Route::get('/create', function () {
            return view('admin.vehicles.create');
        })->name('create');
        Route::get('/categories', function () {
            return view('admin.vehicles.categories');
        })->name('categories');
        Route::get('/brands', function () {
            return view('admin.vehicles.brands');
        })->name('brands');
        
        // Vehicle Registration
        Route::prefix('registration')->name('registration.')->group(function () {
            Route::get('/', function () {
                return view('admin.vehicles.registration-index');
            })->name('index');
            
            // Specific routes MUST come before dynamic routes
            Route::get('/create', function () {
                return view('admin.vehicles.registration-create');
            })->name('create');
            
            Route::get('/pending', function () {
                return view('admin.vehicles.registration-pending');
            })->name('pending');
            
            Route::get('/sold', function () {
                return view('admin.vehicles.registration-sold');
            })->name('sold');
            
            // Dynamic routes come last
            Route::get('/{id}', function ($id) {
                return view('admin.vehicles.registration-view', ['id' => $id]);
            })->name('view');
            
            Route::get('/{id}/edit', function ($id) {
                return view('admin.vehicles.registration-edit', ['id' => $id]);
            })->name('edit');
        });
    });
    
    // Trucks Management
    Route::prefix('trucks')->name('trucks.')->group(function () {
        Route::get('/', function () {
            return view('admin.trucks.index');
        })->name('index');
        
        // Specific routes MUST come before dynamic routes
        Route::get('/create', function () {
            return view('admin.trucks.create');
        })->name('create');
        
        Route::get('/pending', function () {
            return view('admin.trucks.pending');
        })->name('pending');
        
        Route::get('/sold', function () {
            return view('admin.trucks.sold');
        })->name('sold');
        
        // Dynamic routes come last
        Route::get('/{id}', function ($id) {
            return view('admin.trucks.view', ['id' => $id]);
        })->name('view');
        
        Route::get('/{id}/edit', function ($id) {
            return view('admin.trucks.edit', ['id' => $id]);
        })->name('edit');
    });
    
    // Listings
    Route::prefix('listings')->name('listings.')->group(function () {
        Route::get('/active', function () {
            return view('admin.listings.active');
        })->name('active');
        Route::get('/pending', function () {
            return view('admin.listings.pending');
        })->name('pending');
        Route::get('/sold', function () {
            return view('admin.listings.sold');
        })->name('sold');
        Route::get('/archived', function () {
            return view('admin.listings.archived');
        })->name('archived');
    });
    
    // Spare Part Orders
    Route::get('/spare-part-orders', \App\Livewire\Admin\SparePartOrders::class)->name('spare-part-orders');
    Route::get('/garage-orders', \App\Livewire\Admin\GarageOrders::class)->name('garage-orders');

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales', [\App\Http\Controllers\Admin\ReportController::class, 'sales'])->name('sales');
        Route::get('/vehicles', [\App\Http\Controllers\Admin\ReportController::class, 'vehicles'])->name('vehicles');
        Route::get('/users', [\App\Http\Controllers\Admin\ReportController::class, 'users'])->name('users');
    });

    // Find-me-a-car requests
    Route::get('/car-requests', \App\Livewire\Admin\CarRequests::class)->name('car-requests');
    Route::get('/car-requests/{id}', \App\Livewire\Admin\CarRequestDetail::class)->name('car-requests.view');
    
    // Auctions
    Route::get('/auctions', \App\Livewire\Admin\AuctionManagement::class)->name('auctions');
    Route::get('/auctions/{id}', \App\Livewire\Admin\AuctionDetail::class)->name('auctions.detail');
    
    // Import Financing Requests
    Route::get('/import-financing', \App\Livewire\Admin\ImportFinancingRequests::class)->name('import-financing');
    Route::get('/import-financing/{id}', \App\Livewire\Admin\ImportFinancingRequestDetail::class)->name('import-financing.detail');
    
    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', function () {
            return view('admin.orders.index');
        })->name('index');
        Route::get('/pending', function () {
            return view('admin.orders.pending');
        })->name('pending');
        Route::get('/processing', function () {
            return view('admin.orders.processing');
        })->name('processing');
        Route::get('/completed', function () {
            return view('admin.orders.completed');
        })->name('completed');
        
        // Evaluation Orders
        Route::prefix('evaluations')->name('evaluations.')->group(function () {
            Route::get('/', function () {
                return view('admin.orders.evaluations-index');
            })->name('index');
            Route::get('/pending-payment', function () {
                return view('admin.orders.evaluations-pending-payment');
            })->name('pending-payment');
            Route::get('/paid', function () {
                return view('admin.orders.evaluations-paid');
            })->name('paid');
            Route::get('/completed', function () {
                return view('admin.orders.evaluations-completed');
            })->name('completed');
            
            // Debug route to check orders
            Route::get('/debug', function () {
                $orders = \App\Models\Order::all();
                $valuationOrders = \App\Models\Order::where('order_type', 'valuation_report')->get();
                return response()->json([
                    'total_orders' => $orders->count(),
                    'valuation_orders' => $valuationOrders->count(),
                    'all_orders' => $orders->map(fn($o) => [
                        'id' => $o->id,
                        'order_number' => $o->order_number,
                        'type' => $o->order_type,
                        'status' => $o->status,
                    ]),
                    'valuation_details' => $valuationOrders->map(fn($o) => [
                        'id' => $o->id,
                        'order_number' => $o->order_number,
                        'user' => $o->user ? $o->user->name : 'N/A',
                        'vehicle' => $o->vehicle ? $o->vehicle->id : 'N/A',
                    ]),
                ]);
            });
            
            // Test route to check if order 1 exists
            Route::get('/test/{orderId}', function ($orderId) {
                try {
                    $order = \App\Models\Order::with(['vehicle.make', 'vehicle.model', 'user'])
                        ->where('order_type', 'valuation_report')
                        ->findOrFail($orderId);
                    
                    return response()->json([
                        'success' => true,
                        'order' => [
                            'id' => $order->id,
                            'number' => $order->order_number,
                            'status' => $order->status,
                            'user' => $order->user->name,
                            'vehicle' => $order->vehicle->make->name . ' ' . $order->vehicle->model->name,
                        ]
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'error' => $e->getMessage(),
                        'type' => get_class($e)
                    ]);
                }
            });
            
            Route::get('/{orderId}', function ($orderId) {
                return view('admin.orders.evaluation-detail', ['orderId' => $orderId]);
            })->name('view');
        });
        
        // Cash Purchase Orders
        Route::prefix('cash')->name('cash.')->group(function () {
            Route::get('/', function () {
                return view('admin.orders.cash-index');
            })->name('index');
            Route::get('/pending', function () {
                return view('admin.orders.cash-pending');
            })->name('pending');
            Route::get('/approved', function () {
                return view('admin.orders.cash-approved');
            })->name('approved');
            Route::get('/completed', function () {
                return view('admin.orders.cash-completed');
            })->name('completed');
            Route::get('/rejected', function () {
                return view('admin.orders.cash-rejected');
            })->name('rejected');
            Route::get('/{id}', function ($id) {
                return view('admin.orders.cash-detail', ['orderId' => $id]);
            })->name('detail');
        });
        
        // Financing Orders
        Route::prefix('financing')->name('financing.')->group(function () {
            Route::get('/', function () {
                return view('admin.orders.financing-index');
            })->name('index');
            Route::get('/pending', function () {
                return view('admin.orders.financing-index', ['filter' => 'pending']);
            })->name('pending');
            Route::get('/approved', function () {
                return view('admin.orders.financing-index', ['filter' => 'approved']);
            })->name('approved');
            Route::get('/completed', function () {
                return view('admin.orders.financing-index', ['filter' => 'completed']);
            })->name('completed');
            Route::get('/rejected', function () {
                return view('admin.orders.financing-index', ['filter' => 'rejected']);
            })->name('rejected');
            Route::get('/{id}', function ($id) {
                return view('admin.orders.financing-detail', ['orderId' => $id]);
            })->name('detail');
        });
        
        // Leasing Orders
        Route::prefix('leasing')->name('leasing.')->group(function () {
            Route::get('/', function () {
                return view('admin.orders.leasing-index');
            })->name('index');
            Route::get('/pending', function () {
                return view('admin.orders.leasing-index', ['filter' => 'pending']);
            })->name('pending');
            Route::get('/approved', function () {
                return view('admin.orders.leasing-index', ['filter' => 'approved']);
            })->name('approved');
            Route::get('/active', function () {
                return view('admin.orders.leasing-index', ['filter' => 'active']);
            })->name('active');
            Route::get('/completed', function () {
                return view('admin.orders.leasing-index', ['filter' => 'completed']);
            })->name('completed');
            Route::get('/rejected', function () {
                return view('admin.orders.leasing-index', ['filter' => 'rejected']);
            })->name('rejected');
            Route::get('/{id}', function ($id) {
                return view('admin.orders.leasing-detail', ['orderId' => $id]);
            })->name('detail');
        });
    });
    
    // Customers
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', function () {
            return view('admin.customers.index');
        })->name('index');
    });
    
    // Reviews
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', function () {
            return view('admin.reviews.index');
        })->name('index');
    });
    
    // Registration
    Route::prefix('registration')->name('registration.')->group(function () {
        // Customer Registration
        Route::get('/customers', function () {
            return view('admin.registration.customers');
        })->name('customers');
        Route::get('/customers/create', function () {
            return view('admin.registration.customers-create');
        })->name('customers.create');
        Route::get('/customers/{id}/edit', function ($id) {
            return view('admin.registration.customers-edit', ['id' => $id]);
        })->name('customers.edit');
        
        // CFC Registration
        Route::get('/cfcs', function () {
            return view('admin.registration.cfcs');
        })->name('cfcs');
        Route::get('/cfcs/create', function () {
            return view('admin.registration.cfcs-create');
        })->name('cfcs.create');
        Route::get('/cfcs/{id}/edit', function ($id) {
            return view('admin.registration.cfcs-edit', ['id' => $id]);
        })->name('cfcs.edit');
        
        // Agent Registration
        Route::get('/agents', function () {
            return view('admin.registration.agents');
        })->name('agents');
        Route::get('/agents/create', function () {
            return view('admin.registration.agents-create');
        })->name('agents.create');
        Route::get('/agents/{id}/edit', function ($id) {
            return view('admin.registration.agents-edit', ['id' => $id]);
        })->name('agents.edit');
        
        // Lender Registration
        Route::get('/lenders', function () {
            return view('admin.registration.lenders');
        })->name('lenders');
        Route::get('/lenders/create', function () {
            return view('admin.registration.create-lender');
        })->name('lenders.create');
        Route::get('/lenders/{id}/edit', function ($id) {
            return view('admin.registration.edit-lender', ['id' => $id]);
        })->name('lenders.edit');
        
        // Dealer Registration
        Route::get('/dealers', function () {
            return view('admin.registration.dealers');
        })->name('dealers');
        Route::get('/dealers/create', function () {
            return view('admin.registration.create-dealer');
        })->name('dealers.create');
        Route::get('/dealers/{id}/edit', function ($id) {
            return view('admin.registration.edit-dealer', ['id' => $id]);
        })->name('dealers.edit');
    });

    // Users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', function () {
            return view('admin.users.index');
        })->name('index');
        Route::get('/lenders', function () {
            return view('admin.users.lenders');
        })->name('lenders');
        Route::get('/dealers', function () {
            return view('admin.users.dealers');
        })->name('dealers');
        Route::get('/admins', function () {
            return view('admin.users.admins');
        })->name('admins');
        Route::get('/create', function () {
            return view('admin.users.create');
        })->name('create');
        Route::get('/roles', function () {
            return view('admin.users.roles');
        })->name('roles');
        Route::get('/permissions', function () {
            return view('admin.users.permissions');
        })->name('permissions');
    });
    
    // Lending Criteria
    Route::prefix('lending-criteria')->name('lending-criteria.')->group(function () {
        Route::get('/', function () {
            return view('admin.lending-criteria.index');
        })->name('index');
        Route::get('/create', function () {
            return view('admin.lending-criteria.create');
        })->name('create');
        Route::get('/{id}', function ($id) {
            return view('admin.lending-criteria.view', ['criteriaId' => $id]);
        })->name('view');
        Route::get('/{id}/edit', function ($id) {
            return view('admin.lending-criteria.edit', ['criteriaId' => $id]);
        })->name('edit');
    });
    
    // Vehicle Leasing
    Route::prefix('leasing')->name('leasing.')->group(function () {
        Route::get('/', function () {
            return view('admin.leasing.index');
        })->name('index');
        Route::get('/create', function () {
            return view('admin.leasing.create');
        })->name('create');
        Route::get('/{id}', function ($id) {
            return view('admin.leasing.view', ['id' => $id]);
        })->name('view');
        Route::get('/{id}/edit', function ($id) {
            return view('admin.leasing.edit', ['id' => $id]);
        })->name('edit');
    });
    
    // Leasing Cars
    Route::prefix('leasing-cars')->name('leasing-cars.')->group(function () {
        Route::get('/', function () {
            return view('admin.leasing-cars.index');
        })->name('index');
        Route::get('/create', function () {
            return view('admin.leasing-cars.create');
        })->name('create');
        Route::get('/{id}/edit', function ($id) {
            return view('admin.leasing-cars.edit', ['id' => $id]);
        })->name('edit');
        Route::get('/{id}', function ($id) {
            return view('admin.leasing-cars.view', ['id' => $id]);
        })->name('view');
    });
    
    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/vehicles', function () {
            return view('admin.settings.vehicles');
        })->name('vehicles');
        Route::get('/general', function () {
            return view('admin.settings.general');
        })->name('general');
        Route::get('/security', function () {
            return view('admin.settings.security');
        })->name('security');
        Route::get('/notifications', function () {
            return view('admin.settings.notifications');
        })->name('notifications');
        Route::get('/billing', function () {
            return view('admin.settings.billing');
        })->name('billing');
    });
    
    // Pricing Management
    Route::get('/pricing', function () {
        return view('admin.pricing.index');
    })->name('pricing.index');
    
    // Valuation Pricing Management
    Route::get('/valuation-pricing', \App\Livewire\Admin\ValuationPriceManager::class)->name('valuation-pricing.index');
});

// ============================================
// LENDER ROUTES (Additional routes)
// ============================================
Route::get('/lender', function () {
    return redirect()->route('lender.applications.index');
})->name('lender.index');

Route::prefix('lender')->name('lender.')->group(function () {
    // Financing Applications
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/', function () {
            return view('lender.applications.index', ['filter' => 'pending']);
        })->name('index');
        Route::get('/pending', function () {
            return view('lender.applications.index', ['filter' => 'pending']);
        })->name('pending');
        Route::get('/approved', function () {
            return view('lender.applications.index', ['filter' => 'approved']);
        })->name('approved');
        Route::get('/rejected', function () {
            return view('lender.applications.index', ['filter' => 'rejected']);
        })->name('rejected');
        Route::get('/{id}', function ($id) {
            return view('lender.applications.detail', ['applicationId' => $id]);
        })->name('detail');
    });
    
    // Import Financing Requests
    Route::prefix('import-financing')->name('import-financing.')->group(function () {
        Route::get('/', \App\Livewire\Lender\ImportFinancingRequests::class)->name('index');
        Route::get('/{id}', \App\Livewire\Lender\ImportFinancingRequestDetail::class)->name('detail');
    });
});

// ============================================
// OTP VERIFICATION ROUTES
// ============================================
Route::middleware(['auth'])->group(function () {
    Route::get('/verify-otp', [App\Http\Controllers\Auth\OtpVerificationController::class, 'show'])->name('otp.verify.show');
    Route::post('/verify-otp', [App\Http\Controllers\Auth\OtpVerificationController::class, 'verify'])->name('otp.verify');
    Route::post('/resend-otp', [App\Http\Controllers\Auth\OtpVerificationController::class, 'resend'])->name('otp.resend');
});

// ============================================
// AUTHENTICATED ROUTES
// ============================================
// Unified dashboard route – redirects by user role
Route::get('dashboard', \App\Http\Controllers\DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', function () {
        return view('customer.profile');
    })->name('profile.edit');
    
    Route::get('settings/password', function () {
        return view('customer.password');
    })->name('user-password.edit');
    
    Route::get('settings/appearance', function () {
        return view('customer.appearance');
    })->name('appearance.edit');

    // My Orders
    Route::get('my-orders', \App\Livewire\Customer\MyOrders::class)->name('my-orders');
    Route::get('my-adverts', \App\Livewire\Customer\MyAdverts::class)->name('my-adverts');
    Route::get('my-auctions', \App\Livewire\Customer\MyAuctions::class)->name('my-auctions');

    // My car requests (Find-me-a-car)
    Route::get('my-car-requests', \App\Livewire\Customer\MyCarRequests::class)->name('my-car-requests');

    Route::get('settings/two-factor', function () {
        return view('customer.two-factor');
    })->middleware(
        when(
            Features::canManageTwoFactorAuthentication()
                && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
            ['password.confirm'],
            [],
        ),
    )->name('two-factor.show');
});

// ============================================
// CHATBOT API ROUTE
// ============================================
Route::post('/api/chatbot/chat', [App\Http\Controllers\ChatbotController::class, 'chat'])->name('chatbot.chat');

// ============================================
// TEST ROUTES (For Development)
// ============================================
Route::get('/test', function () {
    return view('test.test');
});

Route::get('/test-dashboard', function () {
    return view('test.dashboard');
});
