$(document).ready(function() {
    // Preloader
    $(window).on('load', function() {
        var $preloader = $('#page-preloader'),
                $spinner = $preloader.find('.spinner');
        $spinner.fadeOut();
        $preloader.delay(350).fadeOut(800);
    });

    // Hovers in dream team for touch screen
    if (Modernizr.touch) {
        $(document).on('touchend', '.wrap-dream-team .list-dream-team .team-item', function() {
            $('.wrap-dream-team .list-dream-team .team-item').removeClass('active');
            $(this).addClass('active');
        });
    } else {
        $('.wrap-dream-team .list-dream-team .team-item').hover(function() {
            $(this).find('.mask').css('opacity', 1);
        }, function() {
            $(this).find('.mask').css('opacity', 0);
        });
    }

    // Fancybox 
    $(".fancybox").fancybox();

    
    /* =============================================== */
    /* ======= Accordion panel button collapse ======= */
    /* =============================================== */
    function toggleIcon(e) {
        $(e.target)
                .prev('.panel-heading')
                .toggleClass('active')
                .find('.fa')
                .toggleClass('fa-plus-circle fa-minus-circle');
    }
    $('#accordion-two').on('hidden.bs.collapse shown.bs.collapse', toggleIcon);
    function toggleActive(e) {
        $(e.target)
                .prev('.panel-heading')
                .toggleClass('active')
    }
    $('#accordion-one').on('hidden.bs.collapse shown.bs.collapse', toggleActive);

    /* <!-- =============================================== */
    /* <!-- ======= Isotope ======= -->*/
    /* <!-- =============================================== --> */
    var $container = $('#gallery-items');

    $(window).load(function() {
        $container.isotope({
//		    resizable: false, // disable normal resizing
            transitionDuration: '0.65s',
            masonry: {
                columnWidth: $container.find('.gallery-item:not(.wide)')[0]
            }
        });

        $(window).resize(function() {
            $container.isotope('layout');
        });
    });

    // filter items on button click
    $('#filters').on('click', 'button', function(e) {
        $(e.target).toggleClass('active').siblings().removeClass("active");
        var filterValue = $(this).attr('data-filter');
        $container.isotope({filter: filterValue});
    });

    /* <!-- =============================================== --> */
    /* <!-- =============== Carousels ==================== --> */
    /* <!-- =============================================== -->  */

    $("#testimonials-carousel").owlCarousel({
        singleItem: true,
        autoPlay: true,
        navigation: true,
        navigationText: [, ]
    });
    $("#twitter-carousel").owlCarousel({
        singleItem: true,
        autoPlay: true,
        transitionStyle: 'goDown'
    });
    $("#team-carousel").owlCarousel({
        items: 7,
        autoPlay: true,
        navigation: false
    });

    /* <!-- =============================================== --> */
    /* <!-- ========== Menu Links Scroll ===+============== --> */
    /* <!-- =============================================== -->  */
    $('.scroll').click(function(e) {
        var off = -79;
        var target = this.hash;
        if ($(target).offset().top == 0) {
            off = 0;
        }
        $('html,body').scrollTo(target, 800, {
            offset: off,
            easing: 'easeInOutExpo'
        });
        e.preventDefault();
        //   ---- dissapearing menu on click
        if ($('.navbar-collapse').hasClass('in')) {
            $('.navbar-collapse').removeClass('in').addClass('collapse');
        }
    });

    /* <!-- =============================================== --> */
    /* <!-- ===============  Scrollspy fix ================ --> */
    /* <!-- =============================================== --> */
    $(window).on('load', function() {
        var $body = $('body'),
                $navtop = $('#nav'),
                offset = $navtop.outerHeight();
        // Enable scrollSpy with correct offset based on height of navbar
        $body.scrollspy({
            target: '#nav',
            offset: offset
        });
        // function to do the tweaking
        function fixSpy() {
            // grab a copy the scrollspy data for the element
            var data = $body.data('bs.scrollspy');
            // if there is data, lets fiddle with the offset value
            if (data) {
                // get the current height of the navbar
                offset = $navtop.outerHeight();
                // change the data's offset option to match
                data.options.offset = offset
                // now stick it back in the element
                $body.data('bs.scrollspy', data);
                // and finally refresh scrollspy
                $body.scrollspy('refresh');
            }
        }
        // Now monitor the resize events and make the tweaks
        var resizeTimer;
        $(window).resize(function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(fixSpy, 200);
        });
    });

    /* <!-- =============================================== --> */
    /* <!-- ============ Prarallax =========== --> */
    /* <!-- =============================================== -->  */
    if (!Modernizr.touch) {
//        $('.parallax-section-1').parallax("50%", 0.5);
//        $('.parallax-section-2').parallax("50%", 0.5);
//        $('.parallax-section-3').parallax("50%", 0.5);
//        $('.wrap-features').parallax("50%", 0.5);
//        $('.wrap-counters').parallax("50%", 0.5);
//        $('.wrap-rates').parallax("50%", 0.5);
//        $('.wrap-programs').parallax("50%", 0.5);
//        $('#programs').parallax("50%", 0.5);

    }

    /* <!-- =============================================== --> */
    /* <!-- ============ Progress Bar Animation =========== --> */
    /* <!-- =============================================== -->  */
    $('.skills').waypoint(function() {
        $('.skills-animated').each(function() {
            var persent = $(this).attr('data-percent');
            $(this).find('.progress').animate({
                width: persent + '%'
            }, 300);
        });
    }, {
        offset: '100%',
        triggerOnce: true
    });

    /* <!-- =============================================== --> */
    /* <!-- ============ Search Animation =========== --> */
    /* <!-- =============================================== -->  */
    $('#search-open, #search-close').on('click', function(e) {
        e.preventDefault();
        $('.relative-nav-container').toggleClass('open-search');
        $('.navbar-search').toggleClass('open');
    });

    /* <!-- =============================================== --> */
    /* <!-- ============ Herader Animation =========== --> */
    /* <!-- =============================================== -->  */
    $(window).scroll(function() {
        if ($(this).scrollTop() > 15) {
            $('nav.tea_homepage').addClass("navbar-alt")
        } else {
            $('nav.tea_homepage').removeClass("navbar-alt")
        }
    });
    if ($(window).width() < 769) {
        $('#nav').removeClass('navbar-default').addClass('navbar-inverse');
        $('.floated .with-border').removeClass('with-border');
    }
    /* <!-- =============================================== --> */
    /* <!-- ============ Tooltip  =========== --> */
    /* <!-- =============================================== -->  */
    $("[data-toggle='tooltip']").tooltip();
    $('[data-toggle="popover"]').popover({
        container: "body",
        placement: "top",
        trigger: "hover",
        delay: {show: 0, hide: 500},
        html: true,
        content: function() {
            var content = $(this).attr("data-popover-content");
            return $(content).children(".popover-body").html();
        },
    });

    /* <!-- =============================================== --> */
    /* <!-- === Switch monthly/annual pricing tables  ===== --> */
    /* <!-- =============================================== -->  */
    //switch from monthly to annual pricing tables
    bouncy_filter($('.cd-pricing-container'));

    function bouncy_filter(container) {
        container.each(function() {
            var pricing_table = $(this);
            var filter_list_container = pricing_table.find('.pricing-switcher'),
                    filter_radios = filter_list_container.find('input[type="radio"]'),
                    pricing_table_wrapper = pricing_table.find('.cd-pricing-wrapper');
            //store pricing table items
            var table_elements = {};
            filter_radios.each(function() {
                var filter_type = $(this).val();
                table_elements[filter_type] = pricing_table_wrapper.find('li[data-type="' + filter_type +
                        '"]');
            });
            //detect input change event
            filter_radios.on('change', function(event) {
                event.preventDefault();
                //detect which radio input item was checked
                var selected_filter = $(event.target).val();
                //give higher z-index to the pricing table items selected by the radio input
                show_selected_items(table_elements[selected_filter]);
                //rotate each cd-pricing-wrapper
                //at the end of the animation hide the not-selected pricing tables and rotate back the .cd-pricing-wrapper
                if (!Modernizr.cssanimations) {
                    hide_not_selected_items(table_elements, selected_filter);
                    pricing_table_wrapper.removeClass('is-switched');
                } else {
                    pricing_table_wrapper.addClass('is-switched').eq(0).one(
                            'webkitAnimationEnd oanimationend msAnimationEnd animationend', function() {
                                hide_not_selected_items(table_elements, selected_filter);
                                pricing_table_wrapper.removeClass('is-switched');
                                //change rotation direction if .cd-pricing-list has the .cd-bounce-invert class
                                if (pricing_table.find('.cd-pricing-list').hasClass('cd-bounce-invert'))
                                    pricing_table_wrapper.toggleClass('reverse-animation');
                            });
                }
            });
        });
    }

    function show_selected_items(selected_elements) {
        selected_elements.addClass('is-selected');
    }

    function hide_not_selected_items(table_containers, filter) {
        $.each(table_containers, function(key, value) {
            if (key !== filter) {
                $(this).removeClass('is-visible is-selected').addClass('is-hidden');
            } else {
                $(this).addClass('is-visible').removeClass('is-hidden is-selected');
            }
        });
    }

    /* <!-- =============================================== --> */
    /* <!-- === Switch monthly/annual pricing tables  ===== --> */
    /* <!-- =============================================== --> */
    // Variable to hold scroll type
    var slideDrag,
            // Width of .scroll-content ul
            slideWidth = $(".scroll-slider").width(),
            // Speed of animation in ms
            slideSpeed = 400;

    // Initialize sliders
    $(".scroll-slider").slider({
        animate: slideSpeed,
        start: checkType,
        slide: doSlide,
        max: slideWidth
    });


    function checkType(e) {
        slideDrag = $(e.originalEvent.target).hasClass("ui-slider-handle");
    }

    function doSlide(e, ui) {
//	    var target = $(e.target).prev(".scroll-content"),
        var target = $(".scroll-content"),
                maxScroll = target.prop("scrollWidth") - target.width();

        // Was it a click or drag?
        if (slideDrag === true) {
            // User dragged slider head, match position
            target.prop({scrollLeft: ui.value * (maxScroll / slideWidth)});
        }
        else {
            // User clicked on slider itself, animate to position
            target.stop().animate({
                scrollLeft: ui.value * (maxScroll / slideWidth)
            }, slideSpeed);
        }
    }

    // Popovers for map 
    $('.wrap-map-item .map-marker').popover({
        container: ".wrap-map-item",
        placement: "top",
        trigger: "hover",
        delay: {"hide": 2000},
        html: true,
        content: function() {
            var content = $(this).attr("data-popover-content");
            return $(content).children(".popover-body").html();
        }
    });



    /* <!-- =============================================== --> */
    /* <!-- ===                 Graphs                ===== --> */
    /* <!-- =============================================== --> */

    // Graph
//    var waypointGraph = new Waypoint({
//        element: document.getElementById('graph-line'),
//        handler: function(direction) {
//            $('#graph-line').highcharts({
//                chart: {
//                    type: 'area'
//                },
//                title: {
//                    text: ''
//                },
//                subtitle: {
//                    text: ''
//                },
//                xAxis: {
//                    gridLineDashStyle: 'Dash',
//                    gridLineWidth: 1,
//                    categories: [' ', ' ', ' ', ' ', ' ', ' ', ' '],
//                    title: {
//                        enabled: false
//                    }
//                },
//                yAxis: {
//                    gridLineDashStyle: 'Dash',
//                    title: {
//                        text: ' '
//                    },
//                    labels: {
//                        style: {"color": "#ccced6"}
//                    }
//                },
//                tooltip: {
//                    shared: false,
//                    useHTML: true,
//                    backgroundColor: '#333333',
//                    borderColor: '#333',
//                    borderRadius: '5',
//                    borderWidth: '0',
//                    headerFormat: '<table>',
//                    pointFormat: '<tr>' +
//                            '<td style="color: #fff; font-family: Raleway; ">{point.y}K</td></tr>',
//                    footerFormat: '</table>',
//                    valueDecimals: 2
//                },
//                plotOptions: {
//                    area: {
//                        stacking: 'normal',
//                        lineColor: '#cacddc',
//                        lineWidth: 2,
//                        marker: {
//                            lineWidth: 2,
//                            lineColor: '#cacddc'
//                        }
//                    }
//                },
//                credits: {
//                    enabled: false
//                },
//                series: [{
//                        name: 'FIRST',
//                        showInLegend: false,
//                        data: [11, 26, 15, 43, 38, 55, 79],
//                        color: '#fff',
//                        fillColor: {
//                            linearGradient: {x1: 0, x2: 0, y1: 0, y2: 1},
//                            stops: [
//                                [0, '#e7e8ef'],
//                                [1, '#ffffff']
//                            ]
//                        },
//                        marker: {
//                            radius: 6
//                        }
//                    }, {
//                        name: 'SECOND',
//                        showInLegend: false,
//                        data: [16, 22, 57, 36, 58, 76, 84],
//                        color: '#fff',
//                        lineColor: '#27ad60',
//                        fillColor: {
//                            linearGradient: {x1: 1, x2: 1, y1: 0, y2: 1},
//                            stops: [
//                                [0, '#98d2b4'],
//                                [1, '#fff']
//                            ]
//                        },
//                        marker: {
//                            lineWidth: 2,
//                            lineColor: '#27ad60',
//                            radius: 6,
//                            symbol: 'circle'
//                        }
//
//                    }]
//            });
//            this.destroy();
//        },
//        offset: "100%"
//    });

//    var waypointGoogle = new Waypoint({
//        element: document.getElementById('google-graph'),
//        handler: function() {
//            // Google-graph
//            $('#google-graph').highcharts({
//                chart: {
//                    zoomType: 'xy'
//                },
//                title: {
//                    text: ' '
//                },
//                subtitle: {
//                    text: ' '
//                },
//                xAxis: [{
//                        gridLineDashStyle: 'Dash',
//                        gridLineWidth: 1,
//                        categories: [' ', ' ', ' ', ' ', ' ', ' ',
//                            ' ', ' ', ' ', ' ', ' ', ' ']
//                    }],
//                credits: {
//                    enabled: false
//                },
//                yAxis: [{// Primary yAxis
//                        gridLineDashStyle: 'Dash',
//                        gridLineWidth: 1,
//                        labels: {
//                            style: {"color": "#ccced6", "font-family": "Raleway", "font-size": 13},
//                            format: '{value} '
//                        },
//                        title: {
//                            text: ' '
//                        }
//                    }, {// Secondary yAxis
//                        gridLineDashStyle: 'Dash',
//                        gridLineWidth: 1,
//                        title: {
//                            text: ' '
//                        },
//                        labels: {
//                            style: {"color": "#ccced6", "font-family": "Raleway", "font-size": 13},
//                            format: ' '
//                        }
//                    }],
//                tooltip: {
//                    enabled: true,
//                    shared: false,
//                    useHTML: true,
//                    backgroundColor: '#333333',
//                    borderColor: '#333',
//                    borderRadius: '5',
//                    borderWidth: '0',
//                    headerFormat: '<table>',
//                    pointFormat: '<tr>' +
//                            '<td style="color: #fff; font-family: Raleway; ">{point.y}K</td></tr>',
//                    footerFormat: '</table>',
//                    valueDecimals: 2
//                },
//                plotOptions: {
//                    column: {
//                        stacking: 'normal'
//                    }
//                },
//                series: [{
//                        name: '1',
//                        showInLegend: false,
//                        type: 'column',
//                        yAxis: 1,
//                        data: [144, 61, 96, 119, 134, 80, 269, 115, 206, 184, 85, 44],
//                        stack: 'one',
//                        color: 'rgba(202,205,202,.4)'
//
//                    }, {
//                        name: '2',
//                        showInLegend: false,
//                        type: 'area',
//                        data: [99, 61, 96, 119, 134, 120, 200, 96, 206, 184, 85, 44],
//                        color: '#cdd0de',
//                        fillColor: 'none',
//                        marker: {
//                            lineWidth: 2,
//                            lineColor: '#27ae60',
//                            fillColor: '#FFFFFF',
//                            radius: 5
//                        },
//                    }, {
//                        name: '3',
//                        showInLegend: false,
//                        type: 'column',
//                        yAxis: 1,
//                        data: [49, 71, 106, 129, 144, 176, 135, 76, 216, 194, 95, 54],
//                        stack: 'one',
//                        color: '#27ae60'
//
//
//                    }]
//            });
//            this.destroy();
//        },
//        offset: "100%"
//    });


    // RADAR CHART
//    var waypointRadar = new Waypoint({
//        element: document.getElementById('radar'),
//        handler: function(direction) {
//            var radar = document.getElementById("radar");
//            var parent = document.getElementById("wrapper-radar");
//            radar.width = parent.offsetWidth - 40;
//            radar.height = parent.offsetHeight - 40;
//            var data1 = {
//                labels: ['Mail', 'Feeds', 'IM', 'Twitter', 'F2F', 'Text', 'IRC', 'Blogs'],
//                datasets: [
//                    {
//                        fillColor: "rgba(255,198,0,.4)",
//                        strokeColor: "#ffc600",
//                        pointColor: "#fff",
//                        pointStrokeColor: "#ffc600",
//                        data: [8, 21, 12, 12, 12, 0, 6, 2]
//                    },
//                    {
//                        fillColor: "rgba(39,174,96,.4)",
//                        strokeColor: "#27ae60",
//                        pointColor: "#fff",
//                        pointStrokeColor: "#27ae60",
//                        data: [30, 13, 6, 5, 20, 3, 20, 26]
//                    }
//                ]
//            };
//
//            radarDefaults = {
//                //Boolean - If we show the scale above the chart data			
//                scaleOverlay: false,
//                //Boolean - If we want to override with a hard coded scale
//                scaleOverride: false,
//                //** Required if scaleOverride is true **
//                //Number - The number of steps in a hard coded scale
//                scaleSteps: null,
//                //Number - The value jump in the hard coded scale
//                scaleStepWidth: null,
//                //Number - The centre starting value
//                scaleStartValue: null,
//                //Boolean - Whether to show lines for each scale point
//                scaleShowLine: true,
//                //String - Colour of the scale line	
//                scaleLineColor: "rgba(0,0,0,.1)",
//                //Number - Pixel width of the scale line	
//                scaleLineWidth: 1,
//                //Boolean - Whether to show labels on the scale	
//                scaleShowLabels: false,
//                //Interpolated JS string - can access value
//                scaleLabel: "<%=value%>",
//                //String - Scale label font declaration for the scale label
//                scaleFontFamily: "'Raleway'",
//                //Number - Scale label font size in pixels	
//                scaleFontSize: 12,
//                //String - Scale label font weight style	
//                scaleFontStyle: "bold",
//                //String - Scale label font colour	
//                scaleFontColor: "#7e848e",
//                //Boolean - Show a backdrop to the scale label
//                scaleShowLabelBackdrop: true,
//                //String - The colour of the label backdrop	
//                scaleBackdropColor: "rgba(255,255,255,0.75)",
//                //Number - The backdrop padding above & below the label in pixels
//                scaleBackdropPaddingY: 2,
//                //Number - The backdrop padding to the side of the label in pixels	
//                scaleBackdropPaddingX: 2,
//                //Boolean - Whether we show the angle lines out of the radar
//                angleShowLineOut: true,
//                //String - Colour of the angle line
//                angleLineColor: "rgba(0,0,0,.1)",
//                //Number - Pixel width of the angle line
//                angleLineWidth: 1,
//                //String - Point label font declaration
//                pointLabelFontFamily: "'Raleway'",
//                //String - Point label font weight
//                pointLabelFontStyle: "bold",
//                //Number - Point label font size in pixels	
//                pointLabelFontSize: 12,
//                //String - Point label font colour	
//                pointLabelFontColor: "#7e848e",
//                //Boolean - Whether to show a dot for each point
//                pointDot: true,
//                //Number - Radius of each point dot in pixels
//                pointDotRadius: 5,
//                //Number - Pixel width of point dot stroke
//                pointDotStrokeWidth: 1,
//                //Boolean - Whether to show a stroke for datasets
//                datasetStroke: false,
//                //Number - Pixel width of dataset stroke
//                datasetStrokeWidth: 2,
//                //Boolean - Whether to fill the dataset with a colour
//                datasetFill: false,
//                //Boolean - Whether to animate the chart
//                animation: true,
//                //Number - Number of animation steps
//                animationSteps: 30,
//                //String - Animation easing effect
//                animationEasing: "easeOutQuart",
//                //Function - Fires when the animation is complete
//                onAnimationComplete: null,
//            };
//
//            var radarOptions = jQuery.extend({}, radarDefaults);
//            radarOptions.scaleOverride = true;
//            radarOptions.scaleStepWidth = 4;
//            radarOptions.scaleSteps = 8;
//            radarOptions.scaleStartValue = 0;
//            new Chart(radar.getContext("2d")).Radar(data1, radarOptions);
//
//            this.destroy();
//        },
//        offset: "100%"
//    });











}); // Document ready