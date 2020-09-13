<template>
  <div class @click="rollDices">
    <div class="cube-wrapper d-flex justify-content-center align-items-center">
      <dice :number="first" :isAnimate="isAnimate" :delay="200"></dice>
      <dice :number="second" :isAnimate="isAnimate"></dice>
    </div>
  </div>
</template>

<script>
import Dice from "./Dice.vue";
export default {
  components: {
    Dice,
  },
  props: {
    first: {
      type: Number,
      required: true,
    },
    second: {
      type: Number,
      required: true,
    },
    readonly:{
      type:Boolean,
      default:false
    }
  },
  data() {
    return {
      isAnimate: false,
    };
  },
  methods: {
    rollDices() {
      if(this.readonly)
        return;
        alert(this.readonly);
      if(this.isAnimate)
        return;
      this.isAnimate = true;
      setTimeout(() => {
        this.isAnimate = false;
      }, 2000);
    },
  },
};
</script>

<style scoped>
/* :root {
} */
.cube-wrapper {
  perspective: 1000px;
  --length: 4vw;
  width: 200px;
  height: 300px;
}
</style>