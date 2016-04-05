/* Javascript
 *
 * See http://jhere.net/docs.html for full documentation
 */

var heatMapData = [
  	// The Walrus
    {
        "value": 4000,
        "latitude": 40.01661,
        "longitude": -105.281
    },
  	// The Rio
  	{
        "value": 8000,
        "latitude": 40.01666,
        "longitude": -105.2808
    }
];

$(window).on('load', function() {
  $('#mapContainer').jHERE({
        enable: false, /*Disable everything including interaction*/
        center: [40.016, -105.28],
    	zoom: 17
    }).jHERE('heatmap', heatMapData /*defined elsewhere*/, 'density');
});
