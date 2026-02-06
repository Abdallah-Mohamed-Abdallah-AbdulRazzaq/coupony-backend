<?php

namespace App\Application\Http\Controllers\API\V1;

use App\Application\Http\Controllers\Controller;
use App\Domain\Notification\Services\NotificationService;
use DB;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class NotifyMeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // examples with aliases, pipe-separated names, guards, etc:
            new Middleware(\Spatie\Permission\Middleware\RoleMiddleware::using('admin'), except: ['submit']),
            new Middleware('auth:sanctum', except: ['submit']),
        ];
    }

    public function __construct(
        private NotificationService $notificationService
    ) {

    }
    public function submit(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
        ]);

        DB::table('notify_me')->insert([
            array_merge($data, ['created_at' => now(), 'updated_at' => now()]),
        ]);

        return response()->json(['message' => 'success']);
    }

    public function list()
    {
        $data = DB::table('notify_me')->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function delete($id)
    {
        return DB::table('notify_me')->where('id', $id)->delete();
    }

    public function deleteAll()
    {
        return DB::table('notify_me')->delete();
    }

    public function count()
    {
        return DB::table('notify_me')->count();
    }

    public function get($id)
    {
        return DB::table('notify_me')->where('id', $id)->first();
    }

    public function notifyAll(Request $request)
    {
        $data = $request->validate([
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);
        return $this->notificationService->notifyAll($data);
    }
}
