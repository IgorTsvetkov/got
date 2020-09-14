<template>
  <div>
    <div  :class="{'active':!readonly}" class="cube-wrapper d-flex justify-content-center align-items-center">
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
      default: 1,

    },
    second: {
      type: Number,
      default: 1,
    },
    readonly:{
      type:Boolean,
      default:false
    },
    activate:{
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
      if(this.isAnimate)
        return;
      this.isAnimate = true;
      setTimeout(() => {
        this.isAnimate = false;
        this.$emit("rollFinish");
      }, 2000);
    },
  },
  watch: {
    activate(newValue, oldValue) {
      if(newValue)
      this.rollDices();
    }
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