<template>
  <div class="h-100 position-relative">
    <arrow-move v-if="showArrow" :position="+cell.position" :isArrowFinish="isArrowFinish" :isStartCell="isStartCell" :index="index"></arrow-move>
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
      <image-estate
        v-else-if="cell.tax||cell.utility"
        :src="getImage(cell)"
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
import ImageEstate from "./ImageEstate.vue";
import ArrowMove from "./ArrowMove.vue";
export default {
  components: {
    ImageComponent,
    PropertyCell,
    ImageEstate,
    ArrowMove,
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
    startPositionArrow: {
      type: Number,
      default: undefined,
    },
    endPositionArrow: {
      type: Number,
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
  data() {
    return {
      cellCount: 40,
    };
  },
  computed: {
    showArrow() {
      let isShow =this.playerPositionComparable >= this.startPositionArrow &&this.playerPositionComparable <= this.endPositionArrow;
      if (isShow && this.playerFinishPosition == this.playerPositionComparable) return false;
      return isShow;
    },
    isArrowFinish(){
      return this.playerPositionComparable==this.arrowFinishPosition;
    },
    playerPositionComparable(){
        return this.startPositionArrow > (+this.cell.position)
          ? (+this.cell.position) + this.cellCount
          : (+this.cell.position);
    },
    index(){
        return this.playerPositionComparable-this.startPositionArrow;
    },
    playerFinishPosition() {
      return this.endPositionArrow;
    },
    arrowFinishPosition(){
      return this.endPositionArrow-1;
    },
    isStartCell(){
      return Number(this.cell.position)===Number(this.startPositionArrow);
    }
  },
};
</script>

<style scoped>
.inner-shadow {
  box-shadow: inset 0px 0px 20px black !important;
}
.z-index-1000 {
  z-index: 1000;
}
</style>