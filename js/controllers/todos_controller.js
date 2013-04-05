Todos.TodosController = Ember.ArrayController.extend({
  createTodo: function () {
    // Get the todo title set by the "New Todo" text field
    var title = this.get('newTitle');
    if (!title.trim()) { return; }

    // Create the new Todo model
    Todos.Todo.createRecord({
      title: title,
      iscompleted: false
    });

    // Clear the "New Todo" text field
    this.set('newTitle', '');

    // Save the new model
    this.get('store').commit();
  },

  remaining: function () {
    return this.filterProperty('iscompleted', false).get('length');
  }.property('@each.iscompleted'),

  inflection: function () {
    var remaining = this.get('remaining');
    return remaining === 1 ? 'item' : 'items';
  }.property('remaining'),

  hasCompleted: function () {
    return this.get('completed') > 0;
  }.property('completed'),

  completed: function () {
    return this.filterProperty('iscompleted', true).get('length');
  }.property('@each.iscompleted'),
  
  clearCompleted: function () {
    var completed = this.filterProperty('iscompleted', true);
    completed.invoke('deleteRecord');

    this.get('store').commit();
  },

  allAreDone: function (key, value) {
    if (value === undefined) {
      return !!this.get('length') && this.everyProperty('iscompleted', true);
    } else {
      this.setEach('iscompleted', value);
      return value;
    }
  }.property('@each.iscompleted')
});
