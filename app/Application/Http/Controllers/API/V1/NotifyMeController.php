<?php

namespace App\Application\Http\Controllers\API\V1;

use App\Domain\Notification\Services\NotificationService;
use DB;
use Illuminate\Http\Request;

class NotifyMeController
{
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
        return DB::table('notify_me')->get();
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
