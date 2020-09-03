<template>
  <div>
    <div v-if="min<max"  class="form-group">
      <label for="formControlRange">Аукцион</label>
      {{current}}
      <div class="d-flex">
        {{min}}
        <input type="range" v-model="percent" class="form-control-range" id="formControlRange" />
        {{max}}
      </div>
      <div class="btn btn-primary">Повысить цену</div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    min: {
      type: Number,
      required:true
    },
    max: {
      type: Number,
      required: true,
    },
  },
  data() {
    return {
      percent: 0,
      current:this.min,
      step:(this.max-this.min)/100
    }
  },
  watch: {
    percent(newPersent, oldValue) {
      this.current=this.min+this.step*this.percent;
    }
  },
};
</script>

<style lang="scss" scoped>
</style>