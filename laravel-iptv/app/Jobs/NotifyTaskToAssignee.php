<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\User;
use App\Models\Employee;
use App\Models\Task;
use App\Models\StoreStatus;
use App\Notifications\TaskNotification;

class NotifyTaskToAssignee implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user, $employee, $task, $currentStatus, $previousStatus = null;
    protected $event = "new";

    /**
     * Create a new job instance.
     */
    public function __construct(Employee $employee
        , Task $task
        , StoreStatus $currentStatus
        , StoreStatus $previousStatus = null
        , String $event)
    {
        $this->employee = $employee;
        $this->task = $task;
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
            $employee->user->notify(new TaskNotification($this->task, $this->currentStatus, $this->previousStatus, $this->event));
        }
    }
}
