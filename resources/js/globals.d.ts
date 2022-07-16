import { defineComponent } from "vue";

declare global {
  interface Taskday {
    register(name: string, options: any): void
  }

  let taskday : Taskday;
}
