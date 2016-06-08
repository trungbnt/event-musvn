$(function() {
	$('.event_widget .owl-carousel').owlCarousel({
        loop: false,
        dots: false,
        nav: true,
        navText: ["<<",">>"],
        margin: 30,
        item: 1,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1
            }
        }
    });
});