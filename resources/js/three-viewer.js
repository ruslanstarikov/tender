// resources/js/three-viewer.js

import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';
import { DRACOLoader } from 'three/examples/jsm/loaders/DRACOLoader.js';

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.threejs-viewer').forEach((container) => {
        // The hidden input with class "glb-url" sits in the same parent div
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

        // 5) OrbitControls
        const controls = new OrbitControls(camera, renderer.domElement);
        controls.update();

        // 6) GLTFLoader + DRACOLoader
        const loader = new GLTFLoader();
        const dracoLoader = new DRACOLoader();
        // Point DRACOLoader at the CDN (Vite will automatically handle this path):
        dracoLoader.setDecoderPath('https://unpkg.com/three@0.152.0/examples/js/libs/draco/');
        loader.setDRACOLoader(dracoLoader);

        loader.load(
            glbUrl,
            (gltf) => {
                const object = gltf.scene || gltf.scenes[0];

                // Center the model
                const bbox = new THREE.Box3().setFromObject(object);
                const center = bbox.getCenter(new THREE.Vector3());
                object.position.sub(center);

                scene.add(object);
            },
            undefined,
            (error) => {
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
