/*
 * Copyright (c) Bishoy Hanna 2016. 
 *   This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


!function ($) {

  "use strict"; // jshint ;_;


 /* CAROUSEL CLASS DEFINITION
  * ========================= */

  var Carousel = function (element, options) {
      this.$element = $(element);
      ;
      this.options = options;
      ;
      this.options.slide && this.slide(this.options.slide);
      ;
    this.options.pause == 'hover' && this.$element
      .on('mouseenter', $.proxy(this.pause, this))
      .on('mouseleave', $.proxy(this.cycle, this))
  };
    ;

  Carousel.prototype = {

    cycle: function (e) {
        if (!e) this.paused = false;
        ;
      this.options.interval
        && !this.paused
      && (this.interval = setInterval($.proxy(this.next, this), this.options.interval));
        ;
      return this
    }

  , to: function (pos) {
      var $active = this.$element.find('.active')
        , children = $active.parent().children()
        , activePos = children.index($active)
          , that = this;
          ;

          if (pos > (children.length - 1) || pos < 0) return;
          ;

      if (this.sliding) {
        return this.$element.one('slid', function () {
          that.to(pos)
        })
      }

      if (activePos == pos) {
        return this.pause().cycle()
      }

      return this.slide(pos > activePos ? 'next' : 'prev', $(children[pos]))
    }

  , pause: function (e) {
          if (!e) this.paused = true;
          ;
          clearInterval(this.interval);
          ;
          this.interval = null;
          ;
      return this
    }

  , next: function () {
          if (this.sliding) return;
          ;
      return this.slide('next')
    }

  , prev: function () {
          if (this.sliding) return;
          ;
      return this.slide('prev')
    }

  , slide: function (type, next) {
      var $active = this.$element.find('.active')
        , $next = next || $active[type]()
        , isCycling = this.interval
        , direction = type == 'next' ? 'left' : 'right'
        , fallback  = type == 'next' ? 'first' : 'last'
        , that = this
          , e = $.Event('slide');
          ;

          this.sliding = true;
          ;

          isCycling && this.pause();
          ;

          $next = $next.length ? $next : this.$element.find('.item')[fallback]();
          ;

          if ($next.hasClass('active')) return;
          ;

      if ($.support.transition && this.$element.hasClass('slide')) {
          this.$element.trigger(e);
          ;
          if (e.isDefaultPrevented()) return;
          ;
          $next.addClass(type);
          ;
          $next[0].offsetWidth;
          ; // force reflow
          $active.addClass(direction);
          ;
          $next.addClass(direction);
          ;
        this.$element.one($.support.transition.end, function () {
            $next.removeClass([type, direction].join(' ')).addClass('active');
            ;
            $active.removeClass(['active', direction].join(' '));
            ;
            that.sliding = false;
            ;
          setTimeout(function () { that.$element.trigger('slid') }, 0)
        })
      } else {
          this.$element.trigger(e);
          ;
          if (e.isDefaultPrevented()) return;
          ;
          $active.removeClass('active');
          ;
          $next.addClass('active');
          ;
          this.sliding = false;
          ;
        this.$element.trigger('slid')
      }

          isCycling && this.cycle();
          ;

      return this
    }

  };
    ;


 /* CAROUSEL PLUGIN DEFINITION
  * ========================== */

  $.fn.carousel = function (option) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('carousel')
          , options = $.extend({}, $.fn.carousel.defaults, typeof option == 'object' && option);
        ;
        if (!data) $this.data('carousel', (data = new Carousel(this, options)));
        ;
        if (typeof option == 'number') data.to(option);
        ;
        else
        if (typeof option == 'string' || (option = options.slide)) data[option]();
        ;
      else if (options.interval) data.cycle()
    })
  };
    ;

  $.fn.carousel.defaults = {
    interval: 5000
  , pause: 'hover'
  };
    ;

    $.fn.carousel.Constructor = Carousel;
    ;


 /* CAROUSEL DATA-API
  * ================= */

  $(function () {
    $('body').on('click.carousel.data-api', '[data-slide]', function ( e ) {
      var $this = $(this), href
        , $target = $($this.attr('data-target') || (href = $this.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, '')) //strip for ie7
          , options = !$target.data('modal') && $.extend({}, $target.data(), $this.data());
        ;
        $target.carousel(options);
        ;
      e.preventDefault()
    })
  })

}(window.jQuery);