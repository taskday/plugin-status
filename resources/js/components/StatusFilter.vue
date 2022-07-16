<script lang="ts" setup>
import { computed, PropType } from "vue";
import {
  VDropdown,
  VDropdownButton,
  VDropdownItems,
  VDropdownItem,
} from "taskday";

const props = defineProps({
  value: {
    type: String,
    default: '',
  },
  filter: {
    type: Object,
    required: true,
  },
  column: {
    type: String,
    required: true,
  },
});

const currentColumn = computed(() => {
  return props.filter.columns.find((r: any) => r.handle === props.column);
})

const current = computed(() => {
  return currentColumn.value?.options.find(
    (option: Option) => option.color === props.value
  );
});

const name = computed(() => {
  return current.value?.name;
});

const color = computed(() => {
  return current.value?.color;
});
</script>

<template>
  <VDropdown>
    <VDropdownButton
      :class="{
        'bg-gray-100 dark:bg-gray-400 text-gray-600 dark:text-gray-400 dark:bg-opacity-20': color === 'gray',
        'bg-red-100 dark:bg-red-400 text-red-600 dark:text-red-400 dark:bg-opacity-20': color === 'red',
        'bg-green-100 dark:bg-green-400 text-green-600 dark:text-green-400 dark:bg-opacity-20': color === 'green',
        'bg-yellow-100 dark:bg-yellow-400 text-yellow-600 dark:text-yellow-400 dark:bg-opacity-20': color === 'yellow',
        'bg-blue-100 dark:bg-blue-400 text-blue-600 dark:text-blue-400 dark:bg-opacity-20': color === 'blue',
        'bg-purple-100 dark:bg-purple-400 text-purple-600 dark:text-purple-400 dark:bg-opacity-20': color === 'purple',
      }"
      class="rounded transition font-medium h-9 text-sm whitespace-nowrap w-auto px-4 flex items-center justify-center"
    >
      {{ name }}
    </VDropdownButton>
    <VDropdownItems v-if="currentColumn && currentColumn.options">
      <VDropdownItem
        v-for="option in currentColumn.options"
        :key="option.id"
        @click="$emit('update:value', option.color)"
      >
        <span>{{ option.name }}</span>
      </VDropdownItem>
    </VDropdownItems>
  </VDropdown>
</template>
