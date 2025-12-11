<?php

declare(strict_types=1);

use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InventoryTransactionController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductLineController;
use App\Http\Controllers\ProductModelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;

Route::get('/', fn (): Factory|View => view('welcome'));

Route::get('/demo', fn (): Factory|View => view('demo'))->name('demo');

Route::get('/dashboard', fn (): Factory|View => view('dashboard'))->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function (): void {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Users
    Route::patch('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
    Route::resource('users', UserController::class);

    // Tenants
    Route::patch('/tenants/{tenant}/toggle-active', [TenantController::class, 'toggleActive'])->name('tenants.toggle-active');
    Route::resource('tenants', TenantController::class);

    // Manufacturers
    Route::patch('/manufacturers/{manufacturer}/toggle-active', [ManufacturerController::class, 'toggleActive'])->name('manufacturers.toggle-active');
    Route::resource('manufacturers', ManufacturerController::class);

    // Roles
    Route::resource('roles', RoleController::class);

    // Permissions
    Route::resource('permissions', PermissionController::class);

    // Teams
    Route::patch('/teams/{team}/toggle-active', [TeamController::class, 'toggleActive'])->name('teams.toggle-active');
    Route::resource('teams', TeamController::class);

    // Product Models
    Route::patch('/product-models/{productModel}/toggle-active', [ProductModelController::class, 'toggleActive'])->name('product-models.toggle-active');
    Route::resource('product-models', ProductModelController::class);

    // Product Lines
    Route::resource('product-lines', ProductLineController::class);

    // Product Categories
    Route::resource('product-categories', ProductCategoryController::class);

    // Parts
    Route::patch('/parts/{part}/toggle-active', [PartController::class, 'toggleActive'])->name('parts.toggle-active');
    Route::resource('parts', PartController::class);

    // Warehouses (Estoque)
    Route::resource('warehouses', WarehouseController::class);

    // Inventory Items
    Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('inventory/{inventoryItem}', [InventoryController::class, 'show'])->name('inventory.show');

    // Inventory Transactions
    Route::get('inventory-transactions', [InventoryTransactionController::class, 'index'])->name('inventory-transactions.index');
    Route::get('inventory-transactions/create', [InventoryTransactionController::class, 'create'])->name('inventory-transactions.create');
    Route::post('inventory-transactions', [InventoryTransactionController::class, 'store'])->name('inventory-transactions.store');
    Route::get('inventory-transactions/{inventoryTransaction}', [InventoryTransactionController::class, 'show'])->name('inventory-transactions.show');
});

require __DIR__.'/auth.php';
