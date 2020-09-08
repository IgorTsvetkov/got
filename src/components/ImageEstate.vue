<template class="w-inherit ">
    <div class="w-inherit position-relative">
      <div 
        v-if="playerOwner"
        :style="{'background':playerOwner.hero.color}"
        class="position-absolute opacity-4 w-100 h-100"
      ></div>
      <div v-if="playerOwner" class="w-100 h-100 opacity-8 d-flex position-absolute justify-content-center align-items-center text-light">{{playerOwner.user.username}}</div>
      <img class="w-100 property-img" :src="this.src" alt />
    </div>
</template>

<script>
    export default {
        props: {
            playerOwner: {
                type: Object,
                default: undefined
            },
            src:{
                type:String,
                required:true,
            }
        },
    }
</script>

<style scoped>
.w-inherit {
  width: inherit;
}
</style>