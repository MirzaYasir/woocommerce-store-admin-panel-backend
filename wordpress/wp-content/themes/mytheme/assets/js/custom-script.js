jQuery(document).ready(function($) {
    // Initialize lightGallery
    $('#myCarousel .carousel-inner').lightGallery({
        selector: '.carousel-item a',
        mode: 'lg-slide',
        download: false,
        counter: false
    });
});
