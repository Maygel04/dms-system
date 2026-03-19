<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/* ================= AUTH ================= */
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

/* ================= ADMIN ================= */
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BackupController;

/* ================= APPLICANT ================= */
use App\Http\Controllers\Applicant\DashboardController as ApplicantDashboardController;
use App\Http\Controllers\Applicant\UploadMpdoController;
use App\Http\Controllers\Applicant\UploadMeoController;
use App\Http\Controllers\Applicant\UploadBfpController;
use App\Http\Controllers\Applicant\ViewDocumentsController;
use App\Http\Controllers\Applicant\TrackController;

/* ================= VERIFIERS ================= */
use App\Http\Controllers\MPDO\DashboardController as MPDODashboard;
use App\Http\Controllers\MEO\DashboardController as MEODashboard;
use App\Http\Controllers\BFP\DashboardController as BFPDashboard;

/* ================= PROFILE ================= */
use App\Http\Controllers\ProfileController;

/* ================= ROOT ================= */
Route::get('/', function () {
    return redirect()->route('login');
});

/* ================= AUTH ================= */
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

/* ================= LOGOUT ================= */
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');
})->name('logout');

/* ===================================================== */
/* ================= AUTHENTICATED USERS ================ */
/* ===================================================== */
Route::middleware(['auth'])->group(function () {

    /* ================= PROFILE ================= */
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/upload-photo', [ProfileController::class, 'uploadPhoto'])->name('profile.uploadPhoto');

    /* ===================================================== */
    /* ================= ADMIN ============================== */
    /* ===================================================== */
    Route::middleware(['role:admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

            Route::get('/users', [UserController::class, 'index'])->name('users');
            Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
            Route::post('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');

            Route::get('/create-user', [UserController::class, 'create'])->name('create.user');
            Route::post('/create-user', [UserController::class, 'store'])->name('store.user');

            Route::get('/upload-old-form', [AdminDashboardController::class, 'showUploadOldForm'])->name('upload.old.form');
            Route::post('/upload-old-form', [AdminDashboardController::class, 'uploadOld'])->name('upload.old.form.store');
            Route::post('/upload-old', [AdminDashboardController::class, 'uploadOld'])->name('upload.old');

            Route::get('/departments', [AdminDashboardController::class, 'departments'])->name('departments');
            Route::get('/departments/mpdo', [AdminDashboardController::class, 'mpdoView'])->name('departments.mpdo');
            Route::get('/departments/meo', [AdminDashboardController::class, 'meoView'])->name('departments.meo');
            Route::get('/departments/bfp', [AdminDashboardController::class, 'bfpView'])->name('departments.bfp');

            Route::get('/notifications', [AdminDashboardController::class, 'notifications'])->name('notifications');
            Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('reports');
            Route::get('/payments', [AdminDashboardController::class, 'payments'])->name('payments');
            Route::get('/logs', [AdminDashboardController::class, 'logs'])->name('logs');
            Route::get('/applications', [AdminDashboardController::class, 'applications'])->name('applications');

            Route::get('/backup', [BackupController::class, 'index'])->name('backup');
            Route::post('/backup/database', [BackupController::class, 'databaseBackup'])->name('backup.database');

            Route::get('/report/generate', [AdminDashboardController::class, 'generateReport'])
            ->name('admin.generate.report');
});
      

    /* ===================================================== */
    /* ================= APPLICANT ========================== */
    /* ===================================================== */
    Route::middleware(['role:applicant'])
        ->prefix('applicant')
        ->name('applicant.')
        ->group(function () {

            Route::get('/dashboard', [ApplicantDashboardController::class, 'index'])->name('dashboard');

            Route::get('/upload_mpdo', [UploadMpdoController::class, 'index'])->name('upload_mpdo');
            Route::post('/upload_mpdo', [UploadMpdoController::class, 'store'])->name('upload_mpdo.store');

            Route::get('/upload_meo', [UploadMeoController::class, 'index'])->name('upload_meo');
            Route::post('/upload_meo', [UploadMeoController::class, 'store'])->name('upload_meo.store');

            Route::get('/upload_bfp', [UploadBfpController::class, 'index'])->name('upload_bfp');
            Route::post('/upload_bfp', [UploadBfpController::class, 'store'])->name('upload_bfp.store');

            Route::get('/view_documents', [ViewDocumentsController::class, 'index'])->name('view_documents');

            Route::get('/track', [TrackController::class, 'index'])->name('track');
            Route::post('/save-payment', [TrackController::class, 'savePayment'])->name('save_payment');

            Route::get('/applications', [ApplicantDashboardController::class, 'index'])->name('applications');
        });

    /* ===================================================== */
    /* ================= MPDO =============================== */
    /* ===================================================== */
    Route::middleware(['role:mpdo'])
        ->prefix('mpdo')
        ->name('mpdo.')
        ->group(function () {

            Route::get('/dashboard', [MPDODashboard::class, 'index'])->name('dashboard');
            Route::get('/applications', [MPDODashboard::class, 'applications'])->name('applications');
            Route::post('/save-assessment', [MPDODashboard::class, 'saveAssessment'])->name('saveAssessment');
            Route::post('/save-remark', [MPDODashboard::class, 'saveRemark'])->name('saveRemark');
            Route::post('/verify/{id}', [MPDODashboard::class, 'verify'])->name('verify');
            Route::get('/receipt/{id}', [MPDODashboard::class, 'receipt'])->name('receipt');
            Route::get('/notifications', fn() => view('mpdo.notifications'))->name('notifications');
            Route::get('/reports', [MPDODashboard::class, 'reports'])->name('reports');
            Route::get('/payments', [MPDODashboard::class, 'payments'])->name('payments');
        });

    /* ===================================================== */
    /* ================= MEO ================================ */
    /* ===================================================== */
    Route::middleware(['role:meo'])
        ->prefix('meo')
        ->name('meo.')
        ->group(function () {

            Route::get('/dashboard', [MEODashboard::class, 'index'])->name('dashboard');
            Route::get('/applications', [MEODashboard::class, 'applications'])->name('applications');
            Route::post('/save-assessment', [MEODashboard::class, 'saveAssessment'])->name('saveAssessment');
            Route::post('/save-remark', [MEODashboard::class, 'saveRemark'])->name('saveRemark');
            Route::post('/verify/{id}', [MEODashboard::class, 'verify'])->name('verify');
            Route::post('/issue-endorsement', [MEODashboard::class, 'issueEndorsement'])->name('issue.endorsement');
            Route::post('/mark-paid', [MEODashboard::class, 'markAsPaid'])->name('mark.paid');
            Route::get('/receipt/{id}', [MEODashboard::class, 'receipt'])->name('receipt');
            Route::get('/notifications', fn() => view('meo.notifications'))->name('notifications');
            Route::get('/reports', [MEODashboard::class, 'reports'])->name('reports');
            Route::get('/payments', [MEODashboard::class, 'payments'])->name('payments');
        });

    /* ===================================================== */
    /* ================= BFP ================================ */
    /* ===================================================== */
    Route::middleware(['role:bfp'])
        ->prefix('bfp')
        ->name('bfp.')
        ->group(function () {

            Route::get('/dashboard', [BFPDashboard::class, 'index'])->name('dashboard');
            Route::get('/applications', [BFPDashboard::class, 'applications'])->name('applications');
            Route::post('/save-assessment', [BFPDashboard::class, 'saveAssessment'])->name('saveAssessment');
            Route::post('/verify/{id}', [BFPDashboard::class, 'verify'])->name('verify');
            Route::post('/issue-clearance', [BFPDashboard::class, 'issue'])->name('issue');
            Route::post('/mark-paid', [BFPDashboard::class, 'markAsPaid'])->name('mark.paid');
            Route::post('/save-remark', [BFPDashboard::class, 'saveRemark'])->name('saveRemark');
            Route::get('/notifications', [BFPDashboard::class, 'notifications'])->name('notifications');
            Route::get('/reports', [BFPDashboard::class, 'reports'])->name('reports');
            Route::get('/payments', [BFPDashboard::class, 'payments'])->name('payments');
            Route::get('/receipt/{id}', [BFPDashboard::class, 'receipt'])->name('receipt');
        });
});

/* ================= TEST ================= */
Route::get('/test-profile', function () {
    return 'profile route working';
});