import StatusField from './components/StatusField.vue';
import StatusOptions from './components/StatusOptions.vue';
import KanbanView from './components/KanbanView.vue';

document.addEventListener('taskday:init', () => {
  taskday.register('status', {
    field: StatusField,
    options: StatusOptions,
    views: [
      { type: 'kanban', title: 'Board', component: KanbanView, needs: ['status'] },
    ]
  });
})

