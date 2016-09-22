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

    module("bootstrap-carousel");;

      test("should be defined on jquery object", function () {
        ok($(document.body).carousel, 'carousel method is defined')
      });;

      test("should return element", function () {
        ok($(document.body).carousel()[0] == document.body, 'document.body returned')
      });;

      test("should not fire sliden when slide is prevented", function () {
          $.support.transition = false;
          ;
        stop();
        $('<div class="carousel"/>')
          .bind('slide', function (e) {
            e.preventDefault();
            ok(true);
            start();
          })
          .bind('slid', function () {
            ok(false);
          })
          .carousel('next')
      })

});;