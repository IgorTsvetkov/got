<template>
    <div class="f f-center">
        <div class="grid">
            <div class="relative f" v-for="(cell,index) in cells" :key="index">
                <div  class="absolute figurine">

                    <img v-if="cell.position===0" height="100%" src="/web/images/figurines/figure1.png">
                    <img v-if="cell.position===0" height="100%" src="/web/images/figurines/figure2.png">
                    <img v-if="cell.position===0" height="100%" src="/web/images/figurines/figure3.png">
                    <img v-if="cell.position===0" height="100%" src="/web/images/figurines/figure4.png">
                    <img v-if="cell.position===0" height="100%" src="/web/images/figurines/figure5.png">
                </div>
                <div class="f f-center-horizontal">
                    <ImageComponent 
                    v-if="cell.property"
                    :src="getImage(cell)"
                    :price="cell.property.cost"
                    :price_bgcolor="cell.property.group.color_name"
                 ></ImageComponent>
                <ImageComponent v-else :src="getImage(cell)"></ImageComponent>
                </div>
            </div>
            <div class="cell-center">
                <img src="/web/images/center.jpg" alt="">
            </div>
            <img class="empty-center">
    </div>
    </div>
</template>

<script>
import ImageComponent from './ImageComponent.vue';
import axios from "axios";
export default {
        components:{ImageComponent},
        data() {
            return {
                cells:[],
            }
        },
        created(){
            axios.get("/cells",{
                params:{
                    expand:"property.group,tax,utility,event"
                }
            })
            .then(({data}) => {
                console.log(data);
                this.cells=data;
            })
            .catch(err => {
                console.error(err); 
            })
        },
        methods: {
            getImage(cell) {
                return cell.property?cell.property.src:
                       cell.tax?cell.tax.src:
                       cell.event?cell.event.src:
                       cell.utility?cell.utility.src:
                       "";
            }
        },
    }
</script>

<style scoped>
body{
    background: black;
    }
.grid{
    display: grid;
    grid-template-columns: 8.4vw repeat(14,5.2vw) 8.4vw;
    grid-template-rows: auto;
    grid-gap: 0.5vw;
    width:98vw;
    background: black;
    
}
.cell-center{
    grid-row: 2/6;
    grid-column: 2/7;
    height: auto;
    width:100%;
}
.empty-center{
    grid-row: 2/6;
    grid-column: 7/16;
}
.col-1{
    grid-column: 1/2;
}
.row-2{
    grid-row: 2/3;
}
.row-3{
    grid-row: 3/4;
}
.row-4{
    grid-row: 4/5;
}
.row-5{
    grid-row: 5/6;
}
.col-16{
    grid-column: 16/17;
}
.empty-center{
    width: 100%;
    height: 100%;
}
.cell-center img{
    width:inherit;
}
/*FIGURINE STYLES*/
.absolute{
    position: absolute;
}
.relative{
    position: relative;
}
.f{
    display:flex;
}
.f-center{
    justify-items: center;
    justify-content: center;
    align-items: center;
}
.f-center-horizontal{
    align-items: center;
}
.figurine{
    height: 40px;
    width:40px;
    display: flex;
    width: inherit;
    flex-wrap: wrap;
    z-index: 1;
}
</style>