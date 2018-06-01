<template>
<div>
</div>
</template>
<script>
export default {
  data() {
    return {
      //loading: true
    }
  },
  created() {
    window.events.$on('loadingon', () => this.loadingOn());
    window.events.$on('loadingoff', () => this.loadingOff());
  },
  mounted() { // would work in 'ready', 'attached', etc.
    window.addEventListener('load', () => {
      this.loadingOff();
    });
  },
  methods: {
    loadingOn() {
      $(".sk-cube-grid").fadeIn("slow");
      document.getElementById("blur").classList.add("blur");
    },
    loadingOff() {
      $(".sk-cube-grid").fadeOut("slow");
      document.getElementById("blur").classList.remove("blur");
    }
  }
}
</script>
