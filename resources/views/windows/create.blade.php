@extends('base')

@section('title', 'Window Configuration')

@section('content')
    <div class="max-w-6xl mx-auto bg-base-100 p-6 rounded-lg shadow">
        <h2 class="text-2xl font-semibold mb-4">Window Template Configuration</h2>

        <div id="windows-section" class="mb-6">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-medium">Configured Windows</h4>
                <button type="button" id="add-window-btn" class="btn btn-primary btn-sm">
                    Add Window
                </button>
            </div>
            <div id="selected-windows" class="space-y-4">
                <!-- Selected windows will be displayed here -->
            </div>
            <div id="no-windows-message" class="text-center text-gray-500 py-8 border-2 border-dashed border-gray-300 rounded-lg">
                <p>No windows configured yet. Click "Add Window" to get started.</p>
            </div>
        </div>
    </div>

    {{-- Window Template Selection Modal --}}
    <div id="template-selection-modal" class="modal">
        <div class="modal-box w-11/12 max-w-4xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg">Select Window Template</h3>
                <button class="btn btn-sm btn-circle btn-ghost" onclick="closeTemplateSelection()">✕</button>
            </div>

            <div id="template-grid" class="grid grid-cols-2 md:grid-cols-3 gap-4 max-h-96 overflow-y-auto">
                <!-- Window templates will be loaded here -->
            </div>
        </div>
    </div>

    {{-- Window Configuration Modal --}}
    <div id="window-config-modal" class="modal">
        <div class="modal-box w-11/12 max-w-6xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg">Configure Window</h3>
                <button class="btn btn-sm btn-circle btn-ghost" onclick="closeWindowConfig()">✕</button>
            </div>

            <div id="window-config-content">
                <!-- Window configuration form will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        let windowTemplates = [];
        let selectedWindows = [];
        let windowCounter = 0;
        let currentTemplate = null;

        // Load window templates on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadWindowTemplates();
            initializeEventListeners();
        });

        function loadWindowTemplates() {
            console.log('Loading window templates...');
            fetch('/api/window-templates')
                .then(response => {
                    console.log('API response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Window templates loaded:', data.length);
                    windowTemplates = data;
                    renderTemplateGrid();
                })
                .catch(error => {
                    console.error('Error loading window templates:', error);
                    // For now, create some default templates for demo
                    createDefaultTemplates();
                });
        }

        function createDefaultTemplates() {
            windowTemplates = [
                { id: 1, name: 'Single Panel', cells: [{ x: 0, y: 0, width_ratio: 1, height_ratio: 1, cell_index: 0 }] },
                { id: 2, name: 'Double Panel', cells: [
                    { x: 0, y: 0, width_ratio: 0.5, height_ratio: 1, cell_index: 0 },
                    { x: 0.5, y: 0, width_ratio: 0.5, height_ratio: 1, cell_index: 1 }
                ]},
                { id: 3, name: 'Triple Panel', cells: [
                    { x: 0, y: 0, width_ratio: 0.333, height_ratio: 1, cell_index: 0 },
                    { x: 0.333, y: 0, width_ratio: 0.334, height_ratio: 1, cell_index: 1 },
                    { x: 0.667, y: 0, width_ratio: 0.333, height_ratio: 1, cell_index: 2 }
                ]}
            ];
            renderTemplateGrid();
        }

        function initializeEventListeners() {
            const addWindowBtn = document.getElementById('add-window-btn');
            if (addWindowBtn) {
                addWindowBtn.addEventListener('click', function() {
                    console.log('Add window button clicked');
                    openTemplateSelection();
                });
            }
        }

        function renderTemplateGrid() {
            const grid = document.getElementById('template-grid');
            grid.innerHTML = windowTemplates.map(template => `
                <div class="template-item border rounded-lg p-4 cursor-pointer hover:bg-gray-50 text-center"
                     onclick="selectTemplate(${template.id})">
                    <div class="w-full h-24 mb-2 flex items-center justify-center border-2 border-dashed border-gray-300 rounded">
                        ${generateTemplateSVG(template, 80, 60)}
                    </div>
                    <h4 class="font-medium text-sm">${template.name}</h4>
                    <p class="text-xs text-gray-500">${template.cells.length} cell${template.cells.length > 1 ? 's' : ''}</p>
                </div>
            `).join('');
        }

        function generateTemplateSVG(template, width, height) {
            const cells = template.cells || [];
            const cellElements = cells.map(cell => {
                const x = cell.x * width;
                const y = cell.y * height;
                const w = cell.width_ratio * width;
                const h = cell.height_ratio * height;

                return `<rect x="${x}" y="${y}" width="${w}" height="${h}"
                        fill="none" stroke="#333" stroke-width="1"/>`;
            }).join('');

            return `<svg width="${width}" height="${height}" viewBox="0 0 ${width} ${height}">
                ${cellElements}
            </svg>`;
        }

        function openTemplateSelection() {
            document.getElementById('template-selection-modal').classList.add('modal-open');
        }

        function closeTemplateSelection() {
            document.getElementById('template-selection-modal').classList.remove('modal-open');
        }

        function selectTemplate(templateId) {
            currentTemplate = windowTemplates.find(t => t.id === templateId);
            if (!currentTemplate) return;

            closeTemplateSelection();
            showWindowConfig(currentTemplate);
        }

        function showWindowConfig(template) {
            const modal = document.getElementById('window-config-modal');
            const content = document.getElementById('window-config-content');

            const cellsConfig = template.cells.map((cell, index) => `
                <div class="border rounded-lg p-4">
                    <h5 class="font-medium mb-2">Cell ${cell.cell_index + 1}</h5>
                    <div class="space-y-3">
                        <div>
                            <h6 class="text-xs font-medium text-gray-600 mb-1">Hinged Opening</h6>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="flex items-center">
                                    <input type="checkbox" id="cell-${index}-left" class="checkbox checkbox-sm mr-2">
                                    <span class="text-sm">Open Left</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" id="cell-${index}-right" class="checkbox checkbox-sm mr-2">
                                    <span class="text-sm">Open Right</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" id="cell-${index}-top" class="checkbox checkbox-sm mr-2">
                                    <span class="text-sm">Open Top</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" id="cell-${index}-bottom" class="checkbox checkbox-sm mr-2">
                                    <span class="text-sm">Open Bottom</span>
                                </label>
                            </div>
                        </div>
                        
                        <div>
                            <h6 class="text-xs font-medium text-gray-600 mb-1">Sliding</h6>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="flex items-center">
                                    <input type="checkbox" id="cell-${index}-slide-left" class="checkbox checkbox-sm mr-2">
                                    <span class="text-sm">Slide Left</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" id="cell-${index}-slide-right" class="checkbox checkbox-sm mr-2">
                                    <span class="text-sm">Slide Right</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" id="cell-${index}-slide-top" class="checkbox checkbox-sm mr-2">
                                    <span class="text-sm">Slide Top</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" id="cell-${index}-slide-bottom" class="checkbox checkbox-sm mr-2">
                                    <span class="text-sm">Slide Bottom</span>
                                </label>
                            </div>
                        </div>
                        
                        <div>
                            <h6 class="text-xs font-medium text-gray-600 mb-1">Folding</h6>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="flex items-center">
                                    <input type="checkbox" id="cell-${index}-folding-left" class="checkbox checkbox-sm mr-2">
                                    <span class="text-sm">Folding Left</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" id="cell-${index}-folding-right" class="checkbox checkbox-sm mr-2">
                                    <span class="text-sm">Folding Right</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" id="cell-${index}-folding-top" class="checkbox checkbox-sm mr-2">
                                    <span class="text-sm">Folding Top</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" id="cell-${index}-folding-bottom" class="checkbox checkbox-sm mr-2">
                                    <span class="text-sm">Folding Bottom</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');

            content.innerHTML = `
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-medium mb-4">Window Dimensions</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="label"><span class="label-text">Label (optional)</span></label>
                                <input type="text" id="window-label" class="input input-bordered w-full" placeholder="e.g., Living Room Window">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="label"><span class="label-text">Width (mm)</span></label>
                                    <input type="number" id="window-width" class="input input-bordered w-full" min="100" max="5000" value="1200" required>
                                </div>
                                <div>
                                    <label class="label"><span class="label-text">Height (mm)</span></label>
                                    <input type="number" id="window-height" class="input input-bordered w-full" min="100" max="3000" value="1400" required>
                                </div>
                            </div>
                            <div>
                                <label class="label"><span class="label-text">Notes (optional)</span></label>
                                <textarea id="window-notes" class="textarea textarea-bordered w-full" rows="3" placeholder="Any additional notes..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-medium mb-4">Cell Opening Configuration</h4>
                        <div class="space-y-4 max-h-96 overflow-y-auto">
                            ${cellsConfig}
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <h4 class="font-medium mb-2">Preview</h4>
                    <div class="border rounded-lg p-4 bg-gray-50 flex justify-center">
                        <div id="window-preview">
                            ${generateWindowPreviewSVG(template, 200, 150)}
                        </div>
                    </div>
                    <div class="text-xs text-gray-600 mt-2 text-center">
                        Gray dashed lines indicate opening directions from hinge point. Configure dimensions to see detailed measurements.
                    </div>
                </div>

                <div class="flex gap-2 justify-end mt-6">
                    <button class="btn btn-ghost" onclick="closeWindowConfig()">Cancel</button>
                    <button class="btn btn-primary" onclick="addWindowToProject()">Add Window</button>
                </div>
            `;

            modal.classList.add('modal-open');

            // Add event listeners for real-time preview updates
            setTimeout(() => {
                const previewInputs = ['window-width', 'window-height'];
                const checkboxes = template.cells.map((_, index) =>
                    [`cell-${index}-left`, `cell-${index}-right`, `cell-${index}-top`, `cell-${index}-bottom`,
                     `cell-${index}-slide-left`, `cell-${index}-slide-right`, `cell-${index}-slide-top`, `cell-${index}-slide-bottom`,
                     `cell-${index}-folding-left`, `cell-${index}-folding-right`, `cell-${index}-folding-top`, `cell-${index}-folding-bottom`]
                ).flat();

                [...previewInputs, ...checkboxes].forEach(id => {
                    const element = document.getElementById(id);
                    if (element) {
                        element.addEventListener(element.type === 'checkbox' ? 'change' : 'input',
                            () => updatePreview(template));
                    }
                });
            }, 100);
        }

        function updatePreview(template) {
            const width = document.getElementById('window-width')?.value || 1200;
            const height = document.getElementById('window-height')?.value || 1400;

            const cellConfigs = template.cells.map((cell, index) => ({
                cell_index: cell.cell_index,
                open_left: document.getElementById(`cell-${index}-left`)?.checked || false,
                open_right: document.getElementById(`cell-${index}-right`)?.checked || false,
                open_top: document.getElementById(`cell-${index}-top`)?.checked || false,
                open_bottom: document.getElementById(`cell-${index}-bottom`)?.checked || false,
                slide_left: document.getElementById(`cell-${index}-slide-left`)?.checked || false,
                slide_right: document.getElementById(`cell-${index}-slide-right`)?.checked || false,
                slide_top: document.getElementById(`cell-${index}-slide-top`)?.checked || false,
                slide_bottom: document.getElementById(`cell-${index}-slide-bottom`)?.checked || false,
                folding_left: document.getElementById(`cell-${index}-folding-left`)?.checked || false,
                folding_right: document.getElementById(`cell-${index}-folding-right`)?.checked || false,
                folding_top: document.getElementById(`cell-${index}-folding-top`)?.checked || false,
                folding_bottom: document.getElementById(`cell-${index}-folding-bottom`)?.checked || false
            }));

            const previewDiv = document.getElementById('window-preview');
            if (previewDiv) {
                previewDiv.innerHTML = generateWindowPreviewSVG(template, 200, 150, parseInt(width), parseInt(height), cellConfigs);
            }
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
                if (cellConfigs && cellConfigs[index]) {
                    const config = cellConfigs[index];
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

                    // Folding options - zig-zag arrows (2 sharp angles)
                    if (config.folding_left) {
                        const foldY = centerY;
                        const foldStartX = x + w - 15;
                        const foldEndX = x + 15;
                        const zigzagHeight = 6;
                        const arrowSize = 4;
                        
                        // Calculate middle points for 2-angle zig-zag with sharper angles
                        const totalLength = foldStartX - (foldEndX + arrowSize);
                        const segment1X = foldStartX - totalLength * 0.25;
                        const segment2X = foldStartX - totalLength * 0.75;
                        
                        // Create sharp zig-zag path with just 2 angles
                        const pathData = `M${foldStartX} ${foldY} L${segment1X} ${foldY - zigzagHeight} L${segment2X} ${foldY + zigzagHeight} L${foldEndX + arrowSize} ${foldY}`;
                        
                        elements.push(`<path d="${pathData}" stroke="#000" stroke-width="2" fill="none"/>`);
                        // Arrow head
                        elements.push(`<path d="M${foldEndX + arrowSize} ${foldY - arrowSize} L${foldEndX} ${foldY} L${foldEndX + arrowSize} ${foldY + arrowSize}"
                            stroke="#000" stroke-width="2" fill="#000"/>`);
                    }

                    if (config.folding_right) {
                        const foldY = centerY;
                        const foldStartX = x + 15;
                        const foldEndX = x + w - 15;
                        const zigzagHeight = 6;
                        const arrowSize = 4;
                        
                        // Calculate middle points for 2-angle zig-zag with sharper angles
                        const totalLength = (foldEndX - arrowSize) - foldStartX;
                        const segment1X = foldStartX + totalLength * 0.25;
                        const segment2X = foldStartX + totalLength * 0.75;
                        
                        // Create sharp zig-zag path with just 2 angles
                        const pathData = `M${foldStartX} ${foldY} L${segment1X} ${foldY - zigzagHeight} L${segment2X} ${foldY + zigzagHeight} L${foldEndX - arrowSize} ${foldY}`;
                        
                        elements.push(`<path d="${pathData}" stroke="#000" stroke-width="2" fill="none"/>`);
                        // Arrow head
                        elements.push(`<path d="M${foldEndX - arrowSize} ${foldY - arrowSize} L${foldEndX} ${foldY} L${foldEndX - arrowSize} ${foldY + arrowSize}"
                            stroke="#000" stroke-width="2" fill="#000"/>`);
                    }

                    if (config.folding_top) {
                        const foldX = centerX;
                        const foldStartY = y + h - 15;
                        const foldEndY = y + 15;
                        const zigzagWidth = 6;
                        const arrowSize = 4;
                        
                        // Calculate middle points for 2-angle zig-zag with sharper angles
                        const totalLength = foldStartY - (foldEndY + arrowSize);
                        const segment1Y = foldStartY - totalLength * 0.25;
                        const segment2Y = foldStartY - totalLength * 0.75;
                        
                        // Create sharp zig-zag path with just 2 angles
                        const pathData = `M${foldX} ${foldStartY} L${foldX - zigzagWidth} ${segment1Y} L${foldX + zigzagWidth} ${segment2Y} L${foldX} ${foldEndY + arrowSize}`;
                        
                        elements.push(`<path d="${pathData}" stroke="#000" stroke-width="2" fill="none"/>`);
                        // Arrow head
                        elements.push(`<path d="M${foldX - arrowSize} ${foldEndY + arrowSize} L${foldX} ${foldEndY} L${foldX + arrowSize} ${foldEndY + arrowSize}"
                            stroke="#000" stroke-width="2" fill="#000"/>`);
                    }

                    if (config.folding_bottom) {
                        const foldX = centerX;
                        const foldStartY = y + 15;
                        const foldEndY = y + h - 15;
                        const zigzagWidth = 6;
                        const arrowSize = 4;
                        
                        // Calculate middle points for 2-angle zig-zag with sharper angles
                        const totalLength = (foldEndY - arrowSize) - foldStartY;
                        const segment1Y = foldStartY + totalLength * 0.25;
                        const segment2Y = foldStartY + totalLength * 0.75;
                        
                        // Create sharp zig-zag path with just 2 angles
                        const pathData = `M${foldX} ${foldStartY} L${foldX - zigzagWidth} ${segment1Y} L${foldX + zigzagWidth} ${segment2Y} L${foldX} ${foldEndY - arrowSize}`;
                        
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

        function closeWindowConfig() {
            document.getElementById('window-config-modal').classList.remove('modal-open');
        }

        function addWindowToProject() {
            const label = document.getElementById('window-label').value;
            const width = document.getElementById('window-width').value;
            const height = document.getElementById('window-height').value;
            const notes = document.getElementById('window-notes').value;

            if (!width || !height || width < 100 || height < 100) {
                alert('Please enter valid dimensions');
                return;
            }

            const cellConfigs = currentTemplate.cells.map((cell, index) => ({
                cell_index: cell.cell_index,
                open_left: document.getElementById(`cell-${index}-left`).checked,
                open_right: document.getElementById(`cell-${index}-right`).checked,
                open_top: document.getElementById(`cell-${index}-top`).checked,
                open_bottom: document.getElementById(`cell-${index}-bottom`).checked,
                slide_left: document.getElementById(`cell-${index}-slide-left`).checked,
                slide_right: document.getElementById(`cell-${index}-slide-right`).checked,
                slide_top: document.getElementById(`cell-${index}-slide-top`).checked,
                slide_bottom: document.getElementById(`cell-${index}-slide-bottom`).checked,
                folding_left: document.getElementById(`cell-${index}-folding-left`).checked,
                folding_right: document.getElementById(`cell-${index}-folding-right`).checked,
                folding_top: document.getElementById(`cell-${index}-folding-top`).checked,
                folding_bottom: document.getElementById(`cell-${index}-folding-bottom`).checked
            }));

            const window = {
                id: windowCounter++,
                template_id: currentTemplate.id,
                template: currentTemplate,
                label: label || `Window ${windowCounter}`,
                width_mm: parseInt(width),
                height_mm: parseInt(height),
                notes: notes,
                cells: cellConfigs
            };

            selectedWindows.push(window);
            renderSelectedWindows();
            closeWindowConfig();
        }

        function renderSelectedWindows() {
            const container = document.getElementById('selected-windows');
            const noWindowsMessage = document.getElementById('no-windows-message');

            if (selectedWindows.length === 0) {
                noWindowsMessage.style.display = 'block';
                container.innerHTML = '';
                return;
            }

            noWindowsMessage.style.display = 'none';

            container.innerHTML = selectedWindows.map(window => `
                <div class="window-item border rounded-lg p-6">
                    <div class="flex items-start gap-6">
                        <div class="flex-shrink-0">
                            <h5 class="font-medium text-sm mb-2">Technical Drawing</h5>
                            <div class="border rounded p-2 bg-white">
                                ${generateWindowPreviewSVG(window.template, 300, 200, window.width_mm, window.height_mm, window.cells)}
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-lg">${window.label}</h4>
                            <p class="text-sm text-gray-600 mb-2">${window.template.name} • ${window.width_mm}mm × ${window.height_mm}mm</p>
                            <p class="text-xs text-gray-500 mb-2">${window.cells.length} cells configured</p>
                            ${window.notes ? `<p class="text-sm text-gray-600 mb-2"><strong>Notes:</strong> ${window.notes}</p>` : ''}

                            <div class="bg-gray-50 rounded p-3 text-xs">
                                <strong>Cell Configurations:</strong><br>
                                ${window.cells.map(cell => {
                                    const openings = [];
                                    const slides = [];
                                    const foldings = [];
                                    
                                    if (cell.open_left) openings.push('Left');
                                    if (cell.open_right) openings.push('Right');
                                    if (cell.open_top) openings.push('Top');
                                    if (cell.open_bottom) openings.push('Bottom');
                                    
                                    if (cell.slide_left) slides.push('Left');
                                    if (cell.slide_right) slides.push('Right');
                                    if (cell.slide_top) slides.push('Top');
                                    if (cell.slide_bottom) slides.push('Bottom');
                                    
                                    if (cell.folding_left) foldings.push('Left');
                                    if (cell.folding_right) foldings.push('Right');
                                    if (cell.folding_top) foldings.push('Top');
                                    if (cell.folding_bottom) foldings.push('Bottom');

                                    const cellWidth = Math.round(window.template.cells[cell.cell_index].width_ratio * window.width_mm);
                                    const cellHeight = Math.round(window.template.cells[cell.cell_index].height_ratio * window.height_mm);

                                    const features = [];
                                    if (openings.length) features.push(openings.join(', ') + ' opening');
                                    if (slides.length) features.push(slides.join(', ') + ' sliding');
                                    if (foldings.length) features.push(foldings.join(', ') + ' folding');

                                    return `• Cell ${cell.cell_index + 1}: ${cellWidth}×${cellHeight}mm - ${features.length ? features.join(', ') : 'Fixed'}`;
                                }).join('<br>')}
                            </div>
                        </div>
                        <div class="flex flex-col gap-2">
                            <button class="btn btn-sm btn-outline" onclick="showWindowDetails(${window.id})">View Details</button>
                            <button class="btn btn-sm btn-error" onclick="removeWindow(${window.id})">Remove</button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function removeWindow(windowId) {
            selectedWindows = selectedWindows.filter(window => window.id !== windowId);
            renderSelectedWindows();
        }

        function showWindowDetails(windowId) {
            const window = selectedWindows.find(w => w.id === windowId);
            if (!window) return;

            // Create full-screen modal for detailed technical drawing
            const modal = document.createElement('div');
            modal.className = 'modal modal-open';
            modal.innerHTML = `
                <div class="modal-box w-11/12 max-w-7xl h-5/6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-xl">${window.label} - Technical Drawing</h3>
                        <button class="btn btn-sm btn-circle btn-ghost" onclick="this.closest('.modal').remove()">✕</button>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-full">
                        <div class="lg:col-span-2">
                            <div class="border rounded-lg p-4 bg-white h-full flex items-center justify-center">
                                ${generateWindowPreviewSVG(window.template, 600, 400, window.width_mm, window.height_mm, window.cells)}
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="card bg-base-200">
                                <div class="card-body p-4">
                                    <h4 class="card-title text-sm">Window Specifications</h4>
                                    <div class="text-xs space-y-1">
                                        <p><strong>Template:</strong> ${window.template.name}</p>
                                        <p><strong>Overall Dimensions:</strong> ${window.width_mm} × ${window.height_mm} mm</p>
                                        <p><strong>Total Area:</strong> ${((window.width_mm * window.height_mm) / 1000000).toFixed(2)} m²</p>
                                        <p><strong>Cells:</strong> ${window.cells.length}</p>
                                        ${window.notes ? `<p><strong>Notes:</strong> ${window.notes}</p>` : ''}
                                    </div>
                                </div>
                            </div>

                            <div class="card bg-base-200">
                                <div class="card-body p-4">
                                    <h4 class="card-title text-sm">Cell Details</h4>
                                    <div class="text-xs space-y-2 max-h-64 overflow-y-auto">
                                        ${window.cells.map(cell => {
                                            const templateCell = window.template.cells[cell.cell_index];
                                            const cellWidth = Math.round(templateCell.width_ratio * window.width_mm);
                                            const cellHeight = Math.round(templateCell.height_ratio * window.height_mm);
                                            const cellArea = ((cellWidth * cellHeight) / 1000000).toFixed(2);

                                            const openings = [];
                                            const slides = [];
                                            const foldings = [];
                                            
                                            if (cell.open_left) openings.push('Left');
                                            if (cell.open_right) openings.push('Right');
                                            if (cell.open_top) openings.push('Top');
                                            if (cell.open_bottom) openings.push('Bottom');
                                            
                                            if (cell.slide_left) slides.push('Left');
                                            if (cell.slide_right) slides.push('Right');
                                            if (cell.slide_top) slides.push('Top');
                                            if (cell.slide_bottom) slides.push('Bottom');
                                            
                                            if (cell.folding_left) foldings.push('Left');
                                            if (cell.folding_right) foldings.push('Right');
                                            if (cell.folding_top) foldings.push('Top');
                                            if (cell.folding_bottom) foldings.push('Bottom');

                                            const features = [];
                                            if (openings.length) features.push('Opening: ' + openings.join(', '));
                                            if (slides.length) features.push('Sliding: ' + slides.join(', '));
                                            if (foldings.length) features.push('Folding: ' + foldings.join(', '));

                                            return `
                                                <div class="border-b pb-2">
                                                    <p class="font-medium">Cell ${cell.cell_index + 1}</p>
                                                    <p>Dimensions: ${cellWidth} × ${cellHeight} mm</p>
                                                    <p>Area: ${cellArea} m²</p>
                                                    <p>Position: ${Math.round(templateCell.x * 100)}%, ${Math.round(templateCell.y * 100)}%</p>
                                                    <p>Features: ${features.length ? features.join('<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;') : 'Fixed'}</p>
                                                </div>
                                            `;
                                        }).join('')}
                                    </div>
                                </div>
                            </div>

                            <div class="card bg-base-200">
                                <div class="card-body p-4">
                                    <h4 class="card-title text-sm">Legend</h4>
                                    <div class="text-xs space-y-1">
                                        <div class="flex items-center gap-2">
                                            <div class="w-4 h-0.5 bg-blue-500 border border-blue-500"></div>
                                            <span>Cell boundaries</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="w-4 h-0.5 border-dashed border-gray-500 bg-gray-500"></div>
                                            <span>Opening directions (60° angle)</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="w-4 h-0.5 bg-black"></div>
                                            <span>→ Sliding directions (solid arrows)</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <svg width="16" height="8" viewBox="0 0 16 8">
                                                <path d="M1 4 L4 1 L10 7 L13 4" stroke="black" stroke-width="1" fill="none"/>
                                                <path d="M11 3 L13 4 L11 5" stroke="black" stroke-width="1" fill="black"/>
                                            </svg>
                                            <span>Folding directions (zig-zag arrows)</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="w-4 h-0.5 border-dashed border-gray-500"></div>
                                            <span>Dimension lines</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);
        }
    </script>

    <style>
        .template-item:hover {
            border-color: var(--primary);
        }

        .window-item:hover {
            border-color: var(--primary);
        }
    </style>
@endsection
