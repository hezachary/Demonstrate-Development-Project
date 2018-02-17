/**
 * module for submit keywords and next page, process HTML dom, and display
 * @author Zachary He <hezachary@hotmail.com>
 * @version 1
 */
(function($) {
  /**
   * the jquery select for form
   * @type {string}
   */
  var strFormSelector = 'form[name="search"]';

  /**
   * the search module
   * @type {{init, ajax, reset}}
   */
  var search = (function () {
    var total,
        $form,
        $displayContainer,
        dataList = [];

    /**
     * populate html row data
     *
     * @param {HTMLElement} el
     * @param {array} aryKeywords
     * @returns {string}
     */
    function populateRow (el, aryKeywords) {
      var text = el.innerText;
      var hyperLink = $(el).find('h3 a');
      var title = hyperLink.text();
      var sourceUrl = hyperLink.attr('href');
      dataList.push([text, sourceUrl]);

      var rowHtml = [];
      for(var i in aryKeywords) {
        var count = (text.match(aryKeywords[i].regex) || []).length;
        rowHtml.push('KEYWORD: ' + aryKeywords[i].keyword + ', FOUND ' + count + ' TIME(S)');
      }

      return '<p>POS: ' + dataList.length + ' <br> ' + rowHtml.join(' <br> ') + ' <br> FROM: ' + title + '<br> VIA: ' + sourceUrl + '</p>';
    };

    /**
     * Process AJAX Result
     *
     * @param {string} keywords
     * @param {array} aryKeywords
     * @param {object} data
     */
    function processResult (keywords, aryKeywords, data) {
      var tempNode = $('<div>' + data.raw + '</div>');
      tempNode.find('.g').each(function () {
        var html = populateRow(this, aryKeywords);
        if(dataList.length > total) {
          return false;
        }
        $displayContainer.append(html);
      });

      if (dataList.length < total) {
        var nextUrl = tempNode.find('table#nav a:last').attr('href');
        fnAjax(keywords, nextUrl);
      }
    };

    /**
     * Reset Result display area
     */
    var fnReset = function () {
      dataList = [];
      $displayContainer.empty();
    };

    /**
     * AJAX call with following process
     *
     * @param {string} keywords
     * @param {string} nextUrl
     */
    var fnAjax = function (keywords, nextUrl) {
      var aryKeywords = keywords.replace(/\s+/g, ' ').split(' ');
      for(var i in aryKeywords) {
        aryKeywords[i] = {'keyword': aryKeywords[i], 'regex':new RegExp(aryKeywords[i], 'gi')};
      }

      $.post(basepath, {
        'action': 'ajax',
        'keywords': keywords,
        'nextUrl': nextUrl
      }, function (data) {
        processResult(keywords, aryKeywords, data);
      }, 'json');
    };

    /**
     * Get data for initialization
     *
     * @param jQform
     * @param jQdisplayContainer
     */
    var fnInit = function (jQform, jQdisplayContainer, inputTotal) {
      $form = jQform;
      $displayContainer = jQdisplayContainer;
      total = inputTotal;
    }

    /**
     * use module pattern to only expose the method need to expose
     */
    return {
      'init': fnInit,
      'ajax': fnAjax,
      'reset': fnReset
    }
  })();

  /**
   * Apply code only when page fully loaded
   */
  $(function () {
    var $form = $(strFormSelector);
    var $displayContainer = $('#result');
    $form.on('submit', function (e) {
      e.preventDefault();
      var keywords = $form.find('input[name="keywords"]').val();
      var total = $form.find('input[name="total"]').val();
      search.init($form, $displayContainer, total);
      search.reset();
      search.ajax(keywords, null);
    });
  });
})(jQuery);