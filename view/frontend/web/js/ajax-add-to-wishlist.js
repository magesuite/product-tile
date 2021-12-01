define(['jquery', 'jquery-ui-modules/widget'], function ($) {
    'use strict';

    /**
     * Ajax add to wishlist widget
    */

    $.widget('magesuite.ajaxAddToWishlist', {
        _create: function () {
            var _self = this;
            var params = this.element.data('params');

            this.element.on('click touchend', function(e) {
                e.preventDefault();
                _self._submitHandler(params);
            });

        },

        /**
         * Wishlist data submission via AJAX
         * @param {Object} url 
         */
        _submitHandler: function (params) {

            $.ajax({
                method: 'POST',
                url: params.action,
                data: params.data,
            })
                .done(
                    function (response) {
                        this._onDoneHandler(response);
                    }.bind(this)
                )
                .fail(
                    function (response) {
                        if (
                            response.responseJSON &&
                            response.responseJSON.message
                        ) {
                            this._onFailHandler(response.responseJSON.message);
                        }
                    }.bind(this)
                );
        },

        /**
         * After AJAX request returned with data - 
         * @param {object} response - ajax response
         */
        _onDoneHandler(response) {

            // const newQty = response.wishlist.counter;
            const newQty = parseInt($('.cs-header-user-nav__qty-counter--wishlist .qty').text()) + 1;

            const $wishlistBadge = $('.cs-header-user-nav .cs-header-user-nav__qty-counter--wishlist');
            const wishlistBadgeRect = $wishlistBadge[0].getBoundingClientRect();

            let $clonedBadge = $('.cs-header-user-nav__qty-counter--wishlist-cloned');
            if ($clonedBadge.length) {
                $clonedBadge.remove();
            }
            $clonedBadge = $wishlistBadge.clone();

            const $icon = this.element.find('.cs-links-block-addto__icon');
    
            if (
                !$clonedBadge.length ||
                !$icon.length
            ) {
                return;
            }
    
            const startingPosition = $icon[0].getBoundingClientRect();
            const $clonedQtyHolder = $clonedBadge.find('.cs-header-user-nav__qty-counter-span');
            $clonedQtyHolder.html(newQty);

            $('body').append($clonedBadge);
            $clonedBadge.addClass('cs-header-user-nav__qty-counter--wishlist-cloned');
            $clonedBadge.css({
                top: `${Math.round(parseInt(startingPosition.top, 10))}px`,
                left: `${Math.round(parseInt(startingPosition.left, 10))}px`,
            });
    
            setTimeout(function() {
                $clonedBadge
                .addClass('cs-header-user-nav__qty-counter--wishlist-animating')
                .css({
                    top: Math.round(parseInt(wishlistBadgeRect.top, 10)) > 0 ? Math.round(parseInt(wishlistBadgeRect.top, 10)) + 'px' : '-10rem',
                    left: Math.round(parseInt(wishlistBadgeRect.left, 10)) + 'px',
                });
            }, 300);

            $clonedBadge.one('transitionend', function() {
                $clonedBadge.remove(); 
            });

        },

        /**
         * After AJAX request FAILED - 
         * @param {string} error - XHR error message
         */
        _onFailHandler(error) {

        },
    });

    return $.magesuite.ajaxAddToWishlist;
});

