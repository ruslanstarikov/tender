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
                    <div class="text-xs text-gray-600 mt-2 text-center">
                        Red dashed lines indicate opening directions. Configure dimensions to see detailed measurements.
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
                    [`cell-${index}-left`, `cell-${index}-right`, `cell-${index}-top`, `cell-${index}-bottom`]
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
                open_bottom: document.getElementById(`cell-${index}-bottom`)?.checked || false
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
                    
                    // Opening angles (60 degrees)
                    if (config.open_left) {
                        const angle1 = Math.PI * 5/6; // 150 degrees
                        const angle2 = Math.PI * 7/6; // 210 degrees
                        const len = Math.min(w, h) * 0.15;
                        const x1 = x + len * Math.cos(angle1);
                        const y1 = centerY + len * Math.sin(angle1);
                        const x2 = x + len * Math.cos(angle2);
                        const y2 = centerY + len * Math.sin(angle2);
                        elements.push(`<path d="M${x} ${centerY} L${x1} ${y1} M${x} ${centerY} L${x2} ${y2}" 
                            stroke="#ef4444" stroke-width="2" stroke-dasharray="4,2"/>`);
                    }
                    
                    if (config.open_right) {
                        const angle1 = Math.PI * 1/6; // 30 degrees
                        const angle2 = Math.PI * 11/6; // 330 degrees
                        const len = Math.min(w, h) * 0.15;
                        const x1 = (x + w) + len * Math.cos(angle1);
                        const y1 = centerY + len * Math.sin(angle1);
                        const x2 = (x + w) + len * Math.cos(angle2);
                        const y2 = centerY + len * Math.sin(angle2);
                        elements.push(`<path d="M${x + w} ${centerY} L${x1} ${y1} M${x + w} ${centerY} L${x2} ${y2}" 
                            stroke="#ef4444" stroke-width="2" stroke-dasharray="4,2"/>`);
                    }
                    
                    if (config.open_top) {
                        const angle1 = Math.PI * 2/3; // 120 degrees
                        const angle2 = Math.PI * 1/3; // 60 degrees
                        const len = Math.min(w, h) * 0.15;
                        const x1 = centerX + len * Math.cos(angle1);
                        const y1 = y + len * Math.sin(angle1);
                        const x2 = centerX + len * Math.cos(angle2);
                        const y2 = y + len * Math.sin(angle2);
                        elements.push(`<path d="M${centerX} ${y} L${x1} ${y1} M${centerX} ${y} L${x2} ${y2}" 
                            stroke="#ef4444" stroke-width="2" stroke-dasharray="4,2"/>`);
                    }
                    
                    if (config.open_bottom) {
                        const angle1 = Math.PI * 4/3; // 240 degrees
                        const angle2 = Math.PI * 5/3; // 300 degrees
                        const len = Math.min(w, h) * 0.15;
                        const x1 = centerX + len * Math.cos(angle1);
                        const y1 = (y + h) + len * Math.sin(angle1);
                        const x2 = centerX + len * Math.cos(angle2);
                        const y2 = (y + h) + len * Math.sin(angle2);
                        elements.push(`<path d="M${centerX} ${y + h} L${x1} ${y1} M${centerX} ${y + h} L${x2} ${y2}" 
                            stroke="#ef4444" stroke-width="2" stroke-dasharray="4,2"/>`);
                    }
                }
                
                // Cell dimensions (if window dimensions provided)
                if (windowWidth && windowHeight) {
                    const actualW = Math.round(cell.width_ratio * windowWidth);
                    const actualH = Math.round(cell.height_ratio * windowHeight);
                    
                    // Width dimension line
                    elements.push(`<line x1="${x}" y1="${y - 10}" x2="${x + w}" y2="${y - 10}" 
                        stroke="#666" stroke-width="1" stroke-dasharray="2,2"/>`);
                    elements.push(`<text x="${x + w/2}" y="${y - 15}" text-anchor="middle" 
                        font-family="Arial" font-size="10" fill="#666">${actualW}mm</text>`);
                    
                    // Height dimension line
                    elements.push(`<line x1="${x - 10}" y1="${y}" x2="${x - 10}" y2="${y + h}" 
                        stroke="#666" stroke-width="1" stroke-dasharray="2,2"/>`);
                    elements.push(`<text x="${x - 15}" y="${y + h/2}" text-anchor="middle" 
                        font-family="Arial" font-size="10" fill="#666" transform="rotate(-90, ${x - 15}, ${y + h/2})">${actualH}mm</text>`);
                    
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
                                    const directions = [];
                                    if (cell.open_left) directions.push('Left');
                                    if (cell.open_right) directions.push('Right');
                                    if (cell.open_top) directions.push('Top');
                                    if (cell.open_bottom) directions.push('Bottom');
                                    
                                    const cellWidth = Math.round(window.template.cells[cell.cell_index].width_ratio * window.width_mm);
                                    const cellHeight = Math.round(window.template.cells[cell.cell_index].height_ratio * window.height_mm);
                                    
                                    return `• Cell ${cell.cell_index + 1}: ${cellWidth}×${cellHeight}mm - ${directions.length ? directions.join(', ') + ' opening' : 'Fixed'}`;
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
                                            
                                            const directions = [];
                                            if (cell.open_left) directions.push('Left');
                                            if (cell.open_right) directions.push('Right');
                                            if (cell.open_top) directions.push('Top');
                                            if (cell.open_bottom) directions.push('Bottom');
                                            
                                            return `
                                                <div class="border-b pb-2">
                                                    <p class="font-medium">Cell ${cell.cell_index + 1}</p>
                                                    <p>Dimensions: ${cellWidth} × ${cellHeight} mm</p>
                                                    <p>Area: ${cellArea} m²</p>
                                                    <p>Position: ${Math.round(templateCell.x * 100)}%, ${Math.round(templateCell.y * 100)}%</p>
                                                    <p>Opening: ${directions.length ? directions.join(', ') : 'Fixed'}</p>
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
                                            <div class="w-4 h-0.5 border-dashed border-red-500 bg-red-500"></div>
                                            <span>Opening directions (60° angle)</span>
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