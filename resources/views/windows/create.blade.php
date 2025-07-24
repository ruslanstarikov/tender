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
                </div>
                
                <div class="flex gap-2 justify-end mt-6">
                    <button class="btn btn-ghost" onclick="closeWindowConfig()">Cancel</button>
                    <button class="btn btn-primary" onclick="addWindowToProject()">Add Window</button>
                </div>
            `;
            
            modal.classList.add('modal-open');
        }

        function generateWindowPreviewSVG(template, width, height) {
            const cells = template.cells || [];
            const cellElements = cells.map(cell => {
                const x = cell.x * width;
                const y = cell.y * height;
                const w = cell.width_ratio * width;
                const h = cell.height_ratio * height;
                
                return `<rect x="${x}" y="${y}" width="${w}" height="${h}" 
                        fill="rgba(59, 130, 246, 0.1)" stroke="#3b82f6" stroke-width="2"/>`;
            }).join('');

            return `<svg width="${width}" height="${height}" viewBox="0 0 ${width} ${height}">
                ${cellElements}
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
                open_bottom: document.getElementById(`cell-${index}-bottom`).checked
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
                <div class="window-item border rounded-lg p-4">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            ${generateWindowPreviewSVG(window.template, 80, 60)}
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium">${window.label}</h4>
                            <p class="text-sm text-gray-500">${window.template.name} • ${window.width_mm}mm × ${window.height_mm}mm</p>
                            <p class="text-xs text-gray-400">${window.cells.length} cells configured</p>
                            ${window.notes ? `<p class="text-xs text-gray-400 mt-1">${window.notes}</p>` : ''}
                            <div class="text-xs text-gray-400 mt-1">
                                Opening directions: ${window.cells.map(cell => {
                                    const directions = [];
                                    if (cell.open_left) directions.push('L');
                                    if (cell.open_right) directions.push('R');
                                    if (cell.open_top) directions.push('T');
                                    if (cell.open_bottom) directions.push('B');
                                    return `Cell ${cell.cell_index + 1}: ${directions.length ? directions.join(',') : 'Fixed'}`;
                                }).join(' | ')}
                            </div>
                        </div>
                        <button class="btn btn-sm btn-error" onclick="removeWindow(${window.id})">Remove</button>
                    </div>
                </div>
            `).join('');
        }

        function removeWindow(windowId) {
            selectedWindows = selectedWindows.filter(window => window.id !== windowId);
            renderSelectedWindows();
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