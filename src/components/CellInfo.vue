<template>
  <div v-if="cell&&cell.property">
    <property-card :id="+cell.property_id"></property-card>
  </div>
</template>

<script>

export default {
  props: {
    cell: {
      type: Object,
      default: undefined,
    },
  },
  components: {
    PropertyCard,
  },
  created () {
    console.log('this.cell :>> ', this.cell);;
  },
};
</script>

<style lang="scss" scoped>
</style>