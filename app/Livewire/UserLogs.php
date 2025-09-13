<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Log;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserLogs extends Component
{
    use WithPagination;

    public $subjectType = '';
    public $start_date = '';
    public $end_date = '';
    public $action = '';
    public $ref_no = '';
    public $description = '';
    public $showFilters = false;
    public $perPage = 10;

    public function updating($name, $value)
    {
        if ($name === 'perPage') {
            $this->resetPage();
        }
        $this->resetPage();
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function render()
    {
        $departments = Department::orderBy('name')->get();
        $users = User::all()->keyBy('id');
        $currentUser = Auth::user();
        
        // Only show logs for the current user
        $logsQuery = Activity::with('causer')
            ->where('causer_id', $currentUser->id)
            ->orderByDesc('created_at');

        // Get all unique subject types (full class names)
        $allSubjectTypes = Activity::where('causer_id', $currentUser->id)
            ->select('subject_type')
            ->distinct()
            ->pluck('subject_type');
            
        $subjectTypes = $allSubjectTypes->map(function($type) {
            $base = class_basename($type);
            return $base === 'SupplyProfile' ? 'ProductProfile' : $base;
        })->unique()->values();

        // Map selected base name back to full class name for filtering
        $selectedFullSubjectType = $allSubjectTypes->first(function($type) {
            $base = class_basename($type);
            $selected = $this->subjectType === 'ProductProfile' ? 'SupplyProfile' : $this->subjectType;
            return $base === $selected;
        });
        
        if ($this->subjectType && $selectedFullSubjectType) {
            $logsQuery->where('subject_type', $selectedFullSubjectType);
        }

        if ($this->start_date) {
            $logsQuery->whereDate('created_at', '>=', $this->start_date);
        }
        if ($this->end_date) {
            $logsQuery->whereDate('created_at', '<=', $this->end_date);
        }
        if ($this->action) {
            $logsQuery->where(function($q) {
                if ($this->action === 'Created') $q->where('event', 'created');
                elseif ($this->action === 'Deleted') $q->where('event', 'deleted');
                elseif ($this->action === 'Edited') $q->whereNotIn('event', ['created', 'deleted']);
            });
        }
        if ($this->ref_no) {
            $logsQuery->where(function($q) {
                $q->where('subject_id', 'like', "%{$this->ref_no}%")
                  ->orWhereJsonContains('properties->attributes->id', $this->ref_no)
                  ->orWhereJsonContains('properties->old->id', $this->ref_no);
            });
        }
        if ($this->description) {
            $logsQuery->where(function($q) {
                $q->where('description', 'like', "%{$this->description}%")
                  ->orWhereJsonContains('properties->attributes->description', $this->description)
                  ->orWhereJsonContains('properties->old->description', $this->description);
            });
        }

        $logs = $logsQuery->paginate($this->perPage);
        
        return view('livewire.user-logs', [
            'logs' => $logs,
            'departments' => $departments,
            'users' => $users,
            'subjectTypes' => $subjectTypes,
            'selectedSubjectType' => $this->subjectType,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'selectedAction' => $this->action,
            'ref_no' => $this->ref_no,
            'descriptionFilter' => $this->description,
            'showFilters' => $this->showFilters,
            'currentUser' => $currentUser,
        ]);
    }
}
