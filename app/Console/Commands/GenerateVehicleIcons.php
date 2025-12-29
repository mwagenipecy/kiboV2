<?php

namespace App\Console\Commands;

use App\Models\VehicleMake;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateVehicleIcons extends Command
{
    protected $signature = 'vehicle:generate-icons';
    protected $description = 'Generate placeholder SVG icons for vehicle makes';

    public function handle()
    {
        $makes = VehicleMake::all();
        $colors = [
            '#3B82F6', // Blue
            '#10B981', // Green
            '#8B5CF6', // Purple
            '#F59E0B', // Orange
            '#EF4444', // Red
            '#06B6D4', // Cyan
            '#EC4899', // Pink
            '#84CC16', // Lime
        ];

        $this->info('Generating SVG icons for vehicle makes...');

        foreach ($makes as $index => $make) {
            if ($make->icon) {
                $this->line("Skipping {$make->name} - already has an icon");
                continue;
            }

            $color = $colors[$index % count($colors)];
            $initials = $this->getInitials($make->name);
            
            $svg = $this->generateSVG($initials, $color);
            
            $filename = 'vehicle-icons/' . \Str::slug($make->name) . '.svg';
            Storage::disk('public')->put($filename, $svg);
            
            $make->update(['icon' => $filename]);
            
            $this->info("Generated icon for {$make->name}");
        }

        $this->info('All icons generated successfully!');
        return 0;
    }

    private function getInitials($name)
    {
        $words = explode(' ', $name);
        if (count($words) === 1) {
            return strtoupper(substr($name, 0, 2));
        }
        return strtoupper($words[0][0] . $words[1][0]);
    }

    private function generateSVG($initials, $color)
    {
        return <<<SVG
<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg">
    <rect width="100" height="100" rx="12" fill="{$color}"/>
    <text x="50" y="50" font-family="Arial, sans-serif" font-size="36" font-weight="bold" fill="white" text-anchor="middle" dominant-baseline="central">{$initials}</text>
</svg>
SVG;
    }
}
