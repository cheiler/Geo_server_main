$(document)
    .ready(function () {
        $('.ui.menu .ui.dropdown').dropdown({
            on: 'hover'
        });
        $('.ui.menu a.item')
            .on('click', function () {
                $(this)
                    .addClass('active')
                    .siblings()
                    .removeClass('active');
            });
    });

$(document).ready(function () {
    // fix menu when passed
    $('.masthead')
        .visibility({
            once: false,
            onBottomPassed: function () {
                $('.fixed.menu').transition('fade in');
            },
            onBottomPassedReverse: function () {
                $('.fixed.menu').transition('fade out');
            }
        });

    // create sidebar and attach to menu open
    $('.ui.sidebar')
        .sidebar('attach events', '.toc.item');

    // search region
    $('.ui.search.region')
        .search({
            type: 'category',
            minCharacters: 2,
            apiSettings: {
                onResponse: function (solrResponse) {
                    var response = {
                        results: {}
                    };
                    // translate GitHub API response to work with search
                    $.each(solrResponse.response.docs, function (index, item) {
                        var region = item.region_type[0] || 'Unknown',
                            maxResults = 250;
                        if (index >= maxResults) {
                            return false;
                        }
                        // create new language category
                        if (response.results[region] === undefined) {
                            response.results[region] = {
                                name: region,
                                results: []
                            };
                        }
                        // add result to category
                        response.results[region].results.push({
                            title: item.region_id[0],
                            description: item.region_full_name[0]
//                        url         : item.region_id[0],
//                        title   : item.region_id[0],
                        });
                    });
                    return response;
                },
                url: 'http://localhost:8983/solr/regions/select?q=region_full_name:{query}&rows=250&wt=json'
            }
        });

    // search airport
    $('.ui.search.airport')
        .search({
            type: 'category',
            minCharacters: 4,
            apiSettings: {
                onResponse: function (solrResponse) {
                    var response = {
                        results: {}
                    };
                    // translate GitHub API response to work with search
                    $.each(solrResponse.response.docs, function (index, item) {
                        var airport = item.CountryName[0] || 'Unknown',
                            maxResults = 250;
                        if (index >= maxResults) {
                            return false;
                        }
                        // create new language category
                        if (response.results[airport] === undefined) {
                            response.results[airport] = {
                                name: airport,
                                results: []
                            };
                        }
                        // add result to category
                        response.results[airport].results.push({
                            title: item.AirportCode[0],
                            description: item.AirportName[0]
//                        url         : item.region_id[0],
//                        title   : item.region_id[0],
                        });
                    });
                    return response;
                },
                url: 'http://localhost:8983/solr/airports/select?q=AirportName:{query}&wt=json'
            }
        });

    // popup
    $('.activating.element')
        .popup({
            inline: true,
            on: 'click',
            position: 'bottom left',
            delay: {
                show: 300,
                hide: 800
            }
        });

    // popup for ancestors
    $('.ui.longer.ancestors.modal')
        .modal('attach events', '.ancestors.button', 'show');

    // popup for descendants
    $('.ui.longer.descendants.modal')
        .modal('attach events', '.descendants.button', 'show');

    // popup for properties
    $('.ui.longer.properties.modal')
        .modal('attach events', '.properties.button', 'show');

});