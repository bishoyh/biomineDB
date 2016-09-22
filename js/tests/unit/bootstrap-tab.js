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

    module("bootstrap-tabs");
    ;

      test("should be defined on jquery object", function () {
        ok($(document.body).tab, 'tabs method is defined')
      });
    ;

      test("should return element", function () {
        ok($(document.body).tab()[0] == document.body, 'document.body returned')
      });
    ;

      test("should activate element by tab id", function () {
        var tabsHTML =
            '<ul class="tabs">'
          + '<li><a href="#home">Home</a></li>'
          + '<li><a href="#profile">Profile</a></li>'
            + '</ul>';
          ;

          $('<ul><li id="home"></li><li id="profile"></li></ul>').appendTo("#qunit-fixture");
          ;

          $(tabsHTML).find('li:last a').tab('show');
          ;
          equals($("#qunit-fixture").find('.active').attr('id'), "profile");
          ;

          $(tabsHTML).find('li:first a').tab('show');
          ;
        equals($("#qunit-fixture").find('.active').attr('id'), "home")
      });
    ;

      test("should activate element by tab id", function () {
        var pillsHTML =
            '<ul class="pills">'
          + '<li><a href="#home">Home</a></li>'
          + '<li><a href="#profile">Profile</a></li>'
            + '</ul>';
          ;

          $('<ul><li id="home"></li><li id="profile"></li></ul>').appendTo("#qunit-fixture");
          ;

          $(pillsHTML).find('li:last a').tab('show');
          ;
          equals($("#qunit-fixture").find('.active').attr('id'), "profile");
          ;

          $(pillsHTML).find('li:first a').tab('show');
          ;
        equals($("#qunit-fixture").find('.active').attr('id'), "home")
      });
    ;


      test("should not fire closed when close is prevented", function () {
          $.support.transition = false;;
        stop();
        $('<div class="tab"/>')
          .bind('show', function (e) {
            e.preventDefault();
            ok(true);
            start();
          })
          .bind('shown', function () {
            ok(false);
          })
          .tab('show')
      })

});;