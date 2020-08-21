<template>
  <div @submit.prevent="ajaxSubmit">
    <slot v-if="!this.formHtml"></slot>
  <div v-html="formHtml">
  </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      formHtml: undefined,
    };
  },
  created() {
  },
  methods: {
    async ajaxSubmit(e) {
      let form = e.srcElement;
      let formData=new FormData(form);
      let result = await axios({
          method:form.method,
          url: form.action,
          data: formData
      });
      if(result.data.redirect){
        window.location.pathname=result.data.redirect;
        return;
      }
      console.log('result.data :>> ', result.data);
      this.formHtml = result.data;
      return false;
    },
  },
};
</script>

<style lang="scss" scoped>
</style>