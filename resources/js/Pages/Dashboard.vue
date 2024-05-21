<template>
    <div id="map-container">
        <div id="map" ref="mapRef" />
    </div>
</template>

<script>
  import axios from 'axios';
    import Radar from 'radar-sdk-js';
  import { shallowRef, onMounted, onUnmounted, markRaw, reactive } from 'vue';

  export default {
    name: 'RadarMap',
    data() {
        return {
            mapPoints: []
        };
    },
    methods: {
        initializeMap() {
            axios
            .get('/api/google-maps/markers')
            .then((response) => {
                this.mapPoints = response.data.markers;

            });
        }
    },
    setup(props, { emit }) {
      const mapRef = shallowRef(null);
      const map = shallowRef(null);
      const localMapPoints = reactive([]);
      const markers = reactive([]);

      const loadMarkers = () => {
        axios
        .get('/api/google-maps/markers')
        .then((response) => {
            localMapPoints.push(...response.data.markers);
            localMapPoints.forEach(point => {
                const marker = new Radar.ui.marker({ color: '#007aff'})
                .setLngLat([point.lng, point.lat])
                .addTo(map.value);
                markers.push(marker);
            });
            emit('markerPoints', localMapPoints)
        })
      };


      const clearMarkers = () => {
        markers.forEach(marker => {
            marker.remove();
        });
        markers.length = 0;
        localMapPoints.length = 0;

        emit('markerPoints', localMapPoints);

      };

      onMounted(() => {
        Radar.initialize('prj_live_pk_4fa29c7ab8d33d669b2ccd9b034f51addc537621');

        // create a map
        map.value = markRaw(Radar.ui.map({
          container: mapRef.value,
          style: 'radar-default-v1',
          center: [-73.9911, 40.7342], // NYC
          zoom: 11
        }));

        map.value.on('click', function (e) {
            emit('location-selected', e.lngLat);
        });

        loadMarkers();
    });

      onUnmounted(() => {
        map.value?.remove();
      })

      return {
        map, mapRef, loadMarkers, clearMarkers
      };
    }
};
</script>

<style>
@import "radar-sdk-js/dist/radar.css";
</style>
