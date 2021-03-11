
import * as THREE from '../../libraries/three.js-master/build/three.module.js';

let container, containerWidth, containerHeight;
let camera, scene, renderer;
let mesh;

init();
animate();

function init() {
    container = document.getElementById('webgl');
    containerWidth = 600;
    containerHeight = 400;

    camera = new THREE.PerspectiveCamera( 70, containerWidth / containerHeight, 1, 1000 );
    camera.position.z = 400;
    scene = new THREE.Scene();

    const texture = new THREE.TextureLoader().load( '../libraries/three.js-master/examples/textures/crate.gif' );
    const geometry = new THREE.BoxGeometry( 200, 200, 200 );
    const material = new THREE.MeshBasicMaterial( { map: texture } );

    mesh = new THREE.Mesh( geometry, material );
    console.log(texture)
    scene.add( mesh );

    setRenderer();
    container.appendChild( renderer.domElement );
    window.addEventListener( 'resize', onWindowResize, false );

}

function onWindowResize() {
    camera.aspect = containerWidth / containerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize( containerWidth, containerHeight );
}

function animate() {
    requestAnimationFrame( animate );

    mesh.rotation.x += 0.005;
    mesh.rotation.y += 0.01;

    renderer.render( scene, camera );
}

function setRenderer(){
    renderer = new THREE.WebGLRenderer( { antialias: true } );
    renderer.setPixelRatio( window.devicePixelRatio );
    renderer.setSize( containerWidth, containerHeight );

    // Shadow management
    /*
    renderer.shadowMap.enabled = true ;
    renderer.shadowMap.type = THREE.PCFSoftShadowMap;
    renderer.outputEncoding = THREE.sRGBEncoding;*/
}