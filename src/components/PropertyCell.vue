<template>
  <div class="cell-wrapper d-flex flex-column justify-content-center">
    <div v-if="this.price" class="text-center height" :class="[price_bgcolor]">{{this.price}}</div>
    <div class="w-inherit position-relative">
      <div 
        v-if="playerOwner"
        :style="{'background':playerOwner.hero.color}"
        class="position-absolute opacity-4 w-100 h-100"
      ></div>
      <img class="property-img" :src="this.src" alt />
    </div>
  </div>
</template>

<script>
export default {
  props: {
    src: {
      type: String,
      default: "",
    },
    price: {
      type: Number,
      default: undefined,
    },
    price_bgcolor: {
      type: String,
      default: "bgblack",
    },
    playerOwner: {
      type: Object,
      dafault: undefined,
    },
  },
};
</script>

<style scoped>
.w-inherit {
  width: inherit;
}
.cell-wrapper {
  width: 100%;
  height: auto;
}
.cell-wrapper img,
.cell-center img {
  width: inherit;
}
.price {
  background: white;
  text-align: center;
  font-size: 0.8em;
}
.bgblack {
  background: black;
}
.bgred {
  background: red;
}
.text-center {
  text-align: center;
}
.red {
  background: red;
  color: white;
}
.yellow {
  background: rgb(255, 234, 46);
  color: black;
}
.green {
  background: green;
  color: white;
}
.dark_purple {
  background: purple;
  color: white;
}
.brown {
  background: brown;
  color: white;
}
.dark_blue {
  background: darkcyan;
  color: white;
}
.light_blue {
  background: cyan;
  color: black;
}
.orange {
  background: rgb(255, 90, 31);
  color: white;
}
.green {
  background: green;
  color: white;
}
.height {
  /* font-size: 1.3em; */
  font-size: 1.2vw;
}
</style>