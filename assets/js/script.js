function toggleMobileMenu() {

    const navigation =
        document.getElementById("mobileNavigation");

    if (!navigation) {
        return;
    }

    navigation.classList.toggle("show");
}


// ==========================================
// STOP BREAKING NEWS ANIMATION ON HOVER
// ==========================================

document.addEventListener("DOMContentLoaded", function () {

    const breakingTrack =
        document.querySelector(".breaking-track");

    if (breakingTrack) {

        breakingTrack.addEventListener(
            "mouseenter",
            function () {
                this.style.animationPlayState = "paused";
            }
        );

        breakingTrack.addEventListener(
            "mouseleave",
            function () {
                this.style.animationPlayState = "running";
            }
        );

    }

});