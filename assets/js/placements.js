(function (Requests, window, document, $) {

    const PlacementsForms = {
        placementPosition      : {
            'id'  : $('#placement-position'),
            'data': function (that) {
                return {
                    action          : 'quizAd_placements_position',
                    placementDisplay: that.getCheckedPlacementPositions()
                }
            }
        },
        placementDefault       : {
            'id'  : $('#placement-list'),
            'data': function () {
                return {
                    action     : 'quizAd_placements_list',
                    placementId: $('input[name="placement"]:checked').val()
                }
            }
        },
        placementQuizProperties: {
            'id'  : $('#sentence-list'),
            'data': function () {
                return {
                    action           : 'quizAd_placements_sentence',
                    placementSentence: $('#quest_sentence').val()
                }
            }
        },
        placementDownload      : {
            'id'  : $('#download-placements'),
            'data': function () {
                return {
                    action: 'quizAd_placements_download',
                }
            }
        },
        placementExclude       : {
            'id'  : $('#exclude-placement'),
            'data': function (that) {
                return {
                    action          : 'quizAd_placements_exclude',
                    placementExclude: that.getExcludedPlacements()
                }
            }
        },
        deleteAccount          : {
            'id'  : $('#delete-account'),
            'data': function (that) {
                return {
                    action: 'quizAd_delete',
                    pwd   : that.deleteAccount.id.serializeArray()[0].value
                }
            }
        },

        submitForm: function (actionType) {
            var that = this;
            actionType.id.on('submit', function (e) {
                e.preventDefault();

                var request = Requests();

                request.onSuccess(function (code, responseText) {
                    var responseObject = JSON.parse(responseText);
                    if (responseObject.redirectTo) {
                        window.location.replace(responseObject.redirectTo);
                    }
                    if (responseObject.success) {
                        window.location.reload();
                    }
                    if (responseObject.delMessage.length > 0) {
                        $('.delete-message').html(responseObject.delMessage).show();
                    }
                });

                request.post(ajaxurl, {}, actionType.data(that));
            })
        },

        /**
         * Get (checked in form) placement positions (Post, Frontpage, etc.)
         * and get selected placement position in specific place (ex. in Post name 'hello-world').
         *
         * @return {Array}
         */
        getCheckedPlacementPositions: function () {
            var placements = [];
            $('[name=formPlacementsDisplay]').each(function () {
                    if (!this.checked) {
                        return;
                    }
                    placements.push(this.id);
                }
            );
            return placements;
        },

        getExcludedPlacements: function () {
            var placements = [];
            /** get excluded tags */
            $('input[name="formPlacementsExclude"]').each(function () {
                    if (!this.checked) {
                        return;
                    }
                    placements.push(this.id);
                }
            );
            /** get excluded pages/posts */
            var excludedItems = $('.excluded-placement').find('ul li');
            excludedItems.each(function (key, itemList) {
                placements.push($(itemList).find('a').attr('target'));
            });

            return placements;
        }
    };

    PlacementsForms.submitForm(PlacementsForms.placementPosition);
    PlacementsForms.submitForm(PlacementsForms.placementDefault);
    PlacementsForms.submitForm(PlacementsForms.placementQuizProperties);
    PlacementsForms.submitForm(PlacementsForms.placementDownload);
    PlacementsForms.submitForm(PlacementsForms.placementExclude);
    PlacementsForms.submitForm(PlacementsForms.deleteAccount);


    const AjaxSearchBar = {
        /**
         * @param id - jQuery object
         */
        init: function (id) {
            var that = this;
            id.keypress(function (e) {
                var dataList = $('#data-fetch');

                dataList.find('.search_item').removeClass('active');

                var keyword = e.target.value;
                if (keyword === "" || keyword.length < 2) {
                    dataList.html("");
                    return;
                }

                that.sendPost(dataList, keyword);

            });
        },

        sendPost: function (dataList, keyword) {
            var that    = this;
            var request = QuizAdRequestFactory();
            request.onSuccess(function (code, responseText) {
                var responseObject = JSON.parse(responseText);
                if (responseObject.success) {
                    that.appendData(dataList, responseObject);
                }
            });
            request.post(ajaxurl, {}, {action: 'quizAd_get_post', keyword: keyword});
        },

        appendData: function (dataList, responseObject) {
            var parseHTML = $.parseHTML(responseObject.data);
            dataList.html(parseHTML[0].data);
            dataList.find('.search_item').on('click', function (e) {
                var searchItem = $(e.target);
                if (searchItem.hasClass('active')) {
                    dataList.find('.search_item').removeClass('active');
                    return;
                }
                dataList.find('.search_item').removeClass('active');

                searchItem.addClass('active');
                $('#search-article input#search').val(searchItem.html()).attr('target', searchItem.attr('id'));
            });
        }
    };

    //TODO: replace events on the bottom in to ExcludePlafcement object
    const ExcludePlacement = {};

    AjaxSearchBar.init($('#search-article input#search'))

    $('.add-excluded-btn').on('click', function (e) {
        var searchId = $('#search-article input#search').attr('target');
        if (searchId === undefined || searchId === 'undefined') {
            console.log('Please check page or post.');
            return;
        }
        var nowExcluded = $('.excluded-placement').find('a').map(function (key, id) {
            return id.target;
        });
        if (nowExcluded.length > 0 && $.inArray(searchId, nowExcluded) !== -1) {
            console.log('This position is now added to excluded list.')
            return;
        }
        var listElem = '<li>' + $('#search-article input#search').val() + '<a target="' + searchId + '" class="close"></a></li>';
        $('.excluded-placement ul').append(listElem);
        $('#search-article input#search').attr('target', 'undefined').val('');
        $('#data-fetch').html("");
        $('.excluded-placement ul').find('a.close').on('click', function (e) {
            e.target.parentElement.remove();
        });


    });
    $('.all-categories').on('change', function (e) {
        var categoriesCheckboxes = $('.categories-list').find('input[name=formPlacementsDisplay]').not('.all-categories');
        if (e.target.checked) {
            categoriesCheckboxes.prop('checked', true).attr('disabled', true);
            return;
        }
        categoriesCheckboxes.prop('checked', false).attr('disabled', false);
    });

    $('.excluded-placement ul').find('a.close').on('click', function (e) {
        e.target.parentElement.remove();
    });

    $('input#pp-posts').on('change', function (e) {
        var allCategories        = $('.all-categories');
        var categoriesCheckboxes = $('.categories-list').find('input[name=formPlacementsDisplay]').not('.all-categories');

        if (!$('input#pp-posts').is(':checked')) {
            allCategories.prop('checked', false).attr('disabled', true);
            categoriesCheckboxes.prop('checked', false).attr('disabled', true);
        } else {
            allCategories.attr('disabled', false);
            if (allCategories.prop('checked') !== 'checked') {
                categoriesCheckboxes.attr('disabled', false);
            }
        }
    });

    $('input.delete').click(function () {
        $(".delete-box").toggle("slow", function () {
            // Animation complete.
        });
    });

})(QuizAdRequestFactory, window, document, jQuery);