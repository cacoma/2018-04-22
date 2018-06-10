<template>
<div>
<!-- <slot></slot> -->
<!-- <b-modal dusk="enlargeModal" ref="enlargeModal" id="enlargeModal" class="w-75" hide-footer hide-header size="lg" centered v-model="this.show" hide-header-close> -->
<b-modal dusk="enlargeModal"
          ref="enlargeModal"
          id="enlargeModal"
          class="w-75"
          hide-footer
          size="lg"
          centered
          v-model="this.show"
          @hide="this.hide"
          >
          <!-- hide-header
          no-close-on-backdrop
          no-close-on-esc
          > -->
  <!-- <div class="d-block text-center"> -->
<slot></slot>
<!-- <b-btn variant="outline-success" block @click="hide()">Ok</b-btn> -->
  <!-- </div> -->
</b-modal>
</div>
</template>

<script>
export default {
//  props: ['passed'],
  data() {
    return {
      show: false,
    }
  },
  created() {
    // if (this.passed) {
    //   this.enlarge(this.passed);
    // }
  },
  mounted: function() {
    window.events.$on('enlarge', (type, incoming) => this.enlarge(type, incoming));
    this.$bus.$on('enlargeclose', () => this.hide());
  },
  methods: {
     enlarge(type, info) {
      if (typeof type == "undefined" || typeof info == "undefined") {
        console.log('acessou enlarge, sem dados');
        //console.log(type . info);
        this.show = true;
      }
      if (type == 'create'){
        console.log(info);
        create(info);
        this.show = true;
      } else if (type == 'createTypeStocks'){
        console.log(info);
        createTypeStocks(info);
        this.show = true;
      } else if (type == 'createTypeTreasuries'){
        console.log(info);
        createTypeTreasuries(info);
        this.show = true;
      } else {
      console.log('acessou enlarge, sem dados');
      //console.log(type . info);
      this.show = true;
      }
//       setTimeout(() => {
//         this.hide()
//       }, 5000)
    },
      hide() {
          this.show = false;
          this.$bus.$emit('formHide');
          //this.slotData = '';
    },
  }
}
</script>
