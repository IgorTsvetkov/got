<template>
  <div class="cell-wrapper d-flex flex-column justify-content-center" >
    <div v-if="this.price" class="text-center height" :class="[price_bgcolor]">
      <span>{{this.price}}</span>      
    </div>
    <div class="w-inherit position-relative">
      <div 
        v-if="playerOwner"
        :style="{'background':playerOwner.hero.color}"
        class="position-absolute opacity-4 w-100 h-100"
      ></div>
      <div v-if="playerOwner&&playerOwner" class="w-100 h-100 opacity-8 d-flex position-absolute justify-content-center align-items-center text-light">{{playerOwner.user.username}}</div>
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

.height {
  /* font-size: 1.3em; */
  font-size: 1.2vw;
}
</style>