Todos.Todo = DS.Model.extend({
  title: DS.attr('string'),
  iscompleted: DS.attr('boolean')
});

Todos.Todo.FIXTURES = [
  {
    id: 1,
    title: 'Learn Ember.js',
    iscompleted: true
  },
  {
    id: 2,
    title: '...',
    iscompleted: false
  },
  {
    id: 3,
    title: 'Profit!',
    iscompleted: false
  }
];
