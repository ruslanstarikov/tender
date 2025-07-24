back<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\FrameType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $frameTypes = [
            [
                "filename" => "fixed-1",
                "type" => "fixed",
                "panels" => 1,
                "config" => []
            ],
            [
                "filename" => "fixed-2-horizontal",
                "type" => "fixed",
                "panels" => 2,
                "config" => []
            ],
            [
                "filename" => "fixed-3-horizontal",
                "type" => "fixed",
                "panels" => 3,
                "config" => []
            ],
            [
                "filename" => "casement-left",
                "type" => "casement",
                "panels" => 1,
                "config" => [
                    "left-hinged"
                ]
            ],
            [
                "filename" => "casement-right",
                "type" => "casement",
                "panels" => 1,
                "config" => [
                    "right-hinged"
                ]
            ],
            [
                "filename" => "casement-left-mirrored",
                "type" => "casement",
                "panels" => 1,
                "config" => [
                    "left-hinged",
                    "mirrored"
                ]
            ],
            [
                "filename" => "casement-right-mirrored",
                "type" => "casement",
                "panels" => 1,
                "config" => [
                    "right-hinged",
                    "mirrored"
                ]
            ],
            [
                "filename" => "casement-lr",
                "type" => "casement",
                "panels" => 2,
                "config" => [
                    "left-hinged",
                    "right-hinged"
                ]
            ],
            [
                "filename" => "awning",
                "type" => "awning",
                "panels" => 1,
                "config" => [
                    "top-hinged"
                ]
            ],
            [
                "filename" => "awning-mirrored",
                "type" => "awning",
                "panels" => 1,
                "config" => [
                    "top-hinged",
                    "mirrored"
                ]
            ],
            [
                "filename" => "awning-nw",
                "type" => "awning",
                "panels" => 1,
                "config" => [
                    "top-hinged",
                    "opens-north",
                    "opens-west"
                ]
            ],
            [
                "filename" => "awning-ne",
                "type" => "awning",
                "panels" => 1,
                "config" => [
                    "top-hinged",
                    "opens-north",
                    "opens-east"
                ]
            ],
            [
                "filename" => "hopper",
                "type" => "hopper",
                "panels" => 1,
                "config" => [
                    "bottom-hinged"
                ]
            ],
            [
                "filename" => "hopper-mirrored",
                "type" => "hopper",
                "panels" => 1,
                "config" => [
                    "bottom-hinged",
                    "mirrored"
                ]
            ],
            [
                "filename" => "hopper-sw",
                "type" => "hopper",
                "panels" => 1,
                "config" => [
                    "bottom-hinged",
                    "opens-south",
                    "opens-west"
                ]
            ],
            [
                "filename" => "hopper-se",
                "type" => "hopper",
                "panels" => 1,
                "config" => [
                    "bottom-hinged",
                    "opens-south",
                    "opens-east"
                ]
            ],
            [
                "filename" => "sliding-ox",
                "type" => "sliding",
                "panels" => 2,
                "config" => [
                    "fixed",
                    "slides-left"
                ]
            ],
            [
                "filename" => "sliding-xo",
                "type" => "sliding",
                "panels" => 2,
                "config" => [
                    "slides-right",
                    "fixed"
                ]
            ],
            [
                "filename" => "sliding-xox",
                "type" => "sliding",
                "panels" => 3,
                "config" => [
                    "slides-right",
                    "fixed",
                    "slides-left"
                ]
            ],
            [
                "filename" => "sliding-oxo",
                "type" => "sliding",
                "panels" => 3,
                "config" => [
                    "fixed",
                    "slides",
                    "fixed"
                ]
            ],
            [
                "filename" => "sliding-ox-mirrored",
                "type" => "sliding",
                "panels" => 2,
                "config" => [
                    "fixed",
                    "slides-left",
                    "mirrored"
                ]
            ],
            [
                "filename" => "sliding-xo-mirrored",
                "type" => "sliding",
                "panels" => 2,
                "config" => [
                    "slides-right",
                    "fixed",
                    "mirrored"
                ]
            ],
            [
                "filename" => "doublehung-single",
                "type" => "doublehung",
                "panels" => 1,
                "config" => [
                    "bottom-slides"
                ]
            ],
            [
                "filename" => "doublehung-double",
                "type" => "doublehung",
                "panels" => 1,
                "config" => [
                    "top-slides",
                    "bottom-slides"
                ]
            ],
            [
                "filename" => "tilt-only",
                "type" => "tilt-turn",
                "panels" => 1,
                "config" => [
                    "tilt"
                ]
            ],
            [
                "filename" => "turn-left",
                "type" => "tilt-turn",
                "panels" => 1,
                "config" => [
                    "turn-left"
                ]
            ],
            [
                "filename" => "turn-right",
                "type" => "tilt-turn",
                "panels" => 1,
                "config" => [
                    "turn-right"
                ]
            ],
            [
                "filename" => "tilt-turn-left",
                "type" => "tilt-turn",
                "panels" => 1,
                "config" => [
                    "tilt",
                    "turn-left"
                ]
            ],
            [
                "filename" => "tilt-turn-right",
                "type" => "tilt-turn",
                "panels" => 1,
                "config" => [
                    "tilt",
                    "turn-right"
                ]
            ],
            [
                "filename" => "tilt-turn-nw",
                "type" => "tilt-turn",
                "panels" => 1,
                "config" => [
                    "tilt",
                    "turn",
                    "opens-north",
                    "opens-west"
                ]
            ],
            [
                "filename" => "tilt-turn-ne",
                "type" => "tilt-turn",
                "panels" => 1,
                "config" => [
                    "tilt",
                    "turn",
                    "opens-north",
                    "opens-east"
                ]
            ],
            [
                "filename" => "tilt-turn-sw",
                "type" => "tilt-turn",
                "panels" => 1,
                "config" => [
                    "tilt",
                    "turn",
                    "opens-south",
                    "opens-west"
                ]
            ],
            [
                "filename" => "tilt-turn-se",
                "type" => "tilt-turn",
                "panels" => 1,
                "config" => [
                    "tilt",
                    "turn",
                    "opens-south",
                    "opens-east"
                ]
            ],
            [
                "filename" => "tilt-turn-left-mirrored",
                "type" => "tilt-turn",
                "panels" => 1,
                "config" => [
                    "tilt",
                    "turn-left",
                    "mirrored"
                ]
            ],
            [
                "filename" => "combo-cf",
                "type" => "combo",
                "panels" => 2,
                "config" => [
                    "casement-left",
                    "fixed"
                ]
            ],
            [
                "filename" => "combo-fc",
                "type" => "combo",
                "panels" => 2,
                "config" => [
                    "fixed",
                    "casement-right"
                ]
            ],
            [
                "filename" => "combo-cfc",
                "type" => "combo",
                "panels" => 3,
                "config" => [
                    "casement-left",
                    "fixed",
                    "casement-right"
                ]
            ],
            [
                "filename" => "combo-fcf",
                "type" => "combo",
                "panels" => 3,
                "config" => [
                    "fixed",
                    "casement",
                    "fixed"
                ]
            ],
            [
                "filename" => "combo-cc",
                "type" => "combo",
                "panels" => 2,
                "config" => [
                    "casement-left",
                    "casement-right"
                ]
            ]
        ];

        foreach ($frameTypes as $frameType) {
            FrameType::updateOrCreate(
                ['filename' => $frameType['filename']],
                $frameType
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove all frame types
        FrameType::truncate();
    }
};
