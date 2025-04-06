<template>
  <span v-if="name === 'completed'" class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-green-600/20 ring-inset">{{ name }}</span>
  <span v-else-if="name === 'pending'" class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-yellow-600/20 ring-inset">{{ name }}</span>
  <span v-else-if="name === 'incomplete'" class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-red-600/10 ring-inset">{{ name }}</span>
</template>

<script>
export default {
  props: {
    name: String,
  },
}
</script>
