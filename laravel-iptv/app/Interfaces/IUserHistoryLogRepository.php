<?php

namespace App\Interfaces;

interface IUserHistoryLogRepository 
{
    public function store($data, $activityName);
    public function update($oldData, $data, $activityName);
    public function delete($id, $activityName);
    public function rbacToggle($id, $toggle, $data, $activityName);
    public function raCheckOut($oldData, $data, $activityName);
    public function customUpdate($oldData, $data, $activity);
    public function themeRoom($oldData, $data, $activity);
}