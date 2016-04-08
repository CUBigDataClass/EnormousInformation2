/*
 * Javascript file for BigData Website
 *
 */

var whatsPoppinHeatMap = [
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
    },
  	// Lucile's
  	{
        "value": 5500,
        "latitude": 40.02,
        "longitude": -105.2779
    },
  	// Hapa
  	{
        "value": 7000,
        "latitude": 40.0178,
        "longitude": -105.2809
    }
];

$(window).on('load', function() {
  $('#mapContainer').jHERE({
        enable: false, /*Disable everything including interaction*/
        center: [40.018, -105.28],
    	  zoom: 17
    }).jHERE('heatmap', whatsPoppinHeatMap /*defined elsewhere*/, 'density');
});
