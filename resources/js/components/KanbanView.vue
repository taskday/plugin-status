<template>
  <div class="px-6 mb-4">
    <div>
      <VFormSelect v-model="state.currentHandle" class="max-w-xs">
        <option value="">{{ "Select a field" }}</option>
        <option v-for="field in project.fields" :value="field.handle">
          {{ field.title }}
        </option>
      </VFormSelect>
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
              v-for="column in state.columns"
            >
              <div>
                <span
                  class="rounded-sm h-6 inline-flex items-center text-xs font-medium uppercase tracking-wide"
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
                      <div class="gap-x-1">
                        <Link
                          :href="route('cards.show', element)"
                          class="inline-flex items-center text-left md:text-sm font-medium mb-1 hover:underline"
                          >{{ element.title }}</Link
                        >
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
                      <div class="flex gap-2 items-start mt-2">
                        <VFieldWrapper
                          v-for="field in project.fields.filter(
                            (field) => field.handle !== 'status'
                          )"
                          :key="field.id"
                          :card="element"
                          :field="field"
                        ></VFieldWrapper>
                      </div>
                    </VCard>
                  </template>
                </draggable>
                <KanbanCardForm
                  class="mt-4"
                  :project="project"
                  :status="column"
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
import { watch, reactive, onMounted } from "vue";
import KanbanCardForm from "./KanbanCardForm.vue";
import {
  VCard,
  VFormSelect,
  VFieldWrapper,
  useCardForm,
  VDrawer,
  PageCardsShow,
} from "taskday";

const props = defineProps<{
  title: String;
  breadcrumbs: {
    name: String;
    href: String;
  }[];
  project: Project;
}>();

const state = reactive<{
  columns: any[];
  selected: Card | null;
  options: Option[];
  currentHandle: string;
}>({
  columns: [],
  options: props.project.fields.find((field) => field.type === "status")
    .options,
  selected: null,
  currentHandle:
    localStorage.getItem(props.project.id + "_kanbanview-status-handle") ??
    props.project.fields.find((field) => field.type === "status").handle,
});

onMounted(() => {
  let sel = state.selected;

  if (sel != null) {
    state.selected =
      props.project.cards.find((card: Card) => card.id == sel.id) ?? null;
  }
});

const updateColumn = (column: any, card: Card) => {
  const { form, update } = useCardForm();
  form.fields = {
    [state.currentHandle]: column.color,
  };
  update(card);
};

const cardsForOption = (option: Option): Card[] => {
  return props.project.cards.filter((card) => {
    return card.fields?.some(
      (f) => f.handle === state.currentHandle && f.pivot.value == option.color
    );
  });
};

watch(
  () => props.project + state.currentHandle,
  () => {
    localStorage.setItem(
      props.project.id + "_kanbanview-status-handle",
      state.currentHandle
    );
    let statusField = props.project.fields.find(
      (field) => field.handle === state.currentHandle
    );
    if (statusField) {
      state.options = statusField.options;
      state.columns = state.options?.map((option) => {
        return {
          ...option,
          cards: cardsForOption(option),
        };
      });
    }
  },
  { immediate: true }
);
</script>
