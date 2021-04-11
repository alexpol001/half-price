$(document).ready(function () {
    'use strict';

    $(document).ready(function(){
        $(".accordion-item .title").on('click', function () {
            if ($(this).hasClass('active')) {
                closeAccordion($(this));
            } else {
                openAccordion($(this));
            }
        });
    });

    function closeAllAccordion() {
        $(".accordion-item .title").each(function () {
            closeAccordion($(this));
        })
    }

    function closeAccordion(accordion_title) {
        let content = accordion_title.siblings('.content');
        accordion_title.removeClass('active');
        content.slideUp();
    }

    function openAccordion(accordion_title) {
        closeAllAccordion();
        let content = accordion_title.siblings('.content');
        accordion_title.addClass('active');
        content.slideDown();
    }
});
