const GRID = 20;

let hallways = [];

let waypoints = [];

let locations = [];

let connections = [];

let currentTool = "";

let hallwayStart = null;

let selectedLocation = null;

let draggingLocation = null;

let selectedWaypoint = null;

const canvas = document.getElementById("designerCanvas");

const ctx = canvas.getContext("2d");