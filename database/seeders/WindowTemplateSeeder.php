<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WindowTemplate;
use App\Models\TemplateCell;

class WindowTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // Single Panel Window
        $singlePanel = WindowTemplate::create(['name' => 'Single Panel']);
        TemplateCell::create([
            'template_id' => $singlePanel->id,
            'cell_index' => 0,
            'x' => 0.0000,
            'y' => 0.0000,
            'width_ratio' => 1.0000,
            'height_ratio' => 1.0000,
        ]);

        // Double Panel Window (Left/Right)
        $doublePanel = WindowTemplate::create(['name' => 'Double Panel']);
        TemplateCell::create([
            'template_id' => $doublePanel->id,
            'cell_index' => 0,
            'x' => 0.0000,
            'y' => 0.0000,
            'width_ratio' => 0.5000,
            'height_ratio' => 1.0000,
        ]);
        TemplateCell::create([
            'template_id' => $doublePanel->id,
            'cell_index' => 1,
            'x' => 0.5000,
            'y' => 0.0000,
            'width_ratio' => 0.5000,
            'height_ratio' => 1.0000,
        ]);

        // Triple Panel Window
        $triplePanel = WindowTemplate::create(['name' => 'Triple Panel']);
        TemplateCell::create([
            'template_id' => $triplePanel->id,
            'cell_index' => 0,
            'x' => 0.0000,
            'y' => 0.0000,
            'width_ratio' => 0.3333,
            'height_ratio' => 1.0000,
        ]);
        TemplateCell::create([
            'template_id' => $triplePanel->id,
            'cell_index' => 1,
            'x' => 0.3333,
            'y' => 0.0000,
            'width_ratio' => 0.3334,
            'height_ratio' => 1.0000,
        ]);
        TemplateCell::create([
            'template_id' => $triplePanel->id,
            'cell_index' => 2,
            'x' => 0.6667,
            'y' => 0.0000,
            'width_ratio' => 0.3333,
            'height_ratio' => 1.0000,
        ]);

        // Bay Window (5 panels)
        $bayWindow = WindowTemplate::create(['name' => 'Bay Window (5 Panel)']);
        $angles = [0.0, 0.2, 0.4, 0.6, 0.8];
        foreach ($angles as $index => $angle) {
            TemplateCell::create([
                'template_id' => $bayWindow->id,
                'cell_index' => $index,
                'x' => $angle,
                'y' => 0.0000,
                'width_ratio' => 0.2000,
                'height_ratio' => 1.0000,
            ]);
        }

        // Top/Bottom Split Window
        $splitWindow = WindowTemplate::create(['name' => 'Top/Bottom Split']);
        TemplateCell::create([
            'template_id' => $splitWindow->id,
            'cell_index' => 0,
            'x' => 0.0000,
            'y' => 0.0000,
            'width_ratio' => 1.0000,
            'height_ratio' => 0.5000,
        ]);
        TemplateCell::create([
            'template_id' => $splitWindow->id,
            'cell_index' => 1,
            'x' => 0.0000,
            'y' => 0.5000,
            'width_ratio' => 1.0000,
            'height_ratio' => 0.5000,
        ]);

        // Grid Window (2x2)
        $gridWindow = WindowTemplate::create(['name' => 'Grid Window (2x2)']);
        $positions = [
            [0.0, 0.0], [0.5, 0.0],
            [0.0, 0.5], [0.5, 0.5]
        ];
        foreach ($positions as $index => $position) {
            TemplateCell::create([
                'template_id' => $gridWindow->id,
                'cell_index' => $index,
                'x' => $position[0],
                'y' => $position[1],
                'width_ratio' => 0.5000,
                'height_ratio' => 0.5000,
            ]);
        }

        // Awning Style (Small top, large bottom)
        $awningWindow = WindowTemplate::create(['name' => 'Awning Style']);
        TemplateCell::create([
            'template_id' => $awningWindow->id,
            'cell_index' => 0,
            'x' => 0.0000,
            'y' => 0.0000,
            'width_ratio' => 1.0000,
            'height_ratio' => 0.3000,
        ]);
        TemplateCell::create([
            'template_id' => $awningWindow->id,
            'cell_index' => 1,
            'x' => 0.0000,
            'y' => 0.3000,
            'width_ratio' => 1.0000,
            'height_ratio' => 0.7000,
        ]);
    }
}