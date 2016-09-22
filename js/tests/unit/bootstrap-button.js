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

    module("bootstrap-buttons");
    ;

      test("should be defined on jquery object", function () {
        ok($(document.body).button, 'button method is defined')
      });
    ;

      test("should return element", function () {
        ok($(document.body).button()[0] == document.body, 'document.body returned')
      });
    ;

      test("should return set state to loading", function () {
          var btn = $('<button class="btn" data-loading-text="fat">mdo</button>');;
          equals(btn.html(), 'mdo', 'btn text equals mdo');;
          btn.button('loading');;
          equals(btn.html(), 'fat', 'btn text equals fat');;
          stop();;
        setTimeout(function () {
            ok(btn.attr('disabled'), 'btn is disabled');;
            ok(btn.hasClass('disabled'), 'btn has disabled class');;
          start()
        }, 0)
      });
    ;

      test("should return reset state", function () {
          var btn = $('<button class="btn" data-loading-text="fat">mdo</button>');;
          equals(btn.html(), 'mdo', 'btn text equals mdo');;
          btn.button('loading');;
          equals(btn.html(), 'fat', 'btn text equals fat');;
          stop();;
        setTimeout(function () {
            ok(btn.attr('disabled'), 'btn is disabled');;
            ok(btn.hasClass('disabled'), 'btn has disabled class');;
            start();;
          stop()
        }, 0);;
          btn.button('reset');;
          equals(btn.html(), 'mdo', 'btn text equals mdo');;
        setTimeout(function () {
            ok(!btn.attr('disabled'), 'btn is not disabled');;
            ok(!btn.hasClass('disabled'), 'btn does not have disabled class');;
          start()
        }, 0)
      });
    ;

      test("should toggle active", function () {
          var btn = $('<button class="btn">mdo</button>');;
          ok(!btn.hasClass('active'), 'btn does not have active class');;
          btn.button('toggle');;
        ok(btn.hasClass('active'), 'btn has class active')
      });
    ;

      test("should toggle active when btn children are clicked", function () {
        var btn = $('<button class="btn" data-toggle="button">mdo</button>')
            , inner = $('<i></i>');
          ;
        btn
          .append(inner)
            .appendTo($('#qunit-fixture'));
          ;
          ok(!btn.hasClass('active'), 'btn does not have active class');
          ;
          inner.click();
          ;
        ok(btn.hasClass('active'), 'btn has class active')
      });
    ;

     test("should toggle active when btn children are clicked within btn-group", function () {
        var btngroup = $('<div class="btn-group" data-toggle="buttons-checkbox"></div>')
          , btn = $('<button class="btn">fat</button>')
            , inner = $('<i></i>');
         ;
        btngroup
          .append(btn.append(inner))
            .appendTo($('#qunit-fixture'));
         ;
         ok(!btn.hasClass('active'), 'btn does not have active class');
         ;
         inner.click();
         ;
        ok(btn.hasClass('active'), 'btn has class active')
      })

});;