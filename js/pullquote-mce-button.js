(function(){
  tinymce.create('tinymce.plugins.pullquote', {
    init: function(ed, url){
      ed.addButton('semantic_pullquote', {
        title: 'Pullquote',
        cmd: 'pullquote',
        image: url + '/../assets/pullquote-button20x20.png'
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
        longname: 'Pullquote Button',
        author: 'kylereicks',
        version: '0.1.0'
      }
    }
  });
  tinymce.PluginManager.add('semantic_pullquote', tinymce.plugins.pullquote);
})();
