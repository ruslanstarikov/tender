@extends('base') {{-- your base layout --}}
@section('title', 'Tender Preview')

@section('content')
    <div class="max-w-4xl mx-auto bg-base-100 p-6 rounded-lg shadow">
        <h2 class="text-2xl font-semibold mb-4">Tender Preview</h2>

        {{-- Basic Tender Info --}}
        <div class="mb-6">
            <h3 class="font-semibold text-lg">Project Title:</h3>
            <p>{{ $tender->project_title }}</p>

            <h3 class="font-semibold text-lg mt-4">Address:</h3>
            <p>{{ $tender->property_address }}, {{ $tender->suburb }}, {{ $tender->state }} {{ $tender->post_code }}</p>

            <h3 class="font-semibold text-lg mt-4">Work Schedule:</h3>
            <p>
                {{ \Carbon\Carbon::parse($tender->work_start_datetime)->format('Y-m-d H:i') }}
                &mdash;
                {{ \Carbon\Carbon::parse($tender->work_end_datetime)->format('Y-m-d H:i') }}
            </p>

            <h3 class="font-semibold text-lg mt-4">Premises Entry Info:</h3>
            <p>{{ $tender->premises_entry_info ?? 'N/A' }}</p>

            <h3 class="font-semibold text-lg mt-4">Frame Details:</h3>
            <p>{{ $tender->frame_details ?? 'N/A' }}</p>
        </div>

        <hr class="my-6">

        {{-- Windows Section --}}
        @if ($tender->windows->isNotEmpty())
            <div class="mb-8">
                <h3 class="text-xl font-semibold mb-4">Window Configuration</h3>
                <div class="space-y-6">
                    @foreach ($tender->windows as $window)
                        <div class="window-item border rounded-lg p-6">
                            <div class="flex items-start gap-6">
                                <div class="flex-shrink-0">
                                    <h5 class="font-medium text-sm mb-2">Technical Drawing</h5>
                                    <div class="border rounded p-2 bg-white">
                                        <div id="window-drawing-{{ $window->id }}">
                                            <!-- Technical drawing will be rendered here -->
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-medium text-lg">{{ $window->label ?: 'Window ' . $loop->iteration }}</h4>
                                    <p class="text-sm text-gray-600 mb-2">{{ $window->template->name }} • {{ $window->width_mm }}mm × {{ $window->height_mm }}mm</p>
                                    <p class="text-xs text-gray-500 mb-2">{{ $window->windowCells->count() }} cells configured</p>
                                    @if($window->notes)
                                        <p class="text-sm text-gray-600 mb-2"><strong>Notes:</strong> {{ $window->notes }}</p>
                                    @endif

                                    <div class="bg-gray-50 rounded p-3 text-xs">
                                        <strong>Cell Configurations:</strong><br>
                                        @foreach($window->windowCells as $cell)
                                            @php
                                                $openings = [];
                                                $slides = [];
                                                $foldings = [];

                                                if ($cell->open_left) $openings[] = 'Left';
                                                if ($cell->open_right) $openings[] = 'Right';
                                                if ($cell->open_top) $openings[] = 'Top';
                                                if ($cell->open_bottom) $openings[] = 'Bottom';

                                                if ($cell->slide_left) $slides[] = 'Left';
                                                if ($cell->slide_right) $slides[] = 'Right';
                                                if ($cell->slide_top) $slides[] = 'Top';
                                                if ($cell->slide_bottom) $slides[] = 'Bottom';

                                                if ($cell->folding_left) $foldings[] = 'Left';
                                                if ($cell->folding_right) $foldings[] = 'Right';
                                                if ($cell->folding_top) $foldings[] = 'Top';
                                                if ($cell->folding_bottom) $foldings[] = 'Bottom';

                                                $templateCell = $cell->templateCell;
                                                $cellWidth = round($templateCell->width_ratio * $window->width_mm);
                                                $cellHeight = round($templateCell->height_ratio * $window->height_mm);

                                                $features = [];
                                                if (count($openings)) $features[] = implode(', ', $openings) . ' opening';
                                                if (count($slides)) $features[] = implode(', ', $slides) . ' sliding';
                                                if (count($foldings)) $features[] = implode(', ', $foldings) . ' folding';
                                            @endphp
                                            • Cell {{ $cell->templateCell->cell_index + 1 }}: {{ $cellWidth }}×{{ $cellHeight }}mm - {{ count($features) ? implode(', ', $features) : 'Fixed' }}<br>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <hr class="my-6">
        @endif

        @php $polyGroup = $tender->media->where('category', 'polycam'); @endphp
        @if ($polyGroup->isNotEmpty())
            <div class="mb-8">
                <h4 class="font-semibold text-lg capitalize mb-2">Polycam Models</h4>
                <div class="grid grid-cols-1 gap-6">
                    @foreach ($polyGroup as $media)
                        <div class="border rounded-lg bg-gray-100">
                            <div id="viewer-{{ $media->id }}" class="threejs-viewer w-full h-80"></div>
                            <input type="hidden"
                                   class="glb-url"
                                   value="{{ Storage::url($media->file_path) }}" />
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        {{-- Media Preview --}}
        <div>
            <h3 class="text-xl font-semibold mb-4">Media</h3>

            @if ($tender->media->isEmpty())
                <p class="text-gray-500">No media uploaded for this tender.</p>
            @else
                {{-- Group media by category --}}
                @foreach (['inside', 'parking', 'outside', 'storage'] as $category)
                    @php
                        $grouped = $tender->media->where('category', $category);
                    @endphp

                    @if ($grouped->isNotEmpty())
                        <div class="mb-8">
                            <h4 class="font-semibold text-lg capitalize mb-2">{{ ucfirst($category) }}</h4>
                            <div class="grid grid-cols-3 gap-4">
                                @foreach ($grouped as $media)
                                    @if ($media->media_type === 'photo')
                                        {{-- Show an <img> for photos --}}
                                        <div class="border rounded overflow-hidden">
                                            <img src="{{ Storage::url($media->file_path) }}"
                                                 alt="Photo ({{ $media->category }})"
                                                 class="w-full h-48 object-cover" />
                                        </div>
                                    @elseif ($media->media_type === 'video')
                                        {{-- Show a <video> tag for videos --}}
                                        <div class="border rounded overflow-hidden">
                                            <video controls class="w-full h-48">
                                                <source src="{{ Storage::url($media->file_path) }}" type="video/mp4" />
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
@endsection


@section('scripts')
    @vite('resources/js/three-viewer.js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize window drawings for all windows
            @foreach ($tender->windows as $window)
                renderWindowDrawing({{ $window->id }}, {
                    id: {{ $window->id }},
                    width_mm: {{ $window->width_mm }},
                    height_mm: {{ $window->height_mm }},
                    label: "{{ addslashes($window->label ?? '') }}",
                    template: {
                        id: {{ $window->template->id }},
                        name: "{{ addslashes($window->template->name) }}",
                        cells: [
                            @foreach($window->template->templateCells as $cell)
                            {
                                id: {{ $cell->id }},
                                cell_index: {{ $cell->cell_index }},
                                x: {{ (float) $cell->x }},
                                y: {{ (float) ($cell->y ?? 0) }},
                                width_ratio: {{ (float) $cell->width_ratio }},
                                height_ratio: {{ (float) $cell->height_ratio }}
                            }{{ !$loop->last ? ',' : '' }}
                            @endforeach
                        ]
                    },
                    window_cells: [
                        @foreach($window->windowCells as $wc)
                        {
                            template_cell: {
                                cell_index: {{ $wc->templateCell->cell_index }}
                            },
                            open_left: {{ $wc->open_left ? 'true' : 'false' }},
                            open_right: {{ $wc->open_right ? 'true' : 'false' }},
                            open_top: {{ $wc->open_top ? 'true' : 'false' }},
                            open_bottom: {{ $wc->open_bottom ? 'true' : 'false' }},
                            slide_left: {{ $wc->slide_left ? 'true' : 'false' }},
                            slide_right: {{ $wc->slide_right ? 'true' : 'false' }},
                            slide_top: {{ $wc->slide_top ? 'true' : 'false' }},
                            slide_bottom: {{ $wc->slide_bottom ? 'true' : 'false' }},
                            folding_left: {{ $wc->folding_left ? 'true' : 'false' }},
                            folding_right: {{ $wc->folding_right ? 'true' : 'false' }},
                            folding_top: {{ $wc->folding_top ? 'true' : 'false' }},
                            folding_bottom: {{ $wc->folding_bottom ? 'true' : 'false' }}
                        }{{ !$loop->last ? ',' : '' }}
                        @endforeach
                    ]
                });
            @endforeach
        });

        function renderWindowDrawing(windowId, windowData) {
            const container = document.getElementById(`window-drawing-${windowId}`);
            if (!container) return;

            // Generate SVG for the window using the same approach as create form
            const svg = generateWindowPreviewSVG(
                windowData.template,
                200,
                150,
                windowData.width_mm,
                windowData.height_mm,
                windowData.window_cells
            );
            container.innerHTML = svg;
        }

        function generateWindowPreviewSVG(template, width, height, windowWidth = null, windowHeight = null, cellConfigs = null) {
            const cells = template.cells || [];
            const margin = 40; // Space for dimensions
            const svgWidth = width + (margin * 2);
            const svgHeight = height + (margin * 2);

            let elements = [];

            // Draw cells with opening indicators
            cells.forEach((cell, index) => {
                const x = cell.x * width + margin;
                const y = cell.y * height + margin;
                const w = cell.width_ratio * width;
                const h = cell.height_ratio * height;

                // Cell rectangle
                elements.push(`<rect x="${x}" y="${y}" width="${w}" height="${h}"
                    fill="rgba(59, 130, 246, 0.05)" stroke="#3b82f6" stroke-width="2"/>`);

                // Cell opening indicators (if configurations provided)
                const config = cellConfigs ? cellConfigs.find(c => c.template_cell.cell_index === cell.cell_index) : null;
                if (config) {
                    const centerX = x + w/2;
                    const centerY = y + h/2;
                    const len = Math.min(w, h) * 0.3; // Longer lines for better visibility

                    // Opening angles (60 degrees) - lines start from opposite edge and converge at center of hinge edge
                    const angle60 = Math.PI / 3; // 60 degrees in radians
                    const halfAngle = angle60 / 2; // 30 degrees from center line

                    if (config.open_left) {
                        // Hinge on LEFT center, calculate 60-degree angle from RIGHT edge
                        const hingeX = x; // Left edge center
                        const hingeY = centerY;
                        const distance = w; // Distance from hinge to opposite edge (cell width)

                        // Calculate start points using trigonometry for exact 60-degree angle
                        // But constrain to cell height to avoid extending beyond cell boundaries
                        const startX = x + w; // Right edge
                        const maxSpread = h / 2; // Half the cell height
                        const angleSpread = Math.min(distance * Math.tan(halfAngle), maxSpread);
                        const startY1 = hingeY - angleSpread; // 30 degrees above, constrained
                        const startY2 = hingeY + angleSpread; // 30 degrees below, constrained

                        elements.push(`<path d="M${startX} ${startY1} L${hingeX} ${hingeY} M${startX} ${startY2} L${hingeX} ${hingeY}"
                            stroke="#666" stroke-width="2" stroke-dasharray="4,2"/>`);
                    }

                    if (config.open_right) {
                        // Hinge on RIGHT center, calculate 60-degree angle from LEFT edge
                        const hingeX = x + w; // Right edge center
                        const hingeY = centerY;
                        const distance = w; // Distance from hinge to opposite edge (cell width)

                        // Calculate start points using trigonometry for exact 60-degree angle
                        // But constrain to cell height to avoid extending beyond cell boundaries
                        const startX = x; // Left edge
                        const maxSpread = h / 2; // Half the cell height
                        const angleSpread = Math.min(distance * Math.tan(halfAngle), maxSpread);
                        const startY1 = hingeY - angleSpread; // 30 degrees above, constrained
                        const startY2 = hingeY + angleSpread; // 30 degrees below, constrained

                        elements.push(`<path d="M${startX} ${startY1} L${hingeX} ${hingeY} M${startX} ${startY2} L${hingeX} ${hingeY}"
                            stroke="#666" stroke-width="2" stroke-dasharray="4,2"/>`);
                    }

                    if (config.open_top) {
                        // Hinge on TOP center, calculate 60-degree angle from BOTTOM edge
                        const hingeX = centerX;
                        const hingeY = y; // Top edge center
                        const distance = h; // Distance from hinge to opposite edge (cell height)

                        // Calculate start points using trigonometry for exact 60-degree angle
                        // But constrain to cell width to avoid extending beyond cell boundaries
                        const startY = y + h; // Bottom edge
                        const maxSpread = w / 2; // Half the cell width
                        const angleSpread = Math.min(distance * Math.tan(halfAngle), maxSpread);
                        const startX1 = hingeX - angleSpread; // 30 degrees left, constrained
                        const startX2 = hingeX + angleSpread; // 30 degrees right, constrained

                        elements.push(`<path d="M${startX1} ${startY} L${hingeX} ${hingeY} M${startX2} ${startY} L${hingeX} ${hingeY}"
                            stroke="#666" stroke-width="2" stroke-dasharray="4,2"/>`);
                    }

                    if (config.open_bottom) {
                        // Hinge on BOTTOM center, calculate 60-degree angle from TOP edge
                        const hingeX = centerX;
                        const hingeY = y + h; // Bottom edge center
                        const distance = h; // Distance from hinge to opposite edge (cell height)

                        // Calculate start points using trigonometry for exact 60-degree angle
                        // But constrain to cell width to avoid extending beyond cell boundaries
                        const startY = y; // Top edge
                        const maxSpread = w / 2; // Half the cell width
                        const angleSpread = Math.min(distance * Math.tan(halfAngle), maxSpread);
                        const startX1 = hingeX - angleSpread; // 30 degrees left, constrained
                        const startX2 = hingeX + angleSpread; // 30 degrees right, constrained

                        elements.push(`<path d="M${startX1} ${startY} L${hingeX} ${hingeY} M${startX2} ${startY} L${hingeX} ${hingeY}"
                            stroke="#666" stroke-width="2" stroke-dasharray="4,2"/>`);
                    }

                    // Sliding options - solid arrows
                    if (config.slide_left) {
                        const arrowY = centerY;
                        const arrowStartX = x + w - 15;
                        const arrowEndX = x + 10;
                        const arrowSize = 4;
                        
                        elements.push(`<path d="M${arrowStartX} ${arrowY} L${arrowEndX} ${arrowY}"
                            stroke="#000" stroke-width="3" fill="none"/>`);
                        elements.push(`<path d="M${arrowEndX + arrowSize} ${arrowY - arrowSize} L${arrowEndX} ${arrowY} L${arrowEndX + arrowSize} ${arrowY + arrowSize}"
                            stroke="#000" stroke-width="2" fill="#000"/>`);
                    }

                    if (config.slide_right) {
                        const arrowY = centerY;
                        const arrowStartX = x + 15;
                        const arrowEndX = x + w - 10;
                        const arrowSize = 4;
                        
                        elements.push(`<path d="M${arrowStartX} ${arrowY} L${arrowEndX} ${arrowY}"
                            stroke="#000" stroke-width="3" fill="none"/>`);
                        elements.push(`<path d="M${arrowEndX - arrowSize} ${arrowY - arrowSize} L${arrowEndX} ${arrowY} L${arrowEndX - arrowSize} ${arrowY + arrowSize}"
                            stroke="#000" stroke-width="2" fill="#000"/>`);
                    }

                    if (config.slide_top) {
                        const arrowX = centerX;
                        const arrowStartY = y + h - 15;
                        const arrowEndY = y + 10;
                        const arrowSize = 4;
                        
                        elements.push(`<path d="M${arrowX} ${arrowStartY} L${arrowX} ${arrowEndY}"
                            stroke="#000" stroke-width="3" fill="none"/>`);
                        elements.push(`<path d="M${arrowX - arrowSize} ${arrowEndY + arrowSize} L${arrowX} ${arrowEndY} L${arrowX + arrowSize} ${arrowEndY + arrowSize}"
                            stroke="#000" stroke-width="2" fill="#000"/>`);
                    }

                    if (config.slide_bottom) {
                        const arrowX = centerX;
                        const arrowStartY = y + 15;
                        const arrowEndY = y + h - 10;
                        const arrowSize = 4;
                        
                        elements.push(`<path d="M${arrowX} ${arrowStartY} L${arrowX} ${arrowEndY}"
                            stroke="#000" stroke-width="3" fill="none"/>`);
                        elements.push(`<path d="M${arrowX - arrowSize} ${arrowEndY - arrowSize} L${arrowX} ${arrowEndY} L${arrowX + arrowSize} ${arrowEndY - arrowSize}"
                            stroke="#000" stroke-width="2" fill="#000"/>`);
                    }

                    // Folding options - zig-zag arrows (3 sharp angles)
                    if (config.folding_left) {
                        const foldY = centerY;
                        const foldStartX = x + w - 15;
                        const foldEndX = x + 15;
                        const zigzagHeight = 10;
                        const arrowSize = 4;
                        
                        // Calculate middle points for 3-angle zig-zag with very sharp angles
                        const totalLength = foldStartX - (foldEndX + arrowSize);
                        const segment1X = foldStartX - totalLength * 0.2;
                        const segment2X = foldStartX - totalLength * 0.5;
                        const segment3X = foldStartX - totalLength * 0.8;
                        
                        // Create very sharp zig-zag path with 3 angles
                        const pathData = `M${foldStartX} ${foldY} L${segment1X} ${foldY - zigzagHeight} L${segment2X} ${foldY + zigzagHeight} L${segment3X} ${foldY - zigzagHeight} L${foldEndX + arrowSize} ${foldY}`;
                        
                        elements.push(`<path d="${pathData}" stroke="#000" stroke-width="2" fill="none"/>`);
                        // Arrow head
                        elements.push(`<path d="M${foldEndX + arrowSize} ${foldY - arrowSize} L${foldEndX} ${foldY} L${foldEndX + arrowSize} ${foldY + arrowSize}"
                            stroke="#000" stroke-width="2" fill="#000"/>`);
                    }

                    if (config.folding_right) {
                        const foldY = centerY;
                        const foldStartX = x + 15;
                        const foldEndX = x + w - 15;
                        const zigzagHeight = 10;
                        const arrowSize = 4;
                        
                        // Calculate middle points for 3-angle zig-zag with very sharp angles
                        const totalLength = (foldEndX - arrowSize) - foldStartX;
                        const segment1X = foldStartX + totalLength * 0.2;
                        const segment2X = foldStartX + totalLength * 0.5;
                        const segment3X = foldStartX + totalLength * 0.8;
                        
                        // Create very sharp zig-zag path with 3 angles
                        const pathData = `M${foldStartX} ${foldY} L${segment1X} ${foldY - zigzagHeight} L${segment2X} ${foldY + zigzagHeight} L${segment3X} ${foldY - zigzagHeight} L${foldEndX - arrowSize} ${foldY}`;
                        
                        elements.push(`<path d="${pathData}" stroke="#000" stroke-width="2" fill="none"/>`);
                        // Arrow head
                        elements.push(`<path d="M${foldEndX - arrowSize} ${foldY - arrowSize} L${foldEndX} ${foldY} L${foldEndX - arrowSize} ${foldY + arrowSize}"
                            stroke="#000" stroke-width="2" fill="#000"/>`);
                    }

                    if (config.folding_top) {
                        const foldX = centerX;
                        const foldStartY = y + h - 15;
                        const foldEndY = y + 15;
                        const zigzagWidth = 10;
                        const arrowSize = 4;
                        
                        // Calculate middle points for 3-angle zig-zag with very sharp angles
                        const totalLength = foldStartY - (foldEndY + arrowSize);
                        const segment1Y = foldStartY - totalLength * 0.2;
                        const segment2Y = foldStartY - totalLength * 0.5;
                        const segment3Y = foldStartY - totalLength * 0.8;
                        
                        // Create very sharp zig-zag path with 3 angles
                        const pathData = `M${foldX} ${foldStartY} L${foldX - zigzagWidth} ${segment1Y} L${foldX + zigzagWidth} ${segment2Y} L${foldX - zigzagWidth} ${segment3Y} L${foldX} ${foldEndY + arrowSize}`;
                        
                        elements.push(`<path d="${pathData}" stroke="#000" stroke-width="2" fill="none"/>`);
                        // Arrow head
                        elements.push(`<path d="M${foldX - arrowSize} ${foldEndY + arrowSize} L${foldX} ${foldEndY} L${foldX + arrowSize} ${foldEndY + arrowSize}"
                            stroke="#000" stroke-width="2" fill="#000"/>`);
                    }

                    if (config.folding_bottom) {
                        const foldX = centerX;
                        const foldStartY = y + 15;
                        const foldEndY = y + h - 15;
                        const zigzagWidth = 10;
                        const arrowSize = 4;
                        
                        // Calculate middle points for 3-angle zig-zag with very sharp angles
                        const totalLength = (foldEndY - arrowSize) - foldStartY;
                        const segment1Y = foldStartY + totalLength * 0.2;
                        const segment2Y = foldStartY + totalLength * 0.5;
                        const segment3Y = foldStartY + totalLength * 0.8;
                        
                        // Create very sharp zig-zag path with 3 angles
                        const pathData = `M${foldX} ${foldStartY} L${foldX - zigzagWidth} ${segment1Y} L${foldX + zigzagWidth} ${segment2Y} L${foldX - zigzagWidth} ${segment3Y} L${foldX} ${foldEndY - arrowSize}`;
                        
                        elements.push(`<path d="${pathData}" stroke="#000" stroke-width="2" fill="none"/>`);
                        // Arrow head
                        elements.push(`<path d="M${foldX - arrowSize} ${foldEndY - arrowSize} L${foldX} ${foldEndY} L${foldX + arrowSize} ${foldEndY - arrowSize}"
                            stroke="#000" stroke-width="2" fill="#000"/>`);
                    }
                }

                // Cell dimensions (if window dimensions provided)
                if (windowWidth && windowHeight) {
                    const actualW = Math.round(cell.width_ratio * windowWidth);
                    const actualH = Math.round(cell.height_ratio * windowHeight);

                    // Only show width dimension if it's not the full window width (not implicit)
                    const isFullWidth = Math.abs(cell.width_ratio - 1.0) < 0.001;
                    if (!isFullWidth) {
                        elements.push(`<line x1="${x}" y1="${y - 10}" x2="${x + w}" y2="${y - 10}"
                            stroke="#666" stroke-width="1" stroke-dasharray="2,2"/>`);
                        elements.push(`<text x="${x + w/2}" y="${y - 15}" text-anchor="middle"
                            font-family="Arial" font-size="10" fill="#666">${actualW}mm</text>`);
                    }

                    // Only show height dimension if it's not the full window height (not implicit)
                    const isFullHeight = Math.abs(cell.height_ratio - 1.0) < 0.001;
                    if (!isFullHeight) {
                        elements.push(`<line x1="${x - 10}" y1="${y}" x2="${x - 10}" y2="${y + h}"
                            stroke="#666" stroke-width="1" stroke-dasharray="2,2"/>`);
                        elements.push(`<text x="${x - 15}" y="${y + h/2}" text-anchor="middle"
                            font-family="Arial" font-size="10" fill="#666" transform="rotate(-90, ${x - 15}, ${y + h/2})">${actualH}mm</text>`);
                    }

                    // Cell label
                    elements.push(`<text x="${x + w/2}" y="${y + h/2}" text-anchor="middle"
                        font-family="Arial" font-size="12" fill="#333" font-weight="bold">Cell ${cell.cell_index + 1}</text>`);
                }
            });

            // Overall window dimensions
            if (windowWidth && windowHeight) {
                // Overall width dimension
                elements.push(`<line x1="${margin}" y1="${margin + height + 20}" x2="${margin + width}" y2="${margin + height + 20}"
                    stroke="#333" stroke-width="2" stroke-dasharray="4,4"/>`);
                elements.push(`<text x="${margin + width/2}" y="${margin + height + 35}" text-anchor="middle"
                    font-family="Arial" font-size="12" fill="#333" font-weight="bold">${windowWidth}mm</text>`);

                // Overall height dimension
                elements.push(`<line x1="${margin + width + 20}" y1="${margin}" x2="${margin + width + 20}" y2="${margin + height}"
                    stroke="#333" stroke-width="2" stroke-dasharray="4,4"/>`);
                elements.push(`<text x="${margin + width + 35}" y="${margin + height/2}" text-anchor="middle"
                    font-family="Arial" font-size="12" fill="#333" font-weight="bold" transform="rotate(-90, ${margin + width + 35}, ${margin + height/2})">${windowHeight}mm</text>`);
            }

            return `<svg width="${svgWidth}" height="${svgHeight}" viewBox="0 0 ${svgWidth} ${svgHeight}">
                ${elements.join('')}
            </svg>`;
        }

    </script>
@endsection
