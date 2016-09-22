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

$(function () {

    module("bootstrap-collapse");
    ;

      test("should be defined on jquery object", function () {
        ok($(document.body).collapse, 'collapse method is defined')
      });
    ;

      test("should return element", function () {
        ok($(document.body).collapse()[0] == document.body, 'document.body returned')
      });
    ;

      test("should show a collapsed element", function () {
          var el = $('<div class="collapse"></div>').collapse('show');;
          ok(el.hasClass('in'), 'has class in');;
        ok(/height/.test(el.attr('style')), 'has height set')
      });
    ;

      test("should hide a collapsed element", function () {
          var el = $('<div class="collapse"></div>').collapse('hide');;
          ok(!el.hasClass('in'), 'does not have class in');;
        ok(/height/.test(el.attr('style')), 'has height set')
      });
    ;

      test("should not fire shown when show is prevented", function () {
          $.support.transition = false;;
        stop();
        $('<div class="collapse"/>')
          .bind('show', function (e) {
            e.preventDefault();
            ok(true);
            start();
          })
          .bind('shown', function () {
            ok(false);
          })
          .collapse('show')
      });
    ;

      test("should reset style to auto after finishing opening collapse", function () {
          $.support.transition = false;;
        stop();
        $('<div class="collapse" style="height: 0px"/>')
          .bind('show', function () {
            ok(this.style.height == '0px')
          })
          .bind('shown', function () {
              ok(this.style.height == 'auto');;
            start()
          })
          .collapse('show')
      })

});;