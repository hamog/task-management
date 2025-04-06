<template>
  <div>
    <Head title="tasks" />
    <h1 class="mb-8 text-3xl font-bold">tasks</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset" />
      <Link class="btn-indigo" href="/tasks/create">
        <span>Create</span>
        <span class="hidden md:inline">&nbsp;task</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-left font-bold">
            <th class="pb-4 pt-6 px-6">Id</th>
            <th class="pb-4 pt-6 px-6">Title</th>
            <th class="pb-4 pt-6 px-6">Status</th>
            <th class="pb-4 pt-6 px-6">Started At</th>
            <th class="pb-4 pt-6 px-6">Finished At</th>
            <th class="pb-4 pt-6 px-6">Created At</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="task in tasks.data" :key="task.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
            <td class="border-t">
              <Link class="flex items-center px-6 py-4" :href="`/tasks/${task.id}/edit`" tabindex="-1">
                {{ task.id }}
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/tasks/${task.id}/edit`">
                {{ task.title }}
                <icon v-if="task.deleted_at" name="trash" class="shrink-0 ml-2 w-3 h-3 fill-gray-400" />
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-6 py-4" :href="`/tasks/${task.id}/edit`" tabindex="-1">
                <status :name="task.status"></status>
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-6 py-4" :href="`/tasks/${task.id}/edit`" tabindex="-1">
                {{ task.started_at }}
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-6 py-4" :href="`/tasks/${task.id}/edit`" tabindex="-1">
                {{ task.finished_at }}
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-6 py-4" :href="`/tasks/${task.id}/edit`" tabindex="-1">
                {{ task.created_at }}
              </Link>
            </td>
            <td class="w-px border-t">
              <Link class="flex items-center px-4" :href="`/tasks/${task.id}/edit`" tabindex="-1">
                <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
              </Link>
            </td>
          </tr>
          <tr v-if="tasks.data.length === 0">
            <td class="px-6 py-4 border-t" colspan="4">No tasks found.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <pagination class="mt-6" :links="tasks.links" />
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import pickBy from 'lodash/pickBy'
import Layout from '@/Shared/Layout.vue'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import Pagination from '@/Shared/Pagination.vue'
import SearchFilter from '@/Shared/SearchFilter.vue'
import Status from '../../Shared/Status.vue'

export default {
  components: {
    Status,
    Head,
    Icon,
    Link,
    Pagination,
    SearchFilter,
  },
  layout: Layout,
  props: {
    filters: Object,
    tasks: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
      },
    }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get('/tasks', pickBy(this.form), { preserveState: true })
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
  },
}
</script>
