Todos.Store = DS.Store.extend({
  revision: 11  
});

DS.RESTAdapter.reopen({
  url: 'http://localhost/ember/todomvcpre/api' 
  
  /*findAll: function(store, type, since) {
    var root = this.rootForType(type);

    this.ajax(this.url + '/index.php?controller=todo&action=list', "GET", {
      data: this.sinceQuery(since),
      success: function(json) {
        Ember.run(this, function(){
          this.didFindAll(store, type, json);
        });
      }
    });
  } */

});