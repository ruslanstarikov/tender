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
                @foreach (['inside', 'parking', 'outside', 'storage', 'polycam'] as $category)
                    @php
                        $grouped = $tender->media->where('category', $category);
                    @endphp

                    @if ($grouped->isNotEmpty())
                        <div class="mb-8">
                            <h4 class="font-semibold text-lg capitalize mb-2">{{ ucfirst($category) }}</h4>
                            {{-- ===== Polycam (GLB) ===== --}}


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
    <!-- 1) Three.js core (defines global `THREE`) -->
    <script src="https://unpkg.com/three@0.152.0/build/three.min.js"></script>

    <!-- 2) OrbitControls (non-module build—attaches to `THREE.OrbitControls`) -->
    <script src="https://unpkg.com/three@0.152.0/examples/js/controls/OrbitControls.js"></script>

    <!-- 3) DRACOLoader (non-module) and its decoder files -->
    <script src="https://unpkg.com/three@0.152.0/examples/js/libs/draco/draco_decoder.js"></script>
    <script src="https://unpkg.com/three@0.152.0/examples/js/libs/draco/draco_wasm_wrapper.js"></script>
    <script src="https://unpkg.com/three@0.152.0/examples/js/loaders/DRACOLoader.js"></script>

    <!-- 4) GLTFLoader (non-module build—attaches to `THREE.GLTFLoader`) -->
    <script src="https://unpkg.com/three@0.152.0/examples/js/loaders/GLTFLoader.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Verify that THREE and its loaders exist:
            if (typeof THREE === 'undefined') {
                console.error('THREE is not defined. Check that three.min.js loaded successfully.');
                return;
            }
            if (typeof THREE.OrbitControls !== 'function') {
                console.error('OrbitControls not found on THREE. Check that OrbitControls.js loaded.');
                return;
            }
            if (typeof THREE.GLTFLoader !== 'function') {
                console.error('GLTFLoader not found on THREE. Check that GLTFLoader.js loaded.');
                return;
            }
            if (typeof THREE.DRACOLoader !== 'function') {
                console.error('DRACOLoader not found on THREE. Check that DRACOLoader.js and its decoder files loaded.');
                return;
            }

            // For each .threejs-viewer container, initialize a scene
            document.querySelectorAll('.threejs-viewer').forEach(function(container) {
                const parent = container.parentElement;
                const glbUrl = parent.querySelector('.glb-url').value;

                // 1) Scene & Camera
                const scene = new THREE.Scene();
                scene.background = new THREE.Color(0xf0f0f0);

                const camera = new THREE.PerspectiveCamera(
                    45,
                    container.clientWidth / container.clientHeight,
                    0.1,
                    1000
                );
                camera.position.set(0, 2, 5);

                // 2) Renderer
                const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
                renderer.setSize(container.clientWidth, container.clientHeight);
                container.appendChild(renderer.domElement);

                // 3) Lights
                const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
                scene.add(ambientLight);

                const dirLight = new THREE.DirectionalLight(0xffffff, 0.8);
                dirLight.position.set(5, 10, 7.5);
                scene.add(dirLight);

                // 4) Ground plane (optional)
                const planeGeo = new THREE.PlaneGeometry(10, 10);
                const planeMat = new THREE.MeshStandardMaterial({ color: 0xdddddd, side: THREE.DoubleSide });
                const plane = new THREE.Mesh(planeGeo, planeMat);
                plane.rotation.x = -Math.PI / 2;
                plane.position.y = -1;
                scene.add(plane);

                // 5) OrbitControls (must come after THREE is loaded)
                const controls = new THREE.OrbitControls(camera, renderer.domElement);
                controls.update();

                // 6) GLTFLoader + Draco
                const loader = new THREE.GLTFLoader();
                const dracoLoader = new THREE.DRACOLoader();
                dracoLoader.setDecoderPath('https://unpkg.com/three@0.152.0/examples/js/libs/draco/');
                loader.setDRACOLoader(dracoLoader);

                loader.load(
                    glbUrl,
                    function(gltf) {
                        const object = gltf.scene || gltf.scenes[0];

                        // Center the model
                        const bbox = new THREE.Box3().setFromObject(object);
                        const center = bbox.getCenter(new THREE.Vector3());
                        object.position.sub(center);

                        scene.add(object);
                    },
                    undefined,
                    function(error) {
                        console.error('Error loading GLB:', error);
                    }
                );

                // 7) Animation loop
                function animate() {
                    requestAnimationFrame(animate);
                    controls.update();
                    renderer.render(scene, camera);
                }
                animate();

                // 8) Handle window resize
                window.addEventListener('resize', () => {
                    camera.aspect = container.clientWidth / container.clientHeight;
                    camera.updateProjectionMatrix();
                    renderer.setSize(container.clientWidth, container.clientHeight);
                });
            });
        });
    </script>
@endsection
