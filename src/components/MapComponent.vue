<template>
    <div class="f f-center">
        <div class="grid">
            <div v-for="(cell,index) in cells" :key="index">
                <ImageComponent :src="urlBase+cell.src" :price="cell.price" :text_bgcolor="cell.bg"></ImageComponent>
            </div>
            <div class="cell-center">
                <img src="/web/images/t-1.jpg" alt="">
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
                urlBase:"http://got",
                cells:[],
                // cells:{
                //     0:{src:"/images/t-2.jpg",name:"harenhall"},
                    //top
                    // 0:{src:"./assets/top/t-0.jpg",name:"inn at the croassroads"},
                    // 1:{src:"./assets/top/t-1.jpg",name:"harenhall",price:"220",bg:"red"},
                    // 2:{src:"./assets/top/t-2.jpg",name:"bird"},
                    // 3:{src:"./assets/top/t-3.jpg",name:"lanisport",price:"220"},
                    // 4:{src:"./assets/top/t-4.jpg",name:"casterly rock",price:"240"},
                    // 5:{src:"./assets/top/t-5.jpg",name:"lanister"},
                    // 6:{src:"./assets/top/t-6.jpg",name:"astapor",price:"260"},
                    // 7:{src:"./assets/top/t-7.jpg",name:"yunkai",price:"260"},
                    // 8:{src:"./assets/top/t-8.jpg",name:"the trident"},
                    // 9:{src:"./assets/top/t-9.jpg",name:"meereen",price:"280"},
                    // 10:{src:"./assets/top/t-10.jpg",name:"horn hill",price:"300"},
                    // 11:{src:"./assets/top/t-11.jpg",name:"highgarden",price:"300"},
                    // 12:{src:"./assets/top/t-12.jpg",name:"spider"},
                    // 13:{src:"./assets/top/t-13.jpg",name:"oldtown",price:"320"},
                    // 14:{src:"./assets/top/t-14.jpg",name:"targarien"},
                    // 15:{src:"./assets/top/t-15.jpg",name:"take the black"},
                    // //sides
                    // 16:{src:"./assets/left/l-0.jpg"},
                    // 17:{src:"./assets/right/r-5.jpg"},
                    // 18:{src:"./assets/left/l-1.jpg"},
                    // 19:{src:"./assets/right/r-6.jpg"},
                    // 20:{src:"./assets/left/l-2.jpg"},
                    // 21:{src:"./assets/right/r-7.jpg"},
                    // 22:{src:"./assets/left/l-3.jpg"},
                    // 23:{src:"./assets/right/r-8.jpg"},
                    // //bottom
                    // 24:{src:"./assets/bottom/b-0.jpg"},
                    // 25:{src:"./assets/left/l-4.jpg"},
                    // 26:{src:"./assets/left/l-5.jpg"},
                    // 27:{src:"./assets/left/l-6.jpg"},
                    // 28:{src:"./assets/left/l-7.jpg"},
                    // 29:{src:"./assets/left/l-8.jpg"},
                    // 30:{src:"./assets/bottom/b-1.jpg"},
                    // 31:{src:"./assets/bottom/b-2.jpg"},
                    // 32:{src:"./assets/bottom/b-3.jpg"},
                    // 33:{src:"./assets/bottom/b-4.jpg"},
                    // 34:{src:"./assets/bottom/b-5.jpg"},
                    // 35:{src:"./assets/bottom/b-6.jpg"},
                    // 36:{src:"./assets/bottom/b-7.jpg"},
                    // 37:{src:"./assets/bottom/b-8.jpg"},
                    // 38:{src:"./assets/bottom/b-9.jpg"},
                    // 39:{src:"./assets/bottom/b-10.jpg"},
                // }
            }
        },
        created(){
            axios.get("/cells",{
                params:{
                    expand:"castle"
                }
            })
            .then(({data}) => {
                this.cells=data;
            })
            .catch(err => {
                console.error(err); 
            })
        }
    }
</script>

<style scoped>
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
    background: yellow;
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
</style>