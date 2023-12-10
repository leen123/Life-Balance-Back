<?php

namespace App\Http\Resources;

use App\Http\Resources\Task as ResourcesTask;
use App\Task;

use App\Utils\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class Goal extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $goals = [
            'id' => $this->id,
            'section_id' => $this->section['id'],
            'name' => $this->name,
            'ar_name' => $this->ar_name,
            'image' => isset($this->image) ? asset('uploads/' . $this->image) : '',
            'points' => $this->points,
            'final_date' => $this->final_date,
            'duration' => $this->duration,
            'is_completed' => $this->is_completed == 1,
            'created_at' => $this->created_at,
        ];

        $tasks = isset($this->tasks) ? ResourcesTask::collection($this->tasks) : [];


        $goals['tasks'] = $tasks;

        $total_tasks = count($tasks);
        $goals['total_tasks'] = $total_tasks;

        $tasks_complete = Task::where(['goals_id' => $this->id , 'is_Finished' => 1])->count();

        $goals['tasks_complete'] = $tasks_complete;

        $goals['avg_completed'] = Helper::cal_percentage($tasks_complete , $total_tasks);

        return $goals;
    }
}
