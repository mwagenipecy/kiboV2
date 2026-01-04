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
    
    Route::get('/value', function () {
        return view('cars.value', ['vehicleType' => 'cars']);
    })->name('value');
    
    Route::get('/reviews', function () {
        return view('cars.reviews', ['vehicleType' => 'cars']);
    })->name('reviews');
    
    Route::get('/leasing', function () {
        return view('cars.leasing', ['vehicleType' => 'cars']);
    })->name('leasing');
    
    Route::get('/electric', function () {
        return view('cars.electric', ['vehicleType' => 'cars']);
    })->name('electric');
    
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
    
    Route::get('/used', function () {
        return view('trucks.index', ['vehicleType' => 'trucks']);
    })->name('used');
    
    Route::get('/new', function () {
        return view('trucks.index', ['vehicleType' => 'trucks']);
    })->name('new');
    
    Route::get('/sell', function () {
        return view('trucks.index', ['vehicleType' => 'trucks']);
    })->name('sell');
    
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
// ADMIN PANEL ROUTES
// ============================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
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
    
    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', function () {
            return view('admin.orders.index');
        })->name('index');
        Route::get('/processing', function () {
            return view('admin.orders.processing');
        })->name('processing');
        Route::get('/completed', function () {
            return view('admin.orders.completed');
        })->name('completed');
        Route::get('/cancelled', function () {
            return view('admin.orders.cancelled');
        })->name('cancelled');
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
});

// ============================================
// AUTHENTICATED ROUTES
// ============================================
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

// ============================================
// TEST ROUTES (For Development)
// ============================================
Route::get('/test', function () {
    return view('test.test');
});

Route::get('/test-dashboard', function () {
    return view('test.dashboard');
});
