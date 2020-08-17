<template>
  <div class="btn btn-warning p-1 rounded shadow m-1" @click="changeSlot(index)">
    {{hero_name}}
    <div class="hero-img m-1">
      <img width="100px" :src="hero_src" />
    </div>
    <h4 class="h4 bg-dark p-1 text-light lead shadow" v-if="username">
      {{username}}
      <img v-if="is_owner" src="/web/images/crown.svg" width="25px" />
    </h4>
    <h4 class="h4 bg-dark p-1 text-light lead shadow" v-else>{{"Место "+(index)}}</h4>
  </div>
</template>

<script>
import AuthSocket from "../js/AuthSocket";
import axios from "axios";
export default {
  props: {
    is_owner: {
      type: Boolean,
      default: false,
    },
    username: {
      type: String,
      default: "",
    },
    hero_src:{
      type:String,
      default:"/web/images/figurines/figure0.png"
    },
    hero_name:{
      type:String,
      default:"Faceless men"
    },
    index:{
      type:String,
      default:""
    }
  },
  methods: {
    changeSlot(value) {
      let result = axios.post(`/match/change-slot?slot=${value}`);
      if (result.error) {
        this.error = result.error;
        return;
      }
      this.error = null;
      this.$emit("changeSlot");
    },
  },
  created(){
    axios.defaults.headers.common['X-CSRF-TOKEN']=window.yii.getCsrfToken();
  }
};
</script>

<style scoped>
</style>