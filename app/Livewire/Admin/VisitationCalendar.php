<?php

namespace App\Livewire\Admin;

use App\Models\CarVisitationRequest;
use Carbon\Carbon;
use Livewire\Component;

class VisitationCalendar extends Component
{
    public $currentMonth;
    public $currentYear;

    public function mount(): void
    {
        $this->currentMonth = (int) now()->format('n');
        $this->currentYear = (int) now()->format('Y');
    }

    public function previousMonth(): void
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->subMonth();
        $this->currentMonth = (int) $date->format('n');
        $this->currentYear = (int) $date->format('Y');
    }

    public function nextMonth(): void
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->addMonth();
        $this->currentMonth = (int) $date->format('n');
        $this->currentYear = (int) $date->format('Y');
    }

    public function goToToday(): void
    {
        $this->currentMonth = (int) now()->format('n');
        $this->currentYear = (int) now()->format('Y');
    }

    public function getCalendarDays(): array
    {
        $start = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->startOfMonth()->startOfWeek();
        $end = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->endOfMonth()->endOfWeek();

        $visitationsByDate = CarVisitationRequest::with(['vehicle.make', 'vehicle.model'])
            ->where('status', 'scheduled')
            ->whereNotNull('scheduled_at')
            ->whereBetween('scheduled_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->orderBy('scheduled_at')
            ->get()
            ->groupBy(fn ($v) => $v->scheduled_at->format('Y-m-d'));

        $days = [];
        $date = $start->copy();
        while ($date->lte($end)) {
            $key = $date->format('Y-m-d');
            $days[] = [
                'date' => $date->copy(),
                'isCurrentMonth' => $date->month === $this->currentMonth,
                'isToday' => $date->isToday(),
                'visitations' => $visitationsByDate->get($key, collect()),
            ];
            $date->addDay();
        }

        return $days;
    }

    public function render()
    {
        return view('livewire.admin.visitation-calendar', [
            'calendarDays' => $this->getCalendarDays(),
            'monthName' => Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->format('F Y'),
        ])->layout('layouts.admin');
    }
}
