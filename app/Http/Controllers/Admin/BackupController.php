<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupController extends Controller
{
    public function index()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('admin.backup');
    }

    public function database()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        try {
            $database = config('database.connections.mysql.database');
            $host = config('database.connections.mysql.host');
            $port = config('database.connections.mysql.port');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');

            $fileName = 'database_backup_' . date('Y-m-d_H-i-s') . '.sql';

            $response = new StreamedResponse(function () use ($host, $port, $username, $password, $database) {
                $command = sprintf(
                    'mysqldump --host=%s --port=%s --user=%s --password=%s %s',
                    escapeshellarg($host),
                    escapeshellarg($port),
                    escapeshellarg($username),
                    escapeshellarg($password),
                    escapeshellarg($database)
                );

                passthru($command);
            });

            $response->headers->set('Content-Type', 'application/sql');
            $response->headers->set('Content-Disposition', 'attachment; filename="'.$fileName.'"');

            return $response;

        } catch (\Exception $e) {
            return redirect()->route('admin.backup')->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }
}