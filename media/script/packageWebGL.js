
import * as THREE from '../../libraries/three.js-master/build/three.module.js';
import { GLTFLoader } from '../../libraries/three.js-master/examples/jsm/loaders/GLTFLoader.js';
import { OrbitControls } from '../../libraries/three.js-master/examples/jsm/controls/OrbitControls.js';
import { Sky } from '../../libraries/three.js-master/examples/jsm/objects/Sky.js';
import  Stats  from '../../libraries/three.js-master/examples/jsm/libs/stats.module.js';

let container, containerWidth, containerHeight;
let camera, scene, renderer;
let meshes=[];
let mesh;
let loadingManager, mixer, mixer2, mixer3, truck, house, warehouse, action;
let clock;
let spotLight, lightHelper, shadowCameraHelper;
let controls, direction;
let sky, sun, effectController;
let key;
let ground ;
const raycaster = new THREE.Raycaster();
const mouse = new THREE.Vector2( 1, 1 );

clock = new THREE.Clock();
container = document.getElementById('webgl');
containerWidth = 1500;
containerHeight = 700;
window.addEventListener('keydown', onkeydownAnimation);
window.addEventListener('keyup', onkeyupAnimation)

init();




function init() {
    camera = new THREE.PerspectiveCamera( 70, containerWidth / containerHeight, 1, 1000000000 );
    camera.position.set( 80, -100, 500 );
    scene = new THREE.Scene();

    ground = createPhongMaterial( '../media/webgl/assets_low/sand.jpg', 70, 70 );

    setRenderer();
    container.addEventListener( 'mousemove', onMouseMove );
    container.appendChild( renderer.domElement );

    window.addEventListener( 'resize', onWindowResize, false );

    //	Controls & light
    createControls();
    createLight();

    //  Environnement
    createPlane();
    initSky();

    loadingManager = new THREE.LoadingManager( addObjects );
    createTruck();
    createHouse();
    createWarehouse()
    animate();
}

function onWindowResize() {
    camera.aspect = containerWidth / containerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize( containerWidth, containerHeight );

    controls.handleResize();
}


function onMouseMove( event ) {

  event.preventDefault();

  mouse.x = ( event.clientX / window.innerWidth ) * 2 - 1;
  mouse.y = - ( event.clientY / window.innerHeight ) * 2 + 1;

}
//###############		ANIMATIONS		##################################

function onkeydownAnimation (e) {
    if (e.key === 'z') {
        direction = 'forward';
    } else if (e.key === 'q') {
        direction = 'left'
    } else if (e.key === 's') {
        direction = 'backward'
    } else if (e.key === 'd') {
        direction = 'right'
    }
}

function onkeyupAnimation (e) {
    direction = null;
}

function animate() {
    const delta = clock.getDelta();
    requestAnimationFrame( animate );

    if ( mixer ) mixer.update(delta);

    if (action) {
        if(direction != null) {
            action.paused = false;
            moveTruck();
        } else action.paused = true;
    }
    render()
    renderer.render( scene, camera );
    controls.update();
}

function moveTruck () {
    let speedCoeff = 15;

    switch (direction) {
        case 'forward':
            truck.position.x -= speedCoeff * Math.cos(truck.rotation.y);
            truck.position.z += speedCoeff * Math.sin(truck.rotation.y);
            break;
        case 'backward':
            truck.position.x += speedCoeff * Math.cos(truck.rotation.y);
            truck.position.z -= speedCoeff * Math.sin(truck.rotation.y);
            break;
        case 'right':
            truck.rotation.y -= 0.05;
            truck.rotation.y %= (2 * Math.PI);
            break;
        case 'left':
            truck.rotation.y += 0.05;
            truck.rotation.y %= (2 * Math.PI);
            break;
        default: break;
    }
}

function computeZDirection (x, coeff) {
    return coeff * (x * Math.sin(truck.rotation.y) / Math.cos(truck.rotation.y) + Math.sin(truck.rotation.y));
}

function setRenderer(){
    renderer = new THREE.WebGLRenderer( { antialias: true } );
    renderer.setPixelRatio( window.devicePixelRatio );
    renderer.setSize( containerWidth, containerHeight );



    // Shadow management
    renderer.shadowMap.enabled = true ;
    renderer.shadowMap.type = THREE.PCFSoftShadowMap;
    renderer.outputEncoding = THREE.sRGBEncoding;
}

function render(){
    lightHelper.update();
    //shadowCameraHelper.update();

    raycaster.setFromCamera( mouse, camera );
    const intersection = raycaster.intersectObjects( meshes );

    if ( intersection.length > 0 ) {
      console.log(intersection[0].object.scale.x += 0.01)
    }
    renderer.render( scene, camera );
}


//###############		CONTROLS		##################################

function createControls(){
    controls = new OrbitControls( camera, renderer.domElement );

    controls.enabledDamping = true;
    controls.dampingFactor = 0.05;
    controls.screenPanning = false;

    controls.minDistance = 2;
    controls.maxDistance = 10000000000000000;

    controls.maxPolarAngle = Math.PI / 2 ;
    controls.target = new THREE.Vector3( 0,0,0);

}

//###############		LIGHTS / SHADOWS		##################################

function createLight () {
    const ambient = new THREE.AmbientLight( 0xffffff, 1 );
    scene.add( ambient );

    spotLight = new THREE.SpotLight( 0xffffff);
    spotLight.position.set( 15, 500, 1000 );
    spotLight.intensity = 5;
    spotLight.angle = 1;
    spotLight.penumbra = 1;
    spotLight.decay = 2;
    spotLight.distance = 2000;


    lightHelper = new THREE.SpotLightHelper( spotLight );
    scene.add( lightHelper );

    lightShadow(spotLight);


    scene.add( spotLight );
}

function shadow(obj){
    obj.castShadow = true ;
    obj.receiveShadow = true;
}

function lightShadow(spotLight){
    spotLight.castShadow = true;
    spotLight.shadow.camera.near = 10;
    spotLight.shadow.camera.far = 1000;
    spotLight.shadow.focus = 1;

    // shadowCameraHelper = new THREE.CameraHelper( spotLight.shadow.camera );
    // scene.add( shadowCameraHelper );
}

//###############		MESH		##################################

/*
Creates a MeshPhongMaterial for a given texture
textureName 	name of the used material
repeat_x 		repeat value in x
repeat_y		repeat value in y
*/
function createPhongMaterial(textureName, repeat_x, repeat_y){
    let loader = new THREE.TextureLoader();
    const texture = loader.load( textureName );
    if(repeat_x && repeat_y) {
        texture.wrapS = THREE.RepeatWrapping ;
        texture.wrapT = THREE.RepeatWrapping ;
        texture.repeat.set( repeat_x, repeat_y );
    }
    return new THREE.MeshPhongMaterial( { map: texture} );
}

function createRectangleMesh (x, y, z, material, rot_x, rot_y, rot_z) {
    let rectGeom = new THREE.BoxBufferGeometry( x, y, z );
    let rect = new THREE.Mesh( rectGeom, material );
    rect.rotation.x += rot_x ;
    rect.rotation.y += rot_y ;
    rect.rotation.z += rot_z ;
    return rect ;
}

function addToPosition (object, off_x, off_y, off_z) {
    object.position.x += off_x ;
    object.position.y += off_y ;
    object.position.z += off_z ;
    return object ;
}

//###############		ENVIRONMENT		##################################

function createPlane(){
    let floor;
    floor = createRectangleMesh(1000000,1,1000000,ground,0,0,0);
    addToPosition(floor,0,-35,0);
    floor.receiveShadow = true;
    floor.castShadow = false;
    scene.add(floor);
}

function initSky() {

    // Add Sky
    sky = new Sky();
    sky.scale.setScalar( 450000 );
    scene.add( sky );

    sun = new THREE.Vector3();

    effectController = {
        inclination: 0.1389, // elevation / inclination
        azimuth: 0.2364, // Facing front,
        exposure: renderer.toneMappingExposure
    };

    time();
}

function time() {
    const theta = Math.PI * ( effectController.inclination - 0.5 );
    const phi = 2 * Math.PI * ( effectController.azimuth - 0.5 );

    sun.x = Math.cos( phi );
    sun.y = Math.sin( phi ) * Math.sin( theta );
    sun.z = Math.sin( phi ) * Math.cos( theta );
    sky.material.uniforms[ "sunPosition" ].value.copy( sun );
    renderer.toneMappingExposure = effectController.exposure;
}

//###############		OBJECTS		##################################

function addObjects(){
    scene.add(truck);
    scene.add(house);
    scene.add(warehouse)
    render();
}

function createTruck(){

    const loader = new GLTFLoader(loadingManager);
    loader.load("../media/webGl/nissan/scene.gltf", function(obj){
        obj.scene.position.set(-20, -35, 300);

        //animation
        mixer = new THREE.AnimationMixer( obj.scene );
        action = mixer.clipAction( obj.animations[0] );
        action.timeScale = 2;  // animation speed / 2
        action.play();

        truck = obj.scene;

        truck.scale.set(0.1, 0.1, 0.1);

        //shadow
        truck.traverse( function(child){
            if( child.isMesh ) {
                meshes.push(child);
                shadow(child);
            }
            if(child.name == "ground_occlu")
                child.scale.set(0,0,0);
        });
    });

}

function createHouse(){

    const loader = new GLTFLoader(loadingManager);
    loader.load("../media/webGl/house/scene.gltf", function(obj){
        obj.scene.position.set(-20, -175, 300);

        //animation
        mixer2 = new THREE.AnimationMixer( obj.scene );
        house = obj.scene;

        house.scale.set(1.5,1.5,1.5);
        house.rotation.y = Math.PI;

        //shadow
        house.traverse( function(child){
            if( child.isMesh ) {
                meshes.push(child);
                shadow(child);
            }
        });
    });

}

function createWarehouse(){

    const loader = new GLTFLoader(loadingManager);
    loader.load("../media/webGl/warehouse/scene.gltf", function(obj){
        obj.scene.position.set(-20, -175, 300);

        //animation
        mixer3 = new THREE.AnimationMixer( obj.scene );
        warehouse = obj.scene;

        warehouse.scale.set(0.5, 0.5, 0.5);
        warehouse.position.set(0, 0, -10000);

        //shadow
        warehouse.traverse( function(child){
            if( child.isMesh ) {
                meshes.push(child);
                shadow(child);
            }
        });
    });

}
