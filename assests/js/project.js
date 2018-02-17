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
        aryMatchWords,
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
      var compareData = {'KEYWORD':aryKeywords, 'MATCH WORDS':aryMatchWords};
      for(var category in compareData) {
        for(var i in compareData[category]) {
          var count = (text.match(compareData[category][i].regex) || []).length;
          rowHtml.push(category + ': ' + compareData[category][i].text + ', FOUND ' + count + ' TIME(S)');
        }
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
        aryKeywords[i] = {'text': aryKeywords[i], 'regex':new RegExp(aryKeywords[i], 'gi')};
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
     * @param {jQuery} jQform
     * @param {jQuery} jQdisplayContainer
     * @param {int} inputTotal
     * @param {string} inputMatchwords
     */
    var fnInit = function (jQform, jQdisplayContainer, inputTotal, inputMatchwords) {
      $form = jQform;
      $displayContainer = jQdisplayContainer;
      total = inputTotal;

      aryMatchWords = inputMatchwords.replace(/\s+/g, ' ').split(' ');
      for(var i in aryMatchWords) {
        aryMatchWords[i] = {'text': aryMatchWords[i], 'regex':new RegExp(aryMatchWords[i], 'gi')};
      }
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
      var matchwords = $form.find('input[name="matchwords"]').val();
      var total = $form.find('input[name="total"]').val();
      search.init($form, $displayContainer, total, matchwords);
      search.reset();
      search.ajax(keywords, null);
    });
  });
})(jQuery);