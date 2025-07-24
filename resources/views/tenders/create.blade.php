@extends('base') {{-- or the name of your base layout, e.g. resources/views/layouts/app.blade.php --}}

@section('title', 'Create Tender')

@section('content')
    <div class="max-w-3xl mx-auto bg-base-100 p-6 rounded-lg shadow">
        <h2 class="text-2xl font-semibold mb-4">Create New Tender</h2>

        @if ($errors->any())
            <div class="alert alert-error mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.tenders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Basic Tender Fields --}}
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="label"><span class="label-text">Project Title</span></label>
                    <input type="text" name="project_title" value="{{ old('project_title') }}" class="input input-bordered w-full" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label"><span class="label-text">Address</span></label>
                        <input type="text" name="address" value="{{ old('address') }}" class="input input-bordered w-full" required>
                    </div>
                    <div>
                        <label class="label"><span class="label-text">Suburb</span></label>
                        <input type="text" name="suburb" value="{{ old('suburb') }}" class="input input-bordered w-full" required>
                    </div>
                    <div>
                        <label class="label"><span class="label-text">State</span></label>
                        <input type="text" name="state" value="{{ old('state') }}" class="input input-bordered w-full" required>
                    </div>
                    <div>
                        <label class="label"><span class="label-text">Post Code</span></label>
                        <input type="text" name="post_code" value="{{ old('post_code') }}" class="input input-bordered w-full" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label"><span class="label-text">Work Start Date & Time</span></label>
                        <input type="datetime-local" name="work_start_datetime" value="{{ old('work_start_datetime') }}" class="input input-bordered w-full" required>
                    </div>
                    <div>
                        <label class="label"><span class="label-text">Work End Date & Time</span></label>
                        <input type="datetime-local" name="work_end_datetime" value="{{ old('work_end_datetime') }}" class="input input-bordered w-full" required>
                    </div>
                </div>

                <div>
                    <label class="label"><span class="label-text">Premises Entry Info (e.g. dogs, security)</span></label>
                    <textarea name="premises_entry_info" class="textarea textarea-bordered w-full" rows="3">{{ old('premises_entry_info') }}</textarea>
                </div>

                <div>
                    <label class="label"><span class="label-text">Frame Details (system, color, glass count)</span></label>
                    <textarea name="frame_details" class="textarea textarea-bordered w-full" rows="3">{{ old('frame_details') }}</textarea>
                </div>
            </div>

            <hr class="my-6">

            {{-- Window Template Selection Section --}}
            <h3 class="text-xl font-semibold mb-2">Window Configuration</h3>
            <p class="text-sm text-gray-600 mb-4">Configure windows using templates and customize each cell's opening directions.</p>

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

            <hr class="my-6">

            {{-- Media Upload Sections --}}
            <h3 class="text-xl font-semibold mb-2">Upload Media</h3>
            <p class="text-sm text-gray-600 mb-4">You may upload multiple photos/videos under each category. For Polycam models, upload under "Polycam Models."</p>

            {{-- Categories: inside, parking, outside, storage --}}
            <div class="grid grid-cols-1 gap-8">
                @foreach (['inside', 'parking', 'outside', 'storage'] as $category)
                    <div class="border rounded-lg p-4">
                        <h4 class="font-semibold text-lg capitalize mb-2">{{ ucfirst($category) }}:</h4>

                        {{-- Photos --}}
                        <div class="mb-4">
                            <label class="label"><span class="label-text">Photos ({{ ucfirst($category) }})</span></label>
                            <input type="file"
                                   name="media[{{ $category }}][photos][]"
                                   accept="image/*"
                                   multiple
                                   class="file-input file-input-bordered w-full" />
                        </div>

                        {{-- Videos --}}
                        <div>
                            <label class="label"><span class="label-text">Videos ({{ ucfirst($category) }})</span></label>
                            <input type="file"
                                   name="media[{{ $category }}][videos][]"
                                   accept="video/*"
                                   multiple
                                   class="file-input file-input-bordered w-full" />
                        </div>
                    </div>
                @endforeach

                {{-- ===== GLB (Polycam) Section ===== --}}
                <div class="border rounded-lg p-4">
                    <h4 class="font-semibold text-lg mb-2">Polycam Models (GLB):</h4>
                    <div>
                        <label class="label"><span class="label-text">Upload One or More `.glb` Files</span></label>
                        <input type="file"
                               name="media[polycam][models][]"
                               multiple
                               accept=".glb"
                               class="file-input file-input-bordered w-full" />
                        <p class="text-xs text-gray-500 mt-1">
                            Polycam exports glTF/GLB by default. Upload `.glb` here (each file can be up to your configured max).
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="btn btn-primary">Create Tender</button>
            </div>
        </form>
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
        let frameTypes = [];
        let selectedFrames = [];
        let frameCounter = 0;

        // Load frame types on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadFrameTypes();
            initializeEventListeners();
        });

        function loadFrameTypes() {
            console.log('Loading frame types...');
            fetch('/api/frame-types')
                .then(response => {
                    console.log('API response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Frame types loaded:', data.length);
                    frameTypes = data;
                    renderFrameCatalogue();
                })
                .catch(error => {
                    console.error('Error loading frame types:', error);
                });
        }

        function initializeEventListeners() {
            const addFrameBtn = document.getElementById('add-frame-btn');
            if (addFrameBtn) {
                addFrameBtn.addEventListener('click', function() {
                    console.log('Add frame button clicked');
                    openFrameCatalogue();
                });
            } else {
                console.error('Add frame button not found');
            }
        }

        function renderFrameCatalogue(filterType = 'all') {
            const grid = document.getElementById('frame-catalogue-grid');
            const filteredFrames = filterType === 'all' 
                ? frameTypes 
                : frameTypes.filter(frame => frame.type === filterType);

            grid.innerHTML = filteredFrames.map(frame => `
                <div class="frame-item border rounded-lg p-3 cursor-pointer hover:bg-gray-50 text-center" 
                     onclick="selectFrameType(${frame.id})">
                    <img src="${frame.image_url}" alt="${frame.display_name}" class="w-full h-24 object-contain mb-2">
                    <h4 class="font-medium text-sm">${frame.display_name}</h4>
                    <p class="text-xs text-gray-500">${frame.config_string}</p>
                </div>
            `).join('');
        }

        function openFrameCatalogue() {
            document.getElementById('frame-catalogue-modal').classList.add('modal-open');
        }

        function closeFrameCatalogue() {
            document.getElementById('frame-catalogue-modal').classList.remove('modal-open');
        }

        function selectFrameType(frameTypeId) {
            const frameType = frameTypes.find(f => f.id === frameTypeId);
            if (!frameType) return;

            closeFrameCatalogue();
            showFrameConfig(frameType);
        }

        function showFrameConfig(frameType) {
            const modal = document.getElementById('frame-config-modal');
            const content = document.getElementById('frame-config-content');
            
            content.innerHTML = `
                <div class="space-y-4">
                    <div class="text-center">
                        <img src="${frameType.image_url}" alt="${frameType.display_name}" class="w-32 h-24 object-contain mx-auto mb-2">
                        <h4 class="font-medium">${frameType.display_name}</h4>
                        <p class="text-sm text-gray-500">${frameType.config_string}</p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label"><span class="label-text">Width (mm)</span></label>
                            <input type="number" id="frame-width" class="input input-bordered w-full" min="100" max="5000" value="1200" required>
                        </div>
                        <div>
                            <label class="label"><span class="label-text">Height (mm)</span></label>
                            <input type="number" id="frame-height" class="input input-bordered w-full" min="100" max="3000" value="1500" required>
                        </div>
                    </div>
                    
                    <div>
                        <label class="label"><span class="label-text">Quantity</span></label>
                        <input type="number" id="frame-quantity" class="input input-bordered w-full" min="1" max="100" value="1" required>
                    </div>
                    
                    <div class="flex gap-2 justify-end">
                        <button class="btn btn-ghost" onclick="closeFrameConfig()">Cancel</button>
                        <button class="btn btn-primary" onclick="addFrameToProject(${frameType.id})">Add Frame</button>
                    </div>
                </div>
            `;
            
            modal.classList.add('modal-open');
        }

        function closeFrameConfig() {
            document.getElementById('frame-config-modal').classList.remove('modal-open');
        }

        function addFrameToProject(frameTypeId) {
            const frameType = frameTypes.find(f => f.id === frameTypeId);
            const width = document.getElementById('frame-width').value;
            const height = document.getElementById('frame-height').value;
            const quantity = document.getElementById('frame-quantity').value;

            if (!width || !height || !quantity || width < 100 || height < 100 || quantity < 1) {
                alert('Please enter valid dimensions and quantity');
                return;
            }

            const frame = {
                id: frameCounter++,
                frame_type_id: frameTypeId,
                frame_type: frameType,
                width: parseFloat(width),
                height: parseFloat(height),
                quantity: parseInt(quantity)
            };

            selectedFrames.push(frame);
            renderSelectedFrames();
            closeFrameConfig();
        }

        function renderSelectedFrames() {
            const container = document.getElementById('selected-frames');
            const noFramesMessage = document.getElementById('no-frames-message');
            
            if (selectedFrames.length === 0) {
                noFramesMessage.style.display = 'block';
                container.innerHTML = '';
                return;
            }
            
            noFramesMessage.style.display = 'none';
            
            container.innerHTML = selectedFrames.map(frame => `
                <div class="frame-item border rounded-lg p-4">
                    <div class="flex items-center gap-4">
                        <img src="${frame.frame_type.image_url}" alt="${frame.frame_type.display_name}" class="w-16 h-12 object-contain">
                        <div class="flex-1">
                            <h4 class="font-medium">${frame.frame_type.display_name}</h4>
                            <p class="text-sm text-gray-500">${frame.width}mm × ${frame.height}mm | Qty: ${frame.quantity}</p>
                            <p class="text-xs text-gray-400">${frame.frame_type.config_string}</p>
                        </div>
                        <button class="btn btn-sm btn-error" onclick="removeFrame(${frame.id})">Remove</button>
                    </div>
                    <input type="hidden" name="frames[${frame.id}][frame_type_id]" value="${frame.frame_type_id}">
                    <input type="hidden" name="frames[${frame.id}][width]" value="${frame.width}">
                    <input type="hidden" name="frames[${frame.id}][height]" value="${frame.height}">
                    <input type="hidden" name="frames[${frame.id}][quantity]" value="${frame.quantity}">
                </div>
            `).join('');
        }

        function removeFrame(frameId) {
            selectedFrames = selectedFrames.filter(frame => frame.id !== frameId);
            renderSelectedFrames();
        }

        // Frame filter buttons
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('frame-filter-btn')) {
                document.querySelectorAll('.frame-filter-btn').forEach(btn => btn.classList.remove('active'));
                e.target.classList.add('active');
                const filterType = e.target.dataset.type;
                renderFrameCatalogue(filterType);
            }
        });
    </script>

    <style>
        .frame-filter-btn.active {
            background-color: var(--primary);
            color: white;
        }
        
        .frame-item:hover {
            border-color: var(--primary);
        }
    </style>
@endsection
