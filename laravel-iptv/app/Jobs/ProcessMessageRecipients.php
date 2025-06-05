<?php

namespace App\Jobs;

use App\Models\General\Body\Guests\Room;
use App\Models\General\Body\Messages\MessageRecipient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessMessageRecipients implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $deviceCategoryId;
    protected $default;

    /**
     * Create a new job instance.
     *
     * @param $data
     * @param $deviceCategoryId
     * @param bool $default
     */
    public function __construct($data, $deviceCategoryId = null, $default = false)
    {
        $this->data = $data;
        $this->deviceCategoryId = $deviceCategoryId;
        $this->default = $default;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $rooms = [];

            if ($this->default) {
                // Get all rooms
                $rooms = Room::whereHas('devices')->get();
            } elseif ($this->deviceCategoryId) {
                // Get rooms with devices belonging to the specified category
                $rooms = Room::whereHas('devices.category', function ($query) {
                    $query->where('id', $this->deviceCategoryId);
                })->get();
            }

            foreach ($rooms as $room) {
                $msg = new MessageRecipient();
                $msg->regular_message_id = $this->data['id'];
                $msg->room_id = $room->id; // Assign room ID here
                $msg->status_id = 31; // New

                if (!$msg->save()) {
                    // Rollback if any save fails
                    DB::rollBack();
                    return;
                }
            }
        } catch (\Exception $e) {
            // Handle exceptions (optional: add logging or retry logic)
            DB::rollBack();
            throw $e;
        }
    }
}
