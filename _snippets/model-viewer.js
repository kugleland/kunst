(function ($) {
    'use strict';


    var ObjectModelViewer = {

        init: function (viewer) {

            this.viewer = viewer;
            this._modelViewer = document.getElementById(viewer);

            if (!this._modelViewer) {
                return;
            }

            this.resizeTimer = false;
            this.isShowingDimensions = true;
            this._modelWrap = this._modelViewer.parentElement;
            this._dimensionsCanvas = this._modelViewer.querySelector('.dimensions-canvas');
            this._viewInRoomBtn = this._modelWrap.querySelector('.model-viewer-view-in-room');
            this._viewInRoomSection = document.querySelector('#view-in-room');
            this.midpointMap = [];

            this.eventsHandler = {};
            this.eventsHandler.loadHandler = this.loadHandler.bind(this);
            this.eventsHandler.windowResizeHandler = this.windowResizeHandler.bind(this);
            this.eventsHandler.cameraChangeHandler = this.cameraChangeHandler.bind(this);
            this.eventsHandler.dimensionsToggleHandler = this.dimensionsToggleHandler.bind(this);
            this.eventsHandler.viewInRoomBtnClickHandler = this.viewInRoomBtnClickHandler.bind(this);

            window.addEventListener('resize', this.eventsHandler.windowResizeHandler, {passive: true});
            this._modelViewer.addEventListener('load', this.eventsHandler.loadHandler);
            this._modelViewer.addEventListener('camera-change', this.eventsHandler.cameraChangeHandler);

            this._modelWrap.querySelector('.model-viewer-dimensions-toggle[data-status="hide"]').addEventListener('click', this.eventsHandler.dimensionsToggleHandler);
            this._modelWrap.querySelector('.model-viewer-dimensions-toggle[data-status="show"]').addEventListener('click', this.eventsHandler.dimensionsToggleHandler);
        },

        loadHandler: function (e) {
            var self = this;

            $(document.body).trigger('model_viewer_loaded', [this.viewer, this._modelViewer])

            this.updateHotspots();

            setTimeout(function () {
                self.updateDimensions();
                self._modelViewer.classList.add('model-loaded');

                if (self._modelViewer.canActivateAR) {
                    self._viewInRoomSection.querySelector('.view-in-room__support').style.display = 'block';
                    self._viewInRoomSection.querySelector('.view-in-room__not-support').style.display = 'none';
                } else {
                    self._viewInRoomSection.querySelector('.view-in-room__support').style.display = 'none';
                    self._viewInRoomSection.querySelector('.view-in-room__not-support').style.display = 'block';
                }

                if (self._viewInRoomBtn && self._modelViewer.canActivateAR) {
                    self._viewInRoomBtn.classList.remove('display-none');
                    self._viewInRoomBtn.addEventListener('click', self.eventsHandler.viewInRoomBtnClickHandler);
                }
            }, 10);
        },

        cameraChangeHandler: function (e) {
            this.updateDimensions();
        },

        viewInRoomBtnClickHandler: function (e) {
            e.preventDefault();

            this._modelViewer.activateAR();
        },

        windowResizeHandler: function (e) {
            var self = this;

            clearTimeout(this.resizeTimer);

            this.resizeTimer = setTimeout(function () {
                self.updateDimensions();
            }, 10);
        },

        dimensionsToggleHandler: function (e) {
            if (e.currentTarget.dataset.status === 'hide') {
                e.currentTarget.classList.add('display-none');
                e.currentTarget.nextElementSibling.classList.remove('display-none');

                this.isShowingDimensions = false;
            } else if (e.currentTarget.dataset.status === 'show') {
                e.currentTarget.classList.add('display-none');
                e.currentTarget.previousElementSibling.classList.remove('display-none');

                this.isShowingDimensions = true;
            }

            if (!this.isShowingDimensions) {
                const hotspots = document.querySelectorAll('div.model-viewer-midpoint, div.model-viewer-endpoint');

                for (const hotspot of hotspots) {
                    hotspot.classList.add('hide-midpoint');
                }
            }

            this.updateDimensions();
        },

        updateHotspots: function () {
            var position;

            const center = this._modelViewer.getCameraTarget();
            const size = this._modelViewer.getDimensions();
            const x2 = size.x / 2;
            const y2 = size.y / 2;
            const z2 = size.z / 2;

            const endpointElements = this._modelViewer.querySelectorAll('div.model-viewer-endpoint');

            for (const element of endpointElements) {
                position = element.dataset.position.split(' ');
                position = position.map(Number);
                position[0] *= x2;
                position[1] *= y2;
                position[2] *= z2;
                position[0] = center.x + position[0];
                position[1] = center.y + position[1];
                position[2] = center.z + position[2];
                position = position.join(' ');

                this._modelViewer.updateHotspot({
                    name: element.slot,
                    position: position
                });
            }

            const midpointElements = this._modelViewer.querySelectorAll('div.model-viewer-midpoint');

            for (const element of midpointElements) {
                position = '';
                position = element.dataset.position.split(' ');
                position = position.map(Number);

                var text = (
                    ((1 - Math.abs(position[0])) * (size.x * 1000).toFixed(0)) +
                    ((1 - Math.abs(position[1])) * (size.y * 1000).toFixed(0)) +
                    ((1 - Math.abs(position[2])) * (size.z * 1000).toFixed(0))
                ) + 'mm';
                element.textContent = text;

                this.midpointMap[element.slot] = {value: text, element: element};

                position[0] *= x2;
                position[1] *= y2;
                position[2] *= z2;
                position[0] = center.x + position[0];
                position[1] = center.y + position[1];
                position[2] = center.z + position[2];
                position = position.join(' ');

                this._modelViewer.updateHotspot({
                    name: element.slot,
                    position: position
                });
            }

        },

        updateDimensions: function () {
            if (0 === Object.keys(this.midpointMap).length) {
                return;
            }

            var cxt = this._dimensionsCanvas.getContext('2d'),
                midPoints = [], endPointLocations = [];

            cxt.canvas.width = this._modelViewer.clientWidth;
            cxt.canvas.height = this._modelViewer.clientHeight;
            cxt.canvas.style.width = cxt.canvas.width + 'px';
            cxt.canvas.style.height = cxt.canvas.height + 'px';

            cxt.lineWidth = 0;
            cxt.strokeStyle = 'rgba(255, 255, 255, 0.8)';
            cxt.setLineDash([5, 5]);

            cxt.clearRect(0, 0, cxt.canvas.width, cxt.canvas.height);

            if (!this.isShowingDimensions) {
                this._dimensionsCanvas.classList.add('display-none');
                return;
            }

            this._dimensionsCanvas.classList.remove('display-none');

            const hotspots = this._modelViewer.shadowRoot.querySelectorAll('div.annotation-wrapper')

            for (const hotspot of hotspots) {
                if (!hotspot.children[0].children[0].name.startsWith("hotspot-midpoint")) {
                    continue;
                }

                midPoints.push({
                    name: hotspot.firstChild.firstChild.name,
                    zIndex: parseInt(hotspot.style.zIndex),
                    shadowElement: hotspot
                });
            }

            midPoints.sort(function (a, b) {
                return (a.zIndex > b.zIndex) ? -1 : 1;
            });

            for (var i = 0; i < midPoints.length; ++i) {
                midPoints[i].value = this.midpointMap[midPoints[i].name].value;
                midPoints[i].element = this.midpointMap[midPoints[i].name].element;
                midPoints[i].show = false;
            }

            var midPointIndiciesToShow = [];

            for (var i = 0; i < 3; i++) {
                for (var j = 0; j < midPoints.length; j++) {
                    if (midPoints[j].value in midPointIndiciesToShow) {
                        continue;
                    }
                    midPointIndiciesToShow[midPoints[j].value] = j;
                    break;
                }
            }

            for (var midPointIndexToShow in midPointIndiciesToShow) {
                midPoints[midPointIndiciesToShow[midPointIndexToShow]].show = true;
            }

            for (var i = 0; i < midPoints.length; ++i) {
                if (midPoints[i].show) {
                    midPoints[i].element.classList.remove('hide-midpoint');
                } else {
                    midPoints[i].element.classList.add('hide-midpoint');
                }
            }

            for (var hotspot of hotspots) {
                if (!hotspot.children[0].children[0].name.startsWith("hotspot-endpoint")) {
                    continue;
                }

                endPointLocations.push(hotspot.style.transform);
            }

            if (endPointLocations.length !== 8) {
                return;
            }

            for (var i = 0; i < endPointLocations.length; i++) {
                endPointLocations[i] = endPointLocations[i].substr(endPointLocations[i].lastIndexOf('('))
                    .replace(/[^\d.-\s]/g, '')
                    .split(' ').map(parseFloat);
            }

            cxt.beginPath();

            cxt.moveTo(endPointLocations[0][0], endPointLocations[0][1]);
            cxt.lineTo(endPointLocations[4][0], endPointLocations[4][1]);

            cxt.moveTo(endPointLocations[1][0], endPointLocations[1][1]);
            cxt.lineTo(endPointLocations[5][0], endPointLocations[5][1]);

            cxt.moveTo(endPointLocations[2][0], endPointLocations[2][1]);
            cxt.lineTo(endPointLocations[6][0], endPointLocations[6][1]);

            cxt.moveTo(endPointLocations[3][0], endPointLocations[3][1]);
            cxt.lineTo(endPointLocations[7][0], endPointLocations[7][1]);

            cxt.moveTo(endPointLocations[0][0], endPointLocations[0][1]);
            cxt.lineTo(endPointLocations[1][0], endPointLocations[1][1]);
            cxt.lineTo(endPointLocations[3][0], endPointLocations[3][1]);
            cxt.lineTo(endPointLocations[2][0], endPointLocations[2][1]);
            cxt.lineTo(endPointLocations[0][0], endPointLocations[0][1]);

            cxt.moveTo(endPointLocations[4][0], endPointLocations[4][1]);
            cxt.lineTo(endPointLocations[5][0], endPointLocations[5][1]);
            cxt.lineTo(endPointLocations[7][0], endPointLocations[7][1]);
            cxt.lineTo(endPointLocations[6][0], endPointLocations[6][1]);
            cxt.lineTo(endPointLocations[4][0], endPointLocations[4][1]);

            cxt.stroke();
        }
    };

    var arSection = {

        init: function () {

            this.eventsHandler = {};
            this.eventsHandler.modelViewerLoadedHandler = this.modelViewerLoadedHandler.bind(this);
            this.eventsHandler.viewInRoomButtonClickHandler = this.viewInRoomButtonClickHandler.bind(this);

            this.modelPage = Object.assign({}, ObjectModelViewer);
            this.modelModal = Object.assign({}, ObjectModelViewer);

            this.modelPage.init('model-viewer');
            this.modelModal.init('model-viewer-modal');

            if (window.location.hash === '#ar') {
                var _modelViewer = this.modelPage._modelViewer;

                window.addEventListener('load', function () {
                    //window.theme.modal.showModal('#fullscreen-model-modal');
                    //$(document.body).on('model_viewer_loaded', this.eventsHandler.modelViewerLoadedHandler);

                    if (!_modelViewer.canActivateAR) {
                        alert('AR is not supported on this device');
                    } else {
                        _modelViewer.activateAR();
                    }
                });
            }

            $(document.body).on('click', '.view-in-room-button', this.eventsHandler.viewInRoomButtonClickHandler);
        },

        modelViewerLoadedHandler: function (e, viewer, _modelViewer) {
            if (viewer === 'model-viewer-modal') {
                if (!_modelViewer.canActivateAR) {
                    alert('AR is not supported on this device');
                } else {
                    _modelViewer.activateAR();
                }
            }
        },

        viewInRoomButtonClickHandler: function (e) {
            e.preventDefault();

            if (this.modelPage._modelViewer.canActivateAR) {
                this.modelPage._modelViewer.activateAR();
            } else {
                gsap.to(window, {
                    duration: 1.2,
                    scrollTo: {
                        y: '#view-in-room'
                    }
                });
            }
        }

    };

    arSection.init();

}(jQuery));
