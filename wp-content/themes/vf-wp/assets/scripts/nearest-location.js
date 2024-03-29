window.onload = function() {

    // Configure an object of your locations to detect
    let vfLocationNearestLocations = {
      default: {
        name: "Heidelberg (default)",
        latlon: "49.40768, 8.69079"
      },
      barcelona: {
        name: "Barcelona",
        latlon: "41.38879, 2.15899"
      },
      grenoble: {
        name: "Grenoble",
        latlon: "45.16667, 5.71667"
      },
      hamburg: {
        name: "Hamburg",
        latlon: "53.57532, 10.01534"
      },
      heidelberg: {
        name: "Heidelberg",
        latlon: "49.40768, 8.69079"
      },
      hinxton: {
        name: "EMBL-EBI Hinxton",
        latlon: "52.2, 0.11667"
      },
      rome: {
        name: "Rome",
        latlon: "41.89193, 12.51133"
      }
    }
    // Bootstrap location detection
    vfLocationNearest(vfLocationNearestLocations);
  };