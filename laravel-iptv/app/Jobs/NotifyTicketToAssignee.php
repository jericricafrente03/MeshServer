<?php

namespace App\Jobs;

use App\Models\Employee;
use App\Models\StoreStatus;
use App\Models\Ticket;
use App\Notifications\TicketNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyTicketToAssignee implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user, $employee, $ticket, $currentStatus, $previousStatus = null;
    protected $event = "new";

    /**
     * Create a new job instance.
     */
    public function __construct(Employee $employee
    , Ticket $ticket
    , StoreStatus $currentStatus
    , StoreStatus $previousStatus = null
    , String $event)
    {
        $this->employee = $employee;
        $this->ticket = $ticket;
        $this->currentStatus = $currentStatus;
        $this->previousStatus = $previousStatus;
        $this->event = $event;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->notifyAssignee($this->employee);
    }

    private function notifyAssignee(Employee $employee){ 
        if(isset($employee->user->id)) {
            $employee->user->notify(new TicketNotification($this->ticket, $this->currentStatus, $this->previousStatus, $this->event));
        }
    }
}
