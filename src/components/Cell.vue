<template>
  <div class="h-100">
    <div class="position-absolute h-100 d-flex">
      <slot></slot>
    </div>
    <div class="d-flex justify-content-center h-100">
      <property-cell
        v-if="cell.property"
        :src="getImage(cell)"
        :price="+cell.property.cost"
        :price_bgcolor="cell.property.group.color_name"
        :playerOwner="playerOwner"
      />
      <image-component v-else :src="getImage(cell)" />
    </div>
  </div>
  <!-- :property_game_status="cell.property.propertyGameStatuses[0].player.userusername" -->
</template>

<script>
import ImageComponent from "./ImageComponent.vue";
import PropertyCell from "./PropertyCell.vue";
export default {
  components: {
    ImageComponent,
    PropertyCell,
  },
  props: {
    cell: {
      type: Object,
      default: {},
    },
    playerOwner:{
      type:Object,
      default:undefined
    }
  },
  methods: {
    getImage(cell) {
      let x = cell.property
        ? cell.property.src
        : cell.tax
        ? cell.tax.src
        : cell.event
        ? cell.event.src
        : cell.utility
        ? cell.utility.src
        : "";
      return x;
    },
  },
  computed: {
    // playerOwner() {
    //   if (this.cell && this.cell.property && this.cell.property.playerOwner)
    //     return this.cell.property.playerOwner;
    //   return null;
    // },
  },
};
</script>

<style scoped>
</style>