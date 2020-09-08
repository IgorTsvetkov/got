<template>
  <div class="h-100 position-relative">
    <div
      class="inner-shadow position-absolute h-100 w-100 d-flex align-items-center justify-content-center"
    >
      <slot></slot>
    </div>
    <div class="d-flex justify-content-center h-100">
      <property-cell
        v-if="cell.property"
        :src="getImage(cell)"
        :price="+cell.property.cost"
        :price_bgcolor="cell.property.group.color_name"
      >
        <image-estate :src="getImage(cell)" :playerOwner="playerOwner" />
      </property-cell>
      <image-estate  v-else-if="cell.tax||cell.utility" :src="getImage(cell)" :playerOwner="playerOwner" />
      <image-component v-else :src="getImage(cell)" />
    </div>
  </div>
  <!-- :property_game_status="cell.property.propertyGameStatuses[0].player.userusername" -->
</template>

<script>
import ImageComponent from "./ImageComponent.vue";
import PropertyCell from "./PropertyCell.vue";
import ImageEstate from "./ImageEstate.vue";
export default {
  components: {
    ImageComponent,
    PropertyCell,
    ImageEstate
  },
  props: {
    cell: {
      type: Object,
      default: {},
    },
    playerOwner: {
      type: Object,
      default: undefined,
    },
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
.inner-shadow {
  box-shadow: inset 0px 0px 20px black !important;
}
</style>