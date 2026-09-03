<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FlustraWebhookController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

// Directly redirect root to Login (or Dashboard if already authenticated)
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Flustra WA Gateway Webhook (CSRF verification excluded in bootstrap/app.php)
Route::post('/api/webhook/flustra', [FlustraWebhookController::class, 'handle'])->name('webhook.flustra');
Route::post('/webhook/flustra', [FlustraWebhookController::class, 'handle']);

// Authenticated CRM Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // 1. Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Leads & Pipeline Management
    Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
    Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
    Route::get('/leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
    Route::put('/leads/{lead}', [LeadController::class, 'update'])->name('leads.update');
    Route::delete('/leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');
    Route::post('/leads/{lead}/convert-deal', [LeadController::class, 'convertToDeal'])->name('leads.convert-deal');
    Route::post('/leads/{lead}/kanban-status', [LeadController::class, 'updateStatusAjax'])->name('leads.kanban-status');
    Route::post('/leads/{lead}/quick-followup', [LeadController::class, 'quickFollowUp'])->name('leads.quick-followup');

    // 3. Projects Management & Workflow
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::post('/projects/{project}/status', [ProjectController::class, 'updateStatus'])->name('projects.update-status');

    // 4. Payments & Invoicing
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::patch('/payments/{payment}/status', [PaymentController::class, 'updateStatus'])->name('payments.update-status');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');

    // 5. Official Invoices & Receipts (Printable / PDF)
    Route::get('/invoices/project/{project}', [InvoiceController::class, 'projectInvoice'])->name('invoices.project');
    Route::get('/invoices/payment/{payment}', [InvoiceController::class, 'paymentReceipt'])->name('invoices.receipt');
    Route::get('/invoices/maintenance/{subscription}', [InvoiceController::class, 'maintenanceInvoice'])->name('invoices.maintenance');

    // 6. Maintenance Subscriptions
    Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
    Route::post('/maintenance', [MaintenanceController::class, 'store'])->name('maintenance.store');
    Route::patch('/maintenance/{subscription}/toggle', [MaintenanceController::class, 'toggleStatus'])->name('maintenance.toggle');
    Route::post('/maintenance/{subscription}/reminder', [MaintenanceController::class, 'sendReminder'])->name('maintenance.reminder');

    // 7. WhatsApp Message Logs & Manual Dispatch
    Route::get('/messages', [WhatsAppController::class, 'index'])->name('messages.index');
    Route::post('/messages/send-manual', [WhatsAppController::class, 'sendManual'])->name('messages.send-manual');

    // 8. 1-Click Data Export (Zero-RAM Native Streaming CSV)
    Route::get('/export/leads', [ExportController::class, 'exportLeads'])->name('export.leads');
    Route::get('/export/projects', [ExportController::class, 'exportProjects'])->name('export.projects');
    Route::get('/export/payments', [ExportController::class, 'exportPayments'])->name('export.payments');

    // 9. Activity Logs / Audit Trail (Admin)
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    // 10. Internal User Management (Admin Only)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // 11. Profile & Notification API
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/notifications', function () {
        return response()->json(auth()->user()->unreadNotifications ?? []);
    });
    Route::post('/notifications/{id}/read', function ($id) {
        auth()->user()->notifications()->where('id', $id)->first()?->markAsRead();
        return response()->json(['success' => true]);
    });
});

require __DIR__.'/auth.php';
