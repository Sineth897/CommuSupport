
class MapRoute {

    static init() {
        console.log('MapRoute.init()');
    }

    static async calculateDistance(point1, point2) {

        const A = new google.maps.LatLng(point1.lat, point1.lng);
        const B = new google.maps.LatLng(point2.lat, point2.lng);

        const service = new google.maps.DistanceMatrixService();

        let result = [];

        await service.getDistanceMatrix({
            origins: [A],
            destinations: [B],
            travelMode: google.maps.TravelMode.DRIVING,
            unitSystem: google.maps.UnitSystem.METRIC,
            avoidHighways: false,
            avoidTolls: false,
        }, async (response, status) => {
            if (status !== "OK") {
                results.push("Error: " + status);
            } else {
                const originList = await response.originAddresses;
                const destinationList = await response.destinationAddresses;
                for (let i = 0; i < originList.length; i++) {
                    const results = response.rows[i].elements;
                    for (let j = 0; j < results.length; j++) {
                        const element = results[j];
                        const distance = element.distance.text;
                        const duration = element.duration.text;
                        const from = originList[i];
                        const to = destinationList[j];
                        result.push({distance, duration, from, to});
                    }
                }

            }
        });
        return result;
    }

    static async calculateMultipleDistance(points) {

        // const =
    }

    static async showRoute(point1, point2,div) {

        const A = new google.maps.LatLng(point1.lat, point1.lng);
        const B = new google.maps.LatLng(point2.lat, point2.lng);

        const map = new google.maps.Map(div, {
            center: {lat: 6.9271, lng: 79.8612},
            zoom: 8,
        });

        const directionsService = new google.maps.DirectionsService();

        const directionsRenderer = new google.maps.DirectionsRenderer({
            map: map,
            polylineOptions: {
                strokeColor: "#000000",
                strokeWeight: 3,
            }
        });

        return await directionsService.route({
            origin: A,
            destination: B,
            travelMode: google.maps.TravelMode.DRIVING,
        }, async (response, status) => {
            if (status === "OK") {
               directionsRenderer.setDirections(response);

               return  await response;

            } else {
                return "Directions request failed due to " + status;
            }

        });

    }

}

export default MapRoute;