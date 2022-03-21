<template>
  <div class="flex flex-col h-full flex-grow">
    <div class="-my-2 overflow-x-auto h-full flex-grow pr-16">
      <div class="py-2 align-middle inline-block min-w-full h-full flex-grow">
        <div class="h-full flex-grow">
          <div class="flex mx-auto gap-x-8 overflow-hidden h-full flex-grow pr-16">
            <div
              class="w-96 flex-col flex shrink-0 py-4 rounded translated-container"
              v-for="column in state.columns"
            >
              <div>
                <span
                  class="px-3 rounded h-6 inline-flex items-center text-sm"
                  :class="{
                    'bg-gray-100 dark:bg-gray-400 text-gray-600 dark:text-gray-400 dark:bg-opacity-20':
                      column.color === 'gray',
                    'bg-red-100 dark:bg-red-400 text-red-600 dark:text-red-400 dark:bg-opacity-20':
                      column.color === 'red',
                    'bg-green-100 dark:bg-green-400 text-green-600 dark:text-green-400 dark:bg-opacity-20':
                      column.color === 'green',
                    'bg-yellow-100 dark:bg-yellow-400 text-yellow-600 dark:text-yellow-400 dark:bg-opacity-20':
                      column.color === 'yellow',
                    'bg-blue-100 dark:bg-blue-400 text-blue-600 dark:text-blue-400 dark:bg-opacity-20':
                      column.color === 'blue',
                    'bg-teal-100 dark:bg-teal-400 text-teal-600 dark:text-teal-400 dark:bg-opacity-20':
                      column.color === 'teal',
                    'bg-purple-100 dark:bg-purple-400 text-purple-600 dark:text-purple-400 dark:bg-opacity-20':
                      column.color === 'purple',
                  }"
                  >{{ column.name }}</span
                >
              </div>
              <div class="mt-4">
                <draggable
                  class="space-y-4"
                  @change="
                    ({ added }) => added && updateColumn(column, added.element)
                  "
                  v-model="column.cards"
                  :animation="200"
                  ghost-class="opacity-50"
                  group="tasks"
                  itemKey="id"
                >
                  <template #item="{ element }">
                    <VCard>
                      <VLink
                        :href="route('cards.show', element)"
                        class="flex items-center text-left font-semibold text-sm hover:underline"
                        >{{ element.title }}</VLink
                      >
                    </VCard>
                  </template>
                </draggable>
                <KanbanCardForm class="mt-4" :project="project" :status="column" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import draggable from "vuedraggable";
import { watch, reactive } from "vue";
import KanbanCardForm from "./KanbanCardForm.vue";
import { VCard, VLink, useCardForm } from "taskday";

const props = defineProps<{
  title: String;
  breadcrumbs: {
    name: String;
    href: String;
  }[];
  project: Project;
}>();

type Option = { color: string; name: string };

type Card = { fields: any[] }
type Project = {
  cards: Card[]
  customFields: {
    status: {
      id: string;
      options: Option[];
    }
  }
}

const state = reactive<{
  columns: any[];
  selected: Card | null;
  options: Option[];
}>({
  columns: [],
  options: props.project.customFields?.status?.options,
  selected: null,
});

const updateColumn = (column: any, card: Card) => {
  const { form, update } = useCardForm();
  form.fields = {
    'status': column.color
  }
  update(card);
};

const cardsForOption = (option: Option): Card[] => {
  return props.project.cards.filter((card) => {
    return card.fields?.some(
      (f) => f.type === "status" && f.pivot.value == option.color
    );
  });
};

watch(
  () => props.project,
  () => {
    state.columns = state.options?.map((option) => {
      return {
        ...option,
        cards: cardsForOption(option),
      };
    });
  },
  { immediate: true }
);

// function destroy(status) {
//   Inertia.delete(route('cards.destroy', state.selected), {
//     onSuccess: () => {
//       state.selected = null;
//     }
//   })
// }
</script>
