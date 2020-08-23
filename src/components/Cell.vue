<template>
  <div class="h-100">
    <div class="position-absolute h-100">
      <div v-for="(player,key) in players" :key="key">
        <div v-if="cell.position==player.position">
          <figurine :hero="player.hero"></figurine>
        </div>
      </div>
    </div>
    <div class="d-flex justify-content-center h-100">
      <ImageComponent
        v-if="cell.property"
        :src="getImage(cell)"
        :price="+cell.property.cost"
        :price_bgcolor="cell.property.group.color_name"
      ></ImageComponent>
      <ImageComponent v-else :src="getImage(cell)"></ImageComponent>
    </div>
  </div>
</template>

<script>
import ImageComponent from "./ImageComponent.vue";
import Figurine from "./Figurine.vue";
export default {
  components: {
    ImageComponent,
    Figurine,
  },
  props: {
    cell: {
      type: Object,
      default: {},
    },
    players: {
      type: Array,
      default: [],
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
};
</script>

<style scoped>
</style>