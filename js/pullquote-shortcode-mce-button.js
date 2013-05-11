(function(){
  tinymce.create('tinymce.plugins.cssPullQuote', {
    init: function(ed, url){
      ed.addButton('css_pull_quote', {
        title: 'Pull Quote',
        cmd: 'pullquote',
        image: url + '/../assets/pull-quote-button20x20.png'
      });
      ed.addCommand('pullquote', function(){
        var selectedText = ed.selection.getContent();
        var returnText = '';
        if(selectedText){
          returnText = '[pullquote]' + selectedText + '[/pullquote]';
          ed.execCommand('mceInsertContent', 0, returnText);
        }
      });
    },
    createControl : function(n, cm) {
      return null;
    },
    getInfo: function(){
      return {
        longname: 'CSS Pull Quote Button',
        author: 'kylereicks',
        version: '0.1.0'
      }
    }
  });
  tinymce.PluginManager.add('css_pull_quote', tinymce.plugins.cssPullQuote);
})();
