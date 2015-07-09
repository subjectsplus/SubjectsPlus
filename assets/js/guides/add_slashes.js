//////////////////
// addslashes called inside guide save, above
// ///////////////

function addslashes(string) {

    return (string+'').replace(/([\\"'])/g, "\\$1").replace(/\0/g, "\\0");
}

