/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/pages/calendar.init.js":
/*!*********************************************!*\
  !*** ./resources/js/pages/calendar.init.js ***!
  \*********************************************/
/***/ (function() {

/*
Template Name: Borex - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: Calendar init js
*/

document.addEventListener("DOMContentLoaded", function () {
  var addEvent = new bootstrap.Modal(document.getElementById('event-modal'), {
    keyboard: false
  });
  document.getElementById('event-modal');
  var modalTitle = document.getElementById('modal-title');
  var formEvent = document.getElementById('form-event');
  var selectedEvent = null;
  var newEventData = null;
  var forms = document.getElementsByClassName('needs-validation');
  var selectedEvent = null;
  var newEventData = null;
  var eventObject = null;
  /* initialize the calendar */

  var date = new Date();
  var d = date.getDate();
  var m = date.getMonth();
  var y = date.getFullYear();
  var Draggable = FullCalendar.Draggable;
  var externalEventContainerEl = document.getElementById('external-events');

  // init dragable
  new Draggable(externalEventContainerEl, {
    itemSelector: '.external-event',
    eventData: function eventData(eventEl) {
      return {
        title: eventEl.innerText,
        start: new Date(),
        className: eventEl.getAttribute('data-class')
      };
    }
  });
  var defaultEvents = [{
    title: 'All Day Event',
    start: new Date(y, m, 1),
    className: 'bg-primary'
  }, {
    title: 'Long Event',
    start: new Date(y, m, d - 5),
    end: new Date(y, m, d - 2),
    className: 'bg-warning'
  }, {
    id: 999,
    title: 'Repeating Event',
    start: new Date(y, m, d - 3, 16, 0),
    allDay: false,
    className: 'bg-info'
  }, {
    id: 999,
    title: 'Repeating Event',
    start: new Date(y, m, d + 4, 16, 0),
    allDay: false,
    className: 'bg-primary'
  }, {
    title: 'Meeting',
    start: new Date(y, m, d, 10, 30),
    allDay: false,
    className: 'bg-success'
  }, {
    title: 'Lunch',
    start: new Date(y, m, d, 12, 0),
    end: new Date(y, m, d, 14, 0),
    allDay: false,
    className: 'bg-danger'
  }, {
    title: 'Birthday Party',
    start: new Date(y, m, d + 1, 19, 0),
    end: new Date(y, m, d + 1, 22, 30),
    allDay: false,
    className: 'bg-success'
  }, {
    title: 'Click for Google',
    start: new Date(y, m, 28),
    end: new Date(y, m, 29),
    url: 'http://google.com/',
    className: 'bg-dark'
  }];
  var draggableEl = document.getElementById('external-events');
  var calendarEl = document.getElementById('calendar');
  function addNewEvent(info) {
    addEvent.show();
    formEvent.classList.remove("was-validated");
    formEvent.reset();
    selectedEvent = null;
    modalTitle.innerText = 'Add Event';
    newEventData = info;
  }
  function getInitialView() {
    if (window.innerWidth >= 768 && window.innerWidth < 1200) {
      return 'timeGridWeek';
    } else if (window.innerWidth <= 768) {
      return 'listMonth';
    } else {
      return 'dayGridMonth';
    }
  }
  var calendar = new FullCalendar.Calendar(calendarEl, {
    timeZone: 'local',
    editable: true,
    droppable: true,
    selectable: true,
    initialView: getInitialView(),
    themeSystem: 'bootstrap',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
    },
    // responsive
    windowResize: function windowResize(view) {
      var newView = getInitialView();
      calendar.changeView(newView);
    },
    eventDidMount: function eventDidMount(info) {
      if (info.event.extendedProps.status === 'done') {
        // Change background color of row
        info.el.style.backgroundColor = 'red';

        // Change color of dot marker
        var dotEl = info.el.getElementsByClassName('fc-event-dot')[0];
        if (dotEl) {
          dotEl.style.backgroundColor = 'white';
        }
      }
    },
    eventClick: function eventClick(info) {
      document.getElementById("btn-delete-event").style.display = "block";
      addEvent.show();
      formEvent.reset();
      document.getElementById("event-title").value[0] = "";
      selectedEvent = info.event;
      document.getElementById("event-title").value = selectedEvent.title;
      document.getElementById('event-category').value = selectedEvent.classNames[0];
      newEventData = null;
      modalTitle.innerText = 'Edit Event';
      newEventData = null;
    },
    dateClick: function dateClick(info) {
      document.getElementById("btn-delete-event").style.display = "none";
      addNewEvent(info);
    },
    events: defaultEvents
  });
  calendar.render();

  /*Add new event*/
  // Form to add new event

  formEvent.addEventListener('submit', function (ev) {
    ev.preventDefault();
    var updatedTitle = document.getElementById("event-title").value;
    var updatedCategory = document.getElementById('event-category').value;

    // validation
    if (forms[0].checkValidity() === false) {
      forms[0].classList.add('was-validated');
    } else {
      if (selectedEvent) {
        selectedEvent.setProp("title", updatedTitle);
        selectedEvent.setProp("classNames", [updatedCategory]);
      } else {
        var newEvent = {
          title: updatedTitle,
          start: newEventData.date,
          allDay: newEventData.allDay,
          className: updatedCategory
        };
        calendar.addEvent(newEvent);
      }
      addEvent.hide();
    }
  });
  document.getElementById("btn-delete-event").addEventListener("click", function (e) {
    if (selectedEvent) {
      selectedEvent.remove();
      selectedEvent = null;
      selectedEvent.hide();
    }
  });
  document.getElementById("btn-new-event").addEventListener("click", function (e) {
    document.getElementById("btn-delete-event").style.display = "none";
    addNewEvent({
      date: new Date(),
      allDay: true
    });
  });
});

/***/ }),

/***/ "./resources/scss/bootstrap.scss":
/*!***************************************!*\
  !*** ./resources/scss/bootstrap.scss ***!
  \***************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./resources/scss/icons.scss":
/*!***********************************!*\
  !*** ./resources/scss/icons.scss ***!
  \***********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./resources/scss/app.scss":
/*!*********************************!*\
  !*** ./resources/scss/app.scss ***!
  \*********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	!function() {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = function(result, chunkIds, fn, priority) {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var chunkIds = deferred[i][0];
/******/ 				var fn = deferred[i][1];
/******/ 				var priority = deferred[i][2];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every(function(key) { return __webpack_require__.O[key](chunkIds[j]); })) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	!function() {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/assets/js/pages/calendar.init": 0,
/******/ 			"assets/css/app": 0,
/******/ 			"assets/css/icons": 0,
/******/ 			"assets/css/bootstrap": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = function(chunkId) { return installedChunks[chunkId] === 0; };
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = function(parentChunkLoadingFunction, data) {
/******/ 			var chunkIds = data[0];
/******/ 			var moreModules = data[1];
/******/ 			var runtime = data[2];
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some(function(id) { return installedChunks[id] !== 0; })) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunksymox"] = self["webpackChunksymox"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	}();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["assets/css/app","assets/css/icons","assets/css/bootstrap"], function() { return __webpack_require__("./resources/js/pages/calendar.init.js"); })
/******/ 	__webpack_require__.O(undefined, ["assets/css/app","assets/css/icons","assets/css/bootstrap"], function() { return __webpack_require__("./resources/scss/bootstrap.scss"); })
/******/ 	__webpack_require__.O(undefined, ["assets/css/app","assets/css/icons","assets/css/bootstrap"], function() { return __webpack_require__("./resources/scss/icons.scss"); })
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["assets/css/app","assets/css/icons","assets/css/bootstrap"], function() { return __webpack_require__("./resources/scss/app.scss"); })
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;