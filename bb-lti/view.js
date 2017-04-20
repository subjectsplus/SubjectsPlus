/**
 * Created by acarrasco on 4/20/2017.
 */

var isInIFrame = function () {
    try {
        return window.self !== window.top;
    } catch (e) {
        return true;
    }
};

console.log(isInIFrame());
if (isInIFrame()){
    $('#header').hide();
    $('#push').hide();
}