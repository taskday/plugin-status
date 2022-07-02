<template>
  <div class="mb-8 flex items-center">
    <div class="px-6">
      <VFormList
        :selected="project.fields.find(field => field.handle === currentStatusHandle) ?? project.fields.find(field => field.type === 'status')"
        :options="project.fields.filter(field => field.type === 'status')"
        @change="updateCurrentField"
      >
        <template #trigger="{ item }">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6 mr-2"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"
            />
          </svg>
          <span>{{ item?.title ?? item?.name }}</span>
        </template>
      </VFormList>
    </div>
  </div>
  <div class="flex flex-col h-full flex-grow">
    <div class="-my-2 overflow-x-auto h-full flex-grow pr-16">
      <div
        class="px-6 pb-4 align-middle inline-block min-w-full h-full flex-grow"
      >
        <div class="h-full flex-grow">
          <div
            class="flex mx-auto gap-x-8 overflow-hidden h-full flex-grow pr-16"
          >
            <div
              class="w-96 flex-col flex shrink-0 h-full flex-grow rounded"
              v-for="column in columns"
            >
              <div>
                <span class="rounded-sm h-6 gap-3 pl-1 inline-flex items-center text-xs font-medium uppercase tracking-wide">
                  <span :class="{
                      'block h-3 w-3 rounded-full border border-gray-600 bg-white dark:border-gray-400 dark:bg-gray-400 dark:bg-opacity-20': column.color === 'gray',
                      'block h-3 w-3 rounded-full border border-red-600 bg-red-100 dark:border-red-400 dark:bg-red-400 dark:bg-opacity-20': column.color === 'red',
                      'block h-3 w-3 rounded-full border border-green-600 bg-green-100 dark:border-green-400 dark:bg-green-400 dark:bg-opacity-20': column.color === 'green',
                      'block h-3 w-3 rounded-full border border-yellow-600 bg-yellow-100 dark:border-yellow-400 dark:bg-yellow-400 dark:bg-opacity-20': column.color === 'yellow',
                      'block h-3 w-3 rounded-full border border-blue-600 bg-blue-100 dark:border-blue-400 dark:bg-blue-400 dark:bg-opacity-20': column.color === 'blue',
                      'block h-3 w-3 rounded-full border border-teal-600 bg-teal-100 dark:border-teal-400 dark:bg-teal-400 dark:bg-opacity-20': column.color === 'teal',
                      'block h-3 w-3 rounded-full border border-purple-600 bg-purple-100 dark:border-purple-400 dark:bg-purple-400 dark:bg-opacity-20': column.color === 'purple',
                    }"></span>
                  {{ column.name }}
                </span>
              </div>
              <div class="mt-4">
                <draggable

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
                    <VCard class="mb-4">
                      <div class="flex items-start gap-x-1 mb-1 justify-between">
                        <Link
                          :href="route('cards.show', element)"
                          class="inline-flex items-center text-left md:text-sm font-medium hover:underline"
                        >
                          {{ element.title }}
                        </Link>
                        <button
                          @click="state.selected = element"
                          class="inline-flex items-center ml-2"
                        >
                          <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                            />
                          </svg>
                        </button>
                      </div>
                      <div class="truncate">
                        <VBreadcrumbs v-if="route().current() != 'projects.show'" :items="element.breadcrumbs"></VBreadcrumbs>
                      </div>
                      <div class="flex flex-wrap gap-2 items-start mt-2">
                        <VFieldWrapper
                          v-for="field in project.fields.filter(f => f.handle !== currentStatusHandle)"
                          :key="field.id"
                          :card="element"
                          :field="field"
                        ></VFieldWrapper>
                      </div>
                    </VCard>
                  </template>
                </draggable>
                <KanbanCardForm

                  :project="project"
                  :status="column"
                  :handle="currentStatusHandle"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <VDrawer
    v-if="state.selected"
    :isOpen="true"
    :onClose="() => (state.selected = null)"
  >
    <PageCardsShow :card="state.selected" />
  </VDrawer>
</template>

<script setup lang="ts">
import draggable from "vuedraggable";
import { watch, ref, reactive, onMounted, computed } from "vue";
import KanbanCardForm from "./KanbanCardForm.vue";
import { VCard, VFormList, VFieldWrapper, useCardForm, VDrawer, VBreadcrumbs, PageCardsShow } from "taskday";
import { useStorage } from "@vueuse/core";

const props = defineProps<{
  title: String;
  breadcrumbs: {
    name: String;
    href: String;
  }[];
  project: Project;
}>();

const state = reactive({ selected: null });
const currentStatusHandle = useStorage<string>( props.project.id + "_kanbanview-status-handle", null);
const columns = ref([]);

const updateColumn = (column: any, card: Card) => {
  const { form, update } = useCardForm();
  form.fields = {
    //@ts-ignore
    [currentStatusHandle.value]: column.color,
  };
  update(card);
};

const cardsForOption = (option: Option): Card[] => {
  return props.project.cards.filter((card) => {
    return card.fields?.some(
      (f) =>
        f.handle === currentStatusHandle.value && f.pivot.value == option.color
    );
  });
};

const updateCurrentField = (field: Field) => {
  currentStatusHandle.value = field.handle;
  columns.value = props.project.fields
    .find((field) => field.handle === currentStatusHandle.value)
    ?.options?.map((option) => {
      return {
        ...option,
        cards: cardsForOption(option),
      };
    });
};

watch(
  () => currentStatusHandle.value,
  () => {
    console.log("currentStatusHandle", currentStatusHandle.value);
  }
);


watch(() => props.project, () => {
  let sel = state.selected;

  if (sel != null) {
    state.selected =
      props.project.cards.find((card: Card) => card.id == sel.id) ?? null;
  }

  let statusField = props.project.fields
    .find((field) => field.handle === currentStatusHandle.value) ?? props.project.fields.find(f => f.type == 'status');

  currentStatusHandle.value = statusField.handle;

  columns.value = statusField
    ?.options?.map((option) => {
      return {
        ...option,
        cards: cardsForOption(option),
      };
    });
}, { immediate: true });
</script>
