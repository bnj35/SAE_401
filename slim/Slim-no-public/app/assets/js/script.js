import * as THREE from './three.module.js';
import { STLLoader } from './STLLoader.js';
import { OrbitControls } from './OrbitControls.js';

var canvas = document.querySelectorAll('.display');


var loader = new STLLoader();


canvas.forEach((canvas) => {
    var scene = new THREE.Scene();
    var camera = new THREE.PerspectiveCamera(75, canvas.clientWidth / canvas.clientHeight, 0.1, 1000);
    camera.position.z = 5;
    var renderer = new THREE.WebGLRenderer(scene, camera);
    renderer.setSize(canvas.clientWidth, canvas.clientHeight);
    renderer.setClearColor(0xffffff, 1);
    canvas.appendChild(renderer.domElement);

    var controls = new OrbitControls(camera, renderer.domElement);

    var fileContent = canvas.getAttribute('data-file');
// 
    // Chargez le fichier STL
    loader.load('data:application/octet-stream;base64,'+ fileContent, function (geometry) {
        var material = new THREE.MeshMatcapMaterial()
        var mesh = new THREE.Mesh(geometry, material);
        scene.add(mesh);

        var boundingBox = new THREE.Box3().setFromObject(mesh);
    
        var size = boundingBox.getSize(new THREE.Vector3()).length();
        
    

        var distance = size * 0.5; 
    
        camera.position.set(distance, distance, distance);
    
        camera.lookAt(mesh.position);
    
        controls.update();

        canvas.addEventListener('click', function() {
            canvas.requestFullscreen();
        });

    //change la taille  du rendu en fonction de la taille de l'écran mais ne se remet pas bien
    canvas.addEventListener('fullscreenchange', function() {
        if (canvas.fullscreenElement) {
            renderer.setSize(window.innerWidth,window.innerHeight);
        } else {
            renderer.setSize(canvas.clientWidth,canvas.clientHeight);
        }
    });
        
    });

    const light = new THREE.AmbientLight(0xffffff,10);
    scene.add(light);



    function animate() {
        requestAnimationFrame(animate); 
        controls.update();
        renderer.render(scene, camera);
    }
    animate();

    
});